<?php
	
	$context['posts'] = Timber::get_posts();
	array_unshift($templates, 'archive/archive.twig');
	
	if ( is_day() ) {
		$context['title'] = 'Archive: '.get_the_date( 'D M Y' );
	} else if ( is_month() ) {
		$context['title'] = 'Archive: '.get_the_date( 'M Y' );
	} else if ( is_year() ) {
		$context['title'] = 'Archive: '.get_the_date( 'Y' );
	} else if ( is_tag() ) {
		$context['title'] = single_tag_title( '', false );
	} else if ( is_category() ) {
		array_unshift( $templates, 'archive/archive-' . get_query_var( 'cat' ) . '.twig' );
	} else if ( is_post_type_archive() ) {
		$post_type = get_post_type();
		array_unshift( $templates, 'archive/archive-' . $post_type . '.twig' );
		$post_type_include = $context_base . 'archive-' . $post_type . '.php';
		if (file_exists($post_type_include)) {
			include($post_type_include);
		}
	}
?>