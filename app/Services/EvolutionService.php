<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EvolutionService
{
    private string $apiUrl;
    private string $apiToken;
    private string $instanceName;

    public function __construct()
    {
        $this->apiUrl = config('whatsapp.evolution.api_url', 'http://localhost:8080');
        $this->apiToken = config('whatsapp.evolution.api_token', '');
        $this->instanceName = config('whatsapp.evolution.instance_name', 'lahomes_instance');
    }

    /**
     * Send a WhatsApp message via Evolution API
     *
     * @param string $phoneNumber
     * @param string $message
     * @return array
     */
    public function sendMessage(string $phoneNumber, string $message): array
    {
        try {
            if (!$this->apiToken) {
                Log::channel('whatsapp')->error('Evolution API token missing', [
                    'action' => 'sendMessage',
                ]);
                
                return [
                    'success' => false,
                    'error' => 'Evolution API token not configured',
                ];
            }

            // Format phone number: remove any non-digit characters and add country code if needed
            $formattedPhone = $this->formatPhoneNumber($phoneNumber);

            Log::channel('whatsapp')->info('Evolution API sending message', [
                'phone' => $phoneNumber,
                'formatted_phone' => $formattedPhone,
                'api_url' => $this->apiUrl,
                'instance' => $this->instanceName,
                'message_length' => strlen($message),
            ]);

            // Try multiple evolution API endpoints
            $endpoints = [
                "{$this->apiUrl}/message/sendText/{$this->instanceName}",
                "{$this->apiUrl}/api/message/sendText/{$this->instanceName}",
                "{$this->apiUrl}/messages/send",
                "{$this->apiUrl}/api/send",
            ];

            $lastError = null;
            
            foreach ($endpoints as $endpoint) {
                try {
                    $response = Http::withHeaders([
                        'Authorization' => 'Bearer ' . $this->apiToken,
                        'X-API-Key' => $this->apiToken,
                        'Content-Type' => 'application/json',
                    ])->timeout(10)->post($endpoint, [
                        'number' => $formattedPhone,
                        'text' => $message,
                        'instance' => $this->instanceName,
                    ]);

                    Log::channel('whatsapp')->info('Evolution API response', [
                        'endpoint' => $endpoint,
                        'status' => $response->status(),
                    ]);

                    if ($response->successful()) {
                        $data = $response->json();
                        
                        Log::channel('whatsapp')->info('Evolution API message sent successfully', [
                            'phone' => $phoneNumber,
                            'endpoint' => $endpoint,
                            'message_id' => $data['key']['id'] ?? $data['id'] ?? null,
                        ]);

                        return [
                            'success' => true,
                            'message_id' => $data['key']['id'] ?? $data['id'] ?? 'evolution_' . time(),
                            'data' => $data,
                        ];
                    } elseif ($response->status() !== 401 && $response->status() !== 404) {
                        // Found working endpoint but message failed
                        $errorMessage = $response->json('message') ?? $response->json('error') ?? 'Evolution API error';
                        throw new \Exception($errorMessage);
                    }
                    
                    $lastError = "Status: {$response->status()}";
                    
                } catch (\Exception $e) {
                    $lastError = $e->getMessage();
                    continue;
                }
            }

            // Fallback: If Development mode, log as sent
            if (app()->environment('local', 'testing')) {
                Log::channel('whatsapp')->warning('Evolution API not available, using development mode', [
                    'phone' => $phoneNumber,
                    'message' => substr($message, 0, 50),
                    'mode' => 'development_fallback',
                ]);

                // IMPORTANT: Don't fake success - show actual error
                return [
                    'success' => false,
                    'error' => 'Evolution API not configured: ' . $lastError . '. Check your .env credentials or start Evolution API server.',
                    'mode' => 'development',
                    'last_error' => $lastError,
                ];
            }

            Log::channel('whatsapp')->error('Evolution API all endpoints failed', [
                'phone' => $phoneNumber,
                'last_error' => $lastError,
                'endpoints_tried' => count($endpoints),
            ]);

            return [
                'success' => false,
                'error' => 'Evolution API not available: ' . $lastError,
            ];

        } catch (\Exception $e) {
            Log::channel('whatsapp')->error('Evolution API exception', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage(),
                'exception' => get_class($e),
            ]);

            // Fallback for development
            if (app()->environment('local', 'testing')) {
                return [
                    'success' => false,
                    'error' => 'Evolution API Exception: ' . $e->getMessage() . '. Please check your configuration.',
                    'mode' => 'development',
                ];
            }

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Send message with media (image, PDF, etc.)
     *
     * @param string $phoneNumber
     * @param string $mediaUrl
     * @param string $caption
     * @param string $mediaType (image|document|video|audio)
     * @return array
     */
    public function sendMedia(
        string $phoneNumber,
        string $mediaUrl,
        string $caption = '',
        string $mediaType = 'image'
    ): array {
        try {
            if (!$this->apiToken) {
                return [
                    'success' => false,
                    'error' => 'Evolution API token not configured',
                ];
            }

            $formattedPhone = $this->formatPhoneNumber($phoneNumber);

            $payload = [
                'number' => $formattedPhone,
                'mediaUrl' => $mediaUrl,
            ];

            if ($caption) {
                $payload['caption'] = $caption;
            }

            $endpoint = match ($mediaType) {
                'document' => 'sendDocument',
                'video' => 'sendVideo',
                'audio' => 'sendAudio',
                default => 'sendImage',
            };

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiToken,
                'Content-Type' => 'application/json',
            ])->post("{$this->apiUrl}/message/{$endpoint}/{$this->instanceName}", $payload);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message_id' => $response->json('key.id'),
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'error' => $response->json('message') ?? 'Evolution API error',
            ];

        } catch (\Exception $e) {
            Log::channel('whatsapp')->error('Evolution API media send failed', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Send template message (with variables)
     *
     * @param string $phoneNumber
     * @param string $templateName
     * @param array $variables
     * @return array
     */
    public function sendTemplate(
        string $phoneNumber,
        string $templateName,
        array $variables = []
    ): array {
        try {
            if (!$this->apiToken) {
                return [
                    'success' => false,
                    'error' => 'Evolution API token not configured',
                ];
            }

            $formattedPhone = $this->formatPhoneNumber($phoneNumber);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiToken,
                'Content-Type' => 'application/json',
            ])->post("{$this->apiUrl}/message/sendTemplate/{$this->instanceName}", [
                'number' => $formattedPhone,
                'template' => $templateName,
                'params' => $variables,
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message_id' => $response->json('key.id'),
                ];
            }

            return [
                'success' => false,
                'error' => $response->json('message') ?? 'Evolution API error',
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get message status
     *
     * @param string $messageId
     * @return array
     */
    public function getMessageStatus(string $messageId): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiToken,
            ])->get("{$this->apiUrl}/message/getText/{$this->instanceName}/{$messageId}");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'error' => 'Could not fetch message status',
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Format phone number to E.164 format
     *
     * @param string $phoneNumber
     * @return string
     */
    private function formatPhoneNumber(string $phoneNumber): string
    {
        // Remove all non-digit characters
        $cleaned = preg_replace('/\D/', '', $phoneNumber);

        // If it doesn't start with country code (minimum 11 digits assumed for Pakistan +92)
        if (strlen($cleaned) === 10) {
            // Pakistan mobile: 300... -> 923...
            $cleaned = '92' . $cleaned;
        } elseif (!str_starts_with($cleaned, '92') && strlen($cleaned) === 12) {
            // Add country code if missing
            $cleaned = '92' . substr($cleaned, -10);
        }

        return $cleaned;
    }

    /**
     * Setup webhook endpoint configuration
     *
     * @param string $webhookUrl
     * @return array
     */
    public function setupWebhook(string $webhookUrl): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiToken,
                'Content-Type' => 'application/json',
            ])->post("{$this->apiUrl}/webhook/set/{$this->instanceName}", [
                'url' => $webhookUrl,
                'events' => [
                    'MESSAGES_UPSERT',
                    'MESSAGES_UPDATE',
                    'MESSAGES_DELETE',
                    'MESSAGE_FAILED',
                    'CHATS_UPSERT',
                    'CHATS_UPDATE',
                    'CONTACTS_UPSERT',
                    'CONTACTS_UPDATE',
                ],
            ]);

            if ($response->successful()) {
                Log::channel('whatsapp')->info('Webhook configured successfully', [
                    'url' => $webhookUrl,
                    'instance' => $this->instanceName,
                ]);

                return [
                    'success' => true,
                    'message' => 'Webhook configured',
                ];
            }

            return [
                'success' => false,
                'error' => $response->json('message') ?? 'Failed to setup webhook',
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get instance info
     *
     * @return array
     */
    public function getInstanceInfo(): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiToken,
            ])->get("{$this->apiUrl}/instance/info/{$this->instanceName}");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'error' => 'Could not fetch instance info',
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Test API connection
     *
     * @return bool
     */
    public function testConnection(): bool
    {
        try {
            $response = Http::timeout(5)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiToken,
                ])
                ->asJson()
                ->get("{$this->apiUrl}/instance/fetchInstances");

            return $response->successful();

        } catch (\Exception $e) {
            Log::channel('whatsapp')->error('Evolution API connection test failed', [
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
