<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Kuis;
use App\Models\KuisHasil;
use App\Models\KuisJawaban;
use Illuminate\Http\Request;

class MahasiswaKuisController extends Controller
{
    /**
     * KKM (Kriteria Ketuntasan Minimal)
     */
    const KKM = 75;

    /* ─── Take Quiz ─── */

    public function show(Kuis $kui)
    {
        $user = auth()->user();
        $kelas = $kui->kelas;

        // Check if enrolled
        $enrolled = $kelas->activeEnrollments()->where('mahasiswa_id', $user->id)->exists();
        if (!$enrolled) abort(403, 'Anda tidak terdaftar di kelas ini.');

        // Check if already completed
        $hasil = KuisHasil::where('kuis_id', $kui->id)->where('mahasiswa_id', $user->id)->first();
        if ($hasil && $hasil->isCompleted()) {
            return redirect()->route('mahasiswa.kuis.hasil', $kui)->with('info', 'Anda sudah menyelesaikan kuis ini.');
        }

        // Check if active and not expired
        if (!$kui->is_active) abort(403, 'Kuis ini tidak aktif.');
        if ($kui->isExpired()) abort(403, 'Kuis ini sudah melewati deadline.');

        // Create or get in-progress result
        if (!$hasil) {
            $totalSoal = $kui->soal()->count();
            $hasil = KuisHasil::create([
                'kuis_id' => $kui->id,
                'mahasiswa_id' => $user->id,
                'max_poin' => $totalSoal, // max_poin = total soal (each worth 1 point)
                'waktu_mulai' => now(),
            ]);
        }

        $soal = $kui->acak_soal ? $kui->soal()->inRandomOrder()->get() : $kui->soal;

        return view('mahasiswa.kuis.show', compact('kui', 'kelas', 'soal', 'hasil'));
    }

    /* ─── Submit Quiz ─── */

    public function submit(Request $request, Kuis $kui)
    {
        $user = auth()->user();

        $hasil = KuisHasil::where('kuis_id', $kui->id)->where('mahasiswa_id', $user->id)->first();
        if (!$hasil) abort(404, 'Sesi kuis tidak ditemukan.');
        if ($hasil->isCompleted()) {
            return redirect()->route('mahasiswa.kuis.hasil', $kui)->with('info', 'Kuis sudah diselesaikan.');
        }

        $soalList = $kui->soal;
        $totalSoal = $soalList->count();
        $totalBenar = 0;

        foreach ($soalList as $soal) {
            $jawaban = $request->input("jawaban.{$soal->id}", '');

            $isCorrect = null;
            $poinDidapat = 0;

            if ($soal->isPilihanGanda() && $soal->jawaban_benar) {
                // Auto-grade pilihan ganda
                $isCorrect = strtoupper(trim($jawaban)) === strtoupper(trim($soal->jawaban_benar));
                $poinDidapat = $isCorrect ? 1 : 0;
                if ($isCorrect) $totalBenar++;
            }
            // Essay: will be graded manually by pengajar

            KuisJawaban::updateOrCreate(
                ['kuis_hasil_id' => $hasil->id, 'kuis_soal_id' => $soal->id],
                ['jawaban' => $jawaban, 'is_correct' => $isCorrect, 'poin_didapat' => $poinDidapat]
            );
        }

        // Rumus Nilai = (Jawaban Benar / Total Soal) × 100
        $nilai = $totalSoal > 0 ? round(($totalBenar / $totalSoal) * 100, 1) : 0;

        $hasil->update([
            'total_benar' => $totalBenar,
            'total_poin' => $totalBenar, // 1 poin per jawaban benar
            'max_poin' => $totalSoal,
            'nilai' => $nilai,
            'waktu_selesai' => now(),
        ]);

        return redirect()->route('mahasiswa.kuis.hasil', $kui)->with('success', 'Kuis berhasil dikumpulkan! Nilai Anda: ' . $nilai);
    }

    /* ─── View Result ─── */

    public function hasil(Kuis $kui)
    {
        $user = auth()->user();

        $hasil = KuisHasil::where('kuis_id', $kui->id)
            ->where('mahasiswa_id', $user->id)
            ->with(['jawaban.soal'])
            ->first();

        if (!$hasil) abort(404, 'Anda belum mengerjakan kuis ini.');

        $kelas = $kui->kelas;
        $kui->load('soal');
        $kkm = self::KKM;

        return view('mahasiswa.kuis.hasil', compact('kui', 'kelas', 'hasil', 'kkm'));
    }
}
