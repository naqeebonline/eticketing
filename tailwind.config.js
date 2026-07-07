import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './app/View/Components/**/*.php',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['Plus Jakarta Sans', 'Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                surface: {
                    DEFAULT: '#ffffff',
                    muted: '#f8fafc',
                    elevated: '#ffffff',
                },
                pak: {
                    green: {
                        DEFAULT: '#01411C',
                        dark: '#012a12',
                        mid: '#0B5E2E',
                        light: '#1A7A42',
                        soft: '#E8F5EC',
                    },
                    gold: {
                        DEFAULT: '#C9A227',
                        light: '#E8D48B',
                    },
                },
                primary: {
                    50: '#eef2ff',
                    100: '#e0e7ff',
                    200: '#c7d2fe',
                    300: '#a5b4fc',
                    400: '#818cf8',
                    500: '#6366f1',
                    600: '#4f46e5',
                    700: '#4338ca',
                    800: '#3730a3',
                    900: '#312e81',
                    950: '#1e1b4b',
                },
                success: {
                    50: '#ecfdf5',
                    400: '#34d399',
                    500: '#10b981',
                    600: '#059669',
                    700: '#047857',
                },
                warning: {
                    50: '#fffbeb',
                    400: '#fbbf24',
                    500: '#f59e0b',
                    600: '#d97706',
                },
                danger: {
                    50: '#fef2f2',
                    400: '#f87171',
                    500: '#ef4444',
                    600: '#dc2626',
                    700: '#b91c1c',
                },
            },
            boxShadow: {
                soft: '0 1px 3px 0 rgb(0 0 0 / 0.04), 0 1px 2px -1px rgb(0 0 0 / 0.04)',
                card: '0 1px 3px 0 rgb(0 0 0 / 0.06), 0 4px 12px -2px rgb(0 0 0 / 0.06)',
                elevated: '0 4px 6px -1px rgb(0 0 0 / 0.08), 0 10px 24px -4px rgb(0 0 0 / 0.08)',
            },
            backgroundImage: {
                'mesh-light': 'radial-gradient(at 40% 20%, rgb(99 102 241 / 0.15) 0px, transparent 50%), radial-gradient(at 80% 0%, rgb(14 165 233 / 0.12) 0px, transparent 50%), radial-gradient(at 0% 50%, rgb(168 85 247 / 0.1) 0px, transparent 50%)',
                'mesh-dark': 'radial-gradient(at 40% 20%, rgb(99 102 241 / 0.2) 0px, transparent 50%), radial-gradient(at 80% 0%, rgb(14 165 233 / 0.15) 0px, transparent 50%), radial-gradient(at 0% 50%, rgb(168 85 247 / 0.12) 0px, transparent 50%)',
            },
            animation: {
                'fade-in': 'fadeIn 0.2s ease-out',
                'slide-in': 'slideIn 0.25s ease-out',
                'slide-up': 'slideUp 0.3s ease-out',
                'float': 'float 6s ease-in-out infinite',
                'pulse-soft': 'pulseSoft 3s ease-in-out infinite',
            },
            keyframes: {
                fadeIn: { from: { opacity: '0' }, to: { opacity: '1' } },
                slideIn: { from: { transform: 'translateX(-8px)', opacity: '0' }, to: { transform: 'translateX(0)', opacity: '1' } },
                slideUp: { from: { transform: 'translateY(12px)', opacity: '0' }, to: { transform: 'translateY(0)', opacity: '1' } },
                float: { '0%, 100%': { transform: 'translateY(0)' }, '50%': { transform: 'translateY(-8px)' } },
                pulseSoft: { '0%, 100%': { opacity: '1' }, '50%': { opacity: '0.7' } },
            },
        },
    },
    plugins: [forms],
};
