// webpack.config.js
const defaultConfig = require("@wordpress/scripts/config/webpack.config");
const DependencyExtractionWebpackPlugin = require('@woocommerce/dependency-extraction-webpack-plugin');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const path = require('path');

module.exports = {
    ...defaultConfig,
    entry: {
        'admin/index': path.resolve(__dirname, 'client/admin/index'),
        'blocks/index': path.resolve(__dirname, 'client/blocks/index'),
        'classic/index': path.resolve(__dirname, 'client/classic/index')
    },
    output: {
        filename: '[name].js',
        path: path.resolve(process.cwd(), 'assets/client')
    },
    plugins: [
        new DependencyExtractionWebpackPlugin(),
        new MiniCssExtractPlugin({
            filename: (pathData) => {
                // Get the original entry key (e.g. "admin/index")
                const chunkName = pathData.chunk.name;
                // Replace the last segment ("index") with "style"
                const parts = chunkName.split('/');
                parts[parts.length - 1] = 'style';
                return `${parts.join('/')}.css`;
            },
            ignoreOrder: false,
        }),
    ],
};
