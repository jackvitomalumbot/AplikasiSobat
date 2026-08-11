<?php

namespace App\Http\Controllers;

use App\Models\PengajarUnggulan;

class HomeController extends Controller
{
    public function index()
    {
        $featuredPengajar = PengajarUnggulan::where('aktif', true)
            ->orderBy('urutan')
            ->orderBy('id')
            ->take(3)
            ->get();

        return view('welcome', compact('featuredPengajar'));
    }
}

