<?php

namespace Siterig\Fortress\Security;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class Settings
{
    protected static $defaults = [
        'waf_enabled' => true,
        'block_suspicious_ips' => true,
        'max_login_attempts' => 5,
        'block_duration' => 30,
        'blocked_countries' => [],
        'geoip_last_update' => null,
    ];

    public static function get()
    {
        $settings = Cache::remember('fortress.settings', 3600, function () {
            return array_merge(
                static::$defaults,
                Config::get('fortress.settings', [])
            );
        });

        return (object) $settings;
    }

    public static function update(array $settings)
    {
        $current = static::get();
        $settings = array_merge((array) $current, $settings);
        
        Config::set('fortress.settings', $settings);
        Cache::put('fortress.settings', $settings, 3600);
    }

    public static function isWafEnabled()
    {
        return static::get()->waf_enabled;
    }

    public static function shouldBlockSuspiciousIps()
    {
        return static::get()->block_suspicious_ips;
    }

    public static function getMaxLoginAttempts()
    {
        return static::get()->max_login_attempts;
    }

    public static function getBlockDuration()
    {
        return static::get()->block_duration;
    }

    public static function getBlockedCountries()
    {
        return static::get()->blocked_countries;
    }

    public static function isCountryBlocked($countryCode)
    {
        return in_array($countryCode, static::getBlockedCountries());
    }
} 
