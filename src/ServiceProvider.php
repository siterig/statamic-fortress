<?php

namespace Siterig\Fortress;

use Statamic\Providers\AddonServiceProvider;
use Siterig\Fortress\Security\WAF;
use Siterig\Fortress\Security\BruteForceProtection;
use Siterig\Fortress\Security\CountryBlocking;
use Siterig\Fortress\Security\VulnerabilityScanner;
use Siterig\Fortress\Security\AuditLogger;
use Siterig\Fortress\Widgets\SecurityStatsWidget;
use Siterig\Fortress\Widgets\AttackLogWidget;
use Siterig\Fortress\Widgets\VulnerabilityWidget;
use Siterig\Fortress\Commands\UpdateGeoIPDatabase;
use Statamic\Statamic;

class ServiceProvider extends AddonServiceProvider
{
    protected $routes = [
        'cp' => __DIR__.'/../routes/cp.php',
    ];

    protected $widgets = [
        SecurityStatsWidget::class,
        AttackLogWidget::class,
        VulnerabilityWidget::class,
    ];

    protected $commands = [
        UpdateGeoIPDatabase::class,
    ];

    protected $vite = [
        'input' => [
            'resources/js/cp.js',
            'resources/css/cp.css'
        ],
        'publicDirectory' => 'public/vendor/fortress',
        'buildDirectory' => 'build',
    ];

    public function bootAddon()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'fortress');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'fortress');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Register middleware
        $this->app['router']->aliasMiddleware('fortress.waf', WAF::class);
        $this->app['router']->aliasMiddleware('fortress.brute-force', BruteForceProtection::class);
        $this->app['router']->aliasMiddleware('fortress.country-block', CountryBlocking::class);

        // Register event listeners
        $this->app['events']->listen('auth.login', [AuditLogger::class, 'logLogin']);
        $this->app['events']->listen('auth.logout', [AuditLogger::class, 'logLogout']);

        $this->registerNavigation();
    }

    protected function registerNavigation()
    {
        Statamic::provideToScript([
            'fortress' => [
                'name' => 'Fortress',
                'icon' => 'shield-check',
                'url' => '/cp/fortress',
                'children' => [
                    [
                        'name' => 'Dashboard',
                        'url' => '/cp/fortress',
                    ],
                    [
                        'name' => 'Security Logs',
                        'url' => '/cp/fortress/logs',
                    ],
                    [
                        'name' => 'Settings',
                        'url' => '/cp/fortress/settings',
                    ],
                ],
            ],
        ]);

        Statamic::provideToScript([
            'fortress.widgets' => [
                'security_stats' => [
                    'name' => 'Security Stats',
                    'component' => 'fortress-security-stats',
                ],
                'attack_log' => [
                    'name' => 'Attack Log',
                    'component' => 'fortress-attack-log',
                ],
                'vulnerability' => [
                    'name' => 'Vulnerability Scanner',
                    'component' => 'fortress-vulnerability',
                ],
            ],
        ]);
    }
}
