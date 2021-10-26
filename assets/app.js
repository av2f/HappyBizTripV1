/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// start the Stimulus application
import './bootstrap'

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss'

// require jQuery normally
const $ = require('jquery')

// create global $ and jQuery variables
global.$ = global.jQuery = $

// Add bootstrap
require('bootstrap')

// Add Fontawesome
require('@fortawesome/fontawesome-free/css/all.min.css')
require('@fortawesome/fontawesome-free/js/all.js')

// Handle Loader which is defined in base.html.twig
window.addEventListener('load', () => {
  document.body.removeChild(document.getElementById('loader'))
})
