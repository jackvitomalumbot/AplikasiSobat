<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Pertemuan;
use App\Models\TugasSubmission;

class MahasiswaDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $enrolledKelasIds = $user->enrollments()->where('payment_status', 'paid')->pluck('kelas_id');

        // Get upcoming deadlines
        $deadlines = Pertemuan::whereIn('kelas_id', $enrolledKelasIds)
            ->where('tipe', 'tugas')
            ->where('deadline', '>=', now())
            ->orderBy('deadline')
            ->with('kelas')
            ->take(10)
            ->get();

        // Get all tasks with submission status
        $allTugas = Pertemuan::whereIn('kelas_id', $enrolledKelasIds)
            ->where('tipe', 'tugas')
            ->with(['kelas', 'tugasSubmissions' => fn($q) => $q->where('mahasiswa_id', $user->id)])
            ->orderBy('deadline', 'desc')
            ->get();

        return view('mahasiswa.dashboard', compact('deadlines', 'allTugas'));
    }
}
