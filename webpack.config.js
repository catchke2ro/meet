const webpack = require('webpack');
const path = require('path');
const LodashModuleReplacementPlugin = require('lodash-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

const config = {
	entry: {
		app: './frontend/app.js',
		admin: './frontend/admin.js',
		email: './frontend/email.js',
		pdf: './frontend/pdf.js'
	},
	output: {
		path: path.resolve(__dirname, 'web/dist'),
		filename: '[name].js',
	},
	module: {
		rules: [
			{
				test: /\.(svg|eot|woff|woff2|ttf)$/,
				use: [
					{
						loader: 'file-loader'
					}
				]
			},
			{
				test: /\.s[ac]ss$/,
				use: [
					{
						loader: MiniCssExtractPlugin.loader,
					},
					{
						loader: 'css-loader',
					},
					{
						loader: 'resolve-url-loader',
					},
					{
						loader: 'sass-loader',
						options: {
							sourceMap: true,
						},
					},
				],
			},
		],
	},
	plugins: [
		new webpack.ContextReplacementPlugin(/moment[\/\\]locale$/, /en/),
		new LodashModuleReplacementPlugin,
		new MiniCssExtractPlugin,
		new webpack.ProvidePlugin({
			$: 'jquery',
			jQuery: 'jquery',
			'window.jQuery': 'jquery',
			'Popper': ['popper.js', 'default'],
		}),
	],
};

module.exports = config;