<?php 

global $PL_API_WORDPRESS;
$PL_API_WORDPRESS = array(
	'set' => array(
		'request' => array(
			'url' => 'https://placester.com/api/v2/wordpress/filters/',
			'type' => 'POST'
		),
		'args' => array(
			'url' => ''
		),
		'returns' => array()
	),
	'delete' => array(
		'request' => array(
			'url' => 'https://placester.com/api/v2/wordpress/filters/',
			'type' => 'delete'
		),
		'args' => array(
			'url' => ''
		),
		'returns' => array()
	)
);