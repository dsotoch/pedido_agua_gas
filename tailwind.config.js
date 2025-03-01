/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        'principal': '#00ADEF',
        'principalhover': '#0093CC',
        'secundario': '#293241',
        'secundariohover': '#186886',
        'tarjetas': '#293241',
        'naranja': '#FF6600',
        'color-text':'#282828',
        'color-fondo-productos':'#eeeeee',
        'color-titulos-entrega':'#293241',
        'color-dashboard':'#ebeff2',
        'color-tarjetas':'#f9f9f9',
        'verde':'#25d366',
      },
      fontFamily: {
        sans: ['Open Sans', 'sans-serif'], // Agrega Open Sans sin eliminar la fuente predeterminada
        cabin: ['Cabin', 'sans-serif'],


      }
    },
  },
  plugins: [],
}

