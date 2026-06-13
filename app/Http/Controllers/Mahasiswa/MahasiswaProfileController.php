<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class MahasiswaProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user()->load('mahasiswaDetail');
        return view('mahasiswa.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'foto_profile' => 'nullable|image|max:2048',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        $user = auth()->user();
        $user->nama = $request->nama;

        if ($request->hasFile('foto_profile')) {
            // Delete old photo
            if ($user->foto_profile && File::exists(public_path($user->foto_profile))) {
                File::delete(public_path($user->foto_profile));
            }

            $file = $request->file('foto_profile');
            $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/profiles'), $filename);
            $user->foto_profile = 'uploads/profiles/' . $filename;
        }

        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
            }
            $user->password = $request->new_password;
        }

        $user->save();

        return back()->with('success', 'Profile berhasil diperbarui.');
    }
}
