const fs = require('fs').promises;
const path = require('path');
const task = process.argv[2];

if (task !== 'build' && task !== 'start') {
	console.log(`Invalid task '${task}'`);
	process.exit(1);
}

const isEntry = asset => asset.match(/\.(css|jsx?|tsx?)$/);

fs.readFile('./assets.txt', 'utf8')
	.then(file =>
		file
			.split(/\n+/)
			.filter(l => l && !l.match(/^\s*#/))
			.map(l => l.trim())
	)
	.then(assets => {
		const entryPoints = assets.filter(isEntry);
		const files = assets.filter(f => !isEntry(f));

		const extensions = {};
		files.forEach(file => {
			const ext = path.extname(file);
			if (ext) {
				extensions[ext] = 'file';
			}
		});

		return require('esbuild').build({
			entryPoints,
			stdin: {
				contents: files.map(f => `require('./${f}');`).join('\n'),
				resolveDir: __dirname
			},
			bundle: true,
			watch: task === 'start',
			entryNames: '[name].[hash]',
			assetNames: '[name].[hash]',
			outdir: 'static/dist',
			loader: {
				'.js': 'jsx',
				'.mjs': 'jsx',
				...extensions
			},
			plugins: [
				{
					name: 'manifest',
					setup(build) {
						build.onEnd(result => {
							let manifest = {};
							let { outputs } = result.metafile;
							Object.keys(outputs).forEach(outpath => {
								const meta = outputs[outpath];
								let inpath = meta.entryPoint;
								if (inpath) {
									if (inpath !== '<stdin>') {
										manifest[inpath] = outpath;
									}
								} else {
									let inputs = Object.keys(meta.inputs);
									if (inputs.length === 1) {
										inpath = inputs[0];
										if (files.includes(inpath)) {
											manifest[inpath] = outpath;
										}
									}
								}
							});
							fs.writeFile(
								'manifest.json',
								JSON.stringify(manifest, null, 2)
							);
						});
					}
				}
			],
			metafile: true
		});
	})
	.catch(() => process.exit(1));
