<?php 

class PL_Google_Places {

	function get ($args = array()) {
		$request = PL_Validate::request(array_merge(array('key' => 'AIzaSyBFHauJyYcBXsRN4og09pkdlvFdGILwwvY'),$args), PL_Config::PL_TP_GOOGLE_PLACES('get', 'args'));
		$request = array_merge($request, array('sensor' => 'false'));
		$response = PL_HTTP::send_request(PL_Config::PL_TP_GOOGLE_PLACES('get', 'request', 'url'), $request, PL_Config::PL_TP_GOOGLE_PLACES('get', 'request', 'type'), true, false, true, false);
		if (is_array($response) && isset($response['status']) && $response['status'] == 'OK' ) {
			return $response['results'];
		} else {
			return array();
		}
	}
}