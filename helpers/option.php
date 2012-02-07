<?php 

class PL_Option_Helper {
	
	function api_key() {
	    $api_key = PL_Options::get('placester_api_key');
		if (strlen($api_key) <= 0) {
			throw new PlaceSterNoApiKeyException('API key not specified on settings page');
		}
	    return $api_key;	    
	}

	function post_slug() {
	    $url_slug = get_option('placester_url_slug');
	    if (strlen($url_slug) <= 0) {
	    	$url_slug = 'listing';
	    }
	    return $url_slug;
	}

//end of class
}