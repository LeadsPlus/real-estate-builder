<?php

/**
 * Utilities for all property lists
 */

/**
 * Parses generic parameters of liststings_* functions
 * and returns essential data
 *
 * @param array $parameters
 * @return array
 */
function placester_listings_parse_parameters( $parameters ) {
    $is_list_details = true;
    if ( isset( $parameters['disable_default_click_action'] ) &&
            $parameters['disable_default_click_action'] )
        $is_list_details = false;

    $is_divbased = false;
    if ( isset( $parameters['table_type'] ) &&
            $parameters['table_type'] == 'html' )
        $is_divbased = true;


    // Fields
    $ui_fields = $parameters['attributes'];
    $webservice_fields = array();
    if ( $is_divbased )
        foreach ( $ui_fields as $key )
            $webservice_fields[] = $key;
    else
        foreach ( $ui_fields as $key => $value )
            $webservice_fields[] = $key;

    return array( $ui_fields, $webservice_fields, $is_list_details, $is_divbased );
}
