<?php 

global $PL_TP_GOOGLE_PLACES;
$PL_TP_GOOGLE_PLACES = array(
	'get' => array(
		'request' => array(
			'url' => 'https://maps.googleapis.com/maps/api/place/search/json',
			'type' => 'GET'
		),
		'args' => array(
			'keyword' => '',
			'language' => '',
			'name' => '',
			'rankby' => '',
			'types' => '',
			'sensor' => 'false'
			),
		'returns' => array()
	)
);