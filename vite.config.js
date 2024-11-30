import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/css/app.css', 'resources/js/app.js',
                'resources/css/module.css',

                'resources/css/pacientes.css',
                'resources/js/pacientes.js',

                'resources/css/filtro.css',
                'resources/js/filtro.js',
            ],
            refresh: true,
        }),
    ],
});
