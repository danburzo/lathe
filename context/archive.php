<?php

/*
	Context building for archive pages
	----------------------------------
 */

array_unshift($templates, 'archive/archive.twig');

if (is_search()) {
	$context['title'] = get_search_query();
	array_unshift($templates, 'search.twig');
} else if (is_day()) {
	$context['title'] = 'Archive: '. get_the_date('D M Y');
} else if (is_month()) {
	$context['title'] = 'Archive: '. get_the_date('M Y');
} else if (is_year()) {
	$context['title'] = 'Archive: ' . get_the_date('Y');
} else if (is_tag()) {
	$context['title'] = single_tag_title('', false);
} else if (is_category()) {
	$context['title'] = single_cat_title('', false);
	array_unshift($templates, 'archive/archive-' . get_query_var('cat') . '.twig');
} else if (is_post_type_archive()) {
	$p_type = get_post_type();
	array_unshift($templates, "archive/archive-${$p_type}.twig");
	$post_type_include = context_path("archive-{$p_type}.php");
	if (file_exists($post_type_include)) {
		include($post_type_include);
	}
}

$context['posts'] = new Timber\PostQuery();