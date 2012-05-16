<?php 

class PL_WordPress {

	function set ($args = array()) {
		$site_id = get_option('pls_site_id');
		if (!$site_id) {
			update_option('pls_site_id', sha1(site_url()));
		}
		$request = array_merge(array("api_key" => PL_Option_Helper::api_key()), PL_Validate::request($args, PL_Config::PL_API_WORDPRESS('set', 'args')));
		$request_url = trailingslashit(PL_Config::PL_API_WORDPRESS('set', 'request', 'url')) . $site_id;
		$response = PL_HTTP::send_request($request_url, $request, PL_Config::PL_API_WORDPRESS('set', 'request', 'type'));
		$response = PL_Validate::attributes($response, PL_Config::PL_API_WORDPRESS('set','returns'));
		if (is_array($response)) {
			return $response;
		}
		return false;
	}

	function delete () {
		$site_id = get_option('pls_site_id');
		if (!$site_id) {
			update_option('pls_site_id', sha1(site_url()));
		}
		$request = array_merge(array("api_key" => PL_Option_Helper::api_key()), PL_Validate::request($args, PL_Config::PL_API_WORDPRESS('delete', 'args')));
		$request_url = trailingslashit(PL_Config::PL_API_WORDPRESS('delete', 'request', 'url')) . $site_id;
		$response = PL_HTTP::send_request($request_url, $request, PL_Config::PL_API_WORDPRESS('delete', 'request', 'type'));
		$response = PL_Validate::attributes($response, PL_Config::PL_API_WORDPRESS('delete','returns'));
		if (is_array($response) && isset($response['id']) && $response['id'] == $site_id) {
			return true;
		}
		return false;
	}

}