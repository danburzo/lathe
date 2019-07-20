<?php

class AssetHelper {

	const MANIFEST_FILE = 'parcel-manifest.json';
	static $__manifest__ = false;
	static $asset_path;

	static function init($asset_path) {
		self::$asset_path = $asset_path;
	}

	static function asset($handle, $enqueue = false) {
		// Manifest file has not been loaded yet, let's do that first.
		if (self::$__manifest__ === false) {
			$p = get_template_directory() . self::$asset_path . self::MANIFEST_FILE;
			if (file_exists($p)) {
				self::$__manifest__ = json_decode(file_get_contents($p), TRUE);
			} else {
				self::$__manifest__ = NULL;
			}
		}

		// Manifest not found
		if (self::$__manifest__ === NULL) {
			trigger_error("Could not load manifest file", E_USER_WARNING);
			return;
		}

		// Handle not found in manifest
		if (!isset(self::$__manifest__[$handle])) {
			trigger_error("{$handle} is not defined as an asset", E_USER_WARNING);
			return;
		}

		$src = self::$__manifest__[$handle];
		$uri = get_template_directory_uri() . self::$asset_path . $src;

		if ($enqueue === false) {
			return $uri;
		}

		if ($enqueue === true) {
			if (preg_match('/\.js$/i', $uri)) {
				wp_enqueue_script($handle, $uri);
			} else if (preg_match('/\.css$/i', $uri)) {
				wp_enqueue_style($handle, $uri);
			} else {
				trigger_error("Can't enqueue {$handle}", E_USER_WARNING);
			}
			return;
		}

		if ($enqueue === 'inline') {
			return file_get_contents(
				get_template_directory() . self::$asset_path . $src
			);
		}

		trigger_error("Undefined mode {$enqueue}", E_USER_WARNING);
	}
}