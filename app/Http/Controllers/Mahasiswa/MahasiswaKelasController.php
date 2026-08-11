<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\MateriFile;
use App\Models\Pertemuan;
use App\Models\TugasSubmission;
use App\Services\DocumentWatermarkService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MahasiswaKelasController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $enrolledKelas = $user->enrolledKelas()
            ->wherePivot('payment_status', 'paid')
            ->with('pengajar')
            ->withCount('pertemuan')
            ->get();

        foreach ($enrolledKelas as $kelas) {
            $totalTugas = $kelas->pertemuan()->where('tipe', 'tugas')->count();
            $submittedTugas = TugasSubmission::whereHas('pertemuan', fn($q) => $q->where('kelas_id', $kelas->id))
                ->where('mahasiswa_id', $user->id)->count();
            $kelas->progress = $totalTugas > 0 ? round(($submittedTugas / $totalTugas) * 100) : 0;
            $kelas->submitted = $submittedTugas;
            $kelas->total_tugas = $totalTugas;
        }

        return view('mahasiswa.kelas.index', compact('enrolledKelas'));
    }

    public function show(Kelas $kela)
    {
        $user = auth()->user();

        $enrolled = $user->enrollments()->where('kelas_id', $kela->id)->where('payment_status', 'paid')->exists();
        if (!$enrolled) abort(403, 'Anda belum terdaftar di kelas ini.');

        $kela->load(['pengajar', 'pertemuan.materiFiles', 'pertemuan.absensi']);

        $submissions = TugasSubmission::where('mahasiswa_id', $user->id)
            ->whereHas('pertemuan', fn($q) => $q->where('kelas_id', $kela->id))
            ->get()
            ->keyBy('pertemuan_id');

        // Absensi map for current user
        $absensiMap = Absensi::where('mahasiswa_id', $user->id)
            ->whereHas('pertemuan', fn($q) => $q->where('kelas_id', $kela->id))
            ->get()
            ->keyBy('pertemuan_id');

        // Kuis for this kelas
        $kuisList = $kela->kuis()->where('is_active', true)->with(['soal', 'hasil'])->get();

        return view('mahasiswa.kelas.show', ['kelas' => $kela, 'submissions' => $submissions, 'absensiMap' => $absensiMap, 'kuisList' => $kuisList]);
    }

    /* ─── Detail Pertemuan/Tugas ─── */

    public function showPertemuan(Pertemuan $pertemuan)
    {
        $user = auth()->user();
        $kelas = $pertemuan->kelas;

        $enrolled = $user->enrollments()->where('kelas_id', $kelas->id)->where('payment_status', 'paid')->exists();
        if (!$enrolled) abort(403, 'Anda belum terdaftar di kelas ini.');

        $pertemuan->load(['materiFiles', 'kelas.pengajar']);

        // Get absensi for this user
        $absensi = Absensi::where('pertemuan_id', $pertemuan->id)->where('mahasiswa_id', $user->id)->first();

        // Get submission if tugas
        $submission = null;
        if ($pertemuan->isTugas()) {
            $submission = TugasSubmission::where('pertemuan_id', $pertemuan->id)->where('mahasiswa_id', $user->id)->first();
        }

        return view('mahasiswa.kelas.pertemuan', compact('pertemuan', 'kelas', 'absensi', 'submission'));
    }

    /* ─── Secure PDF Download (Watermarked) ─── */

    public function downloadMateri(MateriFile $materiFile)
    {
        $user     = auth()->user();
        $pertemuan = $materiFile->pertemuan;
        $kelas    = $pertemuan->kelas;

        // Pastikan mahasiswa sudah enrolled dan payment paid
        $enrolled = $user->enrollments()
            ->where('kelas_id', $kelas->id)
            ->where('payment_status', 'paid')
            ->exists();

        if (!$enrolled) {
            abort(403, 'Anda tidak memiliki akses ke file ini.');
        }

        try {
            $service   = new DocumentWatermarkService();
            $pdfPath   = $service->processForDownload($materiFile->file_path, $materiFile->file_name);

            $downloadName = pathinfo($materiFile->file_name, PATHINFO_FILENAME) . '_SobatMedis.pdf';

            return response()->file($pdfPath, [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $downloadName . '"',
                'Cache-Control'       => 'no-store, no-cache',
            ]);
        } catch (\Throwable $e) {
            Log::error('DocumentWatermarkService error: ' . $e->getMessage());
            // Fallback: direct download of original file
            $absPath = public_path($materiFile->file_path);
            if (!file_exists($absPath)) abort(404);
            return response()->download($absPath, $materiFile->file_name);
        }
    }

    /* ─── Submit Tugas + Auto-Absensi ─── */

    public function submitTugas(Request $request, Pertemuan $pertemuan)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
            'catatan' => 'nullable|string',
        ]);

        $user = auth()->user();

        $enrolled = $user->enrollments()->where('kelas_id', $pertemuan->kelas_id)->where('payment_status', 'paid')->exists();
        if (!$enrolled) abort(403);

        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/submissions'), $filename);
        $path = 'uploads/submissions/' . $filename;

        TugasSubmission::updateOrCreate(
            ['pertemuan_id' => $pertemuan->id, 'mahasiswa_id' => $user->id],
            ['file_path' => $path, 'catatan' => $request->catatan]
        );

        // Auto-absensi: mark as "hadir" when submitting tugas
        Absensi::updateOrCreate(
            ['pertemuan_id' => $pertemuan->id, 'mahasiswa_id' => $user->id],
            ['status' => 'hadir', 'keterangan' => 'Otomatis hadir — tugas dikumpulkan']
        );

        return back()->with('success', 'Tugas berhasil dikumpulkan. Absensi otomatis tercatat.');
    }
}
