<?php 
/**
 *  Functions related to leads workflow
 */

/**
 * Gets all information about a certain lead
 *
 * Makes a get request to the API and returns the lead information object 
 * returned by the API
 * 
 * @param WP_User $user 

 * @return mixed The lead object if the request is successfull, false 
 * otherwise
 */
function placester_get_lead_information( $user ) {

    $has_errors = false;
    $error_messages = '';
    try {
        $response = placester_lead_get( $user->placester_api_id );
    }
    catch (ValidationException $e) {
        $validation_data = $e->validation_data;
        placester_process_api_errors( $validation_data, $error_messages );
        $has_errors = true;
    }

    if ( !$has_errors ) 
        return $response;

    return false;
}

/**
 * Gets the collection of lead associated properties ids 
 * 
 * @param WP_User $lead
 *
 * @return mixed An array with associated properties ids if API request is 
 * successfull, false otherwise
 */
function placester_get_lead_favorites_api_ids( $lead ) {
    $lead_info = placester_get_lead_information( $lead );

    if ( $lead_info ) {
        $assoc_properties_array = array();

        if ( isset( $lead_info->properties ) ) {
            foreach( $lead_info->properties as $property ) {
                $assoc_properties_array[] = $property->id;
            }
        }

        return $assoc_properties_array;
    }
    return false; 
}

/**
 * Verifies if a property is set as favorite for the current user
 * 
 * @param int $property_id The property API id
 *
 * @return bool True if favorite, False otherwise
 */
function placester_is_favorite_property( $property_id ) {
    $current_user = wp_get_current_user();
    $lead_favorties = placester_get_lead_favorites_api_ids( $current_user );
    
    if ( is_array( $lead_favorties ) ) {
        if ( in_array( $property_id, $lead_favorties ) )
            return true;
    }

    return false; 
}

/* --------------------------------
 *  Ajax callbacks
 * -------------------------------- */

 /**
  *  Callback function for when the 
  *  "Add property to favorites" button has been clicked
  *
  *  JavaScript in "js/themes/placester.leads.js"
  *
  */
add_action( 'wp_ajax_add_favorite_property', 'placester_ajax_add_favorite_property' );
add_action( 'wp_ajax_nopriv_add_favorite_property', 'placester_ajax_add_favorite_property' );
function placester_ajax_add_favorite_property() {

    if ( !current_user_can( 'placester_lead' ) ) {

    } else { // If user is a lead
        $current_user = wp_get_current_user();
        $property_id = $_POST['property_id'];

        $favorites_ids = placester_get_lead_favorites_api_ids( $current_user );

        // Add the new property in the property array which will be 
        // passsed to the API
        if ( is_array( $favorites_ids ) ) {
            if ( !in_array( $property_id, $favorites_ids ) )
                array_push( $favorites_ids, $property_id );
        }

        $lead_object = new stdClass;
        $lead_object->property_ids = $favorites_ids;

        $error_messages = '';
        $has_errors = false;
        try {
            $response = placester_lead_set( $current_user->placester_api_id, $lead_object );
        }
        catch (ValidationException $e) {
            $validation_data = $e->validation_data;
            placester_process_api_errors( $validation_data, $error_messages );
            $has_errors = true;
        }

        if ( $has_errors )
            echo 'Errors';
        else 
            echo 'success';
    }
    die;
}

 /**
  *  Callback function for when the 
  *  "Remove property from favorites" button has been clicked
  *
  *  JavaScript in "js/themes/placester.leads.js" 
  *  and in "js/admin.leads.js"
  *
  */
add_action( 'wp_ajax_remove_favorite_property', 'placester_ajax_remove_favorite_property' );
function placester_ajax_remove_favorite_property() {
    // If user is a lead
    $current_user = wp_get_current_user();
    $property_id = $_POST['property_id'];

    $favorites_ids = placester_get_lead_favorites_api_ids( $current_user );

    // Add the new property in the property array which will be 
    // passsed to the API

    if ( is_array( $favorites_ids ) ) {
        $key = array_search( $property_id, $favorites_ids );
        if ( $key !== false ) {
            unset( $favorites_ids[$key] );
        }
    }

    $lead_object = new stdClass;
    $lead_object->property_ids = $favorites_ids;

    $error_messages = '';
    $has_errors = false;
    try {
        $response = placester_lead_set( $current_user->placester_api_id, $lead_object );
    }
    catch (ValidationException $e) {
        $validation_data = $e->validation_data;
        placester_process_api_errors( $validation_data, $error_messages );
        $has_errors = true;
    }

    if ( $has_errors )
        echo 'errors';
    else 
        echo count( $favorites_ids ); 

    die;
}

