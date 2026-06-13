<?php

namespace App\Http\Controllers\Pengajar;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Kuis;
use App\Models\KuisHasil;
use App\Models\KuisSoal;
use Illuminate\Http\Request;

class PengajarKuisController extends Controller
{
    /**
     * KKM (Kriteria Ketuntasan Minimal)
     */
    const KKM = 75;

    /* ─── Create Kuis Form ─── */

    public function create(Kelas $kela)
    {
        if ($kela->pengajar_id !== auth()->id()) abort(403);
        return view('pengajar.kuis.create', ['kelas' => $kela]);
    }

    /* ─── Store Kuis + Soal ─── */

    public function store(Request $request, Kelas $kela)
    {
        if ($kela->pengajar_id !== auth()->id()) abort(403);

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'durasi_menit' => 'required|integer|min:1|max:600',
            'deadline' => 'nullable|date',
            'acak_soal' => 'nullable|boolean',
            'soal' => 'required|array|min:1|max:100',
            'soal.*.tipe' => 'required|in:pilihan_ganda,essay',
            'soal.*.pertanyaan' => 'required|string',
        ]);

        $kuis = Kuis::create([
            'kelas_id' => $kela->id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'durasi_menit' => $request->durasi_menit,
            'deadline' => $request->deadline,
            'acak_soal' => $request->boolean('acak_soal'),
        ]);

        $nomor = 1;
        foreach ($request->soal as $soalData) {
            // Only include opsi fields that are within the jumlah_opsi range
            $jumlahOpsi = (int) ($soalData['jumlah_opsi'] ?? 5);

            KuisSoal::create([
                'kuis_id' => $kuis->id,
                'nomor' => $nomor++,
                'tipe' => $soalData['tipe'],
                'pertanyaan' => $soalData['pertanyaan'],
                'opsi_a' => ($soalData['tipe'] === 'pilihan_ganda' && $jumlahOpsi >= 1) ? ($soalData['opsi_a'] ?? null) : null,
                'opsi_b' => ($soalData['tipe'] === 'pilihan_ganda' && $jumlahOpsi >= 2) ? ($soalData['opsi_b'] ?? null) : null,
                'opsi_c' => ($soalData['tipe'] === 'pilihan_ganda' && $jumlahOpsi >= 3) ? ($soalData['opsi_c'] ?? null) : null,
                'opsi_d' => ($soalData['tipe'] === 'pilihan_ganda' && $jumlahOpsi >= 4) ? ($soalData['opsi_d'] ?? null) : null,
                'opsi_e' => ($soalData['tipe'] === 'pilihan_ganda' && $jumlahOpsi >= 5) ? ($soalData['opsi_e'] ?? null) : null,
                'jawaban_benar' => $soalData['jawaban_benar'] ?? null,
                'poin' => 1, // Each question = 1 point (formula: benar/total × 100)
            ]);
        }

        return redirect()->route('pengajar.kelas.show', $kela)->with('success', "Kuis \"{$kuis->judul}\" berhasil dibuat dengan " . count($request->soal) . " soal.");
    }

    /* ─── Show Kuis Detail + Hasil ─── */

    public function show(Kuis $kui)
    {
        $kelas = $kui->kelas;
        if ($kelas->pengajar_id !== auth()->id()) abort(403);

        $kui->load(['soal', 'hasil.mahasiswa', 'hasil.jawaban.soal']);
        $enrolledCount = $kelas->activeEnrollments()->count();

        return view('pengajar.kuis.show', compact('kui', 'kelas', 'enrolledCount'));
    }

    /* ─── Nilai Essay (Benar/Salah) ─── */

    public function nilaiEssay(Request $request, Kuis $kui)
    {
        $kelas = $kui->kelas;
        if ($kelas->pengajar_id !== auth()->id()) abort(403);

        $request->validate([
            'penilaian' => 'required|array',
            'penilaian.*.jawaban_id' => 'required|exists:kuis_jawaban,id',
            'penilaian.*.benar' => 'required|in:0,1',
        ]);

        $hasilId = null;

        foreach ($request->penilaian as $item) {
            $jawaban = \App\Models\KuisJawaban::findOrFail($item['jawaban_id']);
            $isBenar = (bool) $item['benar'];

            $jawaban->update([
                'poin_didapat' => $isBenar ? 1 : 0,
                'is_correct' => $isBenar,
            ]);

            $hasilId = $jawaban->kuis_hasil_id;
        }

        // Recalculate with formula: Nilai = (Benar / Total Soal) × 100
        if ($hasilId) {
            $hasil = KuisHasil::findOrFail($hasilId);
            $hasil->load('jawaban');

            $totalBenar = $hasil->jawaban->where('is_correct', true)->count();
            $totalSoal = $hasil->max_poin; // max_poin = total soal

            $hasil->update([
                'total_benar' => $totalBenar,
                'total_poin' => $totalBenar,
                'nilai' => $totalSoal > 0 ? round(($totalBenar / $totalSoal) * 100, 1) : 0,
            ]);
        }

        return back()->with('success', 'Penilaian essay berhasil disimpan. Nilai mahasiswa telah dihitung ulang.');
    }

    /* ─── Toggle Active ─── */

    public function toggleActive(Kuis $kui)
    {
        $kelas = $kui->kelas;
        if ($kelas->pengajar_id !== auth()->id()) abort(403);

        $kui->update(['is_active' => !$kui->is_active]);

        return back()->with('success', $kui->is_active ? 'Kuis diaktifkan.' : 'Kuis dinonaktifkan.');
    }

    /* ─── Delete Kuis ─── */

    public function destroy(Kuis $kui)
    {
        $kelas = $kui->kelas;
        if ($kelas->pengajar_id !== auth()->id()) abort(403);

        $kui->delete();

        return redirect()->route('pengajar.kelas.show', $kelas)->with('success', 'Kuis berhasil dihapus.');
    }
}
