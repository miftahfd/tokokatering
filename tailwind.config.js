module.exports = {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/**/*.blade.php',
    './resources/**/*.js'
  ],
  daisyui: {
    themes: ['light', 'dark', 'cupcake'],
  },
  plugins: [
    require('daisyui')
  ]
}


