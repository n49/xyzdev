var path = require('path');
var webpack = require('webpack');

const UglifyJsPlugin = require('uglifyjs-webpack-plugin');

module.exports = {
    // entry: ['@babel/polyfill','./js/scripts_es6.js'],
    entry: ['es6-promise-promise','./js/polyfill.js','./js/scripts_es6.js'],
    output: {
        path: path.resolve(__dirname, 'js'),
        filename: 'scripts.js'
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: ['@babel/preset-env']
                    }
                }
            }
        ]
    },
    optimization: {
        minimizer: [
          new UglifyJsPlugin({
            uglifyOptions: {
              output: {
                comments: false
              }
            }
          })
        ]
      },
    stats: {
        colors: true
    },
    devtool: 'source-map',
    mode: 'development',
    watch: true
};
