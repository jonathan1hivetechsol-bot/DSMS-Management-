<?php

namespace App\Http\Controllers;

use App\Models\WhatsAppAlert;
use App\Services\EvolutionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class WhatsAppWebhookController extends Controller
{
    private EvolutionService $evolutionService;

    public function __construct(EvolutionService $evolutionService)
    {
        $this->evolutionService = $evolutionService;
    }

    /**
     * Verify webhook token (called by Evolution API to verify endpoint)
     */
    public function verify(Request $request)
    {
        $token = $request->query('token');
        $challenge = $request->query('challenge');

        if ($token === config('whatsapp.evolution.webhook_secret')) {
            return response($challenge);
        }

        return response('Unauthorized', 401);
    }

    /**
     * Receive real-time events from Evolution API
     * Events: MESSAGES_UPSERT, MESSAGES_UPDATE, MESSAGE_FAILED, etc.
     */
    public function handle(Request $request)
    {
        try {
            $signature = $request->header('X-Webhook-Signature');
            $secret = config('whatsapp.evolution.webhook_secret');

            // Verify webhook signature for security
            if ($signature && !$this->verifySignature($request->getContent(), $signature, $secret)) {
                Log::channel('whatsapp')->warning('Invalid webhook signature', [
                    'ip' => $request->ip(),
                ]);
                return response('Invalid signature', 401);
            }

            $data = $request->json()->all();

            Log::channel('whatsapp')->info('WhatsApp webhook received', [
                'event' => $data['event'] ?? 'unknown',
                'data' => $data,
            ]);

            // Handle different event types
            match ($data['event'] ?? null) {
                'MESSAGES_UPSERT' => $this->handleMessageUpsert($data['data'] ?? []),
                'MESSAGES_UPDATE' => $this->handleMessageUpdate($data['data'] ?? []),
                'MESSAGE_FAILED' => $this->handleMessageFailed($data['data'] ?? []),
                'MESSAGES_DELETE' => $this->handleMessageDelete($data['data'] ?? []),
                default => Log::channel('whatsapp')->info('Unhandled webhook event', ['event' => $data['event'] ?? 'unknown']),
            };

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            Log::channel('whatsapp')->error('Webhook handling error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Handle incoming messages and new messages sent (MESSAGES_UPSERT)
     * This event fires when a new message is created
     */
    private function handleMessageUpsert(array $data)
    {
        try {
            $messages = $data['messages'] ?? [];

            foreach ($messages as $message) {
                // Skip if this is our outgoing message (we'll handle via MESSAGES_UPDATE)
                if ($message['key']['fromMe'] ?? false) {
                    continue;
                }

                // Log incoming messages
                $fromNumber = $message['pushName'] ?? 'Unknown';
                $messageText = $message['message']['conversation'] ?? '';

                Log::channel('whatsapp')->info('Incoming WhatsApp message', [
                    'from' => $fromNumber,
                    'message' => $messageText,
                    'timestamp' => $message['messageTimestamp'] ?? null,
                ]);

                // You can process incoming messages here
                // For example: save to database, trigger notifications, etc.
            }

        } catch (\Exception $e) {
            Log::channel('whatsapp')->error('Error handling message upsert', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Handle message status updates (MESSAGES_UPDATE)
     * Statuses: PENDING, SENT, RECEIVED, READ, FAILED
     */
    private function handleMessageUpdate(array $data)
    {
        try {
            $messages = $data['messages'] ?? [];

            foreach ($messages as $message) {
                $messageId = $message['key']['id'] ?? null;
                $status = $message['status'] ?? null;

                if (!$messageId || !$status) {
                    continue;
                }

                // Find the alert record by provider_message_id
                $alert = WhatsAppAlert::where('provider_message_id', $messageId)
                    ->orWhere('provider_message_id', 'like', "%{$messageId}%")
                    ->first();

                if ($alert) {
                    // Map Evolution API statuses to our statuses
                    $mappedStatus = match ($status) {
                        '1' => 'sent',           // PENDING
                        '2' => 'sent',           // SENT
                        '3' => 'delivered',      // RECEIVED
                        '4' => 'delivered',      // READ
                        '5' => 'failed',         // FAILED
                        default => $status,
                    };

                    $alert->update([
                        'status' => $mappedStatus,
                        'delivered_at' => in_array($status, ['3', '4']) ? now() : $alert->delivered_at,
                        'read_at' => $status === '4' ? now() : $alert->read_at,
                    ]);

                    Log::channel('whatsapp')->info('Message status updated', [
                        'alert_id' => $alert->id,
                        'status' => $mappedStatus,
                    ]);
                }
            }

        } catch (\Exception $e) {
            Log::channel('whatsapp')->error('Error handling message update', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Handle failed messages (MESSAGE_FAILED)
     */
    private function handleMessageFailed(array $data)
    {
        try {
            $messageId = $data['id'] ?? null;
            $errorMessage = $data['message'] ?? 'Unknown error';
            $errorCode = $data['code'] ?? null;

            if ($messageId) {
                $alert = WhatsAppAlert::where('provider_message_id', $messageId)
                    ->orWhere('provider_message_id', 'like', "%{$messageId}%")
                    ->first();

                if ($alert) {
                    $alert->update([
                        'status' => 'failed',
                        'error_message' => $errorMessage,
                        'retry_count' => $alert->retry_count + 1,
                    ]);

                    Log::channel('whatsapp')->error('Message delivery failed', [
                        'alert_id' => $alert->id,
                        'error' => $errorMessage,
                        'code' => $errorCode,
                    ]);

                    // Auto-retry if retry count < max attempts
                    if ($alert->retry_count < 3) {
                        $alert->update(['status' => 'pending']);
                    }
                }
            }

        } catch (\Exception $e) {
            Log::channel('whatsapp')->error('Error handling message failure', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Handle deleted messages (MESSAGES_DELETE)
     */
    private function handleMessageDelete(array $data)
    {
        try {
            $messageId = $data['id'] ?? null;

            if ($messageId) {
                $alert = WhatsAppAlert::where('provider_message_id', $messageId)
                    ->orWhere('provider_message_id', 'like', "%{$messageId}%")
                    ->first();

                if ($alert) {
                    $alert->update(['status' => 'deleted']);

                    Log::channel('whatsapp')->info('Message deleted', [
                        'alert_id' => $alert->id,
                    ]);
                }
            }

        } catch (\Exception $e) {
            Log::channel('whatsapp')->error('Error handling message deletion', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Verify webhook signature
     *
     * @param string $payload
     * @param string $signature
     * @param string $secret
     * @return bool
     */
    private function verifySignature(string $payload, string $signature, string $secret): bool
    {
        $hash = hash_hmac('sha256', $payload, $secret);
        return hash_equals($hash, $signature);
    }

    /**
     * Test webhook endpoint (for manual testing)
     */
    public function test(Request $request)
    {
        return response()->json([
            'success' => true,
            'webhook_url' => route('whatsapp.webhook.handle'),
            'instance' => config('whatsapp.evolution.instance_name'),
            'time' => now()->toIso8601String(),
        ]);
    }
}
