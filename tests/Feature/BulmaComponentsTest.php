<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Bulma Components Integration Tests
 * 
 * Tests the Bulma migration components functionality
 */
class BulmaComponentsTest extends TestCase
{
    /**
     * Test Bulma layout templates can be rendered
     */
    public function test_bulma_layout_renders()
    {
        // Test basic layout rendering without database dependencies
        $view = view('layouts.index-bulma');
        $view->with('slot', '<div>Test Content</div>');
        
        $html = $view->render();
        
        // Test for basic HTML structure
        $this->assertStringContainsString('<!DOCTYPE html>', $html);
        $this->assertStringContainsString('x-data', $html);
        $this->assertStringContainsString('Test Content', $html);
    }
    
    /**
     * Test Bulma DataGrid component renders
     */
    public function test_bulma_datagrid_component()
    {
        // Sample data for testing
        $data = [
            ['id' => 1, 'name' => 'Test Item', 'status' => 'Active']
        ];
        
        $columns = [
            ['field' => 'id', 'title' => 'ID'],
            ['field' => 'name', 'title' => 'Name'],
            ['field' => 'status', 'title' => 'Status']
        ];
        
        $view = view('components.datagrid-bulma', [
            'data' => $data,
            'columns' => $columns,
            'config' => []
        ]);
        
        $html = $view->render();
        
        // Test for Bulma table classes
        $this->assertStringContainsString('class="table', $html);
        $this->assertStringContainsString('x-data="dataGrid', $html);
        $this->assertStringContainsString('Test Item', $html);
    }
    
    /**
     * Test Bulma CSS is included in build
     */
    public function test_bulma_css_exists()
    {
        $manifestPath = public_path('build/manifest.json');
        
        if (!file_exists($manifestPath)) {
            $this->markTestSkipped('Build manifest not found. Run npm run build first.');
        }
        
        $manifest = json_decode(file_get_contents($manifestPath), true);
        
        // Check if Bulma SASS entry exists
        $this->assertArrayHasKey('resources/sass/app.scss', $manifest);
        
        // Check if CSS file exists
        $bulmaCSS = $manifest['resources/sass/app.scss']['file'];
        $this->assertFileExists(public_path('build/' . $bulmaCSS));
    }
    
    /**
     * Test JavaScript components are properly built
     */
    public function test_javascript_components_built()
    {
        $manifestPath = public_path('build/manifest.json');
        
        if (!file_exists($manifestPath)) {
            $this->markTestSkipped('Build manifest not found. Run npm run build first.');
        }
        
        $manifest = json_decode(file_get_contents($manifestPath), true);
        
        // Check if main JS entry exists
        $this->assertArrayHasKey('resources/js/app.js', $manifest);
        
        // Check if JS file exists
        $mainJS = $manifest['resources/js/app.js']['file'];
        $this->assertFileExists(public_path('build/' . $mainJS));
        
        // Check for code splitting (vendor chunk should exist)
        $hasVendorChunk = false;
        foreach ($manifest as $entry) {
            if (isset($entry['name']) && $entry['name'] === 'vendor') {
                $hasVendorChunk = true;
                break;
            }
        }
        
        $this->assertTrue($hasVendorChunk, 'Vendor chunk should exist for optimized builds');
    }
    
    /**
     * Test migration service functionality
     */
    public function test_migration_service()
    {
        $status = \App\Services\MigrationService::getMigrationStatus();
        
        $this->assertIsArray($status);
        $this->assertArrayHasKey('overall_progress', $status);
        $this->assertArrayHasKey('phases', $status);
        $this->assertArrayHasKey('pages', $status);
        
        // Test feature flags
        $bulmaEnabled = \App\Services\MigrationService::isFeatureEnabled('bulma_layout');
        $this->assertIsBool($bulmaEnabled);
        
        // Test template selection
        $template = \App\Services\MigrationService::getTemplate('frame_report');
        $this->assertIsString($template);
    }
    
    /**
     * Test Bulma header component structure
     */
    public function test_bulma_header_component()
    {
        // Test the template structure directly
        $headerContent = file_get_contents(app_path('UI/layouts/header-bulma.blade.php'));
        
        // Test for Bulma navbar classes
        $this->assertStringContainsString('navbar is-primary', $headerContent);
        $this->assertStringContainsString('navbar-brand', $headerContent);
        $this->assertStringContainsString('navbar-menu', $headerContent);
        
        // Test for Alpine.js dropdown functionality
        $this->assertStringContainsString('x-data="dropdown"', $headerContent);
        
        // Test for basic navbar structure
        $this->assertStringContainsString('navbar-item', $headerContent);
        $this->assertStringContainsString('logo', $headerContent);
    }
    
    /**
     * Test Bulma sidebar component structure
     */
    public function test_bulma_sidebar_component()
    {
        // Test the basic structure without complex dependencies
        // Focus on the template structure rather than dynamic content
        $sidebarContent = file_get_contents(app_path('UI/layouts/sidebar-bulma.blade.php'));
        
        // Test for basic sidebar structure in the template file
        $this->assertStringContainsString('class="app-sidebar"', $sidebarContent);
        $this->assertStringContainsString('class="menu"', $sidebarContent);
        
        // Test for Alpine.js accordion functionality
        $this->assertStringContainsString('x-data="accordion"', $sidebarContent);
        
        // Test for user menu structure
        $this->assertStringContainsString('user-menu', $sidebarContent);
    }
    
    /**
     * Test responsive design classes
     */
    public function test_responsive_design()
    {
        $view = view('layouts.index-bulma');
        $view->with('slot', '<div class="content">Responsive test content</div>');
        
        $html = $view->render();
        
        // Test for responsive meta tag
        $this->assertStringContainsString('name="viewport"', $html);
        $this->assertStringContainsString('width=device-width', $html);
        
        // Test for Alpine.js integration
        $this->assertStringContainsString('x-data', $html);
        
        // Test basic structure
        $this->assertStringContainsString('Responsive test content', $html);
    }
    
    /**
     * Test accessibility features
     */
    public function test_accessibility_features()
    {
        // Test the layout template structure for accessibility features
        $view = view('layouts.index-bulma');
        $view->with('slot', ''); // Empty slot to focus on layout
        
        $html = $view->render();
        
        // Test for basic accessibility structure
        $this->assertStringContainsString('lang="en"', $html);
        $this->assertStringContainsString('charset="utf-8"', $html);
        $this->assertStringContainsString('name="viewport"', $html);
        
        // Test for proper HTML structure
        $this->assertStringContainsString('<!DOCTYPE html>', $html);
        $this->assertStringContainsString('x-data', $html);
        
        // Test that the basic HTML document structure is valid
        $this->assertStringContainsString('<head>', $html);
        $this->assertStringContainsString('<body', $html);
    }
    
    /**
     * Test performance optimization
     */
    public function test_performance_optimization()
    {
        $manifestPath = public_path('build/manifest.json');
        
        if (!file_exists($manifestPath)) {
            $this->markTestSkipped('Build manifest not found. Run npm run build first.');
        }
        
        $manifest = json_decode(file_get_contents($manifestPath), true);
        
        // Test for lazy loading (dynamic imports)
        $hasDynamicImports = false;
        foreach ($manifest as $entry) {
            if (isset($entry['dynamicImports']) && !empty($entry['dynamicImports'])) {
                $hasDynamicImports = true;
                break;
            }
        }
        
        $this->assertTrue($hasDynamicImports, 'Should have dynamic imports for lazy loading');
        
        // Test bundle size limits (approximate)
        $mainEntry = $manifest['resources/js/app.js'];
        $mainJSPath = public_path('build/' . $mainEntry['file']);
        
        if (file_exists($mainJSPath)) {
            $fileSize = filesize($mainJSPath);
            $fileSizeKB = $fileSize / 1024;
            
            // Main bundle should be under 120KB (after optimization)
            $this->assertLessThan(125, $fileSizeKB, 'Main JS bundle should be under 125KB');
        }
    }
    
    /**
     * Test component library exports
     */
    public function test_component_library()
    {
        $componentIndexPath = resource_path('js/components/index.js');
        $this->assertFileExists($componentIndexPath);
        
        $content = file_get_contents($componentIndexPath);
        
        // Test for proper exports
        $this->assertStringContainsString('export const BulmaComponents', $content);
        $this->assertStringContainsString('export { default as dataGrid }', $content);
        $this->assertStringContainsString('register: (Alpine)', $content);
    }
}