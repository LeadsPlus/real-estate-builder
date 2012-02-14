<?php 
/** ---------------------------
 *  Utility functions
 *  --------------------------- */
/**
 * Processes the validation data object returned by the 
 * Placester API when action was unsuccessful and adds errors
 * in an array as human readible strings
 * 
 * @param object $validation_data Validation data object returned by the API
 * @param array $error_messages A referenced array that will contain the error 
 * strings
 * 
 * @return void
 */
function placester_process_api_errors( $validation_data, &$error_messages ) {
    if ( is_object($validation_data) )
        foreach ( $validation_data as $key => $value ) 
            if ( is_string($value[0]) )
                $error_messages[$key] = ucfirst(strtolower(str_replace('_', ' ', $key))) . " " . $value[0];
            else if ( is_object($value[0]) ) 
                placester_process_api_errors( $value[0], $error_messages );
}

/** ---------------------------
 *  Wordpress Hooks
 *  --------------------------- */
/**
 * Modifies the message on the register page when a lead is registered
 * 
 * @param mixed $message 
 * 
 * @return void
 * TODO Make this work
 */
add_filter('login_message', 'placester_lead_register_message');
function placester_lead_register_message( $message ) {
// if ( in_array( $GLOBALS['pagenow'], array( 'wp-register.php' ) ) )
    if ( isset( $_REQUEST['role'] ) && ( $_REQUEST['role'] == 'lead' ) && isset( $_REQUEST['action'] ) && ( $_REQUEST['role'] == 'register' ) ) {
        $message = placester_html(
            'p',
            array( 'class' => 'message' ),
            'You are now registering for a lead account.'
        );
    }
    return $message;
}

/**
 * Modifies the role of the new user into a lead
 * when the case applies
 * 
 */
add_action( 'user_register', 'placester_post_lead_register' );
function placester_post_lead_register( $user_id ) {
    if ( isset( $_POST['role'] ) && ( $_POST['role'] == 'lead' ) ) {
        // Setting the role like this instead of using wp_update_user
        // preserves the user meta
        $user = new WP_User($user_id);
        $user->set_role( 'placester_lead' );

        // $user_info = get_userdata( $user_id );
        // $args = array(
        //     'ID' => $user_id,
        //     'role' => 'placester_lead',
        // );
        // wp_update_user( $args );
    }
}

/**
 * Modifies the default WP login errors
 * 
 */
add_filter( 'login_errors', 'placester_login_errors' );
function placester_login_errors( $errors ) {
    // TODO modify login errors
    return $errors;
}

/**
 * Extend authentification to allow email authentification
 * 
 */
remove_filter( 'authenticate', 'wp_authenticate_username_password', 20, 3 );
add_filter( 'authenticate', 'placester_email_authentification', 20, 3 );
function placester_email_authentification( $user, $username, $password ) {
	if ( !empty( $username ) )
		$user = get_user_by( $username, '' );
	if ( $user )
		$username = $user->user_login;
	
	return wp_authenticate_username_password( null, $username, $password );
}

/**
 * Changes the login Username label
 * 
 */
add_action( 'login_form', 'placester_login_form', 10, 3 );
function placester_login_form() {
?>
<script type="text/javascript">
        jQuery(document).ready(function() {
            $('#user_login').closest('label').contents().eq(0).replaceWith("Email or Username");
            $('#login_error').closest('label').contents().eq(0).replaceWith("Email or Username");
        });
</script>
<?php 
}

add_action('registration_errors','placester_registration_errors', 10, 3);
function placester_registration_errors( $errors, $login, $email ) {
    return $errors;
}

/**
 * Fires after a registration form is submitted, and before
 * the user is added to the database.
 *
 * This function will register a lead with the Placester API and
 * with the Wordprss database.
 * 
 */
add_action('register_post','placester_before_registration', 10, 3);
function placester_before_registration( $login, $email, $errors ) {
    if ( isset( $_REQUEST['role'] ) && ($_REQUEST['role'] == 'lead') && empty( $errors->errors ) ) {
        // Remove the empty username error
        $lead_object = new stdClass;
        $lead_object->contact->email = $email;
        $lead_object->lead_type = "direct";

        // Request lead add to the api
        $has_errors = false;
        $error_messages = array();
        $response = '';
        try {
            $response = placester_lead_add($lead_object);
        }
        catch (ValidationException $e) {
            $validation_data = $e->validation_data;
            placester_process_api_errors( $validation_data, $error_messages );
            $has_errors = true;
        }
        if ($has_errors) {
            foreach( $error_messages as $index => $error_message )
                $errors->add('api_error_' . $index, "<strong>API ERROR</strong>: {$error_message}");
        } else {
            $lead_api_keys = get_option( 'placester_users_api_ids' );
            $lead_api_keys[$login] = $response->id;


            update_option( 'placester_users_api_ids', $lead_api_keys );
        } 
    }
}

/**
 * Adds the API user id to the 
 * Wordpress 'placester_api_id' user meta
 * 
 */
add_action( 'user_register', 'placester_add_api_id_to_user' );
function placester_add_api_id_to_user( $user_id ) {
    $user = get_userdata( $user_id );
    if ( isset($user->wp_capabilities['placester_lead']) ) {
        $lead_api_keys = get_option( 'placester_users_api_ids' );
        update_user_meta( $user->ID, 'placester_api_id', $lead_api_keys[$user->user_login] );
        unset( $lead_api_keys[$user->user_login] );
        if ( empty($lead_api_keys) ) {
            delete_option( 'placester_users_api_ids' );
        } else {
            update_option( 'placester_users_api_ids', $lead_api_keys );
        }
    }
}

/**
 * Removes the username and adds a hidden role input to the
 * registration form
 * 
 */
add_action('register_form', 'placester_add_lead_registration_fields');
function placester_add_lead_registration_fields() {
    // Assumed all registrations as lead registrations
    // The reason for this is that if the registration form returns an 
    // error, then the url will not contain the role parameter.
    // This happens because Wordpress does not have an easy way to rewrite
    // the action of the form. (see wp-login.php, case 'register').
    // TODO: Inform the admin that by activation client accounts, all 
    // registrations will default to the "placester_lead" role type.

    if ( isset( $_REQUEST['role'] ) && ( $_REQUEST['role'] == 'lead' ) ) {
?>
<script type="text/javascript">
        jQuery(document).ready(function() {
            form_action = $('#registerform').attr('action');

            $('#registerform').attr('action', form_action + '&role=lead');

        });
</script>
<?php 
        echo placester_html(
            'input', 
            array(
                'id' => 'role',
                'type' => 'hidden',
                'value' => $_REQUEST['role'],
                'name' => 'role',
            )
        );
    }
}

/**
 * Adds the "Register lead account" link a the bottom
 * of the Wordpress register form
 * 
 */
add_action('login_form', 'placester_add_register_links');
function placester_add_register_links() {
?>
<script type="text/javascript">
        jQuery(document).ready(function() {
            $("#nav").attr('style', 'margin: 0 0 0 20px; padding: 16px 0;')
            $register_link = $('#nav a').eq(0);
            $register_lead_link = $register_link.clone();
            href_link = $register_lead_link.attr('href');
            $register_lead_link.attr('href', href_link + '&role=lead');
            $register_lead_link.html('Register lead account');
            $register_link.after( " | ", $register_lead_link );

        });
</script>
<?php 
}

/**
 *  Overwrite the contact methods for the "lead" role
 *
 *  Created a custom profile page for that lead
 */
// add_filter( 'user_contactmethods', 'placester_lead_user_contactmethods', 10, 2 );
// [> Function for adding new contact methods. <]
// function placester_lead_user_contactmethods( $user_contactmethods, $user ) {
    // // Add phone number only if the user is a lead
    // if ( in_array( 'placester_lead', $user->roles ) ) {
        // $default_contactmethods = array(
            // 'aim',
            // 'jabber',
            // 'yim'
        // );

        // // Clear previously defined contact methods
        // foreach( $default_contactmethods as $contact_method )
            // if ( array_key_exists( $contact_method, $user_contactmethods ) )
                // unset( $user_contactmethods[$contact_method] );

        // [> Add the phone number contact method. <]
        // $user_contactmethods['phone'] = 'Phone Number';
    // }

    // return $user_contactmethods;
// }

add_action( 'profile_update', 'placester_lead_update', 10, 2 );
function placester_lead_update( $user_id, $old_user_data ) {
    // EXECUTED WHEN wp_update_user IS CALLED
}

/**
 * Adds an extra "Roommates" column in the users table
 * 
 */
add_filter( 'manage_users_columns', 'placester_add_user_column' );
function placester_add_user_column( $columns ) {
    $columns['roommates'] = 'Roommates';

    return $columns;
}

/**
 * Data in the users table roommates column
 * 
 */
add_filter( 'manage_users_custom_column', 'placester_custom_user_column', 10, 3 );
function placester_custom_user_column( $default, $column_name, $user_id ) {
    if ( $column_name == 'roommates' ) {
        $user = get_userdata( $user_id );
        if ( isset( $user->wp_capabilities['placester_lead'] ) ) {
            $roommates = get_user_meta( $user_id, 'placester_roommates' );
        
            return intval( $roommates );
        } else {
            return '-';
        }
        
    }
}

/**
 * Remove lead from the API 
 * 
 * @param int $user_id The WP user id
 * 
 */
add_action( 'delete_user', 'placester_delete_lead' );
function placester_delete_lead( $user_id ) {
    $user = get_userdata( $user_id );
    if ( isset( $user->wp_capabilities['placester_lead'] ) ) {
        $lead_api_id = get_user_meta( $user_id, 'placester_api_id', true );

        try {
            $result = placester_lead_delete( $lead_api_id );
        }
        catch (Exception $e) {
            $error_messages = array( $e->getMessage() );
            // Do nothing ... for now 
        }
    }
}

/**
 * Hides the admin bar for leads
 * 
 */
add_action( 'init', 'placester_hide_admin_bar_for_leads' );
function placester_hide_admin_bar_for_leads() {
    if ( current_user_can('placester_lead') )
        add_filter( 'show_admin_bar', '__return_false' );
}

/**
 * Redirect profile and dashboard access to the 
 * lead profile page
 * 
 */
// add_action( 'personal_options', 'placester_redirect_to_lead_profile' );
// add_action( 'wp_dashboard_setup', 'placester_redirect_to_lead_profile' );
function placester_redirect_to_lead_profile() {
    if ( current_user_can('placester_lead') ) {
        wp_redirect( 'admin.php?page=placester_lead_profile', 301 );
        exit;
    }
}
