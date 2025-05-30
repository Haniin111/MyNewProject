<<<<<<< HEAD
=======
/**
 * Vite Configuration for Laravel
 * This file configures the build process for frontend assets using Vite
 */

>>>>>>> 37832177f92ffd6ce3d73febe73a42b600edf666
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
<<<<<<< HEAD
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
=======
            // Define the entry points for your application's assets
            input: [
                'resources/css/app.css', // Main CSS file
                'resources/js/app.js'    // Main JavaScript file
            ],
            // Enable hot module replacement for faster development
            refresh: true,
        }),
    ],
    // Configure build options
    build: {
        // Output directory for production build
        outDir: 'public/build',
        // Generate sourcemaps for better debugging
        sourcemap: true,
    },
>>>>>>> 37832177f92ffd6ce3d73febe73a42b600edf666
});
