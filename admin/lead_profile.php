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
 * Returns the contents of the Lead Profile page
 * 
 * @param string $user_id 
 * @access public
 * @return void
 */
function placester_get_lead_profile_table_form( $user_id = '' ) {
    if ( empty($user_id) ){
        $user = wp_get_current_user();
        $user_id = $user->ID;
    } else {
        $user = get_userdata( $user_id );
    }

    $user_phone = get_user_meta( $user_id, 'pl_phone_number', true );
    $result = placester_html(
        'form',
        array(
            'method' => 'post',
            'action' => admin_url('admin.php?page=placester_lead_profile'),
            'id' => 'pl-lead-profile'
        ),
        // TODO Verify lead nonce at update
        wp_nonce_field( 'update-lead_' . $user_id ) .
        placester_html( 'h3', 'Personal Options' ) .
        placester_html(
            'table',
            array( 'class' => 'form-table' ), 
            placester_html(
                'tbody',
                // Username row
                placester_get_form_table_row_alt(
                    'user_login',
                    'Username',
                    placester_html( 
                        'input', 
                        array(
                            'type' => 'text',
                            'name' => 'user_login',
                            'id' => 'user_login',
                            'value' => $user->user_login,
                            'class' => 'regular-text',
                            'disabled' => 'disabled',
                        ) 
                    ),
                    'Usernames cannot be changed'
                ) .
                // Name row
                placester_get_form_table_row_alt(
                    'user_first_name',
                    'First name',
                    placester_html( 
                        'input', 
                        array(
                            'type' => 'text',
                            'name' => 'user_first_name',
                            'id' => 'user_first_name',
                            'value' => $user->first_name,
                            'class' => 'regular-text'
                        ) 
                    )
                ) . 
                // Name row
                placester_get_form_table_row_alt(
                    'user_last_name',
                    'Last name',
                    placester_html( 
                        'input', 
                        array(
                            'type' => 'text',
                            'name' => 'user_last_name',
                            'id' => 'user_last_name',
                            'value' => $user->last_name,
                            'class' => 'regular-text'
                        ) 
                    )
                ) . 
                // E-mail row
                placester_get_form_table_row_alt(
                    'user_email',
                    'E-mail <span class="description">(required)</span>',
                    placester_html( 
                        'input', 
                        array(
                            'type' => 'text',
                            'name' => 'user_email',
                            'id' => 'user_email',
                            'value' => $user->user_email,
                            'class' => 'regular-text'
                        ) 
                    )
                ) . 
                // Phone number row
                placester_get_form_table_row_alt(
                    'user_phone',
                    'Phone number',
                    placester_html( 
                        'input', 
                        array(
                            'type' => 'text',
                            'name' => 'user_phone',
                            'id' => 'user_phone',
                            'value' => $user_phone,
                            'class' => 'regular-text'
                        ) 
                    )
                )  
            ) 
        ) .     
        placester_html( 'h3', 'Account' ) .
        placester_html(
            'table',
            array( 'class' => 'form-table' ), 
            placester_html(
                'tbody',
                placester_get_form_table_row_alt(
                    'user_new_password',
                    'New Password',
                    placester_html( 
                        'input', 
                        array(
                            'type' => 'password',
                            'name' => 'user_new_password',
                            'id' => 'user_new_password',
                            'value' => '',
                            'class' => 'regular-text'
                        ) 
                    ), 
                    'Leave this blank if you don\'t want to change the password'
                ) 
            )
        ) .
        // Submit button
        placester_html(
            'p',
            array( 'class' => 'submit' ),
            placester_html( 
                'input', 
                array( 
                    'type' => 'submit',
                    'name' => 'submit',
                    'id' => 'submit',
                    'class' => 'button-primary',
                    'value' => 'Update Profile'
                )
            ) . 
            placester_html(
                'img',
                array(
                    'src' => admin_url( 'images/wpspin_light.gif' ),
                    'class' => "pl_spinner",
                    'alt' => "ajax-spinner",
                    'style' => 'display:none; margin-left:5px;'
                )
            )
        )
    );

    return $result;
}
/** ---------------------------
 *  Page content
 *  --------------------------- */

echo '<div class="wrap placester_ui">';

if ( isset( $_REQUEST['submit'] ) ) {
    // check_admin_referer('update-lead_' . $user_id);
    
    $current_user = wp_get_current_user();
    $phone = get_user_meta( $current_user->ID, 'placester_lead_phone_number', true );
    $phone = !($phone) ? '' : $phone;

    $lead_object = new stdClass;

    $modified = false;

    if ( $current_user->user_email != $_POST['user_email'] ) {
        $lead_object->contact->email = $_POST['user_email'];
        $modified = true;
    }

    if ( ($current_user->last_name != $_POST['user_last_name']) || ($current_user->last_name != $_POST['user_last_name']) ) {
        $lead_object->contact->name = $_POST['user_first_name'] . $_POST['user_last_name'];
        $modified = true;
    }

    if ( $phone != $_POST['user_phone'] ) {
        $lead_object->contact->phone = $_POST['user_phone'];
        $modified = true;
    }

    if ( $modified ) {
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

    // user_pass
    // if ( !empty($_POST['new_user_password']) )

}

echo placester_get_settings_page_title( 'Lead profile' );

// Print success message if any
if ( !empty( $success_message ) ) {
    placester_success_message( $success_message );
}

// Print errors if any
if ( !empty( $error_messages ) ) {
    foreach( $error_messages as $error )
        placester_error_message( $error );
}

// $c = wp_get_current_user();
// echo "\n <pre>XSTART \n";
// print_r($c);
// echo "\n XEND </pre>";

// $phone = get_user_meta( $c->ID, 'placester_lead_phone_number', true );

echo placester_get_lead_profile_table_form();
echo '</div>';

