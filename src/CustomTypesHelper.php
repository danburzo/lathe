<?php

class CustomTypesHelper {
	static function init() {
		add_action('init', function() {
			// self::cpt_thing();
			// self::tax_thing_type();
		});
	}

/*
	Sample Custom Post Type: Thing
	------------------------------
 */

/*
	static function cpt_thing() {
		register_post_type('thing', array(
			'labels' => array(
				'name' => __('Things', 'lathe'),
				'singular_name' => __('Thing', 'lathe')
			),
			'public' => true,
			'menu_icon' => 'dashicons-book',
			'menu_position' => 5,
			'hierarchical' => true,
			'supports' => array(
				'title',
				'editor',
				'thumbnail',
				'excerpt',
				'custom-fields',
				'page-attributes'
			),
			'has_archive' => true,
			'rewrite' => array(
				'slug' => 'things',
				'with_front' => false
			),
			'show_in_rest' => true
		));
	}
*/

/*
	Sample custom taxonomy: Thing Types
	-----------------------------------
 */

/*
	static function tax_thing_type() {
		register_taxonomy('thing-type', 'thing', array(
			'labels' => array(
				'name' => __('Thing Types', 'lathe'),
				'singular_name' => __('Thing Type', 'lathe')
			),
			'public' => true,
			'show_in_rest' => true,
			'hierarchical' => true,
			'rewrite' => array(
				'slug' => 'thing-types',
				'with_front' => false
			)
		));
	}
*/
}