<?php

/*
	Plugin name: Custom Post Type: Thing
	Version: 1.0.0
	Text Domain: lathe
	Description: Registers the `thing` CPT and the `thing-type` custom taxonomy.
*/

add_action('init', function() {

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
});