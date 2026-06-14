<?php

namespace App\Http\Controllers\Pengajar;

use App\Http\Controllers\Controller;
use App\Models\PengajarDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PengajarProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user()->load('pengajarDetail');
        return view('pengajar.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'kontak' => 'nullable|string|max:20',
            'spesialisasi' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'foto_profile' => 'nullable|image|max:2048',
        ]);

        $user = auth()->user();

        if ($request->hasFile('foto_profile')) {
            // Delete old photo
            if ($user->foto_profile && File::exists(public_path($user->foto_profile))) {
                File::delete(public_path($user->foto_profile));
            }

            $file = $request->file('foto_profile');
            $filename = 'profile_' . $user->id . '_' . time() . '.' . ($file->getClientOriginalExtension() ?: 'jpg');
            $destPath = public_path('uploads/profiles');
            if (!File::isDirectory($destPath)) {
                File::makeDirectory($destPath, 0755, true);
            }
            $file->move($destPath, $filename);
            $user->foto_profile = 'uploads/profiles/' . $filename;
            $user->save();
        }

        $detail = $user->pengajarDetail ?? PengajarDetail::create(['user_id' => $user->id]);
        $detail->update($request->only('kontak', 'spesialisasi', 'bio'));

        return back()->with('success', 'Profile berhasil diperbarui.');
    }
}
