<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

/**
 * Migration Service - Bulma Gradual Migration
 * 
 * Handles the gradual migration from Fomantic-UI to Bulma
 * with feature flags and A/B testing capabilities
 */
class MigrationService
{
    /**
     * Migration configuration
     */
    const MIGRATION_CONFIG = [
        'phases' => [
            'phase1' => [
                'name' => 'Foundation Setup',
                'status' => 'completed',
                'description' => 'Bulma installation and SASS configuration'
            ],
            'phase2' => [
                'name' => 'Layout Migration',
                'status' => 'completed', 
                'description' => 'Header, sidebar, and main layout templates'
            ],
            'phase3' => [
                'name' => 'Component Integration',
                'status' => 'completed',
                'description' => 'DataGrid and interactive components'
            ],
            'phase4' => [
                'name' => 'Production Optimization',
                'status' => 'completed',
                'description' => 'Performance optimization and deployment'
            ],
            'phase5' => [
                'name' => 'Gradual Migration',
                'status' => 'in_progress',
                'description' => 'Page-by-page migration with A/B testing'
            ]
        ],
        
        'features' => [
            'bulma_layout' => [
                'enabled' => true,
                'traffic_percentage' => 100,
                'description' => 'Use Bulma layout templates'
            ],
            'bulma_components' => [
                'enabled' => true,
                'traffic_percentage' => 100,
                'description' => 'Use Bulma interactive components'
            ],
            'bulma_datagrid' => [
                'enabled' => true,
                'traffic_percentage' => 100,
                'description' => 'Use Bulma DataGrid component'
            ],
            'legacy_fallback' => [
                'enabled' => true,
                'traffic_percentage' => 100,
                'description' => 'Keep Fomantic-UI as fallback'
            ]
        ],
        
        'pages' => [
            'frame_report' => [
                'status' => 'migrated',
                'bulma_template' => 'Frame.Report.report-bulma',
                'original_template' => 'Frame.Report.report',
                'traffic_split' => 100 // 100% Bulma
            ],
            'frame_list' => [
                'status' => 'pending',
                'bulma_template' => 'Frame.List.index-bulma',
                'original_template' => 'Frame.List.index',
                'traffic_split' => 0 // 0% Bulma, 100% original
            ],
            'construction_report' => [
                'status' => 'pending',
                'bulma_template' => 'Construction.Report.report-bulma',
                'original_template' => 'Construction.Report.report',
                'traffic_split' => 0
            ]
        ]
    ];
    
    /**
     * Check if a feature is enabled for the current user
     */
    public static function isFeatureEnabled(string $feature): bool
    {
        $config = self::MIGRATION_CONFIG['features'][$feature] ?? null;
        
        if (!$config || !$config['enabled']) {
            return false;
        }
        
        // Always enabled for admins and development
        if (self::isAdmin() || app()->environment('local')) {
            return true;
        }
        
        // Check traffic percentage
        $userPercentile = self::getUserPercentile();
        return $userPercentile <= $config['traffic_percentage'];
    }
    
    /**
     * Get the appropriate template for a page
     */
    public static function getTemplate(string $page): string
    {
        $config = self::MIGRATION_CONFIG['pages'][$page] ?? null;
        
        if (!$config) {
            return $page; // Return original page name if not configured
        }
        
        // Force Bulma for admins in development
        if (self::isAdmin() && app()->environment('local')) {
            return $config['bulma_template'];
        }
        
        // Check traffic split
        $userPercentile = self::getUserPercentile();
        
        if ($userPercentile <= $config['traffic_split']) {
            return $config['bulma_template'];
        }
        
        return $config['original_template'];
    }
    
    /**
     * Get migration status for dashboard
     */
    public static function getMigrationStatus(): array
    {
        $phases = self::MIGRATION_CONFIG['phases'];
        $pages = self::MIGRATION_CONFIG['pages'];
        
        $totalPages = count($pages);
        $migratedPages = count(array_filter($pages, fn($page) => $page['status'] === 'migrated'));
        $completedPhases = count(array_filter($phases, fn($phase) => $phase['status'] === 'completed'));
        
        return [
            'overall_progress' => round(($migratedPages / $totalPages) * 100, 1),
            'phases' => [
                'total' => count($phases),
                'completed' => $completedPhases,
                'in_progress' => count(array_filter($phases, fn($phase) => $phase['status'] === 'in_progress')),
                'pending' => count(array_filter($phases, fn($phase) => $phase['status'] === 'pending'))
            ],
            'pages' => [
                'total' => $totalPages,
                'migrated' => $migratedPages,
                'pending' => $totalPages - $migratedPages
            ],
            'features_enabled' => count(array_filter(
                self::MIGRATION_CONFIG['features'], 
                fn($feature) => $feature['enabled']
            ))
        ];
    }
    
    /**
     * Get component preference (Bulma vs Fomantic-UI)
     */
    public static function getComponentPreference(string $component): string
    {
        // Component mapping
        $componentMap = [
            'datagrid' => 'bulma_datagrid',
            'layout' => 'bulma_layout',
            'interactive' => 'bulma_components'
        ];
        
        $feature = $componentMap[$component] ?? null;
        
        if ($feature && self::isFeatureEnabled($feature)) {
            return 'bulma';
        }
        
        return 'fomantic';
    }
    
    /**
     * Log migration usage for analytics
     */
    public static function logUsage(string $component, string $variant): void
    {
        // In production, this would log to analytics service
        if (app()->environment('production')) {
            // Log to analytics service
            logger()->info('Migration Usage', [
                'component' => $component,
                'variant' => $variant,
                'user_id' => auth()->id(),
                'session_id' => session()->getId(),
                'timestamp' => now()
            ]);
        }
    }
    
    /**
     * Get user percentile for A/B testing (0-100)
     */
    private static function getUserPercentile(): int
    {
        // Use session-based percentile for consistency
        if (Session::has('user_percentile')) {
            return Session::get('user_percentile');
        }
        
        // Generate percentile based on user ID or session
        $identifier = auth()->id() ?? session()->getId();
        $percentile = crc32($identifier) % 100;
        
        Session::put('user_percentile', $percentile);
        
        return $percentile;
    }
    
    /**
     * Check if current user is admin
     */
    private static function isAdmin(): bool
    {
        return auth()->check() && 
               (auth()->user()->level === 'ADMIN' || auth()->user()->level === 'MASTER');
    }
    
    /**
     * Force enable feature for testing
     */
    public static function forceEnableFeature(string $feature): void
    {
        if (app()->environment(['local', 'testing'])) {
            Session::put("force_enable_{$feature}", true);
        }
    }
    
    /**
     * Get CSS framework preference
     */
    public static function getCSSFramework(): string
    {
        if (self::isFeatureEnabled('bulma_layout')) {
            return 'bulma';
        }
        
        return 'fomantic';
    }
    
    /**
     * Get JavaScript framework components
     */
    public static function getJSComponents(): array
    {
        $components = [];
        
        if (self::isFeatureEnabled('bulma_components')) {
            $components[] = 'bulma-components';
        }
        
        // Always include original components for fallback
        if (self::isFeatureEnabled('legacy_fallback')) {
            $components[] = 'original-components';
        }
        
        return $components;
    }
}