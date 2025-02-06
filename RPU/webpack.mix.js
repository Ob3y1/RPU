const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        //
    ]);
    mix.js('resources/js/myjs.js', 'public/js');
    mix.js('resources/js/bootstrap.bundle.min.js', 'public/js');
    mix.js('resources/js/bootstrap.js', 'public/js');
    mix.js('resources/js/color-modes.js', 'public/js');
    mix.js('resources/js/sidebars.js', 'public/js');
    
    mix.css('resources/css/bootstrap.min.css', 'public/css');
    mix.css('resources/css/mystyle.css', 'public/css');
    mix.css('resources/css/sidebars.css', 'public/css');
    mix.copy('resources/images/logo-removebg-preview.png', 'public/images');
    mix.copy('resources/images/logo-removebg-preview1.png', 'public/images');
    mix.copy('resources/images/logo-removebg-preview2.png', 'public/images');

