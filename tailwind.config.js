/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./src/Views/**/*.php", "./src/Components/**/*.php"],
  theme: {
    extend: {},
  },
  plugins: [
    require('daisyui'),
  ],
}

