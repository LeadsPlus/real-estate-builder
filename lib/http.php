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
	function send_request($url, $request, $method = 'GET') {
	    
	    $request_string = '';
	    foreach ($request as $key => $value) {
	        if (is_array($value)) {
	            if ( empty($value) ) {
	                $request_string .= (strlen($request_string) > 0 ? '&' : '') . urlencode($key) . '[]=';
	            }
	            foreach ($value as $k => $v) {
	            	if (is_array($v)) {
	            		
	            		$request_string .= (strlen($request_string) > 0 ? '&' : '') . urlencode($key) . '[' . $k . ']=' . urlencode(implode($v, ','));
	            	} else {
		            	$request_string .= (strlen($request_string) > 0 ? '&' : '') . urlencode($key) . '[' . $k . ']=' . urlencode($v);	
	            	}
	            }
	        } else {
	        	$request_string .= (strlen($request_string) > 0 ? '&' : '') . 
	                $key . '=' . urlencode($value);
	        }
	    }

	    PL_Debug::add_msg('Endpoint Logged As: ' . $method . ' ' . $url);

		switch ($method) {
			case 'POST':
			case 'PUT':
				$response = wp_remote_post($url, array('body' => $request_string, 'timeout' => self::$timeout, 'method' => $method));
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
				break;
			
			case 'GET':
			default:
				// self::clear_cache();
				// pls_dump($url, $request_string);
				$signature = base64_encode(sha1($url . $request_string, true));
	        	$transient_id = 'pl_' . $signature;
	        	$transient = get_transient($transient_id);
	        	// var_dump($transient);
	        	if ($transient) {
					// pls_dump('here');
					PL_Debug::add_msg('------- !!!USING CACHE!!! --------');    	    		
					PL_Debug::add_msg($transient); 	
					// pls_dump($transient);
					return $transient;
	        	} else {
	            	$response = wp_remote_get($url . '?' . $request_string, array('timeout' => self::$timeout));
					PL_Debug::add_msg('------- NO CACHE FOUND --------');    	    		
	        		PL_Debug::add_msg($url . '?' . $request_string);    
					PL_Debug::add_msg($response['body']); 	

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

	    $binary_length = filesize($file_tmpname);
	    $binary_data = fread(fopen($file_tmpname, "r"), $binary_length);

	    $eol = "\r\n";
	    $data = '';
	     
	    $mime_boundary = md5(time());
	     
	    foreach ($request as $key => $value) {
	        $data .= '--' . $mime_boundary . $eol;
	        $data .= 'Content-Disposition: form-data; name="' . $key . '"' . $eol . $eol;
	        $data .= $value . $eol;
	    }

	    $data .= '--' . $mime_boundary . $eol;
	    $data .= 'Content-Disposition: form-data; name="file"; filename="' . $file_name . '"' . $eol;
	    $data .= 'Content-Type: ' . $file_mime_type . $eol;
	    $data .= 'Content-Length: ' . $binary_length . $eol;
	    $data .= 'Content-Transfer-Encoding: binary' . $eol . $eol;
	    $data .= $binary_data . $eol;
	    $data .= "--" . $mime_boundary . "--" . $eol . $eol; // Finish with two eols
	     
	    $params = array('http' => array(
	                      'method' => 'POST',
	                      'header' => 'Content-Type: multipart/form-data; boundary=' . $mime_boundary . $eol,
	                      'content' => $data
	                   ));
	    pls_dump($params);
		$ctx = stream_context_create($params);
		pls_dump($url);
		$handle = @fopen($url, 'r', false, $ctx);

	    if ( !$handle ) {
	    	return 'handle is false';
	    	return false;
	    }
	    	
	    stream_set_timeout( $handle, self::$timeout );

	    $response = stream_get_contents($handle);
	    fclose($handle);

	    $o = json_decode($response);

	    if (!isset($o->code)){
	    	return false;	
	    } else if ($o->code == '201') {
	    	return false;
	    } else if ($o->code == '300') {
	    	return false;
	    } else {
	    	return false;
	    }

	    return $o; 

	}

	function clear_cache() {
	    global $wpdb;
	    $placester_options = $wpdb->get_results(
	        'SELECT option_name FROM ' . $wpdb->prefix . 'options ' .
	        "WHERE option_name LIKE '_transient_pl_%'");

	    foreach ($placester_options as $option) {
	        delete_option( $option->option_name );
	    }
	}
}



