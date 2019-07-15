<?php
	include(get_template_directory() . '/context/context.php');
	Timber::render($templates, $context);
?>