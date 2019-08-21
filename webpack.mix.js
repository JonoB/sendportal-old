let mix = require('laravel-mix');
/*let exec = require('child_process').exec;
let path = require('path');*/

mix.webpackConfig({
    resolve: {
        symlinks: true
    }
});

/*mix.js([
    'resources/js/understand.js',
    'resources/js/errors.js'
], 'public/js/understand.min.js')
    .sass('resources/sass/app.scss', 'public/css');*/


mix.sass('resources/sass/app.scss', 'public/css');

if (mix.inProduction()) {
    mix
        .sourceMaps(true, 'eval-source-map')
        .version();
}

/*mix
    .sass('resources/sass/app.scss', 'public/css')
    .js('resources/js/app.js', 'public/js')
    .copy('node_modules/sweetalert/dist/sweetalert.min.js', 'public/js/sweetalert.min.js')
    .sass('resources/sass/app-rtl.scss', 'public/css')
    .then(() => {
        exec('node_modules/rtlcss/bin/rtlcss.js public/css/app-rtl.css ./public/css/app-rtl.css');
    })
    .version()
    .webpackConfig({
        resolve: {
            modules: [
                path.resolve(__dirname, 'vendor/laravel/spark-aurelius/resources/assets/js'),
                'node_modules'
            ],
            alias: {
                'vue$': mix.inProduction() ? 'vue/dist/vue.min' : 'vue/dist/vue.js'
            }
        }
    });
*/