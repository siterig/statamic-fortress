<?php

namespace Siterig\Fortress\Http\Controllers;

use Illuminate\Http\Request;
use Siterig\Fortress\Security\AuditLogger;

class LogsController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type');
        $logs = AuditLogger::query()
            ->when($type, function ($query) use ($type) {
                return $query->where('type', $type);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('fortress::logs', compact('logs'));
    }
} 
