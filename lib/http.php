<?php 


Class PL_HTTP {
	
	/*
	 * Sends HTTP request and parses genercic elements of API response
	 *
	 * @param string $url
	 * @param array $request
	 * @param string $method
	 * @return array
	 */
	function send_request($url, $request, $method = 'GET') {
	    
	    PL_Debug::add_msg('Endpoint Logged As: ' . $method . ' ' . $url);

	    $request_string = '';
	    
	    foreach ($request as $key => $value) {
	        if (is_array($value)) {
	            if ( empty($value) ) {
	                $request_string .= (strlen($request_string) > 0 ? '&' : '') . urlencode($key) . '[]=';
	            }

	            foreach ($value as $k => $v) {
	            	if (is_array($v)) {
	            		// pls_dump('value is array');
	            		// pls_dump($v);
	            		$request_string .= (strlen($request_string) > 0 ? '&' : '') . urlencode($key) . '[' . $k . ']=' . urlencode(implode($v, ','));
	            	} else {
		            	$request_string .= (strlen($request_string) > 0 ? '&' : '') . urlencode($key) . '[]=' . urlencode($v);	
	            	}
	            }
	        } else {
	        	$request_string .= (strlen($request_string) > 0 ? '&' : '') . 
	                $key . '=' . urlencode($value);
	        }
	    }

	    PL_Debug::add_msg(array('-------','Request String Logged As: ', $request_string, '-------'));    

	    // If the request is a get, attempt to retrieve the response
	    // from the transient cache. POSTs and PUTs are ommited.
	    $affects_cache = ($method != 'GET');

	    if ($affects_cache) {
	    	$response = false;
	    } else {
	        $signature = base64_encode(sha1($url . $request_string, true));
	        $transient_id = 'pl_' . $signature;
	        $response = get_transient($transient_id);
	    }

	    $response = !is_array( $response ) ? $response : false;
	    
	    if ($response === false) {

	        if ($method == 'POST' || $method == 'PUT') {
	            
	            $response = wp_remote_post($url, array(
	                    'body' => $request_string, 
	                    'timeout' => PLACESTER_TIMEOUT_SEC,
	                    'method' => $method
	                ));

	        } else if ($method == 'DELETE') {
	            
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

	        } else {
	        	pls_dump($url . '?' . $request_string);
	            $response = wp_remote_get($url . '?' . $request_string, array(
	                    'timeout' => PLACESTER_TIMEOUT_SEC
	                ));
	        }
	    }

	    PL_Debug::add_msg('-------');
	    PL_Debug::add_msg('Response Logged as:');
	    PL_Debug::add_msg($response);
	    PL_Debug::add_msg('-------');

	    /**
	     *      Defines the caching behavior.
	     *      
	     *      Only cache get requests, requests without errors, and valid responses.
	     */
	    if ($affects_cache && !isset($response->errors) && $response['headers']["status"] === 200) {
	        placester_clear_cache();
	        return 0;
	    } else {
	        if (is_array($response) && isset($response['response']['code'] ) ) {   
	            $response_code = $response['response']['code'];
	        } else {
	            // timeout?
	            $response_code = '408';
	        }

	        // throw http-level exception if no response
	        if (isset($response->errors))
	            throw new Exception(json_encode($response->errors));
	        if ( $response_code == '204' )
	            return null;

	        // decode response as an associative array, rather then 
	        // an object.
	        $o = json_decode($response['body'], TRUE);

	        // TODO When would the body wouldn't contain anything?
	        if ( !isset($o) && ( ($response_code != 200) && ($response_code != 201) ) ) {
	            throw new Exception($response['response']['message']);
	        }

	        if (!isset($o->code))
	        {}
	        else if ($o->code == '201')
	        {}
	        else if (isset ($o->validations))
	            throw new ValidationException($o->message, $o->validations);
	        else
	            return false;
	        // throw new Exception($o->message);

	        if ( isset($transient_id) ) {
	            set_transient( $transient_id, $response, 3600 * 48 );
	        }
	        return $o;
	        

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
	     
	    foreach ($request as $key => $value)
	    {
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
	    
	   $ctx = stream_context_create($params);

	    $handle = @fopen($url, 'r', false, $ctx);
	    if ( !$handle )
	    	return false;
	        // throw new Exception('http_request_failed');

	    stream_set_timeout( $handle, PLACESTER_TIMEOUT_SEC );

	    $response = stream_get_contents($handle);
	    fclose($handle);

	    $o = json_decode($response);

	    if (!isset($o->code))
	    {}
	    else if ($o->code == '201')
	    {}
	    else if ($o->code == '300')
	        return false;
	        //throw new ValidationException($o->message, $o->validations);
	    else
	        return false;
	        // throw new Exception($o->message);

	    return $o; 

		}
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



