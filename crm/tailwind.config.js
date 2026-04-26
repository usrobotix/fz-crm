import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'Figtree', ...defaultTheme.fontFamily.sans],
                mono: ['"PT Mono"', '"Ubuntu Mono"', 'Consolas', 'monospace'],
            },
            fontSize: {
                'ys-xs':  ['12px', { lineHeight: '16px' }],
                'ys-s':   ['14px', { lineHeight: '20px' }],
                'ys-m-s': ['16px', { lineHeight: '20px' }],
                'ys-m':   ['16px', { lineHeight: '24px' }],
                'ys-l':   ['20px', { lineHeight: '28px' }],
                'ys-xl':  ['24px', { lineHeight: '32px' }],
                'ys-xxl': ['32px', { lineHeight: '40px' }],
            },
            colors: {
                'dc-blue': { 10:'#3782ff1a', 100:'#3782ff', 200:'#2556ff', 300:'#0436e4' },
                'dc-gray': { 10:'#cad6ee1a', 20:'#b0bdd633', 30:'#a5b1ca4d', 50:'#475a8080', 80:'#1e242e', 200:'#f6f8fb', 300:'#eff2f7' },
                'dc-green': { 10:'#2eb27a26', 100:'#26aa72', 120:'#ccecd0' },
                'dc-red': { 10:'#dd00001a', 100:'#dd0000', 120:'#f5b2b2' },
                'dc-orange': { 100:'#ff8200', 120:'#ffd9b2' },
                'dc-yellow': { 100:'#ffdd57', 200:'#ffd21f' },
                'dc-violet': { 10:'#685cfc1a', 100:'#685cfc', 120:'#e1defe' },
            },
            borderRadius: {
                '4xs':'4px','3xs':'6px','2xs':'8px','xs':'10px','sm':'12px','md':'14px','lg':'20px',
            },
            boxShadow: {
                'card':    '0 2px 4px 0 rgba(77,86,103,.12), 0 3px 6px 0 rgba(77,86,103,.15)',
                'card-lg': '0 10px 20px rgba(165,177,202,.3), 0 3px 6px rgba(165,177,202,.3)',
                'popover': '0 0 3px rgba(165,177,202,.3), 0 10px 40px rgba(165,177,202,.3)',
            },
        },
    },
    plugins: [forms],
};


/** @type {import('tailwindcss').Config} */
export default {
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
};
