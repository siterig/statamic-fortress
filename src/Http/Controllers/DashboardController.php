<?php

namespace Siterig\Fortress\Http\Controllers;

use Illuminate\Http\Request;
use Siterig\Fortress\Security\AuditLogger;
use Siterig\Fortress\Security\VulnerabilityScanner;

class DashboardController extends Controller
{
    public function index()
    {
        $recentActivity = AuditLogger::getRecentActivity(10);

        $stats = [
            'total_attacks' => AuditLogger::countByType('attack'),
            'brute_force_attempts' => AuditLogger::countByType('brute_force'),
            'blocked_countries' => AuditLogger::countByType('country_block'),
            'vulnerabilities' => AuditLogger::countByType('vulnerability'),
        ];

        return view('fortress::dashboard', compact('recentActivity', 'stats'));
    }

    public function stats()
    {
        return response()->json([
            'total_attacks' => AuditLogger::countByType('attack'),
            'brute_force_attempts' => AuditLogger::countByType('brute_force'),
            'blocked_countries' => AuditLogger::countByType('country_block'),
            'vulnerabilities' => AuditLogger::countByType('vulnerability'),
        ]);
    }

    public function scan()
    {
        $scanner = new VulnerabilityScanner();
        $results = $scanner->scan();

        return response()->json([
            'vulnerabilities' => $results,
        ]);
    }
} 
 