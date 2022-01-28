let mix = require('laravel-mix');

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
    .sass('resources/sass/app.scss', 'css/bulkselect.css')
    .js('resources/js/app.js', 'js/bulkselect.js')
    .setPublicPath('public');
