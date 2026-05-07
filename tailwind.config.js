/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/views/**/*.{blade,html}',
        './resources/js/**/*.{js,jsx,ts,tsx,vue}',
    ],
    theme: {
        extend: {
            colors: {
                black: '#0A0A0A',
                white: '#FFFFFF',
                blue: '#1A56FF',
                red: '#E8192C',
            },
            borderRadius: {
                DEFAULT: '0',
                sm: '0',
                md: '0',
                lg: '0',
                xl: '0',
                full: '9999px',
            },
        },
    },
    plugins: [],
};
