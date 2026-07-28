import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            screens: {
                // lahorefan.com switches from the mobile header/drawer to the full
                // desktop header at 992px (Bootstrap md). Use `nav:` for that switch.
                nav: '992px',
            },
            fontFamily: {
                // Lahore Fan uses Manrope across body, headings and buttons.
                sans: ['Manrope', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Brand green chrome (top bar, CTAs, footer accents).
                brand: {
                    DEFAULT: '#008242',
                    dark: '#0B7046',
                    // Tinted background for highlighted (user's-city) cards.
                    light: '#e7f7ee',
                },
                // Orange is the theme accent — hover / active / current states only.
                accent: {
                    DEFAULT: '#ff8400',
                    dark: '#cc6a00',
                },
                ink: '#1a1a1a',
                body: '#666666',
                footer: {
                    DEFAULT: '#121212',
                    bar: '#1a1a1a',
                },
                line: '#e6e6e6',
            },
            boxShadow: {
                // Lahore Fan's signature card hover shadow.
                card: '0 20px 32px 0 rgba(53, 78, 84, 0.15)',
                header: '0 2px 5px 0 rgba(0, 0, 0, 0.1)',
            },
            maxWidth: {
                container: '1300px',
            },
        },
    },

    plugins: [forms],
};
