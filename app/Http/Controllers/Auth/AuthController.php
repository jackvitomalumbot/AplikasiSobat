<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\MahasiswaDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Sanitize email
        $credentials['email'] = mb_strtolower(trim($credentials['email']));

        // Check device limit BEFORE attempting login (for mahasiswa)
        $user = User::where('email', $credentials['email'])->first();

        if ($user && $user->role === 'mahasiswa') {
            $activeSessions = DB::table('sessions')
                ->where('user_id', $user->id)
                ->where('last_activity', '>=', now()->subMinutes(config('session.lifetime', 120))->getTimestamp())
                ->count();

            if ($activeSessions >= 2) {
                Log::info('Login blocked: device limit reached', [
                    'email' => $credentials['email'],
                    'ip' => $request->ip(),
                    'active_sessions' => $activeSessions,
                ]);

                return back()->withErrors([
                    'email' => 'Anda sudah login di 2 perangkat. Silakan logout dari salah satu perangkat terlebih dahulu.',
                ])->onlyInput('email');
            }
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            Log::info('User logged in', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $user->role,
                'ip' => $request->ip(),
            ]);

            return match ($user->role) {
                'admin' => redirect('/admin/dashboard'),
                'pengajar' => redirect('/pengajar/dashboard'),
                'mahasiswa' => redirect('/mahasiswa/dashboard'),
                default => redirect('/'),
            };
        }

        Log::info('Failed login attempt', [
            'email' => $credentials['email'],
            'ip' => $request->ip(),
        ]);

        return back()->withErrors([
            'email' => 'Email atau password tidak valid.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|min:3|max:255',
            'universitas' => 'required|string|max:255',
            'nim' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'nama' => $request->nama,
            'email' => mb_strtolower(trim($request->email)),
            'password' => $request->password,
            'role' => 'mahasiswa',
        ]);

        MahasiswaDetail::create([
            'user_id' => $user->id,
            'universitas' => $request->universitas,
            'nim' => $request->nim,
        ]);

        event(new Registered($user));

        Auth::login($user);

        Log::info('New user registered', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $request->ip(),
        ]);

        return redirect('/email/verify');
    }

    public function logout(Request $request)
    {
        $userId = Auth::id();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Log::info('User logged out', [
            'user_id' => $userId,
            'ip' => $request->ip(),
        ]);

        return redirect('/');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    public function showResetPassword(Request $request, string $token)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill(['password' => Hash::make($password)])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect('/login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    public function verifyEmail()
    {
        return auth()->user()->hasVerifiedEmail()
            ? redirect(auth()->user()->role === 'mahasiswa' ? '/mahasiswa/dashboard' : '/')
            : view('auth.verify-email');
    }

    public function resendVerification(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
