<?php

use Timber\Loader;

class ACFHelper {
	static function init() {
		/* 
			Allow ACF fields and field groups to be translated.
		*/
		add_action('acf/init', function() {	
			add_filter('acf/settings/l10n_textdomain', function() {
				return 'lathe';
			});
		});

		/*
			Disable the behavior where ACF hides 
			the default WordPress `Custom fields` metaboxes. 
		*/
		add_filter('acf/settings/remove_wp_meta_box', '__return_false');
	}
}
