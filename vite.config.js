import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/admin.css',
                'resources/css/StatsAdmin.css',
                'resources/css/AdminUser.css',
                'resources/css/compra.css',
                'resources/css/pedidos.css',
                'resources/js/app.js',
                'resources/js/AdminControl.js',
                'resources/js/StatsAdmin.js',
                'resources/js/AdminUser.js',
                'resources/js/compra.js',
                'resources/js/login.js',
                'resources/js/pedidos.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
