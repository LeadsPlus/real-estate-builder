<?php
/**
 * Is executed on uninstall
 */

// If uninstall not called from WordPress exit
if( !defined( 'WP_UNINSTALL_PLUGIN' ) )
exit();

global $wpdb;
$placester_options = $wpdb->get_results(
    'SELECT option_name FROM ' . $wpdb->prefix . 'options ' .
    "WHERE (option_name LIKE  'placester%')" .
    "OR (option_name LIKE '_transient_pl%') " .
    "OR (option_name LIKE '_transient_timeout_pl%');");

foreach ($placester_options as $option) {
    delete_option( $option->option_name );
}

$properties = get_posts('post_type=property' );
print_r($properties); 
foreach ( $properties as $property ) {
    wp_delete_post( $property->ID, true );
}

// TODO Add documents 

// TODO Remove lead related info: roles, users, options, transients etc.

