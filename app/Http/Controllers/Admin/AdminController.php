<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Kelas;
use App\Models\MahasiswaDetail;
use App\Models\PengajarDetail;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalMahasiswa = User::where('role', 'mahasiswa')->count();
        $totalPengajar = User::where('role', 'pengajar')->count();
        $totalKelas = Kelas::count();
        $totalEnrollments = Enrollment::where('payment_status', 'paid')->count();

        return view('admin.dashboard', compact('totalMahasiswa', 'totalPengajar', 'totalKelas', 'totalEnrollments'));
    }

    public function mahasiswa(Request $request)
    {
        $query = User::where('role', 'mahasiswa')->with('mahasiswaDetail');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nama', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhereHas('mahasiswaDetail', fn($q2) => $q2->where('nim', 'like', "%{$s}%")->orWhere('universitas', 'like', "%{$s}%"));
            });
        }

        $mahasiswaList = $query->latest()->paginate(15);
        $kelasList = Kelas::with('pengajar')->orderBy('nama_kelas')->get();

        return view('admin.mahasiswa', compact('mahasiswaList', 'kelasList'));
    }

    public function pengajar(Request $request)
    {
        $query = User::where('role', 'pengajar')->with('pengajarDetail')->withCount('kelasAsTeacher');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nama', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhereHas('pengajarDetail', fn($q2) => $q2->where('spesialisasi', 'like', "%{$s}%"));
            });
        }

        $pengajarList = $query->latest()->paginate(15);

        return view('admin.pengajar', compact('pengajarList'));
    }

    public function storePengajar(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'spesialisasi' => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => $request->password,
            'role' => 'pengajar',
            'email_verified_at' => now(),
        ]);

        PengajarDetail::create([
            'user_id' => $user->id,
            'spesialisasi' => $request->spesialisasi,
        ]);

        return back()->with('success', 'Pengajar berhasil ditambahkan.');
    }

    public function destroyUser(User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Tidak bisa menghapus akun admin.');
        }

        $user->delete();

        return back()->with('success', 'Akun berhasil dihapus.');
    }

    /* ─── Change Password ─── */

    public function changePassword(Request $request, User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Tidak bisa mengubah password admin.');
        }

        $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => $request->new_password,
        ]);

        return back()->with('success', "Password {$user->nama} berhasil diubah.");
    }

    /* ─── Export PDF ─── */

    public function exportMahasiswaPdf()
    {
        $mahasiswaList = User::where('role', 'mahasiswa')
            ->with(['mahasiswaDetail', 'enrollments' => fn($q) => $q->where('payment_status', 'paid')])
            ->orderBy('nama')
            ->get();

        $pdf = Pdf::loadView('admin.mahasiswa-pdf', compact('mahasiswaList'));
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('Data_Mahasiswa_SobatMedis_' . now()->format('Ymd') . '.pdf');
    }

    public function exportPengajarPdf()
    {
        $pengajarList = User::where('role', 'pengajar')
            ->with('pengajarDetail')
            ->withCount('kelasAsTeacher')
            ->orderBy('nama')
            ->get();

        $pdf = Pdf::loadView('admin.pengajar-pdf', compact('pengajarList'));
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('Data_Pengajar_SobatMedis_' . now()->format('Ymd') . '.pdf');
    }

    /* ─── Berikan Kelas Gratis ─── */

    public function grantFreeClass(Request $request)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:users,id',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $user = User::findOrFail($request->mahasiswa_id);
        if ($user->role !== 'mahasiswa') {
            return back()->with('error', 'User bukan mahasiswa.');
        }

        $existing = Enrollment::where('mahasiswa_id', $request->mahasiswa_id)
            ->where('kelas_id', $request->kelas_id)
            ->first();

        if ($existing) {
            if ($existing->payment_status === 'paid') {
                return back()->with('error', 'Mahasiswa sudah terdaftar di kelas ini.');
            }
            $existing->update([
                'payment_status' => 'paid',
                'payment_id' => 'FREE-ADMIN-' . strtoupper(uniqid()),
            ]);
        } else {
            Enrollment::create([
                'mahasiswa_id' => $request->mahasiswa_id,
                'kelas_id' => $request->kelas_id,
                'payment_status' => 'paid',
                'payment_id' => 'FREE-ADMIN-' . strtoupper(uniqid()),
            ]);
        }

        $kelas = Kelas::findOrFail($request->kelas_id);
        return back()->with('success', "Kelas \"{$kelas->nama_kelas}\" berhasil diberikan gratis ke {$user->nama}.");
    }
}
