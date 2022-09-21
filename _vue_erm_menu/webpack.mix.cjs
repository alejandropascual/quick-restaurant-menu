let mix = require('laravel-mix')
let tailwindcss = require('tailwindcss')
let path = require('path')
let postcssImport = require('postcss-import')

mix.webpackConfig({
    stats: {
        children: true,
    },
})

mix
    .js('src/main.js', './../assets/js/erm_menu')
    .vue({version: 3})
    //.sourceMaps()
    //.extract()
    //.minify('./../assets/js/erm_menu/main.js')
    .setPublicPath('./../assets/js/erm_menu')

    .postCss('src/style.css', './../assets/js/erm_menu', [postcssImport(), tailwindcss('tailwind.config.cjs'),])
    .alias({'@': path.join(__dirname, 'src/')})
    //.version()
