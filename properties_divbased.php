<?php

/**
 * Called by AJAX (div-based list) to get properties.
 */

require( dirname( __FILE__ ) . '/../../../wp-load.php');
include( 'properties_util.php' );
wp();
status_header( 200 );
//
// Get data
//
$fields = explode( ',', $_REQUEST['fields'] );
$filter_request = placester_filter_parameters_from_http();
placester_add_admin_filters( $filter_request );

if ( isset( $_REQUEST['sort_by'] ) )
    $filter_request['sort_by'] = $_REQUEST['sort_by'];
if ( isset( $_REQUEST['sort_type'] ) )
    $filter_request['sort_type'] = $_REQUEST['sort_type'];

$filter_request['address_mode'] = placester_get_property_address_mode();
// Request
try {
    $response = placester_property_list( $filter_request );
    $response_properties = apply_filters( 'pls_listing_list_ajax_data_request', $response->properties );

    $response_total = $response->total;
}
catch (Exception $e) {
    $response_properties = array();
    $response_total = 0;
}

//
// Process
//
init_templates();

$rows = array();
foreach ( $response_properties as $i ) {
    if ( isset( $_REQUEST['crop_description'] ) && ! empty( $i->description ) ) {
        /** Set up the paramenters for pl_html_substr. */
        $crop_description_params = explode( ' ', $_REQUEST['crop_description'] );
        array_unshift( $crop_description_params, $i->description );

        /** Trim the property description. */
        $i->description = call_user_func_array( 'pl_html_substr', $crop_description_params );
    }
    array_push( $rows, convert_row($i, $fields, false ) );
}

echo json_encode(
    array(
        'total' => $response_total,
        'properties' => $rows
    ) );
