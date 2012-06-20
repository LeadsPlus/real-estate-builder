<?php 

class PL_Education {

	function search ($args = array()) {
		extract(wp_parse_args($args, array('edu_key' => false)));
		if ($edu_key) {
			$response = wp_remote_get('http://api.education.com/service/service.php?f=schoolSearch&sn=sf&v=4&resf=json&maxResult='.$maxResult.'&key=' . $edu_key . $search_params , array('timeout' => 10));
			if (is_array($response) && isset($response['body']) ) {
				$response = json_decode($response['body'], true);
				if (isset($response['faultCode'])) {
					return array();
				} else {
					return $response;
				}
			} else {
				return array();
			}
		}
	}

}