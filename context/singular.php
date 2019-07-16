<?php

$context['post'] = new Timber\Post();

/*
	Context Building
	--------------------------------------
*/

if (is_page()) {

	// nothing yet

} else if (is_single()) {
	
	$post_type_include = $context_base . 'single-' . $context['post']->post_type . '.php';
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
		'page-' . $context['post']->post_name . '.twig', 
		'page.twig'
	);
} else if (is_single()) {
	array_unshift(
		$templates,
		$context['post']->post_parent ? 
			'single/single-' . $context['post']->post_type . '-subpage.twig' :
			'single/single-' . $context['post']->post_type . '-root.twig',
		'single/single-'. $context['post']->post_type . '.twig',
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
		'single/single-' . $context['post']->ID . '.twig'
	);
}
