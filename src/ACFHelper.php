<?php

use Timber\Loader;

class ACFHelper {
	static function init() {
		if (function_exists('acf_add_options_page')) {
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

			/*
				Automatically register option pages
				for the archive page of each custom post type.

				Note that we use the `init` hook rather than the `acf/init` hook,
				with a priority of 11 (smaller than the default 10), so that 
				the theme can register the CPTs before this gets run.
				(For this reason we also want to check that ACF is actually installed.)
			 */

			add_action('init', function() {
				$post_types = get_post_types(
					array(
						'public'   => true,
						'_builtin' => false
					),
					'objects'
				);
				foreach ($post_types as $post_type) {
					$post_type_slug = $post_type->name;
					$options_title = $post_type->labels->name . ": " . __("Archive Options", "lathe");
					acf_add_options_page(array(
						'page_title' => $options_title,
						'menu_title' => $options_title,
						'parent_slug'   => "edit.php?post_type={$post_type_slug}",
						'menu_slug' => "{$post_type_slug}-archive-options",
						'capability' => 'edit_posts',
						'redirect' => false
					));
				}
			}, 11);	
		}

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
