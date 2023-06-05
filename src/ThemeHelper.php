<?php
/*
	Configure the theme
	-------------------

	Setup the theme's capabilities and 
	attach hooks to relevant filters and actions.
 */
class ThemeHelper {
	static function init() {
			/*
				Load translations
			 */
			load_theme_textdomain('lathe', get_template_directory() . '/languages');

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
				'caption',
				'style',
				'script',
				'navigation-widgets'
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

			/*
				Register Theme Options
				----------------------

				See: https://developer.wordpress.org/themes/customize-api/customizer-objects/
			*/
			add_action('customize_register', function($wp_customize) {

				function lathe_sanitize_checkbox($value) {
					return isset($value) && $value == true;
				}

				$wp_customize->add_setting(
					'is_coming_soon_enabled', 
					array(
						'type' => 'theme_mod',
						'capability' => 'edit_theme_options',
						'default' => false,
						'transport' => 'refresh', // or postMessage
						'sanitize_callback' => 'lathe_sanitize_checkbox',
						'sanitize_js_callback' => '', // Basically to_json.
					)
				);

				$wp_customize->add_section(
					'theme_options', 
					array(
						'title' => __('Theme Options', 'lathe'),
						'description' => __('Customize the theme', 'lathe'),
						'priority' => 160,
						'capability' => 'edit_theme_options'
					)
				);

				$wp_customize->add_control(
					'is_coming_soon_enabled', 
					array(
						'type' => 'checkbox',
						'priority' => 10,
						'section' => 'theme_options',
						'label' => __('Coming soon', 'lathe'),
						'description' => __('Enable the Coming Soon page for non-logged-in users.', 'lathe'),
						'active_callback' => ''
					)
				);
			});


			/*
				Customize the WP Query object.

				This only applies to the main query on the page,
				and only on the user-facing website.
			 */
			add_action('pre_get_posts', function($query) {
				if (is_admin() || !$query->is_main_query()) {
					return;
				}

				/*
					For post type archives, ony show the top-level posts.
				 */
				if ($query->is_post_type_archive()) {
					if ($query->query_vars['post_parent'] == false) {
						$query->set('post_parent', 0);
					}
				}
			});

			/*
				Add the `.current_page_parent` class to a CPT archive page
				in a WordPress menu when visiting a single post of that type.

				Reference: https://core.trac.wordpress.org/ticket/38836
				(The code can be removed once the bug is closed)
			 */
			add_filter('nav_menu_css_class', function ($classes, $item) {
				// Get an array of all public custom post types.
				$post_types = get_post_types(
					array(
						'public'   => true,
						'_builtin' => false
					)
				);

				// Check if a custom post type single item is being viewed.
				if (is_singular($post_types)) {
					// Get the post type being viewed.
					$post_type = get_post_type();
					if ($post_type === $item->object) {
						$classes[] = 'current_page_parent';
					}
				}

				return $classes;
			}, 10, 2);

			/* 
				Fixes issue with nesting in the Menu editor
				Reference: https://core.trac.wordpress.org/ticket/18282
			*/
			add_filter('nav_menu_meta_box_object', function($obj) {
				$obj->_default_query = array('posts_per_page' => -1);
				return $obj;
			}, 9);

			/*
				Deregister default WordPress Gutenberg block styles.
				To opt into the feature uncomment the code below:
				
				add_action('wp_enqueue_scripts', function() {
					wp_dequeue_style('wp-block-library');
				});
			*/
	}
}
