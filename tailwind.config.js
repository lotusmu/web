export default {
    darkMode: 'selector',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        "./vendor/livewire/flux-pro/stubs/**/*.blade.php",
        "./vendor/livewire/flux/stubs/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'sans-serif'],
            },
            colors: {
                // Theme-aware zinc colors using RGB space for CSS variables
                zinc: {
                    50: 'rgb(var(--zinc-50) / <alpha-value>)',
                    100: 'rgb(var(--zinc-100) / <alpha-value>)',
                    200: 'rgb(var(--zinc-200) / <alpha-value>)',
                    300: 'rgb(var(--zinc-300) / <alpha-value>)',
                    400: 'rgb(var(--zinc-400) / <alpha-value>)',
                    500: 'rgb(var(--zinc-500) / <alpha-value>)',
                    600: 'rgb(var(--zinc-600) / <alpha-value>)',
                    700: 'rgb(var(--zinc-700) / <alpha-value>)',
                    800: 'rgb(var(--zinc-800) / <alpha-value>)',
                    900: 'rgb(var(--zinc-900) / <alpha-value>)',
                    950: 'rgb(var(--zinc-950) / <alpha-value>)',
                },

                // Theme compliment colors
                compliment: {
                    DEFAULT: 'var(--color-compliment)',
                    content: 'var(--color-compliment-content)',
                    foreground: 'var(--color-compliment-foreground)',
                },
            },
        },
    },
};
