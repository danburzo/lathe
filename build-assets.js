#! /usr/bin/env node

const fs = require('fs').promises;
const path = require('path');
const pkg = require('./package.json');

/*
	The file extensions supported 
	as entry points in esbuild.
 */
const extSupportedAsEntry = /\.(css|mjs|jsx?|tsx?)$/;

/*
	Since esbuild needs explicit loaders
	for each file type you want to process,
	let's include a bunch of common file types
	to the default loaders.
 */
const defaultLoaders = {
	'.js': 'jsx',
	'.mjs': 'jsx',
	'.ts': 'tsx',
	'.tsx': 'tsx',
	'.json': 'json',
	'.txt': 'text',
	'.svg': 'file',
	'.png': 'file',
	'.jpg': 'file',
	'.jpeg': 'file',
	'.gif': 'file',
	'.otf': 'file',
	'.ttf': 'file',
	'.woff': 'file',
	'.woff2': 'file'
};

const args = require('opsh')(
	process.argv.slice(2),
	/* List of boolean CLI options */
	['module', 'h', 'help', 'v', 'version']
);

/*
	Default options
 */
const task = args.operands[0] || 'build';
const infile = args.options.i || args.options.infile || 'assets.txt';
const outdir = args.options.d || args.options.outdir || 'build';
const format = args.options.module ? 'esm' : 'iife';

/*
	Display the version when called with -v or --version
 */
if (args.options.v || args.options.version) {
	console.log(pkg.version);
	process.exit(0);
}

/*
	Display help information when called with -h or --help.
 */
if (args.options.h || args.options.help) {
	outputHelp();
	process.exit(0);
}

if (task !== 'build' && task !== 'start') {
	console.log(`Invalid command '${task}', expected 'start' or 'build'.`);
	process.exit(1);
}

fs.readFile(infile, 'utf8')
	.then(file =>
		file
			.split(/(?:\r\n|\r|\n|\u2028|\u2029)+/)
			.filter(l => l && !l.match(/^\s*#/))
			.map(l => l.trim())
	)
	.then(buildAssets)
	.catch(err => {
		console.error(err);
		process.exit(1);
	});

/*
	esbuild configuration
	---------------------
 */
function buildAssets(assets) {
	/*
		Make a list of files that can't be esbuild entry points, 
		so that we can bundle them more... creatively.
	 */
	const files = assets.filter(f => !f.match(extSupportedAsEntry));

	return require('esbuild').build({
		format,
		outdir,
		bundle: true,
		metafile: true,
		entryPoints: assets.filter(f => f.match(extSupportedAsEntry)),

		/*
			Files from assets.txt that can't be esbuild entry points 
			are loaded indirectly by faking a <stdin> input 
			that `require()`s each individual file.
		 */
		stdin: {
			contents: files.map(f => `require('./${f}');`).join('\n'),
			resolveDir: __dirname
		},
		minify: task === 'build',
		watch: task === 'start',
		entryNames: '[name].[hash]',
		assetNames: '[name].[hash]',
		/*
			If we're requesting in the assets.txt file
			some uncommon file extensions, let's make sure
			we use the 'file' loader for them, otherwise
			esbuild will throw an error.
		 */
		loader: files.map(path.extname).reduce(
			(loaders, ext) => {
				if (ext && !loaders[ext]) {
					loaders[ext] = 'file';
				}
				return loaders;
			},
			{ ...defaultLoaders }
		),

		plugins: [
			require('./esbuild-plugins/postcss')({
				plugins: [require('autoprefixer')]
			}),
			/*
			 	Extract a `manifest.json` file.
				Requires the `metafile: true` option.
			 */
			require('./esbuild-plugins/asset-manifest')(
				path.join(outdir, 'manifest.json')
			)
		]
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
  -i, --infile   The input file containting the asset list.
                 The default is 'assets.txt'.
  -d, --outdir   The output directory. The default is 'build'.
  --module       Bundle .js/.jsx/.ts/.tsx files in ES module format.

General options:
  -h, --help     Show instructions.
  -v, --version  Show current lathe/build-assets version.`);
}
