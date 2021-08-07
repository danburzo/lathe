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
		namespace: 'postcss-ns',
		...options
	};

	const processor = postcss(opts.plugins);

	return {
		name: 'postcss',
		setup(build) {
			build.onResolve(
				{ filter: opts.filter, namespace: 'file' },
				args => {
					return {
						path: args.path,
						namespace: opts.namespace,
						watchFiles: [args.path]
					};
				}
			);

			build.onLoad(
				{ filter: opts.filter, namespace: opts.namespace },
				async args => {
					const contents = await fs.readFile(args.path, 'utf8');
					try {
						const result = await processor.process(contents, {
							from: args.path,
							to: args.path
						});
						return {
							contents: result.css,
							loader: 'css'
						};
					} catch (err) {
						return {
							contents,
							errors: [
								{
									text: err.message,
									location: {
										file: args.path,
										namespace: opts.namespace,
										line: err.line,
										column: err.column,
										lineText:
											contents.split('\n')[err.line - 1]
									},
									detail: err
								}
							]
						};
					}
				}
			);
		}
	};
};
