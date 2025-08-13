/**
 * Performance Audit Script for Bulma Migration
 * Tests Core Web Vitals and performance metrics
 */

import lighthouse from 'lighthouse';
import * as chromeLauncher from 'chrome-launcher';
import fs from 'fs';

const PERFORMANCE_TARGETS = {
    // Core Web Vitals targets
    LCP: 2.5,  // Largest Contentful Paint (seconds)
    FID: 100,  // First Input Delay (milliseconds)  
    CLS: 0.1,  // Cumulative Layout Shift

    // Additional performance metrics
    FCP: 1.8,  // First Contentful Paint (seconds)
    TTI: 3.8,  // Time to Interactive (seconds)
    TBT: 200,  // Total Blocking Time (milliseconds)
    SI: 3.4,   // Speed Index (seconds)

    // Bundle size targets (KB)
    MAIN_JS_SIZE: 120,
    VENDOR_JS_SIZE: 50,
    CSS_SIZE: 800,
    
    // Performance score targets (0-100)
    PERFORMANCE_SCORE: 90,
    ACCESSIBILITY_SCORE: 95,
    BEST_PRACTICES_SCORE: 90,
    SEO_SCORE: 90
};

const PAGES_TO_TEST = [
    {
        name: 'Homepage',
        url: 'http://localhost:8001/',
        critical: true
    },
    {
        name: 'Frame Report (Bulma)',
        url: 'http://localhost:8001/frame/report/1',
        critical: true
    },
    {
        name: 'DataGrid Test Page',
        url: 'http://localhost:8001/test/datagrid-bulma',
        critical: false
    }
];

class PerformanceAuditor {
    constructor() {
        this.results = [];
        this.chrome = null;
        this.failures = [];
        this.warnings = [];
    }

    async init() {
        console.log('🚀 Starting Performance Audit...');
        this.chrome = await chromeLauncher.launch({
            chromeFlags: ['--headless', '--no-sandbox', '--disable-dev-shm-usage']
        });
        console.log(`✅ Chrome launched on port ${this.chrome.port}`);
    }

    async auditPage(page) {
        console.log(`\n📊 Auditing: ${page.name}`);
        console.log(`   URL: ${page.url}`);

        const options = {
            logLevel: 'info',
            output: 'json',
            onlyCategories: ['performance', 'accessibility', 'best-practices', 'seo'],
            port: this.chrome.port
        };

        try {
            const runnerResult = await lighthouse(page.url, options);
            
            if (!runnerResult || !runnerResult.lhr) {
                throw new Error('Failed to get lighthouse results');
            }

            const result = this.processResults(runnerResult.lhr, page);
            this.results.push(result);
            
            this.checkPerformanceTargets(result, page.critical);
            this.displayResults(result);
            
            return result;
            
        } catch (error) {
            console.error(`❌ Error auditing ${page.name}:`, error.message);
            this.failures.push(`${page.name}: ${error.message}`);
            return null;
        }
    }

    processResults(lhr, page) {
        const metrics = lhr.audits;
        const categories = lhr.categories;

        return {
            page: page.name,
            url: page.url,
            timestamp: new Date().toISOString(),
            
            // Core Web Vitals
            coreWebVitals: {
                LCP: this.getMetricValue(metrics['largest-contentful-paint']),
                FID: this.getMetricValue(metrics['first-input-delay']) || 0,
                CLS: this.getMetricValue(metrics['cumulative-layout-shift'])
            },
            
            // Performance Metrics
            performance: {
                FCP: this.getMetricValue(metrics['first-contentful-paint']),
                TTI: this.getMetricValue(metrics['interactive']),
                TBT: this.getMetricValue(metrics['total-blocking-time']),
                SI: this.getMetricValue(metrics['speed-index']),
                score: categories.performance?.score * 100 || 0
            },
            
            // Other Categories
            accessibility: {
                score: categories.accessibility?.score * 100 || 0
            },
            bestPractices: {
                score: categories['best-practices']?.score * 100 || 0
            },
            seo: {
                score: categories.seo?.score * 100 || 0
            },
            
            // Resource Analysis
            resources: this.analyzeResources(lhr)
        };
    }

    getMetricValue(audit) {
        if (!audit || audit.numericValue === undefined) return null;
        
        // Convert to appropriate units
        if (audit.id.includes('time') || audit.id.includes('paint') || audit.id.includes('interactive')) {
            return audit.numericValue / 1000; // Convert ms to seconds
        }
        
        return audit.numericValue;
    }

    analyzeResources(lhr) {
        const networkRequests = lhr.audits['network-requests']?.details?.items || [];
        
        const jsSize = networkRequests
            .filter(req => req.resourceType === 'Script')
            .reduce((total, req) => total + (req.transferSize || 0), 0) / 1024;
            
        const cssSize = networkRequests
            .filter(req => req.resourceType === 'Stylesheet')
            .reduce((total, req) => total + (req.transferSize || 0), 0) / 1024;
            
        return {
            totalRequests: networkRequests.length,
            jsSize: Math.round(jsSize),
            cssSize: Math.round(cssSize),
            totalSize: Math.round(
                networkRequests.reduce((total, req) => total + (req.transferSize || 0), 0) / 1024
            )
        };
    }

    checkPerformanceTargets(result, isCritical) {
        const checks = [
            {
                name: 'LCP (Largest Contentful Paint)',
                value: result.coreWebVitals.LCP,
                target: PERFORMANCE_TARGETS.LCP,
                unit: 's',
                critical: true
            },
            {
                name: 'CLS (Cumulative Layout Shift)',
                value: result.coreWebVitals.CLS,
                target: PERFORMANCE_TARGETS.CLS,
                unit: '',
                critical: true
            },
            {
                name: 'FCP (First Contentful Paint)',
                value: result.performance.FCP,
                target: PERFORMANCE_TARGETS.FCP,
                unit: 's',
                critical: isCritical
            },
            {
                name: 'TTI (Time to Interactive)',
                value: result.performance.TTI,
                target: PERFORMANCE_TARGETS.TTI,
                unit: 's',
                critical: isCritical
            },
            {
                name: 'Performance Score',
                value: result.performance.score,
                target: PERFORMANCE_TARGETS.PERFORMANCE_SCORE,
                unit: '%',
                critical: isCritical,
                higher: true
            },
            {
                name: 'Accessibility Score',
                value: result.accessibility.score,
                target: PERFORMANCE_TARGETS.ACCESSIBILITY_SCORE,
                unit: '%',
                critical: true,
                higher: true
            }
        ];

        checks.forEach(check => {
            if (check.value === null || check.value === undefined) return;
            
            const passed = check.higher ? 
                check.value >= check.target : 
                check.value <= check.target;
                
            if (!passed) {
                const message = `${result.page}: ${check.name} ${check.value}${check.unit} (target: ${check.higher ? '>=' : '<='} ${check.target}${check.unit})`;
                
                if (check.critical) {
                    this.failures.push(message);
                } else {
                    this.warnings.push(message);
                }
            }
        });
    }

    displayResults(result) {
        console.log(`\n📈 Results for ${result.page}:`);
        console.log(`   📊 Performance Score: ${result.performance.score}%`);
        console.log(`   ♿ Accessibility Score: ${result.accessibility.score}%`);
        console.log(`   
   ⚡ Core Web Vitals:`);
        console.log(`      LCP: ${result.coreWebVitals.LCP?.toFixed(2) || 'N/A'}s`);
        console.log(`      CLS: ${result.coreWebVitals.CLS?.toFixed(3) || 'N/A'}`);
        console.log(`   📦 Bundle Sizes:`);
        console.log(`      JS: ${result.resources.jsSize}KB`);
        console.log(`      CSS: ${result.resources.cssSize}KB`);
        console.log(`      Total: ${result.resources.totalSize}KB`);
    }

    async generateReport() {
        const report = {
            summary: {
                timestamp: new Date().toISOString(),
                totalPages: this.results.length,
                passedPages: this.results.length - this.failures.length,
                failures: this.failures.length,
                warnings: this.warnings.length
            },
            results: this.results,
            failures: this.failures,
            warnings: this.warnings,
            targets: PERFORMANCE_TARGETS
        };

        const reportPath = './performance-report.json';
        fs.writeFileSync(reportPath, JSON.stringify(report, null, 2));
        console.log(`\n📄 Report saved to ${reportPath}`);

        return report;
    }

    async cleanup() {
        if (this.chrome) {
            await this.chrome.kill();
            console.log('✅ Chrome closed');
        }
    }

    async run() {
        try {
            await this.init();
            
            for (const page of PAGES_TO_TEST) {
                await this.auditPage(page);
            }
            
            const report = await this.generateReport();
            
            // Print summary
            console.log('\n🏁 Performance Audit Complete!');
            console.log('=====================================');
            console.log(`✅ Pages tested: ${report.summary.totalPages}`);
            console.log(`✅ Passed: ${report.summary.passedPages}`);
            
            if (report.summary.failures > 0) {
                console.log(`❌ Critical failures: ${report.summary.failures}`);
                console.log('\nCritical Issues:');
                this.failures.forEach(failure => console.log(`  • ${failure}`));
            }
            
            if (report.summary.warnings > 0) {
                console.log(`⚠️  Warnings: ${report.summary.warnings}`);
                console.log('\nWarnings:');
                this.warnings.forEach(warning => console.log(`  • ${warning}`));
            }
            
            if (report.summary.failures === 0) {
                console.log('\n🎉 All critical performance targets met!');
                process.exit(0);
            } else {
                console.log('\n🚨 Performance issues detected. Please address critical failures.');
                process.exit(1);
            }
            
        } catch (error) {
            console.error('💥 Performance audit failed:', error);
            process.exit(1);
        } finally {
            await this.cleanup();
        }
    }
}

// Run the audit
if (process.argv[1].includes('performance-audit.js')) {
    const auditor = new PerformanceAuditor();
    auditor.run().catch(console.error);
}

export default PerformanceAuditor;