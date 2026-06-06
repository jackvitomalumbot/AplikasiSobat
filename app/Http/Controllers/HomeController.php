<?php

namespace App\Http\Controllers;

use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $featuredPengajar = User::where('role', 'pengajar')
            ->with('pengajarDetail')
            ->take(3)
            ->get();

        $allPengajar = User::where('role', 'pengajar')
            ->with('pengajarDetail')
            ->get();

        return view('welcome', compact('featuredPengajar', 'allPengajar'));
    }
}
