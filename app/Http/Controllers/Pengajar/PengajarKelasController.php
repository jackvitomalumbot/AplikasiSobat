<?php

namespace App\Http\Controllers\Pengajar;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\MateriFile;
use App\Models\Pertemuan;
use App\Models\TugasSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PengajarKelasController extends Controller
{
    public function index()
    {
        $kelasList = Kelas::where('pengajar_id', auth()->id())
            ->withCount('activeEnrollments')
            ->latest()
            ->get();

        return view('pengajar.kelas.index', compact('kelasList'));
    }

    public function create()
    {
        return view('pengajar.kelas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:100',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'required|string|max:500',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        $data = $request->only('nama_kelas', 'harga', 'deskripsi');
        $data['pengajar_id'] = auth()->id();

        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = 'kelas_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/thumbnails'), $filename);
            $data['thumbnail'] = 'uploads/thumbnails/' . $filename;
        }

        Kelas::create($data);

        return redirect('/pengajar/kelas')->with('success', 'Kelas berhasil dibuat.');
    }

    public function show(Kelas $kela)
    {
        if ($kela->pengajar_id !== auth()->id()) abort(403);

        $kela->load(['pertemuan.materiFiles', 'pertemuan.absensi', 'pertemuan.tugasSubmissions', 'activeEnrollments.mahasiswa', 'kuis.soal', 'kuis.hasil']);

        return view('pengajar.kelas.show', ['kelas' => $kela]);
    }

    public function update(Request $request, Kelas $kela)
    {
        if ($kela->pengajar_id !== auth()->id()) abort(403);

        $request->validate([
            'nama_kelas' => 'required|string|max:100',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'required|string|max:500',
        ]);

        $kela->update($request->only('nama_kelas', 'harga', 'deskripsi'));

        return back()->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kela)
    {
        if ($kela->pengajar_id !== auth()->id()) abort(403);

        if ($kela->thumbnail && File::exists(public_path($kela->thumbnail))) {
            File::delete(public_path($kela->thumbnail));
        }

        $kela->delete();

        return redirect('/pengajar/kelas')->with('success', 'Kelas berhasil dihapus.');
    }

    public function storePertemuan(Request $request, Kelas $kela)
    {
        if ($kela->pengajar_id !== auth()->id()) abort(403);

        $request->validate([
            'judul' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'deskripsi' => 'nullable|string',
            'tipe' => 'required|in:pertemuan,tugas',
            'deadline' => 'nullable|required_if:tipe,tugas|date',
            'instruksi_tugas' => 'nullable|string',
            'files.*' => 'nullable|file|max:10240',
        ]);

        $pertemuan = Pertemuan::create([
            'kelas_id' => $kela->id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tanggal' => $request->tanggal,
            'tipe' => $request->tipe,
            'deadline' => $request->tipe === 'tugas' ? $request->deadline : null,
            'instruksi_tugas' => $request->tipe === 'tugas' ? $request->instruksi_tugas : null,
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/materi'), $filename);
                MateriFile::create([
                    'pertemuan_id' => $pertemuan->id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => 'uploads/materi/' . $filename,
                    'file_type' => $file->getClientOriginalExtension(),
                ]);
            }
        }

        return back()->with('success', ($request->tipe === 'tugas' ? 'Tugas' : 'Pertemuan') . ' berhasil ditambahkan.');
    }

    public function destroyPertemuan(Pertemuan $pertemuan)
    {
        $kelas = $pertemuan->kelas;
        if ($kelas->pengajar_id !== auth()->id()) abort(403);

        foreach ($pertemuan->materiFiles as $file) {
            if (File::exists(public_path($file->file_path))) {
                File::delete(public_path($file->file_path));
            }
        }

        $pertemuan->delete();

        return back()->with('success', 'Pertemuan/tugas berhasil dihapus.');
    }

    /* ─── Pertemuan Detail + Absensi ─── */

    public function showPertemuan(Pertemuan $pertemuan)
    {
        $kelas = $pertemuan->kelas;
        if ($kelas->pengajar_id !== auth()->id()) abort(403);

        $pertemuan->load(['materiFiles', 'absensi.mahasiswa', 'tugasSubmissions.mahasiswa']);

        $enrolledMahasiswa = $kelas->activeEnrollments()->with('mahasiswa')->get()->pluck('mahasiswa');
        $absensiMap = $pertemuan->absensi->keyBy('mahasiswa_id');
        $submissionMap = $pertemuan->tugasSubmissions->keyBy('mahasiswa_id');

        return view('pengajar.kelas.pertemuan', compact('pertemuan', 'kelas', 'enrolledMahasiswa', 'absensiMap', 'submissionMap'));
    }

    public function storeAbsensi(Request $request, Pertemuan $pertemuan)
    {
        $kelas = $pertemuan->kelas;
        if ($kelas->pengajar_id !== auth()->id()) abort(403);

        $request->validate([
            'absensi' => 'required|array',
            'absensi.*.mahasiswa_id' => 'required|exists:users,id',
            'absensi.*.status' => 'required|in:hadir,tidak_hadir,izin',
        ]);

        foreach ($request->absensi as $item) {
            Absensi::updateOrCreate(
                ['pertemuan_id' => $pertemuan->id, 'mahasiswa_id' => $item['mahasiswa_id']],
                ['status' => $item['status'], 'keterangan' => $item['keterangan'] ?? null]
            );
        }

        return back()->with('success', 'Absensi berhasil disimpan.');
    }

    public function nilaiTugas(Request $request, Pertemuan $pertemuan)
    {
        $kelas = $pertemuan->kelas;
        if ($kelas->pengajar_id !== auth()->id()) abort(403);

        $request->validate([
            'submission_id' => 'required|exists:tugas_submissions,id',
            'nilai' => 'required|numeric|min:0|max:100',
        ]);

        $submission = TugasSubmission::findOrFail($request->submission_id);
        $submission->update(['nilai' => $request->nilai]);

        return back()->with('success', 'Nilai berhasil disimpan.');
    }

    /* ─── Export PDF Rekap Absensi ─── */

    public function exportAbsensiPdf(Kelas $kela)
    {
        if ($kela->pengajar_id !== auth()->id()) abort(403);

        $kela->load(['pengajar', 'pertemuan.absensi', 'pertemuan.tugasSubmissions', 'activeEnrollments.mahasiswa.mahasiswaDetail']);

        $pertemuanList = $kela->pertemuan->sortBy('tanggal');
        $mahasiswaList = $kela->activeEnrollments->pluck('mahasiswa')->sortBy('nama');

        // Build absensi matrix: [pertemuan_id][mahasiswa_id] => Absensi
        $absensiMatrix = [];
        foreach ($pertemuanList as $p) {
            $absensiMatrix[$p->id] = $p->absensi->keyBy('mahasiswa_id');
        }

        // Build submission matrix: [pertemuan_id][mahasiswa_id] => TugasSubmission
        $submissionMatrix = [];
        foreach ($pertemuanList as $p) {
            $submissionMatrix[$p->id] = $p->tugasSubmissions->keyBy('mahasiswa_id');
        }

        // Calculate totals
        $totalHadir = 0;
        $totalTidakHadir = 0;
        foreach ($pertemuanList as $p) {
            foreach ($mahasiswaList as $mhs) {
                $absensi = $absensiMatrix[$p->id][$mhs->id] ?? null;
                if ($absensi && $absensi->status === 'hadir') {
                    $totalHadir++;
                } else {
                    $totalTidakHadir++;
                }
            }
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pengajar.kelas.absensi-pdf', [
            'kelas' => $kela,
            'pertemuanList' => $pertemuanList,
            'mahasiswaList' => $mahasiswaList,
            'absensiMatrix' => $absensiMatrix,
            'submissionMatrix' => $submissionMatrix,
            'totalHadir' => $totalHadir,
            'totalTidakHadir' => $totalTidakHadir,
        ]);

        $pdf->setPaper('a4', 'landscape');

        $fileName = 'Rekap_Absensi_' . str_replace(' ', '_', $kela->nama_kelas) . '_' . now()->format('Ymd') . '.pdf';

        return $pdf->download($fileName);
    }
}
