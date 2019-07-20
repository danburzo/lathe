<?php
class ImageHelper {

		static $image_sizes;

		static function init($image_sizes) {
			self::$image_sizes = $image_sizes;
		}

		static function size($src, $size = '') {
			/*
				For SVG files, or for when the size was not found,
				just return the original image.
			 */
			
			if (Timber\ImageHelper::is_svg($src)) {
				return $src;
			}

			if (!isset(self::$image_sizes[$size])) {
				trigger_error("Image size `{$size}` not defined", E_USER_NOTICE);
				return $src;
			}

			$dest = self::$image_sizes[$size];
			return Timber\ImageHelper::resize(
				$src,
				isset($dest[0]) ? $dest[0] : NULL, 
				isset($dest[1]) ? $dest[1] : NULL, 
				isset($dest[2]) ? $dest[2] : NULL
			);
		}
}