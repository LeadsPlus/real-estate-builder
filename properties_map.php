<?php 

/**
 * Called by AJAX (by map object) to get properties
 */

if ( ! defined( 'ABSPATH' ) )
	require_once( dirname( __FILE__ ) . '/../../../wp-load.php');
include( 'properties_util.php' );
wp();
status_header( 200 );

//
// Get data
//
$filter_request = placester_filter_parameters_from_http();
placester_add_admin_filters( $filter_request );

try {
    $response = placester_property_list( $filter_request );
}
catch (Exception $e) {
    echo json_encode( array( 'exception_message' => $e->getMessage() ) );
    exit();
}

//
// Process
//
init_templates();
$fields = explode( ',', $_REQUEST['fields'] );

$output = array();
foreach ( $response->properties as $i )
    array_push( $output, convert_row( $i, $fields, false ) );

echo json_encode( $output );
