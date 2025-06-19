module.exports = {
  darkMode: 'class',
  content: [
    './public/**/*.php',
    './src/views/**/*.php',
    './src/**/*.php',
    './*.php',
  ],
  theme: {
    extend: {
      colors: {
        primary: '#18181b', // shadcn dark
        secondary: '#f4f4f5', // shadcn light
        accent: '#2563eb', // blue-600
        muted: '#71717a',
        border: '#e4e4e7',
        background: '#fff',
        foreground: '#18181b',
      },
      fontFamily: {
        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
      },
    },
  },
  plugins: [],
}; 