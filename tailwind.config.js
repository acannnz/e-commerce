/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./production/application/modules/**/*.php",
    "./production/application/themes/**/*.php",
    "./public/production/ecommerce/assets/js/**/*.js"
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
