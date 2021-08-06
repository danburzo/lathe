#! /usr/bin/env node

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

const outdir = args.options.d || args.options.outdir || 'static/dist';
const format = args.options.module ? 'esm' : 'iife';
const task = args.operands[0];

if (!task || args.options.h || args.options.help) {
	outputHelp();
	process.exit(0);
}

if (task !== 'build' && task !== 'start') {
	console.log(`Invalid command '${task}', expected 'start' or 'build'.`);
	process.exit(1);
}

const extSupportedAsEntry = new Set([
	'.css',
	'.js',
	'.jsx',
	'.mjs',
	'.ts',
	'.tsx'
]);

const extHasLoader = new Set([
	'.css',
	'.js',
	'.jsx',
	'.mjs',
	'.ts',
	'.tsx',
	'.json',
	'.txt'
]);

fs.readFile('./assets.txt', 'utf8')
	.then(file =>
		file
			.split(/\n+/)
			.filter(l => l && !l.match(/^\s*#/))
			.map(l => l.trim())
	)
	.then(buildAssets)
	.catch(err => {
		console.error(err);
		process.exit(1);
	});

/*
	esbuild plugin: extract manifest file
	-------------------------------------

	Produces a manifest from the bundled files
	at the `outfile` destination.
 */
function extractManifestPlugin(outfile) {
	return {
		name: 'extract-manifest',
		setup(build) {
			build.onEnd(result => {
				const manifest = {};
				const { outputs } = result.metafile;
				Object.keys(outputs).forEach(outpath => {
					const meta = outputs[outpath];
					let inpath = meta.entryPoint;
					if (inpath) {
						/*
							If the entry is marked as an entry point,
							include it in the manifest.
						 */
						if (inpath !== '<stdin>') {
							manifest[inpath] = outpath;
						}
					} else {
						/*
							Otherwise look for the files we've required
							through the `<stdin>` entry, and include those
							as well in the manifest.
						 */
						let inputs = Object.keys(meta.inputs);
						if (inputs.length === 1) {
							inpath = inputs[0];
							if (files.includes(inpath)) {
								manifest[inpath] = outpath;
							}
						}
					}
				});
				fs.writeFile(outfile, JSON.stringify(manifest, null, 2));
			});
		}
	};
}

function buildAssets(assets) {
	const entryPoints = assets.filter(f =>
		extSupportedAsEntry.has(path.extname(f))
	);

	const files = assets.filter(f => !extSupportedAsEntry.has(path.extname(f)));

	/*
		Treat all files that are 
	 */
	const loader = files.reduce(
		(loader, file) => {
			const ext = path.extname(file);
			if (ext && !extHasLoader.has(ext)) {
				loader[ext] = 'file';
			}
			return loader;
		},
		{
			'.js': 'jsx',
			'.mjs': 'jsx'
		}
	);

	const fileRequires = files.map(f => `require('./${f}');`).join('\n');

	return esbuild.build({
		entryPoints,
		format,
		stdin: {
			contents: fileRequires,
			resolveDir: __dirname
		},
		bundle: true,
		minify: task === 'build',
		watch: task === 'start',
		entryNames: '[name].[hash]',
		assetNames: '[name].[hash]',
		outdir,
		loader,
		plugins: [extractManifestPlugin(path.join(outdir, 'manifest.json'))],
		metafile: true
	});
}

/*
	Show usage instructions
	-----------------------
 */
function outputHelp() {
	console.log(`
------------------
lathe/build-assets
v${pkg.version}
------------------

Usage:

  ./build-assets.js [command] [options]

Available commands:
  start          Builds the assets in development mode 
                 and rebuilds whenever any of the assets change.
  build          Builds the assets for production.

Available options:
  --module       Bundle .js/.jsx/.ts/.tsx files in ES module format.

General options:
  -h, --help     Show instructions.
  -v, --version  Show current lathe/build-assets version.`);
}
