/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    ],
    theme: {
        extend: {
            colors: {
                ink: { 50: '#f3f6f4', 100: '#e6ece8', 200: '#cfdad3', 300: '#aebfb4', 400: '#869c8f', 500: '#667d70', 600: '#506358', 700: '#3f4f46', 800: '#27342d', 900: '#15211b', 950: '#0b140f' },
                accent: { 50: '#ecf8f1', 100: '#d2efde', 200: '#a7dfc0', 300: '#70c99a', 400: '#3fb173', 500: '#1e9e57', 600: '#168048', 700: '#13663b', 800: '#125132', 900: '#10432b', 950: '#08251a' },
            },
            fontFamily: {
                display: ['"DM Serif Display"', 'ui-serif', 'Georgia', 'serif'],
            },
        },
    },
    plugins: [],
};
