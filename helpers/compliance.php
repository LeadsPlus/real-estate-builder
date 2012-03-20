<?php 

class PL_Compliance {

	function mls_message ($context) {
		$whoami = PL_Helper_User::whoami();
		if ($whoami['provider']['id'] && !empty($whoami['provider']['disclaimer_on']) ) {
			$provider = $whoami['provider'];
			$response = array();
			$response['disclaimer'] = $provider['disclaimer'];
			if ( ( $context == 'listings' || $context == 'search' ) && ( isset($provider['disclaimer_on']['listings']) || isset($provider['disclaimer_on']['search']) ) ) {
				$response['img'] = $provider['first_logo'];
			} elseif ($context == 'inline_search' && isset($provider['disclaimer_on']['inline_search']) ) {
				$response['img'] = $provider['second_logo'];	
			}
			return $response;
		} 
		return false;
	}
}