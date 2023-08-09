import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'alternative': {
                    '50': '#f2f7f9',
                    '100': '#ddeaf0',
                    '200': '#bfd6e2',
                    '300': '#93b9cd',
                    '400': '#5f93b1',
                    '500': '#3f6f8c',
                    '600': '#3b627f',
                    '700': '#355269',
                    '800': '#324658',
                    '900': '#2d3d4c',
                },
                'contrast-alternative': {
                    '50': '#FFFFFF',
                    '100': '#FFFFFF',
                    '200': '#FFFFFF',
                    '300': '#FFFFFF',
                    '400': '#FFFFFF',
                    '500': '#FFFFFF',
                    '600': '#EFEFEF',
                    '700': '#DCDCDC',
                    '800': '#BDBDBD',
                    '900': '#989898'
                },
                'primary': {
                    '50': '#f8f8f8',
                    '100': '#f2f2f2',
                    '200': '#dcdcdc',
                    '300': '#bdbdbd',
                    '400': '#989898',
                    '500': '#7c7c7c',
                    '600': '#656565',
                    '700': '#525252',
                    '800': '#464646',
                    '900': '#3d3d3d'
                },
                'contrast-primary': {
                    '50': '#f0fafb',
                    '100': '#d9eff4',
                    '200': '#b7dfea',
                    '300': '#85c8db',
                    '400': '#4ca7c4',
                    '500': '#308baa',
                    '600': '#2b718f',
                    '700': '#295d75',
                    '800': '#294e61',
                    '900': '#233d4d'
                },
            },
        },
    },

    plugins: [forms, typography],
};
