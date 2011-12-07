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
 * placester_get_formated_roommates 
 * 
 * @param string $user_id 
 *
 * @return array $result
 */
function placester_get_formated_roommates( $user_id = '' ) {
    if ( empty($user_id) ) {
        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;
    }

    $roommate_ids_array = get_user_meta( $user_id, 'placester_roommates', true );

    $result = array();

    if ( is_array($roommate_ids_array) ) {
        foreach( $roommate_ids_array as $roommate_id => $value ) {
            $roommate = get_userdata( $roommate_id );
            $user_data = array();

            $user_data['name'] = ( empty($roommate->first_name) && empty($roommate->last_name) ) ? $roommate->user_nicename : $roommate->first_name . ' ' . $roommate->last_name;
            $user_data['username'] = $roommate->user_login;
            $user_data['email'] = $roommate->user_email;
            $user_data['id'] = $roommate->ID;

            array_push( $result, $user_data );
        }
    }

    return $result;
}

/**
 *  The Placester roommates table
 *
 */
function placester_get_roommates_table() {
    // Define the columns
    $columns = array( 
        array ( 'Name', 'width: 30%' ),
        array ( 'Username', 'width: 30%' ),
        array ( 'E-mail', 'width: 40%' ),
    );

    $roommates = placester_get_formated_roommates();

    $table_data = array();

    foreach( $roommates as $friend ) {
        $delete_url = add_query_arg( array(
            'wp_id' => $friend['id'],
        ));

        $row_actions = array(
            'Delete roommate' => $delete_url,
        );
        $friend_info = array(
            array( 
                $friend['name'],
                $row_actions 
            ),
            $friend['username'],
            $friend['email'],
        );
        array_push( $table_data, $friend_info );
    }

    if ( empty( $table_data ) ) {
        return false;
    }

    // Prepend the Columns array to the table data Array
    array_unshift( $table_data, $columns );
    // If there are no roommates return false
    return placester_get_widefat_table( $table_data, array( 'id' => 'placester_roommates' ) ); 
}

/**
 *  Return the Text field form
 */
function placester_get_sidebar_invite_form() {
    $result = placester_html(
        'form',
        array(
            'method' => 'post',
            'class' => 'clearfix',
            'id' => 'rm_invite_form',
            'action' => '#'
        ),
        placester_html(
            'label',
            array( 'for' => 'rm_email' ),
            'Email address'
        ),
        placester_html(
            'input',
            array(
                'type' => 'text',
                'name' => 'rm_email',
                'id' => 'rm_email',
            )
        ),
        placester_html(
            'label',
            array( 'for' => 'rm_message' ),
            'Message'
        ),
        placester_html(
            'textarea',
            array(
                'type' => 'text',
                'name' => 'rm_message',
                'id' => 'rm_message',
            )
        ),
        // Buttons
        placester_html(
            'input',
            array(
                'type' => 'submit',
                'name' => 'send_invite',
                'class' => 'button',
                'value' => 'Send',
            )
        ) . 
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

    return $result;
}

/**
 * Returns the page content
 *
 */
function placester_get_roommate_page_contents() {
    $roommates_table = placester_get_roommates_table();

    // Create the rows for the invite roommate table
    $email_form_rows_args = array(
        array(
            'th' => placester_html( 'label', array( 'for' => 'rm_email' ), 'Email address' ),
            'td' => placester_html( 'input', array( 'name' => 'rm_email', 'id' => 'rm_email', 'type' => 'text', 'class' => 'regular-text' ) )
        ),
        array(
            'th' => placester_html( 'label', array( 'for' => 'rm_message' ), 'Message' ),
            'td' => placester_html( 'textarea', array( 'name' => 'rm_message', 'id' => 'rm_message', 'rows' => 10, 'cols' => 40, 'class' => 'regular-text' ) )
        )
    );
    $email_form_rows = '';
    foreach ( $email_form_rows_args as $row_args )
        $email_form_rows .= placester_get_form_table_row( $row_args );

    $email_form = placester_get_form_table( 
        array(
            'action' => '#action',
            'rows' => $email_form_rows,
            // Propers send invite
            'post_table' => placester_html(
                'input',
                array(
                    'type' => 'submit',
                    'name' => 'send_invite',
                    'class' => 'button',
                    'value' => 'Send',
                )
            ),
            'extra_attr' => array( 'id' => 'rm_invite_form' )
        )
    );

    $page_content = // ( $roommates_table ) ?
        // If Roommates exist display the roommates table
        placester_get_postbox_containers(
            array( 
                // Main content
                array(
                    $roommates_table .
                    placester_html(
                        'img',
                        array(
                            'src' => admin_url( 'images/wpspin_light.gif' ),
                            'class' => "pl_spinner",
                            'alt' => "ajax-spinner",
                            'style' => 'display:none; margin-top:10px;'
                        ) 
                    ),
                    array( 'style' => 'width: 79.5%' )
                ),
                // Sidebar
                array(
                    placester_get_postbox( placester_get_sidebar_invite_form(), 'Invite roommate' ),
                    array( 'style' => 'width: 19.5%' )
                ),
            ),
            false, // No autowidth
            array( 'class' => 'placester_ui', 'style' => 'margin-top: 10px' )
        );//:
        // Else, if no roommates exist inform the user, 
        // and display the invite form
        // placester_get_postbox_containers(
            // array( 
                // array(
                    // placester_get_postbox( 
                        // placester_html(
                            // 'p',
                            // array( 'style' => 'font-size: 13px; margin: 10px 0 0;' ),
                            // 'It seems you have no roommates. How about adding some?'
                        // ) . $email_form, 
                        // 'Invite roommates' ),
                    // // array( 'style' => 'width: 100%' )
                // ),
            // ),
            // false, // Autowidth
            // array( 'class' => 'placester_ui', 'style' => 'margin-top: 10px' )
        // );
    return $page_content;
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

echo placester_get_settings_page_title( 'Roommates' );

// Print success message if any
if ( !empty( $success_message ) ) {
    placester_success_message( $success_message );
}
// Print errors if any
if ( !empty( $error_message ) ) {
    placester_error_message( $error_message );
}

echo placester_get_roommate_page_contents();
echo '</div>';

