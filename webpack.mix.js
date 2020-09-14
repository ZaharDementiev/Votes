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
mix.options({
    extractVueStyles: false,
    processCssUrls: true,
    terser: {  optimization: {
            minimize: true,
        },
    },
    purifyCss: true,
    //purifyCss: {},
    postCss: [require('autoprefixer')],
    clearConsole: false,
    cssNano: {
        // discardComments: {removeAll: true},
    }
});


mix.config.webpackConfig.output = {
    chunkFilename: 'js/[name].bundle.js?id=[chunkhash]',
    publicPath: '/',
};

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');
