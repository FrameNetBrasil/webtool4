/**
 * Production Deployment Configuration
 * FNBr Webtool 4.0 - Bulma Migration
 */

export default {
    // Environment configuration
    environment: 'production',
    
    // Build settings
    build: {
        // Enable all optimizations
        minify: true,
        sourcemap: false,
        cssCodeSplit: true,
        
        // Bundle size limits (in kB)
        limits: {
            javascript: {
                main: 120,      // Main bundle
                vendor: 50,     // Vendor libraries
                lazy: 250       // Lazy-loaded chunks
            },
            css: {
                main: 800,      // Main CSS bundle
                bulma: 1800     // Bulma CSS bundle
            }
        },
        
        // Asset optimization
        assets: {
            images: {
                optimize: true,
                formats: ['webp', 'avif'],
                quality: 80
            },
            fonts: {
                preload: ['Noto Sans', 'Material Symbols'],
                formats: ['woff2']
            }
        }
    },
    
    // Performance targets
    performance: {
        // Core Web Vitals targets
        vitals: {
            lcp: 2.5,       // Largest Contentful Paint (seconds)
            fid: 100,       // First Input Delay (ms)
            cls: 0.1        // Cumulative Layout Shift
        },
        
        // Bundle size targets
        budgets: [
            {
                type: 'initial',
                maximumWarning: '2mb',
                maximumError: '3mb'
            },
            {
                type: 'anyComponentStyle',
                maximumWarning: '150kb',
                maximumError: '200kb'
            }
        ]
    },
    
    // Caching strategy
    cache: {
        // Static assets
        assets: {
            maxAge: 31536000,   // 1 year
            immutable: true
        },
        
        // HTML files
        html: {
            maxAge: 0,          // No cache
            mustRevalidate: true
        },
        
        // CSS/JS bundles
        bundles: {
            maxAge: 31536000,   // 1 year (with hash)
            immutable: true
        }
    },
    
    // Content Security Policy
    csp: {
        directives: {
            defaultSrc: ["'self'"],
            styleSrc: [
                "'self'", 
                "'unsafe-inline'",
                "https://fonts.googleapis.com"
            ],
            fontSrc: [
                "'self'",
                "https://fonts.gstatic.com"
            ],
            scriptSrc: [
                "'self'",
                "https://unpkg.com/htmx.org@2.0.3",
                "https://cdn.jsdelivr.net"
            ],
            imgSrc: [
                "'self'",
                "data:",
                "https:"
            ]
        }
    },
    
    // Monitoring and analytics
    monitoring: {
        // Performance monitoring
        performance: {
            enabled: true,
            sampleRate: 0.1,
            endpoints: {
                vitals: '/api/vitals',
                errors: '/api/errors'
            }
        },
        
        // Error tracking
        errors: {
            enabled: true,
            dsn: process.env.SENTRY_DSN || null,
            environment: 'production'
        }
    },
    
    // Feature flags
    features: {
        bulmaComponents: true,      // Enable Bulma components
        legacySupport: true,        // Keep Fomantic-UI support
        lazyLoading: true,          // Enable lazy loading
        offlineMode: false,         // PWA offline mode
        analytics: true             // User analytics
    },
    
    // A/B testing for gradual migration
    experiments: {
        bulmaLayout: {
            enabled: true,
            traffic: 0.1,           // 10% of users
            variants: ['fomantic', 'bulma']
        },
        bulmaComponents: {
            enabled: true,
            traffic: 0.05,          // 5% of users
            variants: ['original', 'bulma']
        }
    }
};