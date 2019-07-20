<?php

if (!class_exists('Timber')) {
	add_action('admin_notices', function() {
		include get_stylesheet_directory() . '/src/messages/no-timber-admin.php';
	});
	add_filter('template_include', function($template) {
		return get_stylesheet_directory() . '/static/no-timber.html';
	});
	return;
}

use Timber\Site;
use Timber\Menu;
use Timber\Twig_Function;

require get_template_directory() . '/src/AssetHelper.php';
require get_template_directory() . '/src/ImageHelper.php';
require get_template_directory() . '/src/ThemeHelper.php';

/* The folder(s) containing Twig templates. */
Timber::$dirname = array('templates');

/* Twig template cache */
Timber::$cache = defined('WP_DEBUG') ? WP_DEBUG === false : false;

class LatheSite extends Site {

	function __construct() {

		add_action('after_setup_theme', function() {

			AssetHelper::init('/static/dist/');
			
			/* The set of image sizes used for the `size()` Twig filter */
			ImageHelper::init(array(
				'thumbnail' => [800, 600],
				'full' => [1920, 1280, 'center']
			));

			ThemeHelper::init();
		});

		/* Custom menu locations */
		$menus = array(
			'main-menu' => __('Main Menu', 'lathe'),
			'footer-menu' => __('Footer Menu', 'lathe'),
			'social-menu' => __('Social Menu', 'lathe')
		);

		/* Register menu locations */
		add_action('init', function() use ($menus) {
			register_nav_menus($menus);
		});
		// https://codex.wordpress.org/Plugin_API/Admin_Screen_Reference

		add_action('acf/init', function() {
			/* Allow ACF fields and field groups to be translated */
			add_filter('acf/settings/l10n_textdomain', function() {
				return 'lathe';
			});
			
			/* Create an Options Page */
			$options_page = acf_add_options_page(array(
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
				$loader = new Timber\Loader();
				$loader->clear_cache_twig();
			}
		});

		add_filter('timber/context', function($context) use ($menus) {
			// All menus
			$context['menus'] = [];
			foreach(array_keys($menus) as $key) {
				$context['menus'][$key] = new Menu($key);
			}
			$context['menu'] = $context['menus']['main-menu'];

			// Site Settings
			if (function_exists('get_fields')) {
				$context['options'] = get_fields('option');
			}

			return $context;
		});

		add_filter('timber/twig', function($twig) {
			/* Twig Functions */
			$twig->addFunction(new Twig_Function('asset', array($this, 'asset')));

			/* Twig Filters */
			// TODO: This will need to be changed to Timber\Twig_Filter soon.
			$twig->addFilter(new Twig_SimpleFilter('size', array($this, 'size')));
			$twig->addFilter(new Twig_SimpleFilter('asset', array($this, 'asset')));

			return $twig;
		});

		parent::__construct();
	}

	function asset($handle, $enqueue) {
		return AssetHelper::asset($handle, $enqueue);
	}

	function size($src, $size) {
		return ImageHelper::size($src, $size);
	}
}

new LatheSite();
