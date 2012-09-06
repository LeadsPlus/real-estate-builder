<?php 

class PL_User {
	
	public function whoami($args = array()) {
		static $memo = array();
		$arg_str = http_build_query($args);
		if(isset($memo[$arg_str])) {
			return $memo[$arg_str];
		}

		$request = array_merge(array("api_key" => PL_Option_Helper::api_key()), PL_Validate::request($args, PL_Config::PL_API_USERS('whoami', 'args')));
		$response = PL_HTTP::send_request(trailingslashit(PL_Config::PL_API_USERS('whoami', 'request', 'url')), $request, PL_Config::PL_API_USERS('whoami', 'request', 'type'), true);
		$response = PL_Validate::attributes($response, PL_Config::PL_API_USERS('whoami', 'returns'));
		$memo[$arg_str] = $response;
		return $response;
	}

	public function create($args = array()) {
		$request = PL_Validate::request($args, PL_Config::PL_API_USERS('setup', 'args') );
		$request['source'] = 'wordpress';
		$response = PL_HTTP::send_request(PL_Config::PL_API_USERS('setup', 'request', 'url'), $request, PL_Config::PL_API_USERS('setup', 'request', 'type'));
		return $response;
	}

	public function update($args = array()) {
		$request = array_merge(array("api_key" => PL_Option_Helper::api_key()), PL_Validate::request($args, PL_Config::PL_API_USERS('update', 'args')));
		$response = PL_HTTP::send_request(PL_Config::PL_API_USERS('update', 'request', 'url'), $request, PL_Config::PL_API_USERS('update', 'request', 'type'), false);
		$response = PL_Validate::attributes($response, PL_Config::PL_API_USERS('update', 'returns'));
		return $response;
	}

	public function subscriptions($args = array()) {
		$request = array_merge(array("api_key" => PL_Option_Helper::api_key()), PL_Validate::request($args, PL_Config::PL_API_USERS('subscriptions', 'args')));
		$response = PL_HTTP::send_request(trailingslashit(PL_Config::PL_API_USERS('subscriptions', 'request', 'url')), $request, PL_Config::PL_API_USERS('subscriptions', 'request', 'type'), false);
		$response = PL_Validate::attributes($response, PL_Config::PL_API_USERS('subscriptions', 'returns'));
		return $response;
	}

	public function start_subscription_trial($args = array()) {
		$request = array_merge(array("api_key" => PL_Option_Helper::api_key()), PL_Validate::request($args, PL_Config::PL_API_USERS('start_subscriptions', 'args')));
		$response = PL_HTTP::send_request(trailingslashit(PL_Config::PL_API_USERS('subscriptions', 'request', 'url')), $request, PL_Config::PL_API_USERS('start_subscriptions', 'request', 'type'), false);
		$response = PL_Validate::attributes($response, PL_Config::PL_API_USERS('start_subscriptions', 'returns'));
		return $response;
	}
	
}