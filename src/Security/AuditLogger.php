<?php

namespace Siterig\Fortress\Security;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AuditLogger
{
    protected static $storagePath = 'storage/fortress/logs';
    protected static $logFile = 'security.json';

    protected static function ensureStorageExists()
    {
        if (!File::exists(static::$storagePath)) {
            File::makeDirectory(static::$storagePath, 0755, true);
        }
    }

    protected static function getLogPath()
    {
        return static::$storagePath . '/' . static::$logFile;
    }

    protected static function getLogs()
    {
        static::ensureStorageExists();
        
        $path = static::getLogPath();
        if (!File::exists($path)) {
            return [];
        }

        return json_decode(File::get($path), true) ?? [];
    }

    protected static function saveLogs($logs)
    {
        static::ensureStorageExists();
        File::put(static::getLogPath(), json_encode($logs, JSON_PRETTY_PRINT));
    }

    public static function log($type, $ipAddress, $details = [], $country = null)
    {
        try {
            $logs = static::getLogs();
            
            $logs[] = [
                'id' => (string) Str::uuid(),
                'type' => $type,
                'ip_address' => $ipAddress,
                'country' => $country,
                'details' => $details,
                'created_at' => now()->toIso8601String(),
            ];

            static::saveLogs($logs);
        } catch (\Exception $e) {
            Log::error('Failed to log security event: ' . $e->getMessage(), [
                'type' => $type,
                'ip_address' => $ipAddress,
                'details' => $details,
            ]);
        }
    }

    public static function getRecentActivity($limit = 10)
    {
        $logs = static::getLogs();
        return collect($logs)
            ->sortByDesc('created_at')
            ->take($limit)
            ->values();
    }

    public static function countByType($type)
    {
        return collect(static::getLogs())
            ->where('type', $type)
            ->count();
    }

    public static function query()
    {
        return collect(static::getLogs());
    }

    public static function logLogin($user, $ipAddress)
    {
        return static::log('login', $ipAddress, [
            'user_id' => $user->id,
            'email' => $user->email,
        ]);
    }

    public static function logLogout($user, $ipAddress)
    {
        return static::log('logout', $ipAddress, [
            'user_id' => $user->id,
            'email' => $user->email,
        ]);
    }

    public static function logFailedLogin($email, $ipAddress)
    {
        return static::log('failed_login', $ipAddress, [
            'email' => $email,
        ]);
    }

    public static function logAttack($ipAddress, $details)
    {
        return static::log('attack', $ipAddress, $details);
    }

    public static function logBruteForce($ipAddress, $details)
    {
        return static::log('brute_force', $ipAddress, $details);
    }

    public static function logCountryBlock($ipAddress, $country, $details = [])
    {
        return static::log('country_block', $ipAddress, $details, $country);
    }

    public static function logVulnerability($details)
    {
        return static::log('vulnerability', request()->ip(), $details);
    }
} 
 