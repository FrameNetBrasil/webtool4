import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { minimatch } from 'minimatch';
import { visualizer } from 'rollup-plugin-visualizer';

export default defineConfig(({ mode }) => ({
    plugins: [
        {
            handleHotUpdate(ctx) {
                if (minimatch(ctx.file, '**/storage/framework/views/**/*.php')) {
                    return [];
                }
            }
        },
        laravel({
            input: [
                'resources/js/app.js',
                'resources/sass/app.scss',
            ],
            refresh: ['app/UI/**'],
        }),
        // Bundle analyzer - only in production
        mode === 'production' && process.env.ANALYZE && visualizer({
            filename: 'dist/bundle-analysis.html',
            open: true,
            gzipSize: true,
            brotliSize: true,
        }),
    ].filter(Boolean),
    
    // Build optimizations
    build: {
        target: 'es2018',
        cssCodeSplit: true,
        sourcemap: mode === 'development',
        minify: mode === 'production' ? 'esbuild' : false,
        
        rollupOptions: {
            output: {
                manualChunks: {
                    // Vendor libraries
                    vendor: ['alpinejs'],
                    // Bulma components
                    bulma: ['bulma'],
                    // Large third-party libraries
                    jointjs: ['jointjs'],
                },
                
                // Optimize chunk names
                chunkFileNames: (chunkInfo) => {
                    return `assets/[name]-[hash].js`;
                },
                
                assetFileNames: (assetInfo) => {
                    const info = assetInfo.name.split('.');
                    const ext = info[info.length - 1];
                    if (/\.(css|scss|sass|less)$/.test(assetInfo.name)) {
                        return `assets/[name]-[hash].css`;
                    }
                    if (/\.(png|jpe?g|svg|gif|tiff|bmp|ico)$/i.test(assetInfo.name)) {
                        return `assets/images/[name]-[hash].${ext}`;
                    }
                    if (/\.(woff2?|eot|ttf|otf)$/i.test(assetInfo.name)) {
                        return `assets/fonts/[name]-[hash].${ext}`;
                    }
                    return `assets/[name]-[hash].${ext}`;
                }
            },
        },
        
        // Performance hints
        chunkSizeWarningLimit: 1000,
    },
    
    // Performance optimizations
    optimizeDeps: {
        include: [
            'alpinejs'
        ],
        esbuildOptions: {
            target: 'es2018',
        },
    },
    
    css: {
        preprocessorOptions: {
            less: {
                math: "always",
                relativeUrls: true,
                javascriptEnabled: true,
            },
            scss: {
                api: 'modern',
                includePaths: ['node_modules'],
                silenceDeprecations: ['legacy-js-api']
            },
        },
    },
    // server: {
    //     hmr: {
    //         host: 'localhost',
    //     },
    //     watch: {
    //         usePolling: true
    //     }
    // },
}));
