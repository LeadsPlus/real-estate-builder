<?php
/** ---------------------------
 *  Helper functions
 *  --------------------------- */
/**
 * Returns the invite transient option name 
 * 
 * @param string $email Optional email address
 * @return void
 */
function placester_get_invite_transient_name( $email = '' ) {
    if ( empty($email) ) {
        $current_user = wp_get_current_user();
        $email = $current_user->user_email;
    } 
    $invite_transient_name = 'pl_rm_invite_' . $email;

    return $invite_transient_name;
}

/**
 * Sends an invitation to a certain email
 * 
 * @param string $email The email address of invited
 * @param string $message The message
 * 
 * @return bool Wether the emails sending was a success
 */
function placester_send_invite( $email, $message ) {

    $lead_login_url = site_url( 'wp-login.php' );
    $lead_register_url = site_url( 'wp-login.php?action=register&role=lead' );

    $current_user = wp_get_current_user();
    if ( $current_user->user_email == $email ) 
        return array( 'You cannot invite yourself.' );
    
    $message = !empty( $message ) ? '<br />' . $current_user->user_nicename . ' says:<br />"' . $message . '"<br />' : '';

    $email_body_format = 
        placester_html(
            'html',
            placester_html( 'head' ) .
            placester_html(
                'body',
                '%s (%s) wants to add you as a roommate on <a href="%s">%s</a>.' .
                '<br />' .
                $message .
                'To accept you must login to your account by clicking on this link:' .
                '<br />' .
                '<a href="%s">%s</a>' . 
                '<br />' .
                'If you don\'t have an account, you can register here:' .
                '<br />' .
                '<a href="%s">%s</a>' 
            )
        );

    $email_body = sprintf( 
        $email_body_format,
        $current_user->user_nicename,  
        $current_user->user_email,  
        site_url(),  
        get_bloginfo('name'),
        $lead_login_url,
        $lead_login_url,
        $lead_register_url,
        $lead_register_url
    );
    
    // Set the mail content type to html and send the mail
    add_filter( 'wp_mail_content_type', create_function( '', 'return "text/html";' ) );
    add_filter( 'wp_mail_from_name', create_function( '', '$site_name = get_bloginfo("name"); return $site_name;' ) );

    $result = wp_mail( 
        $email, 
        sprintf( '%s wants to add you as a roommate on %s', $current_user->user_nicename, get_bloginfo('name') ),
        $email_body 
    );

    // If the mail was successfully sent, push a new inviter id to the 
    // invites transient for that mail.
    if ( $result ) {
        $invite_transient = placester_get_invite_transient_name( $email );
        $invites = get_transient( $invite_transient );
        if ( !$invites || !is_array( $invites ) ) {
            $invites = array() ;
        } 
        $invites[$current_user->ID] = true;
        // Two weeks expiration
        set_transient( $invite_transient, $invites, 60*60*24*14 );
    } else {
        return array( 'mail_problem' => 'There was a problem sending the email with your invite. Please try again later.' );
    }

    // Return an empty array which means success
    return array();
}

/**
 * Removes an invitation from the current user by deleting
 * the inviter id from the current user invites transient
 * 
 * @param int $inviter_id The inviter user id
 * @return void
 */
function placester_remove_invite( $inviter_id ) {

    $invite_transient = placester_get_invite_transient_name();

    $invitations_list = get_transient( $invite_transient );
    unset( $invitations_list[$inviter_id] );

    if ( count($invitations_list) > 0 ) {
        set_transient( $invite_transient, $invitations_list, 60*60*24*14 );
    } else {
        delete_transient( $invite_transient );
    }
}

/**
 * Associates a user to another user in a 
 * roommate relationship
 * 
 * @param int $user1_id 
 * @param int $user2_id 
 *
 *  The "placester_roommates" user meta field holds 
 *  all roommates for the current users and has the 
 *  following form: 
 *
 *  array( $roommate_id => true )
 *
 */
function _placester_assoc_user_to( $user1_id, $user2_id ) {
    $user1_roommates = get_user_meta( $user1_id, 'placester_roommates', true );

    if ( !is_array( $user1_roommates ) )
        $user1_roommates = array();

    $user1_roommates[$user2_id] = true;

    update_user_meta( $user1_id, 'placester_roommates', $user1_roommates );
}

/**
 * Remove roommate association between two users
 * 
 * @param int $user1_id 
 * @param int $user2_id 
 *
 *  The "placester_roommates" user meta field holds 
 *  all roommates for the current users and has the 
 *  following form: 
 *
 *  array( $roommate_id => true )
 *
 */
function _placester_unassoc_user_from( $user1_id, $user2_id ) {
    $user1_roommates = get_user_meta( $user1_id, 'placester_roommates', true );

    unset( $user1_roommates[$user2_id] );

    update_user_meta( $user1_id, 'placester_roommates', $user1_roommates );
}

/**
 * Creates roommate relationships between two leads
 * 
 * @param int $user1_id The first lead id
 * @param int $user2_id The second lead id
 *
 * TODO A little redundant. Maybe a different approach?
 */
function placester_create_roommate_relationship( $user1_id, $user2_id ) {
    _placester_assoc_user_to( $user1_id, $user2_id );
    _placester_assoc_user_to( $user2_id, $user1_id );
}

/**
 * Removes a roommate relationship between current user and
 * the user with the supplied id
 * 
 * @param int $user1_id The first lead id
 * @param int $user2_id The second lead id
 *
 */
function placester_remove_roommate_relationship( $wp_id ) {
    $current_user = wp_get_current_user();

    _placester_unassoc_user_from( $wp_id, $current_user->ID );
    _placester_unassoc_user_from( $current_user->ID, $wp_id );

    return true;
}

/** ---------------------------
 *  Ajax functions
 *  --------------------------- */

/**
 *  Callback function for when a 
 *  "Invite roommate" form is submitted.
 *
 *  JavaScript in "js/admin.leads.js"
 *
 */
add_action( 'wp_ajax_send_lead_invite', 'placester_ajax_send_lead_invite' );
function placester_ajax_send_lead_invite() {
    $email_address = trim( $_POST['email'] );
    $email_body = trim( $_POST['message'] );

    $error_messages = array();
    // Verify if this user isn't already added to the roommate list
    $invited_user = get_user_by_email( $email_address );
    if ( $invited_user ) {
        $current_user = wp_get_current_user();
        $current_user_roommates = get_user_meta( $current_user->ID, 'placester_roommates', true );
        if ( isset( $current_user->placester_roommates[$invited_user->ID] ) ) {
            $error_messages = array( "You already have user with email {$email_address} in your roommate list." );
        }
    }

    if ( empty( $error_messages ) )
        $error_messages = placester_send_invite( $email_address, $email_body );

    if ( !empty( $error_messages ) ) {
        foreach ( $error_messages as $error_message )
            echo placester_get_wp_alert_div( "<p>{$error_message}</p>", 'error', 'close', array( 'class' => 'pl_roommate_invite_send' ) );
    } else {
        echo placester_get_wp_alert_div( "<p>The invite for {$email_address} was successfully sent.</p>", 'notice', 'close', array( 'class' => 'pl_roommate_invite_send success' ) );
    }

    die; 
}

/**
 *  Callback function for when the "Accept" 
 *  being added as roommate button is clicked
 *
 *  JavaScript in "js/admin.leads.js"
 *
 */
add_action( 'wp_ajax_accept_invitation', 'placester_ajax_accept_invitation' );
function placester_ajax_accept_invitation() {
    $inviter_id = trim( $_POST['inviter_id'] );
    $current_user = wp_get_current_user();

    $inviter_api_id = get_user_meta( $inviter_id, 'placester_api_id', true );
    $current_user_api_id = get_user_meta( $current_user->ID, 'placester_api_id', true );

    $has_errors = false;
    $result = '';
    try {
        $result = placester_assoc_leads( array( $inviter_api_id, $current_user_api_id ) );
    }
    catch (Exception $e) {
        $error_messages = array( $e->getMessage() );
        $has_errors = true;
    }

    if ( $has_errors ) {
        foreach ( $error_messages as $error_message )
            echo placester_get_wp_alert_div( "<p>{$error_message}</p>", 'error', 'close', array( 'class' => 'pl_roommate_invite' ) );
    } else {
        placester_create_roommate_relationship( $inviter_id, $current_user->ID );
        placester_remove_invite( $inviter_id );

        echo placester_get_wp_alert_div( "<p>The roommate was successfully added.</p>", 'notice', 'close', array( 'class' => 'pl_roommate_invite success', 'id' => $inviter_id ) );
    }

    die; 
}

/**
 *  Callback function for when the "Decline" 
 *  being added as roommate button is clicked
 *
 *  JavaScript in "js/admin.leads.js"
 *
 */
add_action( 'wp_ajax_decline_invitation', 'placester_ajax_decline_invitation' );
function placester_ajax_decline_invitation() {
    $inviter_id = trim( $_POST['inviter_id'] );

    // No API deletion needed since nothing has been added
    
    // Remove the invitation from Wordpress
    placester_remove_invite( $inviter_id );

    echo 'success';
    die; 
}

/**
 *  Callback function for when the 
 *  "Delete roommate" button is clicked
 *
 *  JavaScript in "js/admin.leads.js"
 *
 */
add_action( 'wp_ajax_delete_roommate', 'placester_ajax_delete_roommate' );
function placester_ajax_delete_roommate() {
    $roommate_wp_id = trim( $_POST['roommate_wp_id'] );

    $current_user = wp_get_current_user();
    $roommate_api_id = get_user_meta( $roommate_wp_id, 'placester_api_id', true );
    $current_user_api_id = get_user_meta( $current_user->ID, 'placester_api_id', true );

    $has_errors = false;
    $error_messages = array();
    try {
        $result = placester_unassoc_leads( array( $current_user_api_id, $roommate_api_id ) );
    }
    catch (Exception $e) {
        $error_messages = array( $e->getMessage() );
        $has_errors = true;
    }

        // placester_remove_roommate_relationship( $roommate_wp_id );
    if ( $has_errors ) {
        foreach ( $error_messages as $error_message )
            echo placester_get_wp_alert_div( "<p>{$error_message}</p>", 'error', 'close', array( 'class' => 'pl_roommate_delete' ) );
    } else {
        placester_remove_roommate_relationship( $roommate_wp_id );

        echo placester_get_wp_alert_div( "<p>The roommate was successfully deleted.</p>", 'notice', 'close', array( 'class' => 'pl_roommate_delete success' ) );
    }

    // if ( $result )
        // echo 'success';
    die; 
}

 /**
 *  Callback function for when the "Update Profile" 
 *  button on the lead profile page is clicked
 *
 *  JavaScript in "js/admin.leads.js"
 *
 */
add_action( 'wp_ajax_update_lead_profile', 'placester_ajax_update_lead_profile' );
function placester_ajax_update_lead_profile() {
    $form_first_name = trim( $_POST['user_first_name'] );
    $form_last_name = trim( $_POST['user_last_name'] );
    $form_email = trim( $_POST['user_email'] );
    $form_phone = trim( $_POST['user_phone'] );
    $password = trim( $_POST['user_new_password'] );

    $current_user = wp_get_current_user();

    $phone = get_user_meta( $current_user->ID, 'placester_lead_phone_number', true );
    $phone = !($phone) ? '' : $phone;

    $lead_object = new stdClass;
    $wp_lead_object = new stdClass;


    $api_fields_modified = false;

    $has_errors = false;
    // If any API releated field has been modified prepare for API request
    if ( ($current_user->user_email != $form_email) || ($current_user->last_name != $form_last_name) || ($current_user->last_name != $form_first_name) || ($phone != $form_phone) ) {
        $user_with_email_id = email_exists( $form_email );
        // Continue with update only if the email is unique in the wordpress database
        if ( $user_with_email_id && $user_with_email_id != $current_user->ID ) {
            $has_errors = true;
            $error_messages = array( 'email_exists' => 'An account with this email already exists.' );
        } else {
            $lead_object->contact->email = $form_email;
            $lead_object->contact->name = $form_first_name . ' ' . $form_last_name;
            $lead_object->contact->phone = $form_phone;

            $wp_lead_object->last_name = $form_last_name;
            $wp_lead_object->first_name = $form_first_name;
            $wp_lead_object->user_email = $form_email;

            $api_fields_modified = true;
        }
    }

    // If changes need to be sent to the api, send them
    if ( $api_fields_modified && !$has_errors ) {
        // Request lead add to the api
        $user_api_id = get_user_meta( $current_user->ID, 'placester_api_id', true );
        $has_errors = false;
        $error_messages = array();
        $response = '';
        try {
            $response = placester_lead_set( $user_api_id, $lead_object );
        }
        catch (ValidationException $e) {
            $validation_data = $e->validation_data;
            placester_process_api_errors( $validation_data, $error_messages );
            $has_errors = true;
        }
    }

    ob_clean();
    if ( $has_errors ) {
        $error_divs = '';
        foreach( $error_messages as $error ) {
            $error_divs .= placester_get_wp_alert_div( "<p>{$error}</p>", 'error', 'close', array( 'class' => 'pl_leads_profile_update' ) );
        }
        echo $error_divs;
    } else {
        $wp_lead_object->ID = $current_user->ID;

        if ( !empty($password) )
            $wp_lead_object->user_pass = $password;

        if ( isset($lead_object->contact->phone) )
            update_user_meta( $current_user->ID, 'pl_phone_number', $lead_object->contact->phone );

        // Update Wordpress if fields have been modified
        $userdata = get_object_vars( $wp_lead_object );

        if ( count($userdata) > 1 ) 
            wp_update_user( $userdata );

        echo placester_get_wp_alert_div( "<p>The profile has been successfully updated.</p>", 'notice', 'close', array( 'class' => 'pl_leads_profile_update success' ) );
    }

    die; 
} 
