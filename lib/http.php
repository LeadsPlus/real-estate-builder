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
	function send_request($url, $request, $method = 'GET', $allow_cache = true, $allow_empty_values = false, $force_return = false, $use_ecoding = true) {
	    
	    $request_string = '';
	    foreach ($request as $key => $value) {
	        if (is_array($value)) {
	            if (empty($value) && $allow_empty_values ) {
	                $request_string .= (strlen($request_string) > 0 ? '&' : '') . urlencode($key) . '[]=';
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

	    if (!$use_ecoding) {
	    	$request_string = urldecode($request_string);
	    }

	    PL_Debug::add_msg('Endpoint Logged As: ' . $method . ' ' . $url . '?' . $request_string);

		switch ($method) {
			case 'POST':
			case 'PUT':
				$response = wp_remote_post($url, array('body' => $request_string, 'timeout' => self::$timeout, 'method' => $method));
				if (!is_array($response)) {
					$response = array();
				}
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
	            return json_decode($response, true);
				break;
			
			case 'GET':
			default:
				$cache = new PL_Cache('http');
				if ($allow_cache && $transient = $cache->get($url . $request_string)) {
					return $transient;
				} else {
	        		// pls_dump($url . '?' . $request_string);
	            	$response = wp_remote_get($url . '?' . $request_string, array('timeout' => self::$timeout));
					PL_Debug::add_msg('------- NO CACHE FOUND --------');    	    		
	        		PL_Debug::add_msg($url . '?' . $request_string);    	

	        		// pls_dump($response);
					if ( (is_array($response) && isset($response['headers']) && isset($response['headers']['status']) && $response['headers']['status'] == 200) || $force_return) {
						if (!empty($response['body'])) {
							$body = json_decode($response['body'], TRUE);
							$cache->save($body);
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
		curl_close($ch);
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
	    PL_Cache::clear();
	}
}



