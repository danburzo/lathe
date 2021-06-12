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

/* 
	Disable some WPML clutter
*/
define('ICL_DONT_LOAD_NAVIGATION_CSS', true);
define('ICL_DONT_LOAD_LANGUAGE_SELECTOR_CSS', true);
define('ICL_DONT_LOAD_LANGUAGES_JS', true);

use Timber\Site;
use Timber\Menu;
use Timber\Twig_Function;

require get_template_directory() . '/src/AdminHelper.php';
require get_template_directory() . '/src/AssetHelper.php';
require get_template_directory() . '/src/ImageHelper.php';
require get_template_directory() . '/src/ThemeHelper.php';
require get_template_directory() . '/src/ACFHelper.php';
require get_template_directory() . '/src/CustomTypesHelper.php';

/* The folder(s) containing Twig templates. */
Timber::$dirname = array('templates');

/* Twig template cache */
Timber::$cache = defined('WP_DEBUG') ? WP_DEBUG === false : false;

class LatheSite extends Site {

	function __construct() {

		add_action('after_setup_theme', function() {

			AssetHelper::init('/static/dist/');
			ThemeHelper::init();
			AdminHelper::init();
			CustomTypesHelper::init();
			
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

		/*
			Add things to the Timber context
			--------------------------------

			These values can be read directly 
			in all Twig templates, e.g.:

				{% if options.coming_soon %}
					Website coming soon!
				{% endif %}

			Note that more useful things get added 
			to the Timber context depending on the
			type of the currently displayed page
			in the `src/context/` PHP files.
		 */
		add_filter('timber/context', function($context) use ($menus) {
			/* 
				Site Menus
				----------
			*/
			$context['menus'] = [];
			foreach(array_keys($menus) as $key) {
				$context['menus'][$key] = new Menu($key);
			}
			/* Make the main menu accessible under `menu` directly */
			$context['menu'] = $context['menus']['main-menu'];

			/* 
				Site Settings
				-------------
			*/
			if (function_exists('get_fields')) {
				$context['options'] = get_fields('option');
			}

			/*
				WPML languages
				--------------
			 */
			if (function_exists('icl_get_languages')) {
				/* All languages */
				$context['languages'] = icl_get_languages('skip_missing=0');
				/* Current language */
				if (defined('ICL_LANGUAGE_CODE')) {
					$context['language'] = strtolower(ICL_LANGUAGE_CODE);
				}
			}

			return $context;
		});

		/*
			Add Twig functions and filters
			------------------------------
		 */
		add_filter('timber/twig', function($twig) {
			/* Twig Functions */
			$twig->addFunction(new Twig_Function('asset', array($this, 'asset')));

			/* Twig Filters */
			// TODO: This will need to be changed to Timber\Twig_Filter soon.
			$twig->addFilter(new Twig_SimpleFilter('size', array($this, 'size')));
			$twig->addFilter(new Twig_SimpleFilter('asset', array($this, 'asset')));
			$twig->addFilter(new Twig_SimpleFilter('hostname', function($url) {
				return preg_replace(
					'/[^\da-z]+/i',
					'-',
					str_ireplace('www.', '', parse_url($url, PHP_URL_HOST))
				);
			}));

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
