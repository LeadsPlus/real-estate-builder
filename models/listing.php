<?php 

class PL_Listing {
	
	public function get($args = array()) {
		$config = PL_Config::PL_API_LISTINGS('get');
		$request = array_merge(array("api_key" => PL_Option_Helper::api_key()), PL_Validate::request($args, $config['args']));
		$response = PL_HTTP::send_request($config['request']['url'], $request, $config['request']['type']);
		if (isset($response) && isset($response['listings'])) {
			foreach ($response['listings'] as $key => $listing) {
				$response['listings'][$key] = PL_Validate::attributes($listing, $config['returns']);
			}
		}	
		return $response;
	}

	public function create($args = array()) {
		$config = PL_Config::PL_API_LISTINGS('create');
		$request = array_merge(array("api_key" => PL_Option_Helper::api_key()), PL_Validate::request($args, $config['args']));
		$response = PL_HTTP::send_request($config['request']['url'], $request, $config['request']['type']);
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
		return $response;	
	}

	public function temp_image ($args = array(), $file_name, $file_mime_type, $file_tmpname) {
		$config = PL_Config::PL_API_LISTINGS('temp_image');
		$request = array_merge(array("api_key" => PL_Option_Helper::api_key()), PL_Validate::request($args, $config['args']));
		pls_dump($request);
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