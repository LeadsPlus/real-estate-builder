<?php 

class PL_Walkscore {

	function get_score ($args = array()) {
		extract(wp_parse_args($args, array('lat' => false, 'lng' => false, 'address' => false, 'ws_api_key' => false)));
		if ($lat && $lng && $address && $ws_api_key) {
			$response = wp_remote_get('http://api.walkscore.com/score?format=json&address=' . urlencode($address) .'&lat=' . $lat . '&lon=' . $lng . '&wsapikey=' . $ws_api_key, array('timeout' => 10));
			if (is_array($response) && isset($response['body']) ) {
				return json_decode($response['body'], true);
			} else {
				return array();
			}
		}
	}

}