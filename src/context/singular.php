<?php

use Timber\Post;

/*
	Context building for singular pages
	-----------------------------------
 */


/* Context building */

$context['post'] = new Post();

if (is_page()) {

	// nothing yet

} else if (is_single()) {

	/* 
		Include context building for custom post types.
		
		You can create `single-<post_type>.php` files 
		in the `/context` folder for customization.
	*/
	$inc_post_type = context_path("single-{$context['post']->post_type}.php");
	if (file_exists($inc_post_type)) {
		require_once $inc_post_type;
	}

}

if (is_page_template()) {

	/* 
		Include context building for custom page templates.
		
		You can create `<page-template>.php` files 
		in the `/context` folder for customization.
	*/

	$page_template = get_page_template_slug(get_queried_object_id());
	$inc_page_template = context_path(
		str_replace('page-templates/', '', $page_template)
	);
	if (file_exists($inc_page_template)) {
		require_once $inc_page_template;
	}
}

/* 
	Template Hierarchy for singular pages
	-------------------------------------
*/

if (is_page()) {
	array_unshift(
		$templates, 
		"page-{$context['post']->post_name}.twig", 
		"page.twig"
	);
} else if (is_single()) {
	array_unshift(
		$templates,
		$context['post']->post_parent ? 
			"single/single-{$context['post']->post_type}-subpage.twig" :
			"single/single-{$context['post']->post_type}-root.twig",
		"single/single-{$context['post']->post_type}.twig",
		"single/single.twig"
	);

	if (isset($custom_template)) {
		array_unshift(
			$templates,
			$custom_template
		);
	}

	array_unshift(
		$templates, 
		"single/single-{$context['post']->ID}.twig"
	);
}

if (is_front_page()) {
	array_unshift($templates, 'front.twig');
}
