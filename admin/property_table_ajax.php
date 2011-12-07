<?php
/** ---------------------------
 *  Ajax functions
 *  --------------------------- */

/**
 *  Callback function for when the delete button
 *  in the "My listings" table is clicked.
 *
 *  JavaScript in "js/admin.properties.js"
 *
 */
add_action( 'wp_ajax_delete_listing', 'placester_ajax_delete_listing' );
function placester_ajax_delete_listing() {
    print_r($_REQUEST);
    $listing_id = $_REQUEST['listing_id'];
    $deleted = placester_property_delete($listing_id);
    $delete = (empty($deleted)) ? true : false;
    echo $deleted;
    die; 
}

