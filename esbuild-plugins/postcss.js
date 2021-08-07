const fs = require('fs').promises;
const postcss = require('postcss');

/*
	PostCSS plugin
	--------------
 */
module.exports = function postcssPlugin(options) {
	let opts = {
		filter: /\.css$/,
		plugins: [],
		...options
	};
	const processor = postcss(opts.plugins);
	return {
		name: 'postcss',
		setup(build) {
			build.onLoad({ filter: opts.filter }, async args => {
				const contents = await fs.readFile(args.path, 'utf8');
				try {
					const result = await processor.process(contents, {
						from: args.path,
						to: args.path
					});
					return {
						contents: result.content,
						loader: 'css'
					};
				} catch (err) {
					return {
						errors: [
							{
								text: err.message,
								location: {
									file: args.path,
									namespace: opts.namespace,
									line: err.line,
									column: err.column,
									lineText:
										contents.split(
											/\r\n|\r|\n|\u2028|\u2029/
										)[err.line - 1] || ''
								}
							}
						]
					};
				}
			});
		}
	};
};
