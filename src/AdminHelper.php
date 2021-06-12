<?php

use Timber\Loader;

class AdminHelper {
	static function init() {
		add_action('wp_ajax_clear_twig_cache', function() {
			if (check_ajax_referer('clear_twig_cache')) {
				$loader = new Loader();
				$loader->clear_cache_twig();
				echo "Twig cache cleared.";
				wp_die();
			}
		});

		add_action('wp_ajax_flush_rewrites', function() {
			if (check_ajax_referer('flush_rewrites')) {
				flush_rewrite_rules();
				echo "Rewrite rules flushed.";
				wp_die();
			}
		});

		add_action('admin_bar_menu', function($admin_bar) {

			$admin_bar->add_node(
				array(
					'id' => 'theme-caches',
					'title' => 'Theme Caches'
				)
			);

			$clear_twig_cache_nonce = wp_create_nonce('clear_twig_cache');

			$admin_bar->add_node(
				array(
					'id' => 'clear-twig-cache',
					'parent' => 'theme-caches',
					'title' => 'Clear Twig Cache',
					'href'=>'#',
					'meta' => array(
						'class' => 'wp-admin-bar-ajax-menu-item',
						'html' => Timber::render('admin/ajax-action-form.twig', array(
							'action' => 'clear_twig_cache'
						))
					)
				)
			);

			$flush_rewrites_nonce = wp_create_nonce('flush_rewrites');

			$admin_bar->add_node(
				array(
					'id' => 'flush-rewrite-rules',
					'parent' => 'theme-caches',
					'title' => 'Flush Rewrite Rules',
					'href'=>'#',
					'meta' => array(
						'class' => 'wp-admin-bar-ajax-menu-item',
						'html' => Timber::render('admin/ajax-action-form.twig', array(
							'action' => 'flush_rewrites'
						))
					)
				)
			);
		}, 100);

		$admin_script_url = AssetHelper::asset('static/admin.js', false);
		add_action('admin_enqueue_scripts', function() use ($admin_script_url) {
			wp_enqueue_script('admin.js', $admin_script_url);
		});
		add_action('wp_enqueue_scripts', function() use ($admin_script_url) {
			if (is_admin_bar_showing()) {
				wp_enqueue_script('admin.js', $admin_script_url);
			}
		});
	}
}
?>