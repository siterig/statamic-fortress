<?php

namespace Siterig\Fortress\Widgets;

use Statamic\Widgets\Widget;
use Siterig\Fortress\Security\AuditLogger;

class SecurityStatsWidget extends Widget
{
    public function html()
    {
        $stats = [
            'total_attacks' => AuditLogger::countByType('attack'),
            'brute_force_attempts' => AuditLogger::countByType('brute_force'),
            'blocked_countries' => AuditLogger::countByType('country_block'),
            'vulnerabilities' => AuditLogger::countByType('vulnerability'),
        ];

        return view('fortress::widgets.security-stats', compact('stats'));
    }
} 
 