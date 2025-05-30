<<<<<<< HEAD
=======
/**
 * Tailwind CSS Configuration
 * This file customizes the Tailwind CSS framework for the project
 * @see https://tailwindcss.com/docs/configuration
 */

>>>>>>> 37832177f92ffd6ce3d73febe73a42b600edf666
import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
<<<<<<< HEAD
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
=======
    // Configure the files Tailwind should scan for classes
    content: [
        // Laravel framework pagination views
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        // Compiled blade views
        './storage/framework/views/*.php',
        // Application blade views
        './resources/views/**/*.blade.php',
    ],

    // Theme customization
    theme: {
        extend: {
            // Custom font configuration
            fontFamily: {
                sans: [
                    'Figtree',     // Primary font
                    ...defaultTheme.fontFamily.sans  // Fallback fonts
                ],
            },
            // Add custom colors, spacing, or other theme values here
        },
    },

    // Active plugins
    plugins: [
        forms,  // Adds better base styles for form elements
    ],

    // Future configuration options
    future: {
        // Enable newer Tailwind features as they become available
        hoverOnlyWhenSupported: true,
    },
>>>>>>> 37832177f92ffd6ce3d73febe73a42b600edf666
};
