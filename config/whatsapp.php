<?php

return [
    /*
    |--------------------------------------------------------------------------
    | WhatsApp Provider
    |--------------------------------------------------------------------------
    |
    | Supported providers: "twilio", "meta", "custom"
    |
    */
    'provider' => env('WHATSAPP_PROVIDER', 'twilio'),

    /*
    |--------------------------------------------------------------------------
    | Twilio Configuration
    |--------------------------------------------------------------------------
    |
    | Free Sandbox: Use whatsapp:+15551234567 as FROM number
    | For production, replace with your verified phone number
    |
    */
    'twilio' => [
        'account_sid' => env('TWILIO_ACCOUNT_SID', ''),
        'auth_token' => env('TWILIO_AUTH_TOKEN', ''),
        'from_number' => env('TWILIO_WHATSAPP_NUMBER', 'whatsapp:+15551234567'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Meta (WhatsApp Business) Configuration
    |--------------------------------------------------------------------------
    |
    | Get credentials from: https://developers.facebook.com/
    |
    */
    'meta' => [
        'access_token' => env('META_ACCESS_TOKEN', ''),
        'phone_number_id' => env('META_PHONE_NUMBER_ID', ''),
        'business_account_id' => env('META_BUSINESS_ACCOUNT_ID', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | Evolution API Configuration
    |--------------------------------------------------------------------------
    |
    | Real-time WhatsApp integration
    | Get from: https://evolution-api.com/
    |
    */
    'evolution' => [
        'api_url' => env('EVOLUTION_API_URL', 'http://localhost:8080'),
        'api_token' => env('EVOLUTION_API_TOKEN', ''),
        'webhook_secret' => env('EVOLUTION_WEBHOOK_SECRET', ''),
        'instance_name' => env('EVOLUTION_INSTANCE_NAME', 'lahomes_instance'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom HTTP Endpoint
    |--------------------------------------------------------------------------
    |
    | Use your own WhatsApp API endpoint
    |
    */
    'custom' => [
        'endpoint' => env('WHATSAPP_CUSTOM_ENDPOINT', ''),
        'api_key' => env('WHATSAPP_CUSTOM_API_KEY', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | General Settings
    |--------------------------------------------------------------------------
    */
    'api_key' => env('WHATSAPP_API_KEY', ''),
    'phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID', ''),
    'business_phone_number' => env('WHATSAPP_BUSINESS_PHONE_NUMBER', ''),

    /*
    |--------------------------------------------------------------------------
    | Features
    |--------------------------------------------------------------------------
    */
    'features' => [
        'attendance_alerts' => true,
        'payroll_alerts' => true,
        'grade_alerts' => true,
        'announcement_alerts' => true,
        'fee_alerts' => true,
    ],
];
