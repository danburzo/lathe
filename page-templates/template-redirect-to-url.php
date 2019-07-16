<?php

/*
	Template Name: Redirect to URL
	Template Post Type: page, post
*/

if (function_exists('get_field')) {
	$p = new Timber\Post();
	$redirect_to = get_field('redirect_to_url', $p->ID);
	if ($redirect_to) {
		wp_redirect($redirect_to);
	}
}

include(get_template_directory() . '/index.php');