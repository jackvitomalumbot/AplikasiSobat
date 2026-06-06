<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Handle Midtrans payment notification (webhook).
     *
     * This endpoint receives server-to-server notifications from Midtrans
     * whenever a payment status changes. It is called without authentication
     * and CSRF protection (excluded in bootstrap/app.php).
     */
    public function notification(Request $request)
    {
        $payload = $request->all();

        Log::info('Midtrans Notification Received', [
            'order_id' => $payload['order_id'] ?? 'unknown',
            'transaction_status' => $payload['transaction_status'] ?? 'unknown',
        ]);

        $midtrans = new MidtransService();

        // Verify signature to ensure authenticity
        if (!$midtrans->verifySignature($payload)) {
            Log::warning('Midtrans Notification: Invalid signature', [
                'order_id' => $payload['order_id'] ?? 'unknown',
            ]);

            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $orderId = $payload['order_id'] ?? null;
        $paymentType = $payload['payment_type'] ?? null;
        $transactionTime = $payload['transaction_time'] ?? null;

        if (!$orderId) {
            return response()->json(['message' => 'Missing order_id'], 400);
        }

        // Find the enrollment by payment_id (which stores the order_id)
        $enrollment = Enrollment::where('payment_id', $orderId)->first();

        if (!$enrollment) {
            Log::warning('Midtrans Notification: Enrollment not found', [
                'order_id' => $orderId,
            ]);
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Resolve payment status
        $status = $midtrans->resolvePaymentStatus($payload);

        // Update enrollment
        $enrollment->update([
            'payment_status' => $status,
            'payment_type' => $paymentType,
            'transaction_time' => $transactionTime,
            'paid_at' => $status === 'paid' ? now() : $enrollment->paid_at,
        ]);

        Log::info('Midtrans Notification Processed', [
            'order_id' => $orderId,
            'status' => $status,
            'enrollment_id' => $enrollment->id,
        ]);

        return response()->json(['message' => 'OK']);
    }
}
