<?php 

class PL_Education_Helper {

	function get_schools ($args = array()) {
		$response = PL_Education::search($args);
		if ($response) {
			return $response;
		} else {
			return array();
		}
	}
//end class
}