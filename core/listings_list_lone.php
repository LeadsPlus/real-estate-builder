<?php

/**
 * Body of "placester_listings_list()" function
 * This file is processed only when function is really called
 */

// Check is api key specified
if ( placester_warn_on_api_key() )
    return;

$base_url = WP_PLUGIN_URL . '/placester';

list( $ui_fields, $webservice_fields, $is_list_details, $is_divbased ) =
    placester_listings_parse_parameters( $parameters );

if ( $is_divbased )
    include( 'listings_list_lone_divbased.php' );
else
    include( 'listings_list_lone_datatable.php' );
