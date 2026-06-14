<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;

class MahasiswaTransaksiController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $transactions = Enrollment::where('mahasiswa_id', $user->id)
            ->with(['kelas.pengajar'])
            ->latest()
            ->paginate(15);

        $paidCount = Enrollment::where('mahasiswa_id', $user->id)
            ->where('payment_status', 'paid')
            ->count();

        $totalSpent = Enrollment::where('mahasiswa_id', $user->id)
            ->where('payment_status', 'paid')
            ->whereHas('kelas')
            ->get()
            ->sum(fn($e) => $e->kelas->harga ?? 0);

        return view('mahasiswa.transaksi', compact('transactions', 'paidCount', 'totalSpent'));
    }
}
