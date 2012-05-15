<?php 

class PL_Option_Helper {
	
	function api_key() {
	    $api_key = PL_Options::get('placester_api_key');
		if (strlen($api_key) <= 0) {
			return false;
		}
	    return $api_key;	    
	}

	function set_api_key($new_api_key) {
		if (empty($new_api_key) ) {
			return array('result' => false,'message' => 'Google Places keys must not be empty');
		}
		if (get_option('placester_api_key') == $new_api_key) {
			return array('result' => false,'message' => 'You\'re already using that Placester API Key.');
		}
		$response = PL_User::whoami(array('api_key' => $new_api_key));
		if ($response && isset($response['user'])) {
			$option_result = PL_Options::set('placester_api_key', $new_api_key);
			if ($option_result) {
				// Nuke the cache if they change their API Key
				PL_HTTP::clear_cache();
				return array('result' => true,'message' => 'You\'ve successfully changed your Placester API Key. This page will reload in momentarily.');
			} else {
				return array('result' => false,'message' => 'There was an error. Are you sure that\'s a valid Placester API key?');
			}
		} 
		return array('result' => false,'message' => 'That \'s not a valid Placester API Key.');
	}

	function set_google_places_key ($new_places_key) {
		if (get_option('placester_places_api_key') == $new_places_key) {
			return array('result' => false,'message' => 'You\'re already using that Places API Key.');
		} else {
			$response = update_option('placester_places_api_key', $new_places_key);
			if ($response) {
				return array('result' => true, 'message' => 'You\'ve successfully updated your Google Places API Key');
			} else {
				return array('result' => false, 'message' => 'There was an error. Please try again.');
			}
		}
	}

	function get_google_places_key () {
		$places_api_key = get_option('placester_places_api_key', '');
		return $places_api_key;
	}

	function post_slug() {
	    $url_slug = get_option('placester_url_slug');
	    if (strlen($url_slug) <= 0) {
	    	$url_slug = 'listing';
	    }
	    return $url_slug;
	}

	public function set_polygons ($add = false, $remove_id = false) {
		$polygons = PL_Options::get('pls_polygons', array() );
		if ($add) {
			$polygons[] = $add;
		}
		if ($remove_id !== false) {
			if (isset($polygons[$remove_id])) {
				unset($polygons[$remove_id]);

			}
		}
		$response = PL_Options::set('pls_polygons', $polygons);
		return $response;
	}

	public function get_polygons () {
		return PL_Options::get('pls_polygons', array());	
	}

	public function set_global_filters ($args) {
		extract(wp_parse_args($args, array('filters' => array())));
		return PL_Options::set('pls_global_search_filters', $filters);		
	}

	public function get_global_filters () {
		return PL_Options::get('pls_global_search_filters');		
	}


	public function set_log_errors ($report_errors = 1) {
		$response = PL_Options::set('pls_log_errors', $report_errors);
		return $response;
	}

	public function get_log_errors () {
		$response = PL_Options::get('pls_log_errors');	
		if ($response == NULL) {
			return true;
		}
		return $response;
	}

	public function set_block_address ($block_address = 0) {
		$response = PL_Options::set('pls_block_address', $block_address);
		return $response;
	}

	public function get_block_address () {
		$response = PL_Options::get('pls_block_address');	
		if ($response == NULL) {
			return true;
		}
		return $response;
	}

	public function set_default_country ($default_country) {
		return PL_Options::set('pls_default_country', $default_country);
	}

	public function get_default_country () {
		$response = PL_Options::get('pls_default_country');	
		return $response;
	}

	public function set_translations ($dictionary) {
		return PL_Options::set('pls_amenity_dictionary', $dictionary);
	}

	public function get_translations () {
		$response = PL_Options::get('pls_amenity_dictionary');	
		return $response;
	}

//end of class
}