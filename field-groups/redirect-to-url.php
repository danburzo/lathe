<?php

/*
	ACF Group: Redirect to URL
	--------------------------
 */

acf_add_local_field_group(array(
	'key' => 'group_5d2da28277e2a',
	'title' => __('Redirect to URL', 'lathe'),
	'fields' => array(
		array(
			'key' => 'field_5d2da28ed032d',
			'label' => __('URL', 'lathe'),
			'name' => 'redirect_to_url',
			'type' => 'url',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'page_template',
				'operator' => '==',
				'value' => 'page-templates/template-redirect-to-url.php',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'side',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
));