<?php

namespace Siterig\Fortress\Security;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class BruteForceProtection
{
    protected $maxAttempts = 5;
    protected $decayMinutes = 30;
    protected $lockoutMinutes = 60;

    public function handle(Request $request, Closure $next)
    {
        if ($this->isBlocked($request)) {
            return response()->json([
                'error' => 'Too many login attempts. Please try again later.',
                'retry_after' => $this->getRetryAfter($request)
            ], 429);
        }

        $response = $next($request);

        if ($response->getStatusCode() === 401) {
            $this->incrementAttempts($request);
        } else {
            $this->clearAttempts($request);
        }

        return $response;
    }

    protected function isBlocked(Request $request)
    {
        $key = $this->getCacheKey($request);
        return Cache::get($key) >= $this->maxAttempts;
    }

    protected function incrementAttempts(Request $request)
    {
        $key = $this->getCacheKey($request);
        $attempts = Cache::get($key, 0) + 1;
        
        if ($attempts >= $this->maxAttempts) {
            $this->logBlockedAttempt($request);
            Cache::put($key, $attempts, $this->lockoutMinutes * 60);
        } else {
            Cache::put($key, $attempts, $this->decayMinutes * 60);
        }
    }

    protected function clearAttempts(Request $request)
    {
        Cache::forget($this->getCacheKey($request));
    }

    protected function getCacheKey(Request $request)
    {
        return 'fortress:login_attempts:' . sha1($request->ip() . $request->input('email'));
    }

    protected function getRetryAfter(Request $request)
    {
        $key = $this->getCacheKey($request);
        $ttl = Cache::getTimeToLive($key);
        return max(0, $ttl);
    }

    protected function logBlockedAttempt(Request $request)
    {
        Log::channel('fortress')->warning('Brute Force Attack Blocked', [
            'ip' => $request->ip(),
            'email' => $request->input('email'),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()
        ]);
    }
} 
