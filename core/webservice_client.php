<?php

/**
 * Web service interface with placester remote properties storage
 */

/**
 * ValidationException 
 * 
 * @internal
 */
class ValidationException extends Exception
{
    /* Object containing error messages for each not valid field, i.e.
     * $validation_data->zip will contain error message for 'zip' field
     */
    public $validation_data;



    /*
     * Constructor
     *
     * @param string $message
     * @param object $validation_data
     */
    function __construct($message, $validation_data)
    {
        parent::__construct($message);
        $this->validation_data = $validation_data;
    }
}



define( 'PLACESTER_TIMEOUT_SEC', 10 );


function placester_process_array_into_curl_string( $array, $name ) {
    $return = '';
    foreach( $array as $key => $value ) {
        $return .= "&{$name}[{$key}]={$value}";
    }
    
    return substr( $return, 1 );
}

/*
 * Returns fields acceptable as filter parameters
 *
 * @return array
 */
function placester_filter_parameters_from_http()
{
    $acceptable =
        array
        (
            'agency_id',
            'available_on',
            'bathrooms',
            'bedrooms',
            array('box', 'min_latitude'),
            array('box' , 'max_latitude'),
            array('box' , 'min_longitude'),
            array('box' , 'max_longitude'),
            'half_baths',
            'limit',
            'listing_types',
            array('location' , 'city'),
            array('location' , 'state'),
            array('location' , 'zip'),
            'max_bathrooms',
            'max_bedrooms',
            'max_half_baths',
            'max_price',
            'min_bathrooms',
            'min_bedrooms',
            'min_half_baths',
            'min_price',
            'offset',
            'property_type',
            'purchase_types',
            'sort_by',
            'sort_type',
            'zoning_types',

            'is_featured',
            'is_new'
        );

    $filter_request = array();
    foreach ($acceptable as $key)
    {
        if (is_array($key))
        {
            $request = $_REQUEST;
            $output_key = '';
            for ($n = 0; $n < count($key); $n++)
            {
                $k = $key[$n];
                if (!isset($request[$k]))
                    break;

                $output_key = $output_key . ($n <= 0 ? $k : '[' . $k . ']');
                if ($n < count($key) - 1)
                    $request = $request[$k];
                else
                    $filter_request[$output_key] = $request[$k];
            }
        }
        elseif (isset($_REQUEST[$key]))
            $filter_request[$key] = $_REQUEST[$key];
    }

    return $filter_request;
}

/**
 * Creates a lead
 * 
 * @param object $lead_object The lead object with all the lead information
 *
 * @return The lead API id
 */
function placester_lead_add( $lead_object ) {
            /** Define the default argument array. */
    
    $request =
        array (
            'api_key' => placester_get_api_key(),
            'lead_type' => $lead_object->lead_type,
            'contact[name]' =>$lead_object->name ,
            'contact[email]' => $lead_object->email,
            'contact[phone]' => $lead_object->phone 
        );

    $url = 'http://placester.com/api/v1.0/leads.json';
        
    // Do request
    try {
        $return = placester_send_request($url, $request, 'POST');    
    } catch (Exception $e) {
        return 'error';
    }
    

    return $return; 
}

/*
 * Gets all information about a lead
 * 
 * @param string $id 
 * @param string $method
 */
function placester_lead_get( $id )
{
    if ( isset( $id ) ) {
        $request = 
            array (
                'api_key' => placester_get_api_key(),
            );
        // Don't pass description if blank, API will create it automatically
        $url = 'http://api.placester.com/v1.0/leads/' . $id . '.json';
        // Do request
        return placester_send_request( $url, $request, 'GET' );
    }
}

/*
 * Modifies a lead
 * 
 * @param string $id 
 * @param stdClass $lead_object The lead object with all the information
 * @param string $method
 */
function placester_lead_set($id, $lead_object, $method = 'PUT')
{
    if ( isset( $id ) ) {
        $request = 
            array (
                'api_key' => placester_get_api_key(),
                'notes' => $lead_object->notes,
                'status' => $lead_object->status,
                'est_value' => $lead_object->est_value,
                'contact[name]' => trim($lead_object->contact->name),
                'contact[email]' => $lead_object->contact->email,
                'contact[phone]' => $lead_object->contact->phone,
            );

        // Don't pass description if blank, API will create it automatically
        $url = 'http://api.placester.com/v1.0/leads/' . $id . '.json';

        placester_cut_empty_fields( $request );

        // Set after cutting because if empty array is passed all associated 
        // properties are deleted
        $request['property_ids'] = $lead_object->property_ids;
// echo "\n <pre>XSTART \n";
// print_r($request );
// echo "\n XEND </pre>";

        return placester_send_request( $url, $request, $method );
    }
}

/*
 * Deletes a lead
 * 
 * @param string $id 
 */
function placester_lead_delete( $id ) {
    if ( isset( $id ) ) {
        $request = 
            array (
                'api_key' => placester_get_api_key(),
            );
        // Don't pass description if blank, API will create it automatically
        $url = 'http://api.placester.com/v1.0/leads/' . $id . '.json';
        // Do request

        return placester_send_request( $url, $request, 'DELETE' );
    }
}

/*
 * Associates leads
 * 
 * @param string $id 
 * @param array $lead_ids_array The lead object with all the information
 * @param string $method
 */
function placester_assoc_leads( $lead_ids_array, $method = 'POST') {
    if ( is_array( $lead_ids_array ) ) {
        $request = 
            array (
                'api_key' => placester_get_api_key(),
                'lead_ids' => $lead_ids_array
            );

        $url = 'http://api.placester.com/v1.0/leads/assoc';

        // Do request
        $r = placester_send_request($url, $request, $method);

        return $r;
    }
}

/*
 * Unassociates leads
 * 
 * @param string $id 
 * @param array $lead_ids_array The lead object with all the information
 */
function placester_unassoc_leads( $lead_ids_array ) {
    if ( is_array( $lead_ids_array ) ) {
        $request = 
            array (
                'api_key' => placester_get_api_key(),
                'lead_ids' => $lead_ids_array,
            );

        $url = 'http://api.placester.com/v1.0/leads/assoc/';

        // Make the request
        return placester_send_request($url, $request, 'DELETE');
    }
}

/*
 * Returns list of properties
 *
 * @param array $parameters - http parameters for api
 * @return array
 */
function placester_property_list($parameters = array()) {
    // Prepare parameters
    $url = 'http://api.placester.com/v1.0/properties.json';
        
    $request = $parameters;
    $request['api_key'] = placester_get_api_key();

    // Override is_featured & is_new
    if ( isset( $request['is_featured'] ) || isset( $request['featured'] ) ) {
        unset( $request['is_featured'], $request['featured'] );
        $request['property_ids'] = placester_properties_featured_ids();
    }

    if ( isset( $request['is_new'] ) || isset( $request['new'] ) ) {
        unset( $request['is_new'], $request['new'] );
        $request['property_ids'] = placester_properties_new_ids();
    }

    $request['address_mode'] = placester_get_property_address_mode();

    placester_cut_empty_fields($request);

    $return = placester_send_request( $url, $request, "GET" );

    // Do request
    return $return;
}

/**
 * Returns all property listings
 *
 * @return object 
 */
function placester_get_properties()
{
    $request = array(
        'api_key' => placester_get_api_key(),
        'address_mode' => placester_get_property_address_mode(),
    );

    $url = 'http://api.placester.com/v1.0/properties.json';

    return placester_send_request($url, $request, 'GET');
}


/*
 * Returns property
 *
 * @param array $parameters - http parameters for api
 * @return object
 */
function placester_property_get($id)
{
    // Prepare parameters
    $url = 'http://api.placester.com/v1.0/properties/' . $id . '.json';
    $request = array(
        'api_key' => placester_get_api_key(),
        'address_mode' => placester_get_property_address_mode(),
    );

    // Do request
    return placester_send_request($url, $request);
}



function placester_apikey_info($api_key)
{
    
    $url = 'http://api.placester.com/v1.0/organizations/whoami.json';
    $request = array('api_key' => $api_key);

    return placester_send_request($url, $request);
}



/*
 * Creates property
 *
 * @param array $p - http parameters for api
 * @return array
 */
function placester_property_add($p)
{
    $request =
        array
        (
            'api_key' => placester_get_api_key(),
            'property_type' => $p->property_type,
            'listing_types' => $p->listing_types,
            'zoning_types' => $p->zoning_types,
            'purchase_types' => $p->purchase_types,
            'bedrooms' => $p->bedrooms,
            'bathrooms' => $p->bathrooms,
            'half_baths' => $p->half_baths,
            'price' => $p->price,
            'available_on' => $p->available_on,
            'url' => placester_get_property_value($p, 'url'),
            'description' => $p->description,
            'location[address]' => $p->location->address,
            'location[neighborhood]' => $p->location->neighborhood,
            'location[city]' => $p->location->city,
            'location[state]' => $p->location->state,
            'location[country]' => $p->location->country,
            'location[zip]' => $p->location->zip,
            'location[unit]' => $p->location->unit,
            'location[coords][latitude]' => $p->location->coords->latitude,
            'location[coords][longitude]' => $p->location->coords->longitude
        );

    $url = 'http://api.placester.com/v1.0/properties.json';
        
    // Do request
    return placester_send_request($url, $request, 'POST');
}



/*
 * Modifies property
 *
 * @param string $id
 * @param array $p - http parameters for api
 * @return array
 */
function placester_property_set($id, $p, $method='PUT')
{
    if ( isset($p->location->coords) ) {
        $request = 
            array
            (
                'api_key' => placester_get_api_key(),
                'property_type' => $p->property_type,
                'listing_types' => $p->listing_types,
                'zoning_types' => $p->zoning_types,
                'purchase_types' => $p->purchase_types,
                'bedrooms' => $p->bedrooms,
                'bathrooms' => $p->bathrooms,
                'half_baths' => $p->half_baths,
                'price' => $p->price,
                'available_on' => $p->available_on,
                'url' => placester_get_property_value($p, 'url'),
                'description' => $p->description,
                'location[address]' => $p->location->address,
                'location[neighborhood]' => $p->location->neighborhood,
                'location[city]' => $p->location->city,
                'location[state]' => $p->location->state,
                'location[country]' => $p->location->country,
                'location[zip]' => $p->location->zip,
                'location[unit]' => $p->location->unit,
                'location[coords][latitude]' => $p->location->coords->latitude,
                'location[coords][longitude]' => $p->location->coords->longitude
            );
        // Don't pass description if blank, API will create it automatically
        if ( !$request['description'] ) unset( $request['description'] );
        $url = 'http://api.placester.com/v1.0/properties/' . $id . '.xml';
        // Do request
        return placester_send_request($url, $request, $method);
    }
}



/*
 * Bulk change of property urls
 *
 * @param string $url_format
 * @param array $p - http parameters for api
 * @return array
 */
function placester_property_seturl_bulk($url_format, $filter)
{
    $request = $filter;
    $request['api_key'] = placester_get_api_key();
    $request['url_format'] = $url_format;

    $url = 'http://api.placester.com/v1.0/properties/urls.json';

    // Do request
    return placester_send_request($url, $request, 'PUT');
}



/*
 * Adds image to property
 *
 * @param string $property_id
 * @param string $file_name
 * @param string $file_mime_type
 * @param string $file_tmpname
 * @return array
 */
function placester_property_image_add($property_id, $file_name, $file_mime_type, $file_tmpname, $api_key = '')
{
    $url = 'http://api.placester.com/v1.0/properties/media/image/' .  $property_id . '.json';
    if ( $api_key )
        $request = array('api_key' => $api_key);
    else 
        $request = array('api_key' => placester_get_api_key());

    $ret = placester_send_request_multipart($url, $request, $file_name, $file_mime_type, $file_tmpname);

    return $ret;
}



/*
 * Deletes image from property
 *
 * @param string $property_id
 * @param string $image_url
 * @return array
 */
function placester_property_image_delete($property_id, $image_id)
{
    $request = 
        array
        (
            'api_key' => placester_get_api_key(),
            'image_id' => $image_id
        );

    $url = 'http://placester.com/api/v1.0/properties/media/image/' . $property_id . '.xml';

    // Do request
    return placester_send_request($url, $request, 'DELETE');
}

/*
 * Deletes property
 *
 * @param string $property_id
 * @return array
 */
function placester_property_delete($property_id)
{
    $request = 
        array
        (
            'api_key' => placester_get_api_key(),
        );

    $url = 'http://placester.com/api/v1.0/properties/' . $property_id . '.xml';

    $response = placester_send_request($url, $request, 'DELETE');
    
    // Delete any property posts
    if ( empty($response) ) {
        $args = array (
            'post_type' => 'property',
        );
        $posts = get_posts($args);
        foreach( $posts as $post ) {
            if ( $post->post_name == $property_id ) {
                $deleted = wp_delete_post($post->ID);
                break;
            }
        }
    } 

    return $response;
}

/*
 * Adds new user
 *
 * @param object $user
 * @return array
 */
function placester_user_add($user)
{
    $request = array(
            'first_name' => $user->first_name,
            'source' => 'wordpress',
            'last_name' => $user->last_name,
            'email' => $user->email,
            'website' => $user->website,
            'phone' => $user->phone,
            'location[address]' => $user->location->address,
            'location[city]' => $user->location->city,
            'location[zip]' => $user->location->zip,
            'location[state]' => $user->location->state,
            'location[unit]' => $user->location->unit
        );
    placester_cut_empty_fields($request);

    $url = 'https://api.placester.com/v1.0/users/setup.json';
        
    // Do request
    return placester_send_request($url, $request, 'POST');
}



/*
 * Returns user by id
 *
 * @param string $company_id
 * @param string $user_id
 * @return object
 */
function placester_user_get($company_id, $user_id)
{
    $url = 'http://api.placester.com/v1.0/users';
    $request = 
        array
        (
            'api_key' => placester_get_api_key(),
            'agency_id' => $company_id,
            'user_id' => $user_id
        );

    return placester_send_request($url, $request);
}



/*
 * Modifies user
 *
 * @param object $user
 * @return array
 */
function placester_user_set($user)
{
    
    $request = array(
            'api_key' => placester_get_api_key(),
            'source' => 'wordpress',
            'user_id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'website' => $user->website,
            'phone' => $user->phone,
            'location[address]' => (isset($user->location) ? $user->location->address : ''),
            'location[city]' => (isset($user->location) ? $user->location->city : ''),
            'location[zip]' => (isset($user->location) ? $user->location->zip : ''),
            'location[state]' => (isset($user->location) ? $user->location->state : ''),
            'location[unit]' => (isset($user->location) ? $user->location->unit : '')
        );
    placester_cut_empty_fields($request);

    $url = 'https://api.placester.com/v1.0/users.json';

    // Do request
    return placester_send_request($url, $request, 'PUT');
}



/*
 * Returns current company
 *
 * @return array
 */
function placester_company_get()
{
    $url = 'http://api.placester.com/v1.0/organizations.json';
    $request = array('api_key' => placester_get_api_key());

    return placester_send_request($url, $request);
}



/*
 * Modifies company
 *
 * @param object $company
 * @return array
 */
function placester_company_set($id, $company)
{
    $request =
        array
        (
            'api_key' => placester_get_api_key(),
            'name' => $company->name,
            'phone' => $company->phone,
            'settings[use_polygons]' => false,
            'settings[enable_campaigns]' => false,
            'settings[require_approval]' => false,
            'location[address]' => $company->location->address,
            'location[city]' => $company->location->city,
            'location[country]' => $company->location->country,
            'location[state]' => $company->location->state,
            'location[unit]' => $company->location->unit
        );

    if ( $request['location[country]'] != 'BZ' )
        $request['location[zip]'] = $company->location->zip;

    placester_cut_empty_fields($request);

    $url = 'https://api.placester.com/v1.0/organizations/' . $id . '.json';

    // Do request
    return placester_send_request($url, $request, 'PUT');
}


/**
 *      Checks Theme Compatibility
 */
function placester_theme_check($theme)
{
    $request =
        array
        (
            'hash' => $theme->hash,
            'domain' => $theme->domain,
            'theme_name' => $theme->name,
        );
    placester_cut_empty_fields($request);

    $url = 'http://api.placester.com/v1.0/theme/license.json';
        
    try {
        placester_send_request($url, $request, 'POST');        
    } catch (Exception $e) {
        
    }

}

/*
 * Returns list of locations
 *
 * @return array
 */
function placester_location_list()
{
    $request = array('api_key' => placester_get_api_key());

    $url = 'http://api.placester.com/v1.0/properties/locations.json';

    // Do request
    return placester_send_request($url, $request, 'GET');
}



/*
 * Utils
 */

/*
 * Sends HTTP request and parses genercic elements of API response
 *
 * @param string $url
 * @param array $request
 * @param string $method
 * @return array
 */
function placester_send_request($url, $request, $method = 'GET')
{
    
    $request_string = '';
    
    foreach ($request as $key => $value)
    {
        if (is_array($value))
        {
            if ( empty($value) ) {
                $request_string .= (strlen($request_string) > 0 ? '&' : '') . urlencode($key) . '[]=';
            }
            foreach ($value as $v)
                $request_string .= (strlen($request_string) > 0 ? '&' : '') . 
                    urlencode($key) . '[]=' . urlencode($v);
        }
        else
            $request_string .= (strlen($request_string) > 0 ? '&' : '') . 
                $key . '=' . urlencode($value);
    }

    // If the request is a get, attempt to retrieve the response
    // from the transient cache. POSTs and PUTs are ommited.
    $affects_cache = ($method != 'GET');

    if ($affects_cache)
        $response = false;
    else
    {
        $signature = base64_encode(sha1($url . $request_string, true));
        $transient_id = 'pl_' . $signature;
        
        $response = get_transient($transient_id);
    }

    $response = !is_array( $response ) ? $response : false;
    if ($response === false)
    {
        if ($method == 'POST' || $method == 'PUT')
        {
            PL_Dump::add_msg($url);
            $response = wp_remote_post($url, 
                array (
                    'body' => $request_string, 
                    'timeout' => PLACESTER_TIMEOUT_SEC,
                    'method' => $method
                ));
            PL_Dump::add_msg($reponse);
        }
        else if ($method == 'DELETE')
        {
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

        }
        else {
            $response = wp_remote_get($url . '?' . $request_string, 
                array
                (
                    'timeout' => PLACESTER_TIMEOUT_SEC
                ));
        }
        
        
        /**
         *      Defines the caching behavior.
         *      
         *      Only cache get requests, requests without errors, and valid responses.
         */
    }

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

        $o = json_decode($response['body']);

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
function placester_send_request_multipart($url, $request, $file_name, $file_mime_type, $file_tmpname)
{
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


function placester_clear_cache()
{
    global $wpdb;
    $placester_options = $wpdb->get_results(
        'SELECT option_name FROM ' . $wpdb->prefix . 'options ' .
        "WHERE option_name LIKE '_transient_pl_%'");

    foreach ($placester_options as $option) {
        delete_option( $option->option_name );
    }

}
