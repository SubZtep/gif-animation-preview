
const webpack = require('webpack')
const path = require('path')
const AssetsPlugin = require('assets-webpack-plugin')

module.exports = {
	outputDir: 'admin',

	chainWebpack: config => {
		config
			.entryPoints
				.clear()
				.end()

			.entry('app')
				.add(path.resolve(__dirname, 'src/admin/main.js'))
				.end()

			.output
				.filename('[name].js')
				.chunkFilename('[name].js')
				.end()

			.plugins
				.delete('html')
				.delete('preload')
				.delete('prefetch')
				.end()

			.resolve
				.extensions
					.add('.js')
					.add('.json')
					.add('.vue')
					.end()

		//console.log('config', config)
	},

	css: {
		//extract: false
		loaderOptions: {
			css: {
				modules: true,
				fileName: '[name]'
			}
		}
	},
}
