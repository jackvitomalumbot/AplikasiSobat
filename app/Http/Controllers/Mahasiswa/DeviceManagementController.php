<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DeviceManagementController extends Controller
{
    /**
     * Show the list of active devices/sessions for the current mahasiswa.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $currentSessionId = $request->session()->getId();

        $sessions = DB::table('sessions')
            ->where('user_id', $user->id)
            ->where('last_activity', '>=', now()->subMinutes(config('session.lifetime', 120))->getTimestamp())
            ->orderByDesc('last_activity')
            ->get()
            ->map(function ($session) use ($currentSessionId) {
                return (object) [
                    'id' => $session->id,
                    'ip_address' => $session->ip_address ?? 'Tidak diketahui',
                    'user_agent' => $this->parseUserAgent($session->user_agent ?? ''),
                    'last_activity' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
                    'last_activity_raw' => $session->last_activity,
                    'is_current' => $session->id === $currentSessionId,
                ];
            });

        return view('mahasiswa.devices', compact('sessions'));
    }

    /**
     * Revoke/destroy a specific session (remote logout).
     */
    public function destroy(Request $request, string $sessionId)
    {
        $user = $request->user();
        $currentSessionId = $request->session()->getId();

        // Prevent deleting current session via this route
        if ($sessionId === $currentSessionId) {
            return back()->with('error', 'Gunakan tombol Logout untuk keluar dari perangkat ini.');
        }

        // Only allow deleting own sessions
        $deleted = DB::table('sessions')
            ->where('id', $sessionId)
            ->where('user_id', $user->id)
            ->delete();

        if ($deleted) {
            return back()->with('success', 'Perangkat berhasil di-logout.');
        }

        return back()->with('error', 'Session tidak ditemukan.');
    }

    /**
     * Parse user agent string into a human-readable device description.
     */
    protected function parseUserAgent(string $ua): string
    {
        if (empty($ua)) {
            return 'Perangkat tidak diketahui';
        }

        $browser = 'Browser tidak diketahui';
        $os = 'OS tidak diketahui';

        // Detect browser
        if (str_contains($ua, 'Edg/')) {
            $browser = 'Microsoft Edge';
        } elseif (str_contains($ua, 'Chrome/') && !str_contains($ua, 'Edg/')) {
            $browser = 'Google Chrome';
        } elseif (str_contains($ua, 'Firefox/')) {
            $browser = 'Mozilla Firefox';
        } elseif (str_contains($ua, 'Safari/') && !str_contains($ua, 'Chrome/')) {
            $browser = 'Safari';
        } elseif (str_contains($ua, 'Opera') || str_contains($ua, 'OPR/')) {
            $browser = 'Opera';
        }

        // Detect OS
        if (str_contains($ua, 'Windows')) {
            $os = 'Windows';
        } elseif (str_contains($ua, 'Macintosh') || str_contains($ua, 'Mac OS')) {
            $os = 'macOS';
        } elseif (str_contains($ua, 'Linux') && !str_contains($ua, 'Android')) {
            $os = 'Linux';
        } elseif (str_contains($ua, 'Android')) {
            $os = 'Android';
        } elseif (str_contains($ua, 'iPhone') || str_contains($ua, 'iPad')) {
            $os = 'iOS';
        }

        return "{$browser} — {$os}";
    }
}
