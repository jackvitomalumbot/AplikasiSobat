<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class DeviceLimitMiddleware
{
    /**
     * Maximum number of concurrent sessions allowed for mahasiswa.
     */
    protected int $maxDevices = 2;

    /**
     * Handle an incoming request.
     *
     * Only enforced for users with role 'mahasiswa'.
     * Checks the sessions table for active sessions belonging to this user.
     * If the current session is already counted, it's allowed through.
     * If there are already $maxDevices other sessions, block the request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Only apply to authenticated mahasiswa
        if (!$user || $user->role !== 'mahasiswa') {
            return $next($request);
        }

        $currentSessionId = $request->session()->getId();

        // Count active sessions for this user, excluding the current one
        $activeSessions = DB::table('sessions')
            ->where('user_id', $user->id)
            ->where('id', '!=', $currentSessionId)
            ->where('last_activity', '>=', now()->subMinutes(config('session.lifetime', 120))->getTimestamp())
            ->count();

        if ($activeSessions >= $this->maxDevices) {
            // Force logout this session since it exceeds the limit
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/login')->withErrors([
                'email' => 'Anda sudah login di ' . $this->maxDevices . ' perangkat. Silakan logout dari salah satu perangkat terlebih dahulu, atau kelola perangkat Anda setelah login.',
            ]);
        }

        return $next($request);
    }
}
