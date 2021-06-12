<?php

use Timber\Loader;

class ACFHelper {
	static function init() {
		add_action('acf/init', function() {
			/* Allow ACF fields and field groups to be translated */
			add_filter('acf/settings/l10n_textdomain', function() {
				return 'lathe';
			});
			
			/* Create an Options Page */
			acf_add_options_page(array(
				'page_title' => __('Site Options', 'lathe'),
				'menu_title' => __('Site Options', 'lathe'),
				'menu_slug' => 'site-options',
				'capability' => 'edit_posts',
				'redirect' => false
			));
		});

		add_action('acf/input/admin_head', function() {
			add_meta_box(
				'site-options-actions', 
				__('Actions', 'lathe'), 
				function() {
					Timber::render('admin/metabox/actions.twig');
				},
				"acf_options_page",
				"side"
			);
		});
	}
}
