import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { readFileSync } from 'node:fs';
import { fileURLToPath, URL } from 'node:url';

function componentStyle(path) {
    const source = readFileSync(fileURLToPath(new URL(path, import.meta.url)), 'utf8');
    const style = source.match(/<style scoped>([\s\S]*?)<\/style>/)?.[1] ?? '';

    return style.replace(/:deep\(([^)]+)\)/g, '$1');
}

export default defineConfig({
    plugins: [
        {
            name: 'memoriq-landing-styles',
            enforce: 'pre',
            transform(code, id) {
                const filePath = id.split('?', 1)[0].replaceAll('\\', '/');

                if (! filePath.endsWith('/resources/css/landing.css')) {
                    return null;
                }

                return [
                    componentStyle('./resources/js/components/ExtensionInstallCta.vue'),
                    componentStyle('./resources/js/views/LandingView.vue'),
                    code,
                ].join('\n');
            },
        },
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/landing.css',
                'resources/css/page.css',
                'resources/js/app.js',
                'resources/js/landing.js',
                'resources/js/page.js',
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            '@': fileURLToPath(new URL('./resources/js', import.meta.url)),
            vue: 'vue/dist/vue.esm-bundler.js',
        },
    },
});