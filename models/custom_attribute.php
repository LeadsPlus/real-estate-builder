<?php

class PL_Custom_Attributes {
	
	public function get($args = array()) {
		$config = PL_Config::PL_API_CUST_ATTR('get');
		$request = array_merge(array( "api_key" => placester_get_api_key()), PL_Validate::request($args, $config['args']));
		$response = PL_HTTP::send_request($config['request']['url'], $request);
		foreach ($response as $attribute => $value) {
			//$value['id'] compensating for a bug in the api
		 	$response[$attribute] = PL_Validate::attributes($value['id'], $config['returns']);
		 }
		return $response;
	}

	public function create() {
		
	}

	public function update() {
		
	}

	public function details() {
		
	}

	public function keys_for_options ($args = array()) {
		$options = array();
		$attributes = self::get($args);
		foreach ($attributes as $attribute => $value) {
			$options[$value['id']] = (string) $value['cat'] . ': ' . $value['name'];
		}
		return $options;
	}

//end class
}