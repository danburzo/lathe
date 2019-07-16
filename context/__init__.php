<?php

/*
	Timber Context configuration
	----------------------------

	This is the main file for configuring what's available
	on the Timber context, depending on the type of page
	to display.
 */

// The Timber Context to which we'll add things
$context = Timber::context();

/*
	"Coming Soon" page for non-logged-in users.
	You can comment this out when you're ready.
*/

if (
	isset($context['options']) && 
	$context['options']['is_coming_soon_enabled'] === true &&
	!is_user_logged_in() && !is_admin()
) {
	$templates = array('coming-soon.twig');
	return;
}

/* 
	The $templates variable holds our Twig template hierarchy.
	At the base we always have the index template,
	but depending on the type of page we'll add stuff in front.
*/
$templates = array('index.twig');

/*
	A helper function that returns 
	a path to a file from the `/context` folder.
 */
function context_path($path) {
	return get_template_directory() . '/context/' . $path;
}

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

/*
	Here we branch off into singular vs. archive pages.
	The context definition for these basic types are 
	split into their own files.
 */
if (is_singular()) {
	require_once context_path('singular.php');
} else {
	require_once context_path('archive.php');
}