import {defineConfig} from 'vite';
import laravel, {refreshPaths} from 'laravel-vite-plugin';
import fs from 'fs';
import path from 'path';

// Dynamically discover all theme assets
function discoverThemeAssets() {
    const themesPath = path.resolve('resources/themes');
    const assets = [
        'resources/css/app.css',
        'resources/js/app.js'
    ];

    if (!fs.existsSync(themesPath)) {
        console.warn('No themes directory found at:', themesPath);
        return assets;
    }

    // Get all theme directories
    const themes = fs.readdirSync(themesPath, {withFileTypes: true})
        .filter(dirent => dirent.isDirectory())
        .map(dirent => dirent.name);

    console.log('🎨 Discovered themes:', themes);

    // Add CSS and JS files for each theme
    themes.forEach(theme => {
        const cssPath = `resources/themes/${theme}/css/theme.css`;
        const jsPath = `resources/themes/${theme}/js/theme.js`;

        // Check if CSS file exists
        if (fs.existsSync(cssPath)) {
            assets.push(cssPath);
            console.log(`✅ Added CSS: ${cssPath}`);
        } else {
            console.log(`⚠️  Missing CSS: ${cssPath}`);
        }

        // Check if JS file exists
        if (fs.existsSync(jsPath)) {
            assets.push(jsPath);
            console.log(`✅ Added JS: ${jsPath}`);
        }
    });

    console.log('📦 Total assets to build:', assets.length);
    return assets;
}

export default defineConfig({
    plugins: [
        laravel({
            input: discoverThemeAssets(),
            refresh: [
                ...refreshPaths,
                'app/Livewire/**',
                'app/Filament/**',
                'resources/themes/**', // Watch theme files for changes
            ],
        }),
    ],
    build: {
        rollupOptions: {
            output: {
                assetFileNames: (assetInfo) => {
                    // Keep theme assets organized
                    if (assetInfo.names?.includes('theme')) {
                        return 'assets/themes/[name]-[hash][extname]';
                    }
                    return 'assets/[name]-[hash][extname]';
                }
            }
        }
    }
});
