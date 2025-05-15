import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['public/scss/style.scss', 'resources/js/app.js'],
            output: 'public/css',
            refresh: true,
        }),
    ],
});
