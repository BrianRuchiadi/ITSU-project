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

mix.styles([
    'node_modules/datatables.net-bs4/css/datatables.net-bs4',
    'node_modules/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css',
    'node_modules/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css'
], 'public/css/vendor/vendor.css');

mix.scripts([
    'node_modules/jquery/dist/jquery.min.js',

    'node_modules/datatables.net/js/jquery.dataTables.min.js',
    'node_modules/datatables.net-bs4/js/dataTables.bootstrap4.min.js',

    'node_modules/datatables.net-buttons/js/dataTables.buttons.min.js',
    'node_modules/datatables.net-buttons/js/buttons.print.min.js',
    'node_modules/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js',

    'node_modules/datatables.net-responsive/js/dataTables.responsive.min.js',
    'node_modules/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js'

], 'public/js/vendor/vendor.js');

mix.sass('resources/sass/layout/dashboard.scss', 'public/css/layout');

mix.sass('resources/sass/page/auth/login.scss', 'public/css/page/auth');
mix.sass('resources/sass/page/auth/register.scss', 'public/css/page/auth');
mix.sass('resources/sass/page/auth/verified.scss', 'public/css/page/auth');
mix.sass('resources/sass/page/auth/change-password.scss', 'public/css/page/auth');
mix.sass('resources/sass/page/auth/reset-password.scss', 'public/css/page/auth');
mix.sass('resources/sass/page/customer/application-form.scss', 'public/css/page/customer');
mix.sass('resources/sass/page/customer/referral-link.scss', 'public/css/page/customer');
mix.sass('resources/sass/page/customer/contract-list.scss', 'public/css/page/customer');
mix.sass('resources/sass/page/customer/contract-details.scss', 'public/css/page/customer');
mix.sass('resources/sass/page/customer/invoice.scss', 'public/css/page/customer');
mix.sass('resources/sass/page/customer/delivery-order.scss', 'public/css/page/customer');

mix.sass('resources/sass/page/contract/pending-contract-list.scss', 'public/css/page/contract');
mix.sass('resources/sass/page/contract/pending-contract-details.scss', 'public/css/page/contract');
mix.sass('resources/sass/page/contract/delivery-order.scss', 'public/css/page/contract');
mix.sass('resources/sass/page/contract/invoice.scss', 'public/css/page/contract');
mix.sass('resources/sass/page/contract/contract-list.scss', 'public/css/page/contract');
mix.sass('resources/sass/page/contract/contract-details.scss', 'public/css/page/contract');

mix.sass('resources/sass/page/utilities/email-verify.scss', 'public/css/page/utilities');

mix.sass('resources/sass/page/print/print-contract.scss', 'public/css/page/print');

mix.copyDirectory('resources/assets/pictures', 'public/assets/pictures');