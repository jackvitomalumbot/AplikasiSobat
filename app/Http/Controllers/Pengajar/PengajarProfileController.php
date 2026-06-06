<?php

namespace App\Http\Controllers\Pengajar;

use App\Http\Controllers\Controller;
use App\Models\PengajarDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            if ($user->foto_profile) {
                Storage::disk('public')->delete($user->foto_profile);
            }
            $user->foto_profile = $request->file('foto_profile')->store('profiles', 'public');
            $user->save();
        }

        $detail = $user->pengajarDetail ?? PengajarDetail::create(['user_id' => $user->id]);
        $detail->update($request->only('kontak', 'spesialisasi', 'bio'));

        return back()->with('success', 'Profile berhasil diperbarui.');
    }
}
