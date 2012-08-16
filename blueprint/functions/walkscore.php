<?php 

class PLS_Walkscore_Helper {

	function get_score ($lat, $lng, $address, $ws_api_key) {
		$args = array('lat' => $lat, 'lng' => $lng, 'address' => $address, 'ws_api_key' => $ws_api_key);
		return PLS_Plugin_API::get_walkscore($args);
	}
//end of class
}