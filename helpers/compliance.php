<?php 

class PL_Compliance {

	function mls_message ($args) {
		extract(wp_parse_args($args, array('context' => false, 'agent_name' => false, 'office_name' => false)));
		$whoami = PL_Helper_User::whoami();
		// pls_dump($whoami);
		if (!empty($whoami['provider']['disclaimer_on']) || !empty($whoami['provider']['office_on']) || !empty($whoami['provider']['agent_on'])) {
			$provider = $whoami['provider'];
			$response = array();

			if ( $context == 'listings') {
				if (isset($provider['disclaimer_on']['listings']) && !empty($provider['disclaimer_on']['listings'])) {
					$response['disclaimer'] = $provider['disclaimer'];	
					$response['img'] = $provider['first_logo'];
				}
				if (isset($provider['agent_on']['listings']) && !empty($provider['agent_on']['listings']) && $agent_name) {
					$response['agent_name'] = $agent_name;
				}
				if (isset($provider['office_on']['listings']) && !empty($provider['office_on']['listings']) && $office_name) {
					$response['office_name'] = $office_name;
				}
			} elseif ( $context == 'search') {
				if (isset($provider['disclaimer_on']['search']) && !empty($provider['disclaimer_on']['search'])) {
					$response['disclaimer'] = $provider['disclaimer'];	
					$response['img'] = $provider['first_logo'];
				}
				if (isset($provider['agent_on']['search']) && !empty($provider['agent_on']['search']) && $agent_name) {
					$response['agent_name'] = $agent_name;
				}
				if (isset($provider['office_on']['search']) && !empty($provider['office_on']['search']) && $office_name) {
					$response['office_name'] = $office_name;
				}
			} elseif ( $context == 'inline_search') {

				if (isset($provider['disclaimer_on']['inline_search']) && !empty($provider['disclaimer_on']['inline_search'])) {
					$response['disclaimer'] = $provider['disclaimer'];	
					$response['img'] = $provider['second_logo'];
				}
				if (isset($provider['agent_on']['inline_search']) && !empty($provider['agent_on']['inline_search']) && $agent_name) {
					$response['agent_name'] = $agent_name;
				}
				if (isset($provider['office_on']['inline_search']) && !empty($provider['office_on']['inline_search']) && $office_name) {
					
					$response['office_name'] = $office_name;
				}
			}
			return $response;
		} 
		return false;
	}
}