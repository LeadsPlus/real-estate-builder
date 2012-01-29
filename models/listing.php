<?php 

class PL_Listing {
	
	public function get($args = array()) {
		$config = PL_Config::PL_API_LISTINGS('get');
		$request = array_merge(array("api_key" => placester_get_api_key()), PL_Validate::request($args, $config['args']));
		$response = PL_HTTP::send_request($config['request']['url'], $request);
		if (isset($response) && isset($response['listings'])) {
			foreach ($response['listings'] as $key => $listing) {
				$response['listings'][$key] = PL_Validate::attributes($listing, $config['returns']);
			}
		}
		
		return $response;
	}

	public function create() {
		
	}

	public function update() {
		
	}

	public function delete() {
		
	}

	public function details() {
		
	}

	public function locations($args = array()) {
		$config = PL_Config::PL_API_LISTINGS('get.locations');
		$request = array_merge(array("api_key" => placester_get_api_key()), PL_Validate::request($args, $config['args']));
		return PL_Validate::attributes(PL_HTTP::send_request($config['request']['url'], $request), $config['returns']);
	}

	public function locations_for_options($return_only) {
		$options = array();
		$response = self::locations();
		if ($return_only && isset($response[$return_only])) {
			foreach ($response[$return_only] as $key => $value) {
				$options[$value] = $value;
			}
			return $options;	
		} else {
			return array();	
		}
	}

// end of class
}