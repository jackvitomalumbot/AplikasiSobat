<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class HelpController extends Controller
{
    public function index()
    {
        return view('help');
    }

    public function sendEmail(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // For now, just flash a success message
        // In production, use Mail::send() with proper mail configuration
        return back()->with('success', 'Email bantuan berhasil dikirim! Tim kami akan merespons dalam 1x24 jam.');
    }
}
