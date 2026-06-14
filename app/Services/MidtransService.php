<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class MidtransService
{
    protected string $serverKey;
    protected string $apiUrl;
    protected bool $isProduction;

    public function __construct()
    {
        $this->serverKey = config('midtrans.server_key');
        $this->apiUrl = config('midtrans.api_url');
        $this->isProduction = config('midtrans.is_production');
    }

    /**
     * Create a Snap transaction and get the Snap Token.
     *
     * @param string $orderId   Unique order ID (e.g., "ORDER-123-timestamp")
     * @param int    $amount    Total amount in IDR (integer, no decimal)
     * @param array  $itemDetails  Array of items: [['id','price','quantity','name'], ...]
     * @param array  $customer  Customer info: ['first_name','email','phone']
     * @return array ['snap_token' => '...', 'redirect_url' => '...'] or ['error' => '...']
     */
    public function createSnapTransaction(
        string $orderId,
        int $amount,
        array $itemDetails = [],
        array $customer = []
    ): array {
        $payload = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $amount,
            ],
        ];

        if (!empty($itemDetails)) {
            $payload['item_details'] = $itemDetails;
        }

        if (!empty($customer)) {
            $payload['customer_details'] = $customer;
        }

        // Add callbacks
        $payload['callbacks'] = [
            'finish' => url('/mahasiswa/transaksi') . '?payment=finish',
            'error' => url('/mahasiswa/transaksi') . '?payment=error',
            'pending' => url('/mahasiswa/transaksi') . '?payment=pending',
        ];

        $response = $this->postRequest('/snap/v1/transactions', $payload);

        if (isset($response['token'])) {
            return [
                'snap_token' => $response['token'],
                'redirect_url' => $response['redirect_url'] ?? null,
            ];
        }

        Log::error('Midtrans Snap: Failed to create transaction', [
            'order_id' => $orderId,
            'response' => $response,
        ]);

        return ['error' => $response['error_messages'] ?? ['Gagal membuat transaksi pembayaran.']];
    }

    /**
     * Verify the notification signature from Midtrans.
     *
     * Signature formula: SHA512(order_id + status_code + gross_amount + server_key)
     *
     * @param array $notification  The decoded JSON notification payload
     * @return bool
     */
    public function verifySignature(array $notification): bool
    {
        $orderId = $notification['order_id'] ?? '';
        $statusCode = $notification['status_code'] ?? '';
        $grossAmount = $notification['gross_amount'] ?? '';

        $expectedSignature = hash(
            'sha512',
            $orderId . $statusCode . $grossAmount . $this->serverKey
        );

        $receivedSignature = $notification['signature_key'] ?? '';

        return hash_equals($expectedSignature, $receivedSignature);
    }

    /**
     * Determine the payment status from a Midtrans notification.
     *
     * @param array $notification
     * @return string  One of: 'paid', 'pending', 'failed', 'expired', 'refunded'
     */
    public function resolvePaymentStatus(array $notification): string
    {
        $transactionStatus = $notification['transaction_status'] ?? '';
        $fraudStatus = $notification['fraud_status'] ?? 'accept';

        return match ($transactionStatus) {
            'capture' => $fraudStatus === 'accept' ? 'paid' : 'failed',
            'settlement' => 'paid',
            'pending' => 'pending',
            'deny', 'cancel' => 'failed',
            'expire' => 'expired',
            'refund', 'partial_refund' => 'refunded',
            default => 'pending',
        };
    }

    /**
     * Get transaction status from Midtrans API.
     *
     * @param string $orderId
     * @return array
     */
    public function getTransactionStatus(string $orderId): array
    {
        return $this->getRequest("/v2/{$orderId}/status");
    }

    /**
     * POST request to Midtrans API.
     */
    protected function postRequest(string $endpoint, array $payload): array
    {
        $url = $this->apiUrl . $endpoint;

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json',
                'Authorization: Basic ' . base64_encode($this->serverKey . ':'),
            ],
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 30,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            Log::error('Midtrans cURL Error', ['url' => $url, 'error' => $error]);
            return ['error_messages' => ['Connection error: ' . $error]];
        }

        $decoded = json_decode($response, true);

        if ($httpCode >= 400) {
            Log::warning('Midtrans API Error', [
                'url' => $url,
                'http_code' => $httpCode,
                'response' => $decoded,
            ]);
        }

        return $decoded ?? ['error_messages' => ['Invalid response from Midtrans.']];
    }

    /**
     * GET request to Midtrans API.
     */
    protected function getRequest(string $endpoint): array
    {
        $url = $this->apiUrl . $endpoint;

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Authorization: Basic ' . base64_encode($this->serverKey . ':'),
            ],
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 30,
        ]);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            Log::error('Midtrans cURL Error', ['url' => $url, 'error' => $error]);
            return ['error_messages' => ['Connection error: ' . $error]];
        }

        return json_decode($response, true) ?? ['error_messages' => ['Invalid response.']];
    }
}
