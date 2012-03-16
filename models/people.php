<?php 

class PL_People {

	public function create($args = array()) {
		$request = array_merge(array("api_key" => PL_Option_Helper::api_key()), PL_Validate::request($args, PL_Config::PL_API_PEOPLE('create', 'args') ) );
		$response = PL_HTTP::send_request(PL_Config::PL_API_PEOPLE('create', 'request', 'url'), $request, PL_Config::PL_API_PEOPLE('create', 'request', 'type'));
		return $response;
	}

	public function update($args = array()) {
		$request = array_merge(array("api_key" => PL_Option_Helper::api_key()), PL_Validate::request($args, PL_Config::PL_API_PEOPLE('create', 'args') ) );
		if (isset($args['fav_listing_ids']) && empty($args['fav_listing_ids'])) {
			$request['fav_listing_ids'] = array();
		}
		$update_url = trailingslashit(PL_Config::PL_API_PEOPLE('create', 'request', 'url')) . $request['id'];
		unset($request['id']);

		$response = PL_HTTP::send_request($update_url, $request, 'PUT', false, true);
		return $response;
	}

	public function details($args = array()) {
		$config = PL_Config::PL_API_PEOPLE('details');
		$request = array_merge(array("api_key" => PL_Option_Helper::api_key()), PL_Validate::request($args, $config['args']));
		$details_url = trailingslashit($config['request']['url']) . $request['id'];
		$response = PL_HTTP::send_request($details_url, $request, $config['request']['type'], $config['request']['cache']);
		return $response;	
	}

}