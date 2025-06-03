<?php

return [
    /*
    |--------------------------------------------------------------------------
    | WAF Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the Web Application Firewall settings
    |
    */
    'waf' => [
        'enabled' => true,
        'whitelist' => [
            // Add IP addresses to whitelist
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Brute Force Protection
    |--------------------------------------------------------------------------
    |
    | Configure the brute force protection settings
    |
    */
    'brute_force' => [
        'enabled' => true,
        'max_attempts' => 5,
        'decay_minutes' => 30,
        'lockout_minutes' => 60,
    ],

    /*
    |--------------------------------------------------------------------------
    | Country Blocking
    |--------------------------------------------------------------------------
    |
    | Configure country blocking settings
    |
    */
    'country_blocking' => [
        'enabled' => false,
        'blocked_countries' => [
            // Add country codes to block (e.g., 'RU', 'CN')
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | GeoIP Configuration
    |--------------------------------------------------------------------------
    |
    | Configure GeoIP settings for country blocking
    |
    */
    'geoip' => [
        'license_key' => env('MAXMIND_LICENSE_KEY'),
        'database_path' => storage_path('app/geoip/GeoLite2-Country.mmdb'),
        'update_frequency' => 'weekly', // How often to check for updates
        'last_update' => null, // Will be set automatically
    ],

    /*
    |--------------------------------------------------------------------------
    | Vulnerability Scanner
    |--------------------------------------------------------------------------
    |
    | Configure the vulnerability scanner settings
    |
    */
    'vulnerability_scanner' => [
        'enabled' => true,
        'scan_interval' => 3600, // 1 hour
    ],

    /*
    |--------------------------------------------------------------------------
    | Audit Logging
    |--------------------------------------------------------------------------
    |
    | Configure audit logging settings
    |
    */
    'audit_logging' => [
        'enabled' => true,
        'log_channel' => 'fortress',
        'retention_days' => 30,
    ],
]; 
 