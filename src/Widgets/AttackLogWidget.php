<?php

namespace Siterig\Fortress\Widgets;

use Statamic\Widgets\Widget;
use Siterig\Fortress\Security\AuditLogger;

class AttackLogWidget extends Widget
{
    public function html()
    {
        $logs = AuditLogger::getRecentActivity(5);

        return view('fortress::widgets.attack-log', compact('logs'));
    }
} 
 