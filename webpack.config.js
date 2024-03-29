const path = require('path');
const BrowserSyncPlugin = require('browser-sync-webpack-plugin');
const {
    tree
} = require('gulp');
module.exports = {
    mode: 'production',
    entry: {
        admin: path.resolve(__dirname, 'src/scripts/admin.js'),
    },
    output: {
        filename: '[name].min.js',
        path: path.resolve(__dirname, 'public/assets/scripts/'),
    },
    module: {

        rules: [{
            test: /\.js$/,
            exclude: /node_modules/,

            use: [{
                loader: 'babel-loader',
                options: {
                    presets: ['@babel/preset-env'],
                }
            }, ],
        }],
    },
    plugins: [
        new BrowserSyncPlugin({
            host: 'localhost',
            port: 4040,
            injectChanges: true,
            watch: true,
            reloadOnRestart: true,
            reloadDelay: 400,
            files: ['./**/*.php', './public/assets/css/*.min.css', './public/assets/scripts/*.min.js'],
            watchEvents: ['change', 'add', 'unlink', 'addDir', 'unlinkDir'],
            proxy: 'http://localhost/practice/wordpress/wp-admin',
        })
    ],
    devtool: '#source-map',
    watch: true,
    watchOptions: {
        ignored: [/node_modules/, './src/sass/*.scss', './gulpfile.js', './package-lock.json']
    }
};