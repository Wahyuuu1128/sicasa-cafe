import defaultTheme from 'tailwindcss/defaultTheme';

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
            colors: {
                'brand-lime': '#A8FF00',
                'brand-blue': '#182B87',
                'ink': '#17191C',
                'paper': '#F7F7F2',
                'surface': '#FFFFFF',
            },
            fontFamily: {
                // Kita buat custom nama font biar gampang dipanggil di HTML nanti
                'heading': ['"Space Grotesk"', ...defaultTheme.fontFamily.sans],
                'body': ['"Inter"', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    plugins: [],
};