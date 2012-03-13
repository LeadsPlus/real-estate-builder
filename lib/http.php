<?php 


Class PL_HTTP {
	
	static $timeout = 10;

	/*
	 * Sends HTTP request and parses genercic elements of API response
	 *
	 * @param string $url
	 * @param array $request
	 * @param string $method
	 * @return array
	 */
	function send_request($url, $request, $method = 'GET', $allow_cache = true) {
	    
	    $request_string = '';
	    foreach ($request as $key => $value) {
	        if (is_array($value)) {
	            if (empty($value) ) {
	                // $request_string .= (strlen($request_string) > 0 ? '&' : '') . urlencode($key) . '[]=';
	            }
	            foreach ($value as $k => $v) {
	            	if (is_array($v)) {
	            		foreach ($v as $i => $j) {
	            			$request_string .= (strlen($request_string) > 0 ? '&' : '') . urlencode($key) . '[' . $k . ']['.$i.']=' . urlencode($j);
	            		}
	            	} else {
		            	$request_string .= (strlen($request_string) > 0 ? '&' : '') . urlencode($key) . '[' . $k . ']=' . urlencode($v);	
	            	}
	            }
	        } else {
	        	$request_string .= (strlen($request_string) > 0 ? '&' : '') . 
	                $key . '=' . urlencode($value);
	        }
	    }

	    PL_Debug::add_msg('Endpoint Logged As: ' . $method . ' ' . $url . '?' . $request_string);

		switch ($method) {
			case 'POST':
			case 'PUT':
				// pls_dump($url);
				// pls_dump($request_string);
				$response = wp_remote_post($url, array('body' => $request_string, 'timeout' => self::$timeout, 'method' => $method));
				return json_decode($response['body'], TRUE);
				break;
			
			case 'DELETE':
				$ch = curl_init( $url );
	            curl_setopt($ch, CURLOPT_POSTFIELDS, $request_string);
	            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
	            curl_setopt($ch, CURLOPT_HEADER, 0); 
	            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
	            $response = curl_exec($ch);
	            curl_close($ch);

	            if (!$response) {
	                $response = array();
	                $response['headers']["status"] = 200;
	            }
	            else {
	                $response = array();
	                $response['headers']["status"] = 400;
	            }
	            return $response['body'];
				break;
			
			case 'GET':
			default:
				$signature = base64_encode(sha1($url . $request_string, true));
	        	$transient_id = 'pl_' . $signature;
	        	$transient = get_transient($transient_id);
	        	// pls_dump($url . '?' . $request_string);
	        	if ($allow_cache && $transient) {
					PL_Debug::add_msg('------- !!!USING CACHE!!! --------');    	    		
					return $transient;
	        	} else {
	            	$response = wp_remote_get($url . '?' . $request_string, array('timeout' => self::$timeout));
					PL_Debug::add_msg('------- NO CACHE FOUND --------');    	    		
	        		PL_Debug::add_msg($url . '?' . $request_string);    	


					if (is_array($response) && isset($response['headers']) && $response['headers']['status'] == 200 ) {
						if (!empty($response['body'])) {
							$body = json_decode($response['body'], TRUE);
							set_transient( $transient_id, $body , 3600 * 48 );
							return $body;
						} else {
							return false;
						}
					} else {
						PL_Debug::add_msg('------- ERROR VALIDATING REQUEST. --------');    	    		
						return false;
					}
	        	}
				break;
		}
	}


	/*
	 * Sends multipart HTTP request and parses genercic elements of API response.
	 * Used to upload file
	 *
	 * @param string $url
	 * @param array $request
	 * @param string $file_name
	 * @param string $file_mime_type
	 * @param string $file_tmpname
	 * @return array
	 */
	function send_request_multipart($url, $request, $file_name, $file_mime_type, $file_tmpname) {
		unset($request['action']);
		// pls_dump($url, $request, $file_name, $file_mime_type, $file_tmpname);

		$wp_upload_dir = wp_upload_dir();
		$file_location = trailingslashit($wp_upload_dir['path']) . $file_name;
		// pls_dump($file_location);
		move_uploaded_file($file_tmpname, $file_location);


		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_VERBOSE, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_URL, $url );
		//most importent curl assues @filed as file field
		$post_array = array(
			"file"=>"@".$file_location
		);
		$post_array = array_merge($post_array, $request);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_array); 
		$response = curl_exec($ch);
		if ($response === false) {
			// dumps error
			// var_dump(curl_error($ch));
		}

	    $o = json_decode($response, true);
	    return $o;

	    if (!isset($o['code'])){
	    	return false;	
	    } else if ($o['code'] == '201') {
	    	return false;
	    } else if ($o['code'] == '300') {
	    	return false;
	    } else {
	    	return false;
	    }

	    return $o; 

	}

	function clear_cache() {
	    global $wpdb;
	    $placester_options = $wpdb->get_results('SELECT option_name FROM ' . $wpdb->prefix . 'options ' ."WHERE option_name LIKE '_transient_pl_%'");
	    foreach ($placester_options as $option) {
	        delete_option( $option->option_name );
	    }
	}

	function num_items_cached () {
		global $wpdb;
	    $placester_options = $wpdb->get_results('SELECT option_name FROM ' . $wpdb->prefix . 'options ' ."WHERE option_name LIKE '_transient_pl_%'");		
	    if ($placester_options && is_array($placester_options)) {
	    	return count($placester_options);
	    }
	    return 0;
	}
}



