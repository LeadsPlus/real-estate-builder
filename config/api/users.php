<?php 

global $PL_API_USERS;
$PL_API_USERS = array(
	'whoami' => array(
		'request' => array(
			'url' => 'https://placester.com/api/v2.0/organizations/whoami',
			'type' => 'GET'
		),
		'args' => array(),
		'returns' => array(
			'id' => false,
			'name' => false,
			'phone' => false,
			'website' => false,
			'is_verified' => false,
			'api_key_id' => false,
			'location' => array(
				'address' => false,
				'locality' => false,
				'region' => false,
				'postal' => false,
				'neighborhood' => false,
				'country' => false,
				'latitude' => false,
				'longitude' => false
			),
			'provider' => array(
				'id' => false,
				'name' => false,
				'website' => false
			),
			'user' => array(
				'id' => false,
				'first_name' => false,
				'last_name' => false,
				'email' => false,
				'phone' => false,
				'website' => false,
			),
			'disabled_publishers' => array()
		)
	),
	'setup' => array(
		'request' => array(
			'url' => 'https://placester.com/api/v2.0/users/setup',
			'type' => 'POST'
		),
		'args' => array(
			'email' => array(
				'label' => 'Confirm Email Address',
				'type' => 'text',
				'group' => 'required'
			),
			'first_name'  => array('label' => 'First Name','type' => 'text'),
			'last_name'  => array('label' => 'Last Name','type' => 'text'),
			'phone'  => array('label' => 'Phone Number','type' => 'text'),
			'website' => array(),
			'about' => array(),
			'slogan' => array(),
			'has_group' => array(),
			'password'  => array('label' => 'Password','type' => 'password'),
			'password_confirmation'  => array('label' => 'Confirm Password','type' => 'confirm_password'),
			'location' => array(
				'address' => array('label' => 'Street','type' => 'text'),
				'postal' => array('label' => 'Zip','type' => 'text'),
				'region'  => array('label' => 'State','type' => 'text'),
				'locality'  => array('label' => 'City','type' => 'text'),
				'country'  => array('label' => 'Country','type' => 'text')
			)
		),
		'returns' => array(
		)
	)
);