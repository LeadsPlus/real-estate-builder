<?php

/**
 * Called by AJAX (datatable list) to get properties
 */

require_once( dirname( __FILE__ ) . '/../../../wp-load.php' );
include( 'properties_util.php' );
wp();
status_header( 200 );

//
// Get data
//
$fields = explode( ',', $_REQUEST['fields'] );
$filter_request = placester_filter_parameters_from_http();
if ( ! isset( $_REQUEST['no_admin_filter'] ) )
    placester_add_admin_filters( $filter_request );
else {
    if ( ! current_user_can( 'edit_themes' ) )
        die( 'permission denied' );
}
$filter_request['address_mode'] = placester_get_property_address_mode();

// Paging parameters
if ( isset( $_GET['iDisplayStart'] ) )
    $filter_request['offset'] = $_GET['iDisplayStart'];
if ( isset( $_GET['iDisplayLength'] ) )
    $filter_request['limit'] = $_GET['iDisplayLength'];

// Ordering parameters
if ( isset( $_GET['iSortCol_0'] ) ) {
    $filter_request['sort_by'] = $fields[intval( $_GET['iSortCol_0'] )];
    $filter_request['sort_type'] = strtolower( $_GET['sSortDir_0'] );
}

// Filtering
/*
if ( $_GET['sSearch'] != "" )
{ 
   // todo once implemented by API 
}*/

// Request
try {
    $response = placester_property_list($filter_request);
    // print_r($response);
    // print_r($filter_request);
    $response_properties = $response->properties;
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
foreach ( $response_properties as $i )
    array_push( $rows, convert_row( $i, $fields, true ) );

echo json_encode(
    array(
        'sEcho' => intval( $_GET['sEcho'] ),
        'iTotalRecords' => $response_total,
        'iTotalDisplayRecords' => $response_total,
        'aaData' => $rows
    ) );
