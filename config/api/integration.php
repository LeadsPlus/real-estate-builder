<?php 

global $PL_API_INTEGRATION;
$PL_API_INTEGRATION = array(
	'get' => array(
		'request' => array(
			'url' => 'https://placester.com/api/v2/integration/requests',
			'type' => 'GET'
		),
		'args' => array(),
		'returns' => array(
			'id' => false,
			'url' => false,
			'username' => false, 
			'ua_username' => false,
			'updated_at' => false,
			'created_at' => false,
			'completed_at' => false,
			'status' => false
		)
	),	
	'create' => array(
		'request' => array(
			'url' => 'https://placester.com/api/v2/integration/requests',
			'type' => 'POST'
		),
		'args' => array(
			'url' => array('type' => 'text', 'group' => 'basic', 'label' => 'RETS Url'),
			'username'  => array('type' => 'text', 'group' => 'basic', 'label' => 'Username'), 
			'password'  => array('type' => 'text', 'group' => 'basic', 'label' => 'Password'),
			'ua_username' => array('type' => 'text', 'group' => 'basic', 'label' => 'User Agent Username'),
			'ua_password' => array('type' => 'text', 'group' => 'basic', 'label' => 'User Agent Password'),
			'feed_agent_id' => array('type' => 'text', 'group' => 'basic', 'label' => 'Agent Id')
		),
		'returns' => array(
			'id' => false
		)
	)
);