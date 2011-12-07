
<?php
/**
 *  Included in function placester_admin_documents_html from admin/init.php
 */
require_once( dirname(__FILE__) . '/../core/util.php' );
require_once( dirname(__FILE__) . '/util.php' );

/** ---------------------------
 *  Utility functions
 *  --------------------------- */

/**
 *  The leads favorites table
 *
 */
function placester_get_favorites_table() {
    // Define the columns
    $columns = array( 
        array ( 'Property', 'width: 100%' ),
    );

    $table_data = array();

    // Get the lead object from the API
    $current_user = wp_get_current_user();
    $lead_info = placester_get_lead_information( $current_user );

    if ( isset( $lead_info->properties ) ) {
        foreach( $lead_info->properties as $favorite ) {
            $row_actions = array(
                'View' => site_url( placester_post_slug() . "/{$favorite->id}" ) ,
                'Remove from favorites' => "#{$favorite->id}",
            );
            $favorite_info = array(
                array( 
                    $favorite->full_address,
                    $row_actions 
                )
            );
            array_push( $table_data, $favorite_info );
        }
    }

    if ( empty( $table_data ) ) {
        return false;
    }

    // Prepend the Columns array to the table data Array
    array_unshift( $table_data, $columns );

    // If there are no roommates return false
    return placester_get_widefat_table( $table_data, array( 'id' => 'placester_favorite_properties', 'style' => 'margin-top:10px' ) ); 
}

/** ---------------------------
 *  Page content
 *  --------------------------- */

echo '<div class="wrap">';

if ( isset( $_REQUEST['p_action'] ) && ( $_REQUEST['p_action'] == 'delete' ) ) {
    if ( empty( $error_message ) ) {
        $success_message = '';
    } 
}

echo placester_get_settings_page_title( 'Favorite properties' );

// Print success message if any
if ( !empty( $success_message ) ) {
    placester_success_message( $success_message );
}
// Print errors if any
if ( !empty( $error_message ) ) {
    placester_error_message( $error_message );
}

// TODO display the correct address_mode
echo placester_html(
    'div',
    array(
        'id' => 'pl_favorites_container',
    ),
    placester_get_favorites_table() . 
    placester_html(
        'img',
        array(
            'src' => admin_url( 'images/wpspin_light.gif' ),
            'class' => "pl_spinner",
            'alt' => "ajax-spinner",
            'style' => 'display:none; margin-top:10px;'
        ) 
    )
);
echo '</div>';

