<?php

$p = new Timber\Post();
$context['post'] = $p;

if (function_exists('get_field')) {
	$redirect_to = get_field('redirect_to', $p->ID);
	if ($redirect_to) {
		wp_redirect($redirect_to);
	}
}

/*
	Context Building
	--------------------------------------
*/

if (is_page()) {

	// nothing yet

} else if (is_single()) {
	
	$post_type_include = $context_base . 'single-' . $p->post_type . '.php';
	if (file_exists($post_type_include)) {
		include($post_type_include);
	}
}

if (is_page_template()) {
	$page_template = get_page_template_slug(get_queried_object_id());
	$page_template_include = $context_base . str_replace('page-templates/', '', $page_template);
	if (file_exists($page_template_include)) {
		include($page_template_include);
	}
}

/* 
	Template Hierarchy
	---------------------------------------
*/

if (is_page()) {
	array_unshift(
		$templates, 
		'page-' . $p->post_name . '.twig', 
		'page.twig'
	);
} else if (is_single()) {
	array_unshift(
		$templates,
		$p->post_parent ? 
			'single/single-' . $p->post_type . '-subpage.twig' :
			'single/single-' . $p->post_type . '-root.twig',
		'single/single-'. $p->post_type . '.twig',
		'single/single.twig'
	);

	if (isset($custom_template)) {
		array_unshift(
			$templates,
			$custom_template
		);
	}

	array_unshift(
		$templates, 
		'single/single-' . $p->ID . '.twig'
	);
}
