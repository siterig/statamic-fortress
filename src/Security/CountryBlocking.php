<?php

namespace Siterig\Fortress\Security;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use GeoIp2\Database\Reader;

class CountryBlocking
{
    protected $blockedCountries = [];
    protected $geoipReader;

    public function __construct()
    {
        $this->blockedCountries = config('fortress.blocked_countries', []);
        $this->geoipReader = new Reader(storage_path('app/geoip/GeoLite2-Country.mmdb'));
    }

    public function handle(Request $request, Closure $next)
    {
        if ($this->isWhitelisted($request)) {
            return $next($request);
        }

        try {
            $country = $this->getCountryFromIp($request->ip());
            
            if (in_array($country, $this->blockedCountries)) {
                $this->logBlockedAccess($request, $country);
                return response()->json(['error' => 'Access denied from your country'], 403);
            }
        } catch (\Exception $e) {
            Log::channel('fortress')->error('Country blocking error: ' . $e->getMessage());
        }

        return $next($request);
    }

    protected function getCountryFromIp($ip)
    {
        $cacheKey = "fortress:country:{$ip}";
        
        return Cache::remember($cacheKey, 3600, function () use ($ip) {
            $record = $this->geoipReader->country($ip);
            return $record->country->isoCode;
        });
    }

    protected function isWhitelisted(Request $request)
    {
        $ip = $request->ip();
        return Cache::get("fortress:whitelist:{$ip}", false);
    }

    protected function logBlockedAccess(Request $request, string $country)
    {
        Log::channel('fortress')->warning('Country Blocked Access', [
            'ip' => $request->ip(),
            'country' => $country,
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'timestamp' => now()
        ]);
    }
} 
