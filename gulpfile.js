var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.sass(['app.scss'])
    
    .styles([

                'bootstrap.css',
                'select2.css',
                'fakeLoader.css',
                'lity.css',
                'sweetalert.css'

            ]) // 'bootflat.css',


    .scripts([

        'jquery.js',
        'bootstrap.js',
        'select2.js',
        'vue-1.0.24.js',
        'fakeLoader.min.js',
        'lity.js',
        'sweetalert.min.js',
        'helpers.js'


        ]);

});
