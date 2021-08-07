const fs = require('fs').promises;
const path = require('path');

/*
	esbuild plugin: extract manifest file
	-------------------------------------

	Produces a manifest from the bundled files
	at the `outfile` destination.
 */
module.exports = function extractManifestPlugin(outfile) {
	return {
		name: 'extract-manifest',
		setup(build) {
			build.onEnd(result => {
				const manifest = {};
				if (!result.metafile) {
					console.info(
						'No manifest file produced. Error occurred, see above.'
					);
					return;
				}
				const { outputs } = result.metafile;
				Object.keys(outputs).forEach(outpath => {
					const meta = outputs[outpath];
					let inpath;
					if (meta.entryPoint) {
						if (meta.entryPoint !== '<stdin>') {
							inpath = meta.entryPoint;
						}
					} else {
						inpath = Object.keys(meta.inputs).pop();
					}
					if (inpath) {
						manifest[inpath] = {
							path: outpath
						};
					}
				});
				Object.keys(manifest).forEach(inpath => {
					Object.keys(outputs[manifest[inpath].path].inputs).forEach(
						input => {
							if (
								input !== inpath &&
								manifest[input] &&
								path.extname(input).match(/\.(css|js)$/)
							) {
								if (!manifest[inpath].dependencies) {
									manifest[inpath].dependencies = [];
								}
								manifest[inpath].dependencies.push(input);
							}
						}
					);
				});
				console.info(
					`Updated ${outfile} (${new Date().toTimeString()})`
				);
				fs.writeFile(outfile, JSON.stringify(manifest, null, '\t'));
			});
		}
	};
};
