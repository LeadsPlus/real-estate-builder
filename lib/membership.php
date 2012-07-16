<?php 
// Class Designed to Handle the rigors of Membership, and membership options...
// or something like that. 
// by matt, though much was taken from alex. 


PL_Membership::init();
class PL_Membership {
    
    static function init() {
        add_action( 'wp_ajax_nopriv_pl_register_lead', array( __CLASS__, 'ajax_create_lead'  ));
        add_action( 'wp_ajax_nopriv_pl_login', array( __CLASS__, 'placester_ajax_login'  )); 

        add_action( 'wp_ajax_add_favorite_property', array(__CLASS__,'ajax_add_favorite_property'));
        add_action( 'wp_ajax_nopriv_add_favorite_property', array(__CLASS__,'ajax_add_favorite_property'));
        add_action( 'wp_ajax_remove_favorite_property', array(__CLASS__,'ajax_remove_favorite_property'));

        add_shortcode('favorite_link_toggle', array(__CLASS__,'placester_favorite_link_toggle'));
        add_shortcode('lead_user_navigation', array(__CLASS__,'placester_lead_control_panel'));

        // Create the "Property lead" role
        $lead_role = add_role( 'placester_lead','Property Lead',array('add_roomates' => true,'read_roomates' => true,'delete_roomates' => true,'add_favorites' => true,'delete_roomates' => true,'level_0' => true,'read' => true));
    }

    public function get_favorite_ids () {
        $person = PL_People_Helper::person_details();
        $ids = array();
				if (isset($person['fav_listings'])) {
				  foreach ( (array) $person['fav_listings'] as $fav_listings) {
				      $ids[] = $fav_listings['id'];
				  }
				}
			  return $ids;
    }

    public function ajax_add_favorite_property () {
        if ($_POST['property_id']) {
            $api_response = PL_People_Helper::associate_property($_POST['property_id']);
            echo json_encode($api_response);   
        } else {
            echo false;
        }
        die();
    }

    public function ajax_remove_favorite_property () {
        if ($_POST['property_id']) {
            $api_response = PL_People_Helper::unassociate_property($_POST['property_id']);
            echo json_encode($api_response);
            die();   
        }
    }

    static function get_client_area_url () {
        global $wpdb;
        $page_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = 'client-profile'");
        return get_permalink($page_id);
    }

    public function get_user () {
        $wp_user = wp_get_current_user();
        if ($wp_user->ID) {
            return $wp_user;
        }
        return false;
    }

    /**
     *  Callback function for when the frontend 
     *  lead register form is submitted 
     *
     *  JavaScript in "js/theme/placester.membership.js"
     *
     */
    static function ajax_create_lead ()
    {
        //make sure it's from a form we created
        if ( !wp_verify_nonce($_POST['nonce'], 'placester_true_registration') ) {
            //malicious
            echo 'Sorry, your nonce didn\'t verify. Try using the form on the site.';
            die();
        }        

        //all validation rules in a single place.
        $lead_object = self::validate_registration($_POST);

        //check for lead errors
       if ( !empty($lead_object['errors']) ) {
            $error_messages = self::process_errors($lead_object['errors']);
            echo $error_messages;
            die(); // oops TODO: Fix the -1 random ass issue.
        } else {
            //create the lead!
            echo json_encode(self::create_lead($lead_object));
            die();
        }
    }

    // mother function for all lead creation.
    static function create_lead($lead_object) {
        $wordpress_user_id = self::create_wordpress_user_lead($lead_object);
        if ( !is_wp_error($wordpress_user_id) ) {
        
            //force blog to be set immediately or MU throws errors.
            $blogs = get_blogs_of_user($wordpress_user_id);
            $first_blog = current($blogs);
            update_user_meta( $wordpress_user_id, 'primary_blog', $first_blog->userblog_id );
            
            $response = PL_People_Helper::add_person($lead_object);
            if (isset($response['code'])) {
                $lead_object['errors'][] = $response['message'];
                foreach ($response['validations'] as $key => $validation) {
                    $lead_object['errors'][] = $response['human_names'][$key] . implode($validation, ' and ');    
                }
                $lead_object['errors'][] = 'placester_create_failed';                    
            }
            
            // If the API call was successfull, inform the user of his 
            // password and set the password change nag
            if ( empty( $lead_object['errors'] ) ) {
                update_user_meta( $wordpress_user_id, 'placester_api_id', $response['id'] );
                wp_new_user_notification( $wordpress_user_id);
            }

            if (get_option('pls_send_client_option') && get_option('pls_send_client_text')) {
                wp_mail($lead_object['username'], 'Your new account on ' . site_url(), get_option('pls_send_client_text'));
            }

            //login user if successfully sign up.
            wp_set_auth_cookie($wordpress_user_id, true, is_ssl());
        } else {
            //failure
            $lead_object['errors'][] = 'wp_user_create_failed';
        }
        die();
    } 
        

    /**
     *  Callback function for when the 
     *  frontend login form is submitted
     *
     *  JavaScript in "js/theme/placester.membership.js"
     *
     */
    static function placester_ajax_login() {
        extract( $_POST );
        
        $errors = array();

        $sanitized_username = sanitize_user( $username );
        if ( empty( $sanitized_username ) ) 
            $errors['missing_username'] = "The username is required.";
        elseif ( empty( $password ) ) 
            $errors['missing_pass'] = "The password is required.";
        else {
            $userdata = get_user_by( 'login', $sanitized_username );
            // If the username exists, verify if the password is correct
            // pls_dump($userdata);
            if ( $userdata ) {
                if ( !wp_check_password( $password, $userdata->user_pass, $userdata->ID ) ) 
                    $errors['wrong_pass'] = "The password is not correct.";

            } else {
                $errors['wrong_user'] = "The username is invalid.";
            }
        }

        if ( !empty( $errors ) ) {
            foreach( $errors as $key => $error ) 
                echo "<div class='pl_login_alert pl_error error {$key}'>{$error}</div>";
        } else {
            // Not actually seen since user is redirected.
            echo "<div class='pl_login_alert success'>You have been successfully logged in.</div>";
        }

        die;
    }

    //creates wordpress users given lead_objects
    private static function create_wordpress_user_lead($lead_object) {
        // Wordpress doesn't support phone.
        $userdata = array(
            'user_pass' => $lead_object['password'],
            'user_login' => $lead_object['username'],
            'user_email' => $lead_object['metadata']['email'],
            'role' => 'placester_lead', 
        );

        $user_id = wp_insert_user( $userdata );

        //user creation failed.
        if ( !$user_id ) {
            return false;
        } else {
            return $user_id;
        }
    }

    // validates all registration data.
    private static function validate_registration($post_vars) {
        if ( is_array($post_vars)) {
            
            $lead_object['username'] = '';
            $lead_object['metadata']['email'] = '';
            $lead_object['password'] = '';
            $lead_object['name'] = '';
            $lead_object['phone'] = '';
            $lead_object['lead_type'] = get_bloginfo('url');
            $lead_object['errors'] = array();

            foreach ($post_vars as $key => $value) {
                switch ($key) {
                    case 'username':
                        $username['errors'] = array();
                        $username['unvalidated'] = $value;
                        $username['validated'] = '';

                        //handles all random edge cases
                        $username_validation = self::validate_username($username, $lead_object);
                        
                        //split verification array
                        $username = $username_validation['username'];
                        $lead_object = $username_validation['lead_object'];

                        // if no errors, set username
                        if( empty($username['errors']) ){
                            $lead_object['username'] = $username['validated'];    
                        }
                
                        break;
                    
                    case 'email':
                        $email['errors'] = array();
                        $email['unvalidated'] = $value;
                        $email['validated'] = '';

                        $email_validation = self::validate_email($email, $lead_object);

                        //split verification array
                        $email = $email_validation['email'];
                        $lead_object = $email_validation['lead_object'];

                        if ( empty($email['errors']) ) {
                            $lead_object['metadata']['email'] = $email['validated'];
                        }

                        break;
                    
                    case 'password':
                        $password['errors'] = array();
                        $password['unvalidated'] = $value;
                        $confirm_password = $post_vars['confirm'];
                        $password['validated'] = '';

                        $password_validation = self::validate_password($password, $confirm_password, $lead_object);

                        //split verification array
                        $password = $password_validation['password'];
                        $lead_object = $password_validation['lead_object'];
                        
                        if ( empty($password['errors']) ) {
                            $lead_object['password'] = $password['validated'];
                        }                        
                        break;
                    
                    case 'name':
                        // we'll be fancy later.
                        if ( !empty($value) ) {
                            $lead_object['name'] = $value;
                        }
                        break;

                    case 'phone':
                        // we'll be fancy later.
                        if ( !empty($value) ) {
                            $lead_object['phone'] = $value;
                        };
                }
            }
        }
        
        return $lead_object;

    }

    //rules for validating passwords
    private static function validate_password($password, $confirm_password, $lead_object) {
        //make sure we have password and confirm.
        if (!empty($password['unvalidated']) && !empty($confirm_password) )  {

            //make sure they are the same
            if ($password['unvalidated'] == $confirm_password ) {
                $password['validated'] = $password['unvalidated'];
            } else {
                //they aren't the same
                $lead_object['errors'][] = 'password_mismatch';
                $password['errors'] = true;
            }
        } else {
            // missing one.
            if ( empty($password['unvalidated']) ) {
                $lead_object['errors'][] = 'password_empty';
                $password['errors'] = true;
            }

            if ( empty($confirm_password)) {
                 $lead_object['errors'][] = 'confirm_empty';
                $password['errors'] = true;   
            }
        }

        return array('password' => $password, 'lead_object' => $lead_object);


    }

    //rules for validating email addresses
    private static function validate_email ($email, $lead_object)
    {
        if ( empty($email['unvalidated']) ) {
            $lead_object['errors'][] = 'email_required';
            $email['errors'] = true;
        } else {

            //something in email, is it valid?
            if ( is_email($email['unvalidated'] ) ) {
                if ( email_exists($email['unvalidated']) ) {
                    $lead_object['errors'][] = 'email_taken';
                    $email['errors'] = true;    
                } else {
                    $email['validated'] = $email['unvalidated'];        
                }
                
            } else {
                $lead_object['errors'][] = 'email_invalid';
                $email['errors'] = true;
            }
        }

        return array('email' => $email, 'lead_object' => $lead_object);
    }

    // rules for validating the username
    private static function validate_username ($username, $lead_object)
    {

        //check for empty..
        if ( !empty($username['unvalidated']) ) {
            //check to see if it's valid
            $username['unvalidated'] = sanitize_user($username['unvalidated']);
        
        } else {
            //generate one from the email, because wordpress requries it
            $lead_object['errors'][] = 'username_empty';
            $username['errors'] = true;

        }

        // check if username exists. 
        if ( username_exists($username['unvalidated']) ) {
            $lead_object['errors'][] = 'username_exists';
            $username['errors'] = true;
        } else {
            $username['validated'] = $username['unvalidated'];
        }

        return array('username' => $username, 'lead_object' => $lead_object);

    }

    // used for processing errors for the various forms. 
    private static function process_errors($errors)
    {
        
        $error_messages = '<div><ul>';

        foreach ($errors as $error => $type) {
            
            switch ($type) {
                case 'username_exists':
                    $error_messages .= self::create_error_message('That username already exists');
                    break;
                
                case 'username_empty':
                    $error_messages .= self::create_error_message('Username is required.');
                    break;

                case 'email_required':
                    $error_messages .= self::create_error_message('Email is required');
                    break;
                
                case 'email_invalid':
                    $error_messages .= self::create_error_message('Your email is invalid');
                    break;
                
                case 'email_taken':
                    $error_messages .= self::create_error_message('That email is already taken.');
                    break;

                case 'password_mismatch':
                    $error_messages .= self::create_error_message('Your passwords don\'t match');
                    break;

                case 'password_empty':
                    $error_messages .= self::create_error_message('Password is required');
                    break;
                
                case 'confirm_empty':
                    $error_messages .= self::create_error_message('Confirm password is empty');
                    break;
                
                default:
                    $error_messages .= self::create_error_message('There was an errror, try again soon.');
                    break;
            }
        }

        $error_messages .= '</ul></div>';
        return $error_messages;
    }

    //easy way to process all messages. 
    private static function create_error_message ($message) {
        ob_start();
            ?><li><?php echo $message; ?></li><?php
        $error_message = ob_get_clean();
        return $error_message;
    }

    /**
     * Creates a registration form
     *
     * The paramater will be used as an action for the registration form and it 
     * will be used in the ajax callback at submission
     * 
     * @param string $role The Wordpress role
     *
     */
    static function generate_lead_reg_form ($role = 'placester_lead')
    {
       if ( ! is_user_logged_in() ) {
        ob_start();
        ?>
        <div style="display: none">
            <form method="post" action="#<?php echo $role; ?>" id="pl_lead_register_form" name="pl_lead_register_form">
                <div style="display:none" id="form_message_box"></div>
                <h2>Sign Up</h2>
                <!-- <p>
                    <label for="pl_reg_username">Username</label>
                    <input type="text" tabindex="20" size="20" class="input" id="user_login" name="user_login">
                </p> -->
                <p>
                    <label for="user_email">Email</label>
                    <input type="text" tabindex="20" size="20" class="input" id="user_email" name="user_email">
                </p>
                <p>
                    <label for="user_password">Password</label>
                    <input type="password" tabindex="20" size="20" class="input" id="user_password" name="user_password">
                </p>
                <p>
                    <label for="user_confirm">Confirm Password</label>
                    <input type="password" tabindex="20" size="20" class="input" id="user_confirm" name="user_confirm">
                </p>
                <!-- <p>
                    <label for="user_fname">Name</label>
                    <input type="text" tabindex="20" size="20" class="input" id="user_fname" name="user_fname">
                </p>
                <p>
                    <label for="user_phone">Phone</label>
                    <input type="text" tabindex="20" size="20" class="input" id="user_phone" name="user_phone">
                </p> -->
                <p>
                    <input type="submit" tabindex="20" class="submit button" value="Register" id="pl_register" name="pl_register">
                </p>
                <?php echo wp_nonce_field( 'placester_true_registration', 'register_nonce_field' ); ?>
                <input type="hidden" id="register_form_submit_button" name="_wp_http_referer" value="/listings/">
            </form>
        </div>
        <?php
        $result = ob_get_clean();
    } else {
        ob_start();
        ?>
            <div style="display:none">
                <div class="pl_error error" id="pl_lead_register_form">
                 You cannot register a user if you are logged in. You shouldn't even see a "Register" link.   
                </div>
            </div>
        <?php
        $result = ob_get_clean();       
    }

    return $result; 
    }


    /**
     * Adds a "Add property to favorites" link 
     * if the user is not logged in, or if 
     * the property is not in the favorite list, 
     * and a "Remove property from favorites" otherwise
     * 
     * TODO If logged in and not lead display something informing them 
     * of what they need to do to register a lead account
     */
    function placester_favorite_link_toggle( $atts ) {
        $defaults = array(
            'add_text' => 'Add property to favorites',
            'remove_text' => 'Remove property from favorites',
            'spinner' => admin_url( 'images/wpspin_light.gif' ),
            'property_id' => false
        );

        $args = wp_parse_args( $atts, $defaults );
        extract( $args, EXTR_SKIP );
        
        $is_lead = current_user_can( 'placester_lead' );
        if ( !$is_lead ) {
            return;
        }

        // $add_link_attr = array('href' => "#{$property_id}",'id' => 'pl_add_favorite','class' => 'pl_prop_fav_link');
        // $remove_link_attr = array('href' => "#{$property_id}",'id' => 'pl_remove_favorite','class' => 'pl_prop_fav_link');
        
        // // Add extra classes if user not loggend in or doesn't have a lead account
        // if (  ) {
        //     // $add_link_attr['class'] .= 'guest'; 
        //     // $add_link_attr['href'] =  
        //     // $add_link_attr['target'] = "_blank"; 
        // } else {
            
        //     // Return the remove link if favorite
        //     if ( $is_favorite )
        //         $add_link_attr['style'] = "display:none;";
        // }
        // if ( !isset($add_link_attr['style']) ) {
        //     $remove_link_attr['style'] = "display:none;";
        // }

        
        $is_favorite = self::is_favorite_property($property_id);

        ob_start();
        ?>
            <div id="pl_add_remove_lead_favorites">
                <?php if (is_user_logged_in()): ?>
                    <a href="<?php echo "#" . $property_id ?>" id="pl_add_favorite" class="pl_prop_fav_link" <?php echo $is_favorite ? "style='display:none;'" : "" ?> ><?php echo $add_text ?></a>
                <?php else: ?>
                    <a href="<?php echo self::get_client_area_url() ?>" target="_blank" id="pl_add_favorite" class="guest"><?php echo $add_text ?></a>
                <?php endif ?>
                <a href="<?php echo "#" . $property_id ?>" id="pl_remove_favorite" class="pl_prop_fav_link" <?php echo !$is_favorite ? "style='display:none;'" : "" ?> ><?php echo $remove_text ?></a>
                <img class="pl_spinner" src="<?php echo $spinner ?>" alt="ajax-spinner" style="display:none; margin-left: 5px;">
            </div>
        <?php
        return ob_get_clean();
    }


    function is_favorite_property ($property_id) {
        $person = PL_People_Helper::person_details();
        // pls_dump($property_id, $person['fav_listings']);
        if ( isset($person['fav_listings']) && is_array($person['fav_listings']) ) {
            foreach ($person['fav_listings'] as $fav_listing) {
                if ($fav_listing['id'] == $property_id) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Adds "Login | Register" if not logged in
     * or "Logout | My account" if logged in 
     *
     * TODO If logged in and not lead display something informing them 
     * of what they need to do to register a lead account
     */
    function placester_lead_control_panel( $args ) {
        $defaults = array(
            'loginout' => true,
            'profile' => true,
            'register' => true,
            'container_tag' => false,
            'container_class' => false,
            'anchor_tag' => false,
            'anchor_class' => false,
            'separator' => ' | ',
            'inside_pre_tag' => false,
            'inside_post_tag' => false
        );
        $args = wp_parse_args( $args, $defaults );
        extract( $args, EXTR_SKIP );

        $is_lead = current_user_can( 'placester_lead' );

        /** The login or logout link. */
        if ( ! is_user_logged_in() ) {
            $loginout_link = '<a class="pl_login_link" href="#pl_login_form">Log in</a>';
        } else {
            $loginout_link = '<a href="' . esc_url( wp_logout_url(site_url()) ) . '" id="pl_logout_link">Log out</a>';
        }
        if ($anchor_tag) {
            $loginout_link = "<{$anchor_tag} class={$anchor_class}>" . $inside_pre_tag . $loginout_link . $inside_post_tag . "</{$anchor_tag}>";
        }    


        /** The register link. */
        $register_link = '<a class="pl_register_lead_link" href="#pl_lead_register_form">Register</a>';
        if ($anchor_tag) {
            $register_link = "<{$anchor_tag} class={$anchor_class}>" . $inside_pre_tag . $register_link . $inside_post_tag . "</{$anchor_tag}>";
        }

        /** The profile link. */
        $profile_link = '<a id="pl_lead_profile_link" target="_blank" href="' . self::get_client_area_url() . '">My Account</a>';
        if ($anchor_tag) {
            $profile_link = "<{$anchor_tag} class={$anchor_class}>" . $inside_pre_tag . $profile_link . $inside_post_tag . "</{$anchor_tag}>";
        }
        // var_dump($profile_link);

        $loginout_link = ( $loginout ) ? $loginout_link : '';
        $register_link = ( $register ) ? ( empty($loginout_link) ? $register_link : $separator . $register_link ) : '';
        $profile_link = ( $profile ) ? ( empty($loginout_link) ? $profile_link : $separator . $profile_link ) : '';

        if ( ! is_user_logged_in() ) {
            $args = array( 
                'echo' => false,
                'form_id' => 'pl_login_form',
            );
            /** Get the login form. */
            $login_form = wp_login_form( $args );
            if ($container_tag) {
                return "<{$container_tag} class={$container_class}>" . $loginout_link . $register_link . "</{$container_tag}>" . self::generate_lead_reg_form() . "<div style='display:none;'>{$login_form}</div>";
            }
            return $loginout_link . $register_link . self::generate_lead_reg_form() . "<div style='display:none;'>{$login_form}</div>";
        } else {
            /** Remove the link to the profile if the current user is not a lead. */
            $extra = $is_lead ? $profile_link : "";
            if ($container_tag) {
                return "<{$container_tag} class={$container_class}>" . $loginout_link . $extra . "</{$container_tag}>";
            }
            return $loginout_link . $extra;
        }
    }


}