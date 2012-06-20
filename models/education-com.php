<?php 

class PL_Education {

	function search ($args = array()) {
		extract(wp_parse_args($args, array('edu_key' => false)));
		if ($edu_key) {
			$response = wp_remote_get('http://api.education.com/service/service.php?f=schoolSearch&sn=sf&v=4&resf=json&key=' . $edu_key , array('timeout' => 10));
			if (is_array($response) && isset($response['body']) ) {
				return json_decode($response['body'], true);
			} else {
				return array();
			}
		}
	}

}