<?php 

class PL_Listing {
	
	public function get($args = array()) {
		// merge incoming args with preset options, basically api key at this point.
		$request = array_merge(array("api_key" => PL_Option_Helper::api_key()), PL_Validate::request($args, PL_Config::PL_API_LISTINGS('get', 'args')));
		// sent request, use details from config.
		$response = PL_HTTP::send_request(PL_Config::PL_API_LISTINGS('get', 'request', 'url'), $request, PL_Config::PL_API_LISTINGS('get', 'request', 'type'));
		// validate response. 
		if (isset($response) && isset($response['listings']) && is_array($response['listings'])) {
			foreach ($response['listings'] as $key => $listing) {
				$response['listings'][$key] = PL_Validate::attributes($listing, PL_Config::PL_API_LISTINGS('get','returns'));
				PL_Pages::manage_listing($response['listings'][$key]);
			}
		} else {
			$response = PL_Validate::attributes($response, array('listings' => array(), 'total' => 0));
		}
		return $response;
	}

	public function create($args = array()) {
		$request = array_merge(array("api_key" => PL_Option_Helper::api_key()), PL_Validate::request($args, PL_Config::PL_API_LISTINGS('create', 'args')));
		$response = PL_HTTP::send_request(PL_Config::PL_API_LISTINGS('create', 'request', 'url'), $request, PL_Config::PL_API_LISTINGS('create', 'request', 'type'));
		return $response;
	}

	public function update() {
		
	}

	public function delete() {
		
	}

	public function details($args = array()) {
		$config = PL_Config::PL_API_LISTINGS('details');
		$request = array_merge(array("api_key" => PL_Option_Helper::api_key()), PL_Validate::request($args, $config['args']));
		$details_url = trailingslashit($config['request']['url']) . $request['id'];
		$response = PL_HTTP::send_request($details_url, $request, $config['request']['type']);
		$response = PL_Validate::attributes($response, $config['returns']);
		return $response;	
	}

	public function temp_image ($args = array(), $file_name, $file_mime_type, $file_tmpname) {
		$config = PL_Config::PL_API_LISTINGS('temp_image');
		$request = array_merge(array("api_key" => PL_Option_Helper::api_key()), PL_Validate::request($args, $config['args']));
		$response = PL_HTTP::send_request_multipart($config['request']['url'], $request, $file_name, $file_mime_type, $file_tmpname);
		return $response;	
	}

	public function locations($args = array()) {
		$config = PL_Config::PL_API_LISTINGS('get.locations');
		$request = array_merge(array("api_key" => PL_Option_Helper::api_key()), PL_Validate::request($args, $config['args']));
		return PL_Validate::attributes(PL_HTTP::send_request($config['request']['url'], $request), $config['returns']);
	}


// end of class
}