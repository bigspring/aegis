<?php

$config['navigation'] = array(

	'welcome' => array(

		'name' => 'Home',
		'icon' => 'fa-home', // top level icon
	),

	'logout' => array(

		'name' => 'Somewhere Else',
		'icon' => 'fa-location-arrow', // top level icon
	),

	'user' => array(

		'name' => 'Manage Users',
		'icon' => 'fa-users', // top level icon

		'users' => array(
			'name' => 'Users',
			'description' => 'This is a control panel for managing users, and it only accessible to administrators.',
			'icon' => ''
		)
	)
);