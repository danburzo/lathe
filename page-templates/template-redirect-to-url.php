<?php

/*
	Template Name: Redirect to URL
	Template Post Type: page, post
*/

use Timber\Post;

function redirect_to_url() {
	if (function_exists('get_field')) {
		$p = new Post();
		$url = get_field('redirect_to_url', $p->ID);
		if ($url) {
			wp_redirect($url);
		}
	}
};

redirect_to_url();
require get_template_directory() . '/index.php';