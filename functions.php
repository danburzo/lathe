<?php

if (!class_exists('Timber')) {
	add_action('admin_notices', function() {
		echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php') ) . '</a></p></div>';
	});
	
	add_filter('template_include', function($template) {
		return get_stylesheet_directory() . '/static/no-timber.html';
	});
	
	return;
}

/*
	Where to look for Twig templates.
 */
Timber::$dirname = array('templates');

class LatheSite extends Timber\Site {

	function __construct() {

		add_action('after_setup_theme', array($this, 'configure_theme'));

		/*
			Custom menu locations
			---------------------
		*/
		$menus = array(
			'main-menu' => __('Main Menu', 'lathe'),
			'footer-menu' => __('Footer Menu', 'lathe'),
			'social-menu' => __('Social Menu', 'lathe')
		);

		/* Register menu locations */
		add_action('init', function() use ($menus) {
			register_nav_menus($menus);
		});

		$this->setup_site_settings();

		add_filter('timber_context', function($context) use ($menus) {
			// Pagination
			$context['pagination'] = Timber::get_pagination();

			// Main menu
			$context['menu'] = new Timber\Menu('main-menu');

			// All menus
			$context['menus'] = [];

			// Site Settings
			if (function_exists('get_fields')) {
				$context['options'] = get_fields('option');
			}

			foreach(array_keys($menus) as $key) {
				$context['menus'][$key] = new Timber\Menu($key);
			}

			return $context;
		});
		
		parent::__construct();
	}

	/*
		Configure the theme
		-------------------

		Setup the theme's capabilities and 
		attach hooks to relevant filters and actions.
	 */
	function configure_theme() {

		/*
			Load translations
		 */
		load_theme_textdomain('lathe', get_template_directory() . '/languages');

		/*
			Enable support for post formats.
		 */
		add_theme_support('post-formats');

		/*
			Enable support for post thumbnails on posts and pages.
			
			https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support('post-thumbnails');

		add_theme_support('menus');
		add_theme_support('html5', array(
			'comment-list', 
			'comment-form', 
			'search-form', 
			'gallery', 
			'caption'
		));

		/*
			Add support for responsive embedded content.
		 */
		add_theme_support('responsive-embeds');

		/*
			Add default posts and comments RSS feed links to head.
		 */
		add_theme_support('automatic-feed-links');

		/* 
			This denotes that the theme does not set its own 
			<title> tag, but rather lets WordPress (or plugins)
			decides what to show.

			The filters below allow us to control aspects
			of <title> generation from within the theme.
		*/
		add_theme_support('title-tag');

		add_filter('document_title_parts', function($title) {
			// Remove the tagline from the front page
			unset($title['tagline']);
			return $title;
		});

		add_filter('document_title_separator', function($sep) {
			return '·';
		});
	}

	function setup_site_settings() {
		if(function_exists('acf_add_options_page')) {
			acf_add_options_page(array(
				'page_title' => __('Site Settings', 'lathe'),
				'menu_title' => __('Site Settings', 'lathe'),
				'menu_slug' => __('site-settings', 'lathe'),
				'capability' => 'edit_posts',
				'redirect' => false
			));
		}

		if(function_exists('acf_add_local_field_group')) {
			require_once 'field-groups/general.php';
		}
	}
	
}

new LatheSite();
