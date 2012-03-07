<?php 

global $PL_API_PEOPLE;
$PL_API_PEOPLE = array(
	'create' => array(
		'request' => array(
			'url' => 'https://placester.com/api/v2/people',
			'type' => 'POST'
		),
		'args' => array(
			'relation' => '',
			'cust_relation' => '',
			'metadata' => array(),
			'rel_people' => array(),
			'listings' => array(),
			'shared_with_ids' => array(),
			'fav_listing_ids' => array(),
			'location' => array(
				'address' => array('type' => 'text','group' => 'location', 'label' => 'Address'), 
				'locality'  => array('type' => 'text','group' => 'location', 'label' => 'City'),
				'region'  => array('type' => 'text','group' => 'location', 'label' => 'State'),
				'postal' => array('type' => 'text','group' => 'location', 'label' => 'Zip Code'),
				'unit'  => array('type' => 'text','group' => 'location', 'label' => 'Unit'),
				'neighborhood'  => array('type' => 'text','group' => 'location', 'label' => 'Neighborhood'),
				'country'  => array('type' => 'text','group' => 'location', 'label' => 'Country')
			)
		),
		'returns' => array(
			'id' => false
		)
	),
	'details' => array(
		'request' => array(
			'url' => 'https://placester.com/api/v2/people/',
			'type' => 'GET',
			'cache' => false
		),
		'args' => array(
			'id' => ''
		),
		'returns' => array(
			'id' => false,
			'relation' => '',
			'cust_relation' => '',
			'cur_data' => array(),
			'uncur_data' => array(),
			'rel_people' => array(),
			'fav_listings' => array()
		)
	)
);