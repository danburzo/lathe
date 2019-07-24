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
require get_template_directory() . '/src/ACFHelper.php';

/* The folder(s) containing Twig templates. */
Timber::$dirname = array('templates');

/* Twig template cache */
Timber::$cache = defined('WP_DEBUG') ? WP_DEBUG === false : false;

class LatheSite extends Site {

	function __construct() {

		add_action('after_setup_theme', function() {

			ThemeHelper::init();
			AssetHelper::init('/static/dist/');
			
			/* The set of image sizes used for the `size()` Twig filter */
			ImageHelper::init(array(
				'thumbnail' => [800, 600],
				'full' => [1920, 1280, 'center']
			));

			ACFHelper::init();
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
