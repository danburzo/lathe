<?php

$context_base = get_template_directory() . '/context/';

$context = Timber::context();

$templates = array('index.twig');

if (is_404()) {
	$templates = array('404.twig');
	return;
}

if (is_search()) {
	$context['posts'] = new Timber\PostQuery();
	$templates = array('search.twig', 'archive.twig', 'index.twig');
	return;
}

if (is_home()) {
	array_unshift($templates, 'home.twig');
}

if (is_singular()) {
	include($context_base . 'singular.php');
} else {
	include($context_base . 'archive.php');
}