let mix = require('laravel-mix');
let path = require('path');

let webpackConfig = {
    resolve: {
        alias: {
            'orchid': path.resolve(`${__dirname}/../../../`, 'vendor/orchid'),
        },
    },
};

if (mix.inProduction()) {
    mix.version();
}

mix.webpackConfig(webpackConfig);

mix
    .css('resources/css/app.css', 'css/bulkselect.css')
    .js('resources/js/app.js', 'js/bulkselect.js')
    .setPublicPath('public');
