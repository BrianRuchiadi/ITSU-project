const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');

mix.sass('resources/sass/layout/dashboard.scss', 'public/css/layout');

mix.sass('resources/sass/page/auth/login.scss', 'public/css/page/auth');
mix.sass('resources/sass/page/auth/register.scss', 'public/css/page/auth');
mix.sass('resources/sass/page/auth/verified.scss', 'public/css/page/auth');
mix.sass('resources/sass/page/auth/change-password.scss', 'public/css/page/auth');
mix.sass('resources/sass/page/customer/application-form.scss', 'public/css/page/customer');
mix.sass('resources/sass/page/customer/referral-link.scss', 'public/css/page/customer');

mix.copyDirectory('resources/assets/pictures', 'public/assets/pictures');