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
				'menu_slug' => __('site-options', 'lathe'),
				'capability' => 'edit_posts',
				'redirect' => false
			));
		});

		add_action( 'acf/input/admin_head', function() {
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

		add_action('wp_ajax_clear_twig_cache', function() {
			if (check_ajax_referer('clear_twig_cache')) {
				$loader = new Loader();
				$loader->clear_cache_twig();
			}
		});

		add_action('wp_ajax_flush_rewrites', function() {
			if (check_ajax_referer('flush_rewrites')) {
				flush_rewrite_rules();
			}
		});
	}
}