<?php 

class PL_Google_Places_Helper {

	function search ($request) {
		$response = PL_Google_Places::get($request);
		return $response;
	}

}