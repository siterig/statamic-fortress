<?php

namespace Siterig\Fortress\Security;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class WAF
{
    protected $rules = [
        'sql_injection' => [
            'pattern' => '/(\b(select|insert|update|delete|drop|union|exec|where|from|into|values|set)\b.*\b(from|into|values|set|where)\b)/i',
            'description' => 'SQL Injection attempt detected'
        ],
        'xss' => [
            'pattern' => '/(<script|javascript:|on\w+\s*=)/i',
            'description' => 'XSS attack attempt detected'
        ],
        'path_traversal' => [
            'pattern' => '/(\.\.\/|\.\.\\|\.\.\/\.\.|\.\.\\\.\.)/',
            'description' => 'Path traversal attempt detected'
        ],
        'command_injection' => [
            'pattern' => '/(\b(cat|chmod|curl|wget|bash|sh|perl|python|ruby|php)\b)/i',
            'description' => 'Command injection attempt detected'
        ]
    ];

    public function handle(Request $request, Closure $next)
    {
        if ($this->isWhitelisted($request)) {
            return $next($request);
        }

        foreach ($this->rules as $rule) {
            if ($this->checkRule($request, $rule)) {
                $this->logAttack($request, $rule['description']);
                return response()->json(['error' => 'Access denied'], 403);
            }
        }

        return $next($request);
    }

    protected function checkRule(Request $request, array $rule)
    {
        $inputs = array_merge(
            $request->all(),
            $request->headers->all(),
            [$request->getContent()]
        );

        foreach ($inputs as $input) {
            if (is_string($input) && preg_match($rule['pattern'], $input)) {
                return true;
            }
        }

        return false;
    }

    protected function isWhitelisted(Request $request)
    {
        $ip = $request->ip();
        return Cache::get("fortress:whitelist:{$ip}", false);
    }

    protected function logAttack(Request $request, string $description)
    {
        Log::channel('fortress')->warning('WAF Attack Blocked', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'description' => $description,
            'timestamp' => now()
        ]);
    }
} 
