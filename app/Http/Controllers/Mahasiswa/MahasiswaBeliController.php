<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Kelas;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MahasiswaBeliController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $enrolledIds = $user->enrollments()->pluck('kelas_id');

        $query = Kelas::where('is_active', true)
            ->whereNotIn('id', $enrolledIds)
            ->with('pengajar')
            ->withCount('activeEnrollments');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nama_kelas', 'like', "%{$s}%")
                  ->orWhere('deskripsi', 'like', "%{$s}%")
                  ->orWhereHas('pengajar', fn($q2) => $q2->where('nama', 'like', "%{$s}%"));
            });
        }

        $availableKelas = $query->latest()->paginate(12);

        return view('mahasiswa.beli-kelas', compact('availableKelas'));
    }

    public function checkout(Kelas $kela)
    {
        $user = auth()->user();

        // Already enrolled?
        $exists = Enrollment::where('mahasiswa_id', $user->id)->where('kelas_id', $kela->id)->exists();
        if ($exists) {
            return redirect('/mahasiswa/kelas')->with('error', 'Anda sudah terdaftar di kelas ini.');
        }

        $midtransClientKey = config('midtrans.client_key');
        $midtransSnapUrl = config('midtrans.snap_url');

        return view('payment.checkout', [
            'kelas' => $kela,
            'midtransClientKey' => $midtransClientKey,
            'midtransSnapUrl' => $midtransSnapUrl,
        ]);
    }

    /**
     * Create a Midtrans Snap transaction and return the snap token.
     *
     * This is called via AJAX from the checkout page.
     */
    public function createTransaction(Request $request, Kelas $kela)
    {
        $user = auth()->user();

        // Prevent duplicate enrollment
        $existingEnrollment = Enrollment::where('mahasiswa_id', $user->id)
            ->where('kelas_id', $kela->id)
            ->first();

        if ($existingEnrollment) {
            if ($existingEnrollment->payment_status === 'paid') {
                return response()->json(['error' => 'Anda sudah terdaftar di kelas ini.'], 409);
            }

            // If there's a pending enrollment with a valid snap token, reuse it
            if ($existingEnrollment->snap_token && $existingEnrollment->payment_status === 'pending') {
                return response()->json([
                    'snap_token' => $existingEnrollment->snap_token,
                ]);
            }
        }

        // Generate unique order ID
        $orderId = 'SM-' . $kela->id . '-' . $user->id . '-' . time();

        // Create or update enrollment with pending status
        $enrollment = Enrollment::updateOrCreate(
            ['mahasiswa_id' => $user->id, 'kelas_id' => $kela->id],
            [
                'payment_status' => 'pending',
                'payment_id' => $orderId,
            ]
        );

        // Create Midtrans Snap transaction
        $midtrans = new MidtransService();
        $result = $midtrans->createSnapTransaction(
            orderId: $orderId,
            amount: (int) $kela->harga,
            itemDetails: [
                [
                    'id' => 'KELAS-' . $kela->id,
                    'price' => (int) $kela->harga,
                    'quantity' => 1,
                    'name' => mb_substr($kela->nama_kelas, 0, 50),
                ],
            ],
            customer: [
                'first_name' => $user->nama,
                'email' => $user->email,
            ]
        );

        if (isset($result['error'])) {
            Log::error('Failed to create Midtrans transaction', [
                'order_id' => $orderId,
                'error' => $result['error'],
            ]);

            // Cleanup the pending enrollment
            $enrollment->delete();

            return response()->json([
                'error' => 'Gagal memproses pembayaran. Silakan coba lagi.',
            ], 500);
        }

        // Save snap token to enrollment
        $enrollment->update(['snap_token' => $result['snap_token']]);

        return response()->json([
            'snap_token' => $result['snap_token'],
        ]);
    }

    /**
     * Handle post-payment redirect (client-side callback from Snap).
     * This is NOT the authoritative payment confirmation — that comes from the webhook.
     */
    public function paymentFinish(Request $request, Kelas $kela)
    {
        $user = auth()->user();

        $enrollment = Enrollment::where('mahasiswa_id', $user->id)
            ->where('kelas_id', $kela->id)
            ->first();

        if (!$enrollment) {
            return redirect('/mahasiswa/beli-kelas')->with('error', 'Transaksi tidak ditemukan.');
        }

        // The actual status update happens via webhook, but we show appropriate message
        return match ($enrollment->payment_status) {
            'paid' => redirect('/mahasiswa/kelas')->with('success', 'Pembayaran berhasil! Kelas "' . $kela->nama_kelas . '" telah ditambahkan.'),
            'pending' => redirect('/mahasiswa/kelas')->with('info', 'Pembayaran sedang diproses. Kelas akan aktif setelah pembayaran dikonfirmasi.'),
            default => redirect('/mahasiswa/beli-kelas')->with('error', 'Pembayaran gagal atau dibatalkan. Silakan coba lagi.'),
        };
    }
}
