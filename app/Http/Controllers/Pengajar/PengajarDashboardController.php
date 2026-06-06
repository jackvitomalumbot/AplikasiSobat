<?php

namespace App\Http\Controllers\Pengajar;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Kelas;
use App\Models\TugasSubmission;

class PengajarDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $kelasList = Kelas::where('pengajar_id', $user->id)->withCount(['activeEnrollments'])->get();
        $kelasIds = $kelasList->pluck('id');

        $totalMahasiswa = Enrollment::whereIn('kelas_id', $kelasIds)->where('payment_status', 'paid')->distinct('mahasiswa_id')->count('mahasiswa_id');
        $totalKelas = $kelasList->count();
        $tugasBelumDinilai = TugasSubmission::whereHas('pertemuan', fn($q) => $q->whereIn('kelas_id', $kelasIds))
            ->whereNull('nilai')->count();

        $totalPendapatan = Enrollment::whereIn('kelas_id', $kelasIds)
            ->where('payment_status', 'paid')
            ->get()
            ->sum(fn($e) => $e->kelas->harga ?? 0);

        $kelasTerbaru = $kelasList->sortByDesc('created_at')->take(6);

        return view('pengajar.dashboard', compact(
            'totalMahasiswa', 'totalKelas', 'tugasBelumDinilai', 'totalPendapatan', 'kelasTerbaru'
        ));
    }
}
