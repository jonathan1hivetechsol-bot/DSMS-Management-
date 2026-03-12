<?php

namespace App\Services;

use App\Models\WhatsAppAlert;
use App\Models\WhatsAppTemplate;
use App\Models\WhatsAppRecipient;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    private string $provider;
    private string $apiKey;
    private string $phoneNumberId;
    private string $businessPhoneNumber;

    public function __construct()
    {
        $this->provider = config('whatsapp.provider', 'twilio');
        $this->apiKey = config('whatsapp.api_key', '');
        $this->phoneNumberId = config('whatsapp.phone_number_id', '');
        $this->businessPhoneNumber = config('whatsapp.business_phone_number', '');
    }

    /**
     * Send a WhatsApp message to a recipient
     *
     * @param string $phoneNumber
     * @param string $message
     * @param WhatsAppTemplate|null $template
     * @param array|null $data
     * @return WhatsAppAlert|null
     */
    public function sendMessage(
        string $phoneNumber,
        string $message,
        ?WhatsAppTemplate $template = null,
        ?array $data = null
    ): ?WhatsAppAlert {
        try {
            // Create alert record
            $alert = WhatsAppAlert::create([
                'template_id' => $template?->id,
                'recipient_phone' => $this->formatPhoneNumber($phoneNumber),
                'status' => 'pending',
                'message' => $message,
                'data' => $data,
                'provider' => $this->provider,
                'retry_count' => 0,
            ]);

            // Send the message
            $response = $this->executeRequest($alert);

            if ($response['success']) {
                $alert->update([
                    'status' => 'sent',
                    'sent_at' => now(),
                    'provider_message_id' => $response['message_id'] ?? null,
                ]);

                Log::channel('whatsapp')->info('WhatsApp message sent', [
                    'alert_id' => $alert->id,
                    'phone' => $phoneNumber,
                    'provider' => $this->provider,
                ]);

                return $alert;
            } else {
                $alert->update([
                    'status' => 'failed',
                    'error_message' => $response['error'] ?? 'Unknown error',
                ]);

                Log::channel('whatsapp')->error('WhatsApp message failed', [
                    'alert_id' => $alert->id,
                    'phone' => $phoneNumber,
                    'error' => $response['error'] ?? 'Unknown error',
                ]);

                return $alert;
            }
        } catch (\Exception $e) {
            Log::channel('whatsapp')->error('WhatsApp service error', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Send message using template
     *
     * @param WhatsAppRecipient $recipient
     * @param WhatsAppTemplate $template
     * @param array $variables
     * @return WhatsAppAlert|null
     */
    public function sendTemplateMessage(
        WhatsAppRecipient $recipient,
        WhatsAppTemplate $template,
        array $variables = []
    ): ?WhatsAppAlert {
        $message = $this->populateTemplate($template->template, $variables);

        return $this->sendMessage(
            $recipient->phone_number,
            $message,
            $template,
            $variables
        );
    }

    /**
     * Populate template variables
     *
     * @param string $template
     * @param array $variables
     * @return string
     */
    public function populateTemplate(string $template, array $variables = []): string
    {
        $message = $template;

        foreach ($variables as $key => $value) {
            $message = str_replace(
                '{' . $key . '}',
                $value,
                $message
            );
        }

        return $message;
    }

    /**
     * Execute the actual request based on provider
     *
     * @param WhatsAppAlert $alert
     * @return array
     */
    private function executeRequest(WhatsAppAlert $alert): array
    {
        return match ($this->provider) {
            'evolution' => $this->sendViaEvolution($alert),
            'twilio' => $this->sendViaTwilio($alert),
            'meta' => $this->sendViaMeta($alert),
            'custom' => $this->sendViaCustom($alert),
            default => [
                'success' => false,
                'error' => 'Unknown provider: ' . $this->provider,
            ],
        };
    }

    /**
     * Send via Evolution API (Real-time WhatsApp)
     *
     * @param WhatsAppAlert $alert
     * @return array
     */
    private function sendViaEvolution(WhatsAppAlert $alert): array
    {
        try {
            $evolutionService = app(EvolutionService::class);
            $response = $evolutionService->sendMessage($alert->recipient_phone, $alert->message);

            return $response;

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Send via Twilio (Free Sandbox)
     *
     * @param WhatsAppAlert $alert
     * @return array
     */
    private function sendViaTwilio(WhatsAppAlert $alert): array
    {
        try {
            $accountSid = config('whatsapp.twilio.account_sid');
            $authToken = config('whatsapp.twilio.auth_token');
            $fromNumber = config('whatsapp.twilio.from_number', 'whatsapp:+15551234567');

            if (!$accountSid || !$authToken) {
                return [
                    'success' => false,
                    'error' => 'Twilio credentials not configured',
                ];
            }

            $response = Http::withBasicAuth($accountSid, $authToken)
                ->asForm()
                ->post("https://api.twilio.com/2010-04-01/Accounts/{$accountSid}/Messages.json", [
                    'From' => $fromNumber,
                    'To' => 'whatsapp:' . $alert->recipient_phone,
                    'Body' => $alert->message,
                ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message_id' => $response->json('sid'),
                ];
            }

            return [
                'success' => false,
                'error' => $response->json('message') ?? 'Twilio API error',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Send via Meta (WhatsApp Business Cloud API)
     *
     * @param WhatsAppAlert $alert
     * @return array
     */
    private function sendViaMeta(WhatsAppAlert $alert): array
    {
        try {
            $accessToken = config('whatsapp.meta.access_token');
            $phoneNumberId = config('whatsapp.meta.phone_number_id');

            if (!$accessToken || !$phoneNumberId) {
                return [
                    'success' => false,
                    'error' => 'Meta credentials not configured',
                ];
            }

            $response = Http::withToken($accessToken)
                ->post("https://graph.instagram.com/v18.0/{$phoneNumberId}/messages", [
                    'messaging_product' => 'whatsapp',
                    'to' => $alert->recipient_phone,
                    'type' => 'text',
                    'text' => [
                        'body' => $alert->message,
                    ],
                ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message_id' => $response->json('messages.0.id'),
                ];
            }

            return [
                'success' => false,
                'error' => $response->json('error.message') ?? 'Meta API error',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Send via Custom HTTP endpoint
     *
     * @param WhatsAppAlert $alert
     * @return array
     */
    private function sendViaCustom(WhatsAppAlert $alert): array
    {
        try {
            $endpoint = config('whatsapp.custom.endpoint');
            $apiKey = config('whatsapp.custom.api_key');

            if (!$endpoint) {
                return [
                    'success' => false,
                    'error' => 'Custom endpoint not configured',
                ];
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post($endpoint, [
                'phone' => $alert->recipient_phone,
                'message' => $alert->message,
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message_id' => $response->json('message_id'),
                ];
            }

            return [
                'success' => false,
                'error' => $response->json('error') ?? 'Custom API error',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Format phone number to international format
     *
     * @param string $phone
     * @return string
     */
    private function formatPhoneNumber(string $phone): string
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Add country code if not present (assuming +1 for US, can be customized)
        if (strlen($phone) == 10) {
            $phone = '1' . $phone;
        }

        return '+' . $phone;
    }

    /**
     * Retry failed messages
     */
    public function retryFailedMessages(): void
    {
        $failedAlerts = WhatsAppAlert::where('status', 'failed')
            ->where('retry_count', '<', 3)
            ->where('created_at', '>=', now()->subDays(1))
            ->get();

        foreach ($failedAlerts as $alert) {
            $alert->increment('retry_count');
            $response = $this->executeRequest($alert);

            if ($response['success']) {
                $alert->update([
                    'status' => 'sent',
                    'sent_at' => now(),
                    'provider_message_id' => $response['message_id'] ?? null,
                ]);
            }
        }
    }
}
