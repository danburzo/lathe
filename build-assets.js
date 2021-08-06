const fs = require('fs').promises;
const path = require('path');
const opsh = require('opsh');
const esbuild = require('esbuild');
const pkg = require('./package.json');

const args = opsh(process.argv.slice(2), [
	'module',
	'h',
	'help',
	'v',
	'version'
]);

if (args.options.v || args.options.version) {
	console.log(pkg.version);
	process.exit(0);
}

if (args.options.h || args.options.help) {
	process.exit(0);
}

const outDir = args.options.d || args.options.outdir || 'static/dist';
const format = args.options.module ? 'esm' : 'iife';
const task = args.operands[0];

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

		return esbuild.build({
			entryPoints,
			format,
			stdin: {
				contents: files.map(f => `require('./${f}');`).join('\n'),
				resolveDir: __dirname
			},
			bundle: true,
			minify: task === 'build',
			watch: task === 'start',
			entryNames: '[name].[hash]',
			assetNames: '[name].[hash]',
			outdir: outDir,
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
								path.join(outDir, 'manifest.json'),
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
