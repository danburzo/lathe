<?php

acf_add_local_field_group(array(
	'key' => 'group_5d2da02649c38',
	'title' => __('Site Settings: General', 'lathe'),
	'fields' => array(
		array(
			'key' => 'field_5d2da033798ad',
			'label' => __('Enable "Coming Soon" Page', 'lathe'),
			'name' => 'is_coming_soon_enabled',
			'type' => 'true_false',
			'instructions' => __('Should we display the "Coming Soon" page to unauthenticated users?', 'lathe'),
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => __('Enable "Coming Soon" Page', 'lathe'),
			'default_value' => 0,
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'site-settings',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
));