const mix = require('laravel-mix');
const webpack = require('webpack');

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

mix.ts('resources/js/app.ts', 'public/js')
    .sass('resources/scss/app.scss', 'public/css')
    .vue()
    .webpackConfig({
        plugins: [
            new webpack.DefinePlugin({
                __VUE_OPTIONS_API__: true,
                __VUE_PROD_DEVTOOLS__: false,
            }),
        ],
    });
