<?php 

class PL_User {
	
	public function whoami($args = array()) {
		$request = array_merge(array("api_key" => PL_Option_Helper::api_key()), PL_Validate::request($args, PL_Config::PL_API_USERS('whoami', 'args')));
		$response = PL_HTTP::send_request(trailingslashit(PL_Config::PL_API_USERS('whoami', 'request', 'url')), $request, PL_Config::PL_API_USERS('whoami', 'request', 'type'));
		$response = PL_Validate::attributes($response, PL_Config::PL_API_USERS('whoami', 'returns'));
		return $response;
	}
}