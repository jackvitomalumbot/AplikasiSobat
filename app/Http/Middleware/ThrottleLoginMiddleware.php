<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ThrottleLoginMiddleware
{
    protected RateLimiter $limiter;

    /**
     * Max attempts before lockout.
     */
    protected int $maxAttempts = 5;

    /**
     * Lockout duration in minutes.
     */
    protected int $decayMinutes = 5;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    /**
     * Handle an incoming login request.
     *
     * Rate limits by IP + email combination to prevent brute-force attacks.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only throttle POST login requests
        if ($request->isMethod('POST')) {
            $key = $this->throttleKey($request);

            if ($this->limiter->tooManyAttempts($key, $this->maxAttempts)) {
                $seconds = $this->limiter->availableIn($key);
                $minutes = ceil($seconds / 60);

                Log::warning('Login throttled', [
                    'ip' => $request->ip(),
                    'email' => $request->input('email'),
                    'retry_after' => $seconds,
                ]);

                return back()
                    ->withInput($request->only('email'))
                    ->withErrors([
                        'email' => "Terlalu banyak percobaan login. Silakan coba lagi dalam {$minutes} menit.",
                    ]);
            }

            // Process the login request
            $response = $next($request);

            // If login failed (redirect back with errors), increment attempt
            if ($response->isRedirection() && session()->has('errors')) {
                $this->limiter->hit($key, $this->decayMinutes * 60);

                Log::info('Failed login attempt', [
                    'ip' => $request->ip(),
                    'email' => $request->input('email'),
                    'attempts' => $this->limiter->attempts($key),
                ]);
            } else {
                // Successful login — clear the limiter
                $this->limiter->clear($key);
            }

            return $response;
        }

        return $next($request);
    }

    /**
     * Generate a unique throttle key from the IP and email.
     */
    protected function throttleKey(Request $request): string
    {
        $email = mb_strtolower(trim($request->input('email', '')));
        return 'login|' . $request->ip() . '|' . $email;
    }
}
