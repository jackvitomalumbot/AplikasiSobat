<?php

namespace App\Http\Controllers;

use App\Models\PengajarUnggulan;

class HomeController extends Controller
{
    public function index()
    {
        $featuredPengajar = PengajarUnggulan::where('aktif', true)
            ->where('tipe', 'unggulan')
            ->orderBy('urutan')
            ->orderBy('id')
            ->take(3)
            ->get();

        $rekanPengajar = PengajarUnggulan::where('aktif', true)
            ->where('tipe', 'rekan')
            ->orderBy('urutan')
            ->orderBy('id')
            ->get();

        return view('welcome', compact('featuredPengajar', 'rekanPengajar'));
    }
}

