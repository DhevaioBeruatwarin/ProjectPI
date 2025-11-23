<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    public function __construct()
    {
        // Set konfigurasi Midtrans
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = env('MIDTRANS_IS_SANITIZED', true);
        Config::$is3ds = env('MIDTRANS_IS_3DS', true);

        // PENTING: Override notification URL
        // Gunakan APP_URL dari .env (harus URL ngrok saat development)
        $appUrl = env('APP_URL', 'http://localhost:8000');
        Config::$overrideNotifUrl = $appUrl . '/payment-callback';

        Log::info('Midtrans Configuration Loaded', [
            'notification_url' => Config::$overrideNotifUrl,
            'is_production' => Config::$isProduction,
            'server_key_length' => strlen(Config::$serverKey)
        ]);
    }

    public function createTransaction($orderId, $grossAmount, $customerDetails, $itemDetails)
    {
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $grossAmount,
            ],
            'callbacks' => [
                'finish' => route('pembeli.payment.success') . '?order_id=' . $orderId,
            ],
            'customer_details' => $customerDetails,
            'item_details' => $itemDetails,
            'enabled_payments' => [
                'credit_card',
                'bca_va',
                'bni_va',
                'bri_va',
                'permata_va',
                'other_va',
                'gopay',
                'shopeepay',
                'qris'
            ],
            'expiry' => [
                'start_time' => date('Y-m-d H:i:s O'),
                'unit' => 'hours',
                'duration' => 24
            ],
        ];

        try {
            Log::info('Creating Midtrans Transaction', [
                'order_id' => $orderId,
                'amount' => $grossAmount
            ]);

            $snapToken = Snap::getSnapToken($params);

            Log::info('Snap Token Created Successfully', [
                'order_id' => $orderId,
                'token' => substr($snapToken, 0, 20) . '...'
            ]);

            return $snapToken;
        } catch (\Exception $e) {
            Log::error('Midtrans Transaction Error', [
                'error' => $e->getMessage(),
                'order_id' => $orderId
            ]);
            throw new \Exception('Error creating Midtrans transaction: ' . $e->getMessage());
        }
    }

    public function getTransactionStatus($orderId)
    {
        try {
            return \Midtrans\Transaction::status($orderId);
        } catch (\Exception $e) {
            Log::error('Get Transaction Status Error', [
                'error' => $e->getMessage(),
                'order_id' => $orderId
            ]);
            throw new \Exception('Error getting transaction status: ' . $e->getMessage());
        }
    }
}