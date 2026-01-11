import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/jquery.js'],
            refresh: true,
        }),
    ],
    server: {
        host: '0.0.0.0',
        port: 8880,
        strictPort: true,
        hmr: {
            host: '192.168.1.12', //Updated to current local IP
        }
    },
    input: [
        'resources/js/app.js',
        'resources/js/echo.js',
        'resources/js/jquery.js',
    ],
});
