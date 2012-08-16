<?php

class Placester_Contact_Widget extends WP_Widget {

  function Placester_Contact_Widget() {
    $widget_ops = array('classname' => 'Placester_Contact_Widget', 'description' => 'Works only on the Property Details Page.' );
    $this->WP_Widget( 'Placester_Contact_Widget', 'Placester: Contact Form', $widget_ops );
  }

  //Front end contact form
  function form($instance){
    //Defaults
    $instance = wp_parse_args( (array) $instance, array('title'=>'', 'button' => 'Submit', 'modern' => 0) );

    $title = htmlspecialchars($instance['title']);

    extract($instance, EXTR_SKIP);

    $modern_checked = isset($instance['modern']) && $instance['modern'] == 1 ? 'checked' : '';
    $show_property_checked = isset($instance['show_property']) && $instance['show_property'] == 1 ? 'checked' : '';

    // Output the options
    echo '<p><label for="' . $this->get_field_name('title') . '"> Title: </label><input class="widefat" type="text" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" value="' . $title . '" /></p>';
    echo '<p><label for="' . $this->get_field_name('button') . '"> Submit button label: </label><input class="widefat" type="text" id="' . $this->get_field_id('button') . '" name="' . $this->get_field_name('button') . '" value="' . $button . '" /></p>';
    echo '<p><input class="checkbox" type="checkbox" id="' . $this->get_field_id('modern') . '" name="' . $this->get_field_name('modern') . '"' . $modern_checked . ' style="margin-right: 5px;"/><label for="' . $this->get_field_id('modern') . '"> Use placeholders instead of labels</label></p>';
    echo '<p><input class="checkbox" type="checkbox" id="' . $this->get_field_id('show_property') . '" name="' . $this->get_field_name('show_property') . '"' . $show_property_checked . ' style="margin-right: 5px;"/><label for="' . $this->get_field_id('show_property') . '"> Display property address on the form when viewing a property page</label></p>';

    ?>

<?php 
  }
  
  // Update settings
  function update($new_instance, $old_instance){
    $instance = $old_instance;
    $instance['title'] = strip_tags(stripslashes($new_instance['title']));
    $instance['button'] = strip_tags(stripslashes($new_instance['button']));
    $instance['modern'] = isset($new_instance['modern']) ? 1 : 0;
    $instance['show_property'] = isset($new_instance['show_property']) ? 1 : 0;
    return $instance;
  }

  // Admin widget
  function widget($args, $instance) {
    

    // Find where this widget's sidebar falls in the list of registered sidebars
    // Use this to get a number we can use for unique tabindexes
    if(isset($args['id'])) {
      // Widget is rendering as part of a sidebar
      $sidebar_pos = array_search($args['id'], array_keys(wp_get_sidebars_widgets())) + 1;
    }
    elseif(isset($instance['number'])) {
      // Widget is being passed an instance number
      // the theme is probably instantiating the widget itself
      $sidebar_pos = $instance['number'];
    }
    else {
      // Nothing else to go on, really. Make a counter and
      // increment it each time the widget it rendered on this
      // page hit
      static $instance_count = 1;
      $sidebar_pos = $instance_count++;
    }

      global $post;
        
        if (!empty($post) && isset($post->post_type) && $post->post_type == 'property') {
          $data = unserialize($post->post_content);
        } else {
          $data = array();
        }
        extract($args);
        
        $title = apply_filters('widget_title', empty($instance['title']) ? '&nbsp;' : $instance['title']);
        $submit_value = apply_filters('button', empty($instance['button']) ? 'Send' : $instance['button']);
        $email_label = apply_filters('email_label', !isset($instance['email_label']) ? 'Email Address (required)' : $instance['email_label']);
        $email_value = apply_filters('email_value', !isset($instance['email_value']) ? 'Email Address' : $instance['email_value']);
        $phone_label = apply_filters('phone_label', !isset($instance['phone_label']) ? 'Phone Number (required)' : $instance['phone_label']);
        $phone_value = apply_filters('phone_value', !isset($instance['phone_value']) ? 'Phone Number' : $instance['phone_value']);
        $name_label = apply_filters('name_label', !isset($instance['name_label']) ? 'Name (required)' : $instance['name_label']);
        $name_value = apply_filters('name_value', !isset($instance['name_value']) ? 'Name' : $instance['name_value']);
        $question_label = apply_filters('question_label', !isset($instance['question_label']) ? 'Any questions for us?' : $instance['question_label']);
        $container_class = apply_filters('container_class', empty($instance['container_class']) ? '' : $instance['container_class']);
        $inner_class = apply_filters('inner_class', empty($instance['inner_class']) ? '' : $instance['inner_class']);
        $inner_containers = apply_filters('inner_containers', empty($instance['inner_containers']) ? '' : $instance['inner_containers']);
        $textarea_container = apply_filters('textarea_container', !isset($instance['textarea_container']) ? $inner_containers : $instance['textarea_container']);
        $button_class = apply_filters('button_class', !isset($instance['button_class']) ? 'button-primary' : $instance['button_class']);
        
        $email_confirmation = apply_filters('email_confirmation', empty($instance['email_confirmation']) ? false : $instance['email_confirmation']);
        $send_to_email = apply_filters('send_to_email', !isset($instance['send_to_email']) ? '' : $instance['send_to_email']);

        $modern = ( isset($instance['modern']) && !empty($instance['modern']) ) ? 1 : 0;
        $show_property = ( isset($instance['show_property']) && !empty($instance['show_property']) ) ? 1 : 0;        
        $template_url = get_template_directory_uri();

        echo '<section class="side-ctnr placester_contact ' . $container_class . '">' . "\n";
        if ( $title ) {
          echo '<h3>' . $title . '</h3>';
        } 
          ?>
              <section class="<?php echo $inner_class; ?> common-side-cont clearfix">
                  <div class="msg">Thank you for the email, we\'ll get back to you shortly</div>
                  <form name="widget_contact" action="" method="post">
                  <?php
                  // For HTML5 enabled themes
                  if ( $modern == 0 ) { ?>
                    <?php echo empty($instance['inner_containers']) ? '' : '<div class="' . $instance['inner_containers'] .'">'; ?>

                    <label class="required" for="name"><?php echo $name_label; ?></label><input class="required" id="name" placeholder="<?php echo $name_value ?>" type="text" name="name" tabindex="<?php echo $sidebar_pos; ?>1" />
                    <?php echo empty($instance['inner_containers']) ? '' : '</div>'; ?>

                    <?php echo empty($instance['inner_containers']) ? '' : '<div class="' . $instance['inner_containers'] .'">'; ?>
                    <label class="required" for="email"><?php echo $email_label; ?></label><input class="required" id="email" placeholder="<?php echo $email_value ?>" type="email" name="email" tabindex="<?php echo $sidebar_pos; ?>2" />
                    <?php echo empty($instance['inner_containers']) ? '' : '</div>'; ?>

                    <?php if(isset($instance['phone_number'])) { ?>
                      <?php echo empty($instance['inner_containers']) ? '' : '<div class="' . $instance['inner_containers'] .'">'; ?>
                      <label class="required" for="phone"><?php echo $phone_label; ?></label><input class="required" id="phone" placeholder="<?php echo $phone_value ?>" type="text" name="phone" tabindex="<?php echo $sidebar_pos; ?>3" />
                      <?php echo empty($instance['inner_containers']) ? '' : '</div>'; ?>
                    <?php } ?>

                    <?php if($show_property == 1) : ?>
                      <?php $full_address = @self::_get_full_address($data); if(!empty($full_address)) : ?>
                        <?php echo empty($instance['inner_containers']) ? '' : '<div class="' . $instance['inner_containers'] .'">'; ?>
                        <label>Property</label><span class="info"><?php echo str_replace("\n", " ", $full_address); ?></span>
                        <?php echo empty($instance['inner_containers']) ? '' : '</div>'; ?>                      
                      <?php endif; ?>
                    <?php endif; ?>

                    <?php echo empty($instance['textarea_container']) ? '' : '<div class="' . $instance['textarea_container'] .'">'; ?>
                    <label for="question"><?php echo $question_label; ?></label><textarea rows="5" name="question" placeholder="<?php echo $question_label; ?>" tabindex="<?php echo $sidebar_pos; ?>4"></textarea>
                    <?php echo empty($instance['textarea_container']) ? '' : '</div>'; ?>

                    <input type="hidden" name="id" value="<?php if(isset($data['id'])) { echo $data['id']; } ?>">
                    <input type="hidden" name="fullAddress" value="<?php echo @self::_get_full_address($data);  ?>">
                    <input type="hidden" name="email_confirmation" value="<?php echo $email_confirmation;  ?>">
                    <input type="hidden" name="send_to_email" value="<?php echo $send_to_email;  ?>">
                  <?php } else { ?>
                    <input class="required" placeholder="<?php echo $email_label; ?>" type="email" name="email" tabindex="<?php echo $sidebar_pos; ?>1" />
                    <input class="required" placeholder="<?php echo $name_label; ?>" type="text" name="name" tabindex="<?php echo $sidebar_pos; ?>2" />

                    <?php if($show_property == 1) : ?>
                      <?php $full_address = @self::_get_full_address($data); if(!empty($full_address)) : ?>
                        <?php echo empty($instance['inner_containers']) ? '' : '<div class="' . $instance['inner_containers'] .'">'; ?>
                        <label>Property</label><span class="info"><?php echo str_replace("\n", " ", $full_address); ?></span>
                        <?php echo empty($instance['inner_containers']) ? '' : '</div>'; ?>                      
                      <?php endif; ?>
                    <?php endif; ?>

                    <textarea rows="5" placeholder="<?php echo $question_label; ?>" name="question" tabindex="<?php echo $sidebar_pos; ?>3"></textarea>
                    <input type="hidden" name="id" value="<?php echo @$data['id'];  ?>">
                    <input type="hidden" name="fullAddress" value="<?php echo @self::_get_full_address($data);  ?>">
                    <input type="hidden" name="email_confirmation" value="<?php echo $email_confirmation;  ?>">
                    <input type="hidden" name="send_to_email" value="<?php echo @$send_to_email;  ?>">
                  <?php } ?>
                    <input type="submit" value="<?php echo $submit_value; ?>" class="<?php echo $button_class; ?>" tabindex="<?php echo $sidebar_pos; ?>4" />
                  </form>
                <div class="placester_loading"></div>
              </section>  
              <div class="separator"></div>
            </section>
    <?php }

  /**
   * Compensate for different address fields in the API response
   * @param array $data
   * @return string
   */
  function _get_full_address($data) {
    if(isset($data['location']['full_address'])) {
      return $data['location']['full_address'];
    }
    elseif(isset($data['location']['address'])) {
      // TODO: Localize address formatting
      $address  = $data['location']['address'] . " \n";
      $address .= $data['location']['locality'] . ", " . $data['location']['region'] . " " . $data['location']['postal'] . " \n";
      $address .= $data['location']['country'];

      return $address;
    }
    else {
      return '';
    }
  }

  // }
} // End Class

// add_action('init', 'placester_contact_widget');
// // Style
// function placester_contact_widget() {
//     $myStyleUrl = WP_PLUGIN_URL . '/placester/css/contact.widget.ajax.css';
//     wp_enqueue_style( 'contactwidgetcss', $myStyleUrl );
//     $myScriptUrl = WP_PLUGIN_URL . '/placester/js/contact.widget.ajax.js';
//     wp_enqueue_script( 'contactwidgetjs', $myScriptUrl, array('jquery') );

//     // Get current page protocol
//     $protocol = isset( $_SERVER['HTTPS'] ) ? 'https://' : 'http://';

//     $params = array(
//         'ajaxurl' => admin_url( 'admin-ajax.php', $protocol ),
//     );
//     wp_localize_script( 'contactwidgetjs', 'contactwidgetjs', $params );
// }

// Ajax function
add_action( 'wp_ajax_placester_contact', 'ajax_placester_contact' );
add_action( 'wp_ajax_nopriv_placester_contact', 'ajax_placester_contact' );
function ajax_placester_contact() {
    
    if( !empty($_POST) ) {
      $error = "";
      $message = "A prospective client wants to get in touch with you. \n\n";

      // Check to make sure that the name field is not empty
      if( trim($_POST['name']) == '' || trim($_POST['name']) == 'Name' ) {
        $error .= "Your name is required<br/>";
      } else {
        $message .= "Name: " . trim($_POST['name']) . " \n";
      }

      // Check to make sure sure that a valid email address is submitted
      if( trim($_POST['email']) == '' || trim($_POST['email']) == 'Email Address' )  {
        $error .= "An email address is required<br/>";
      } else if ( !eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$", trim($_POST['email'])) ) {
        $error .= "A valid email address is required<br/>";
      } else {
        $message .= "Email Address: " . trim($_POST['email']) . " \n";
      }

      // Check to make sure that the last name field is not empty
      if (isset($_POST['phone'])) {
        if( trim($_POST['phone']) == '' || trim($_POST['phone']) == 'Phone Number' ) {
          $error .= "Your phone number is required<br/>";
        } else {
          $message .= "Phone Number: " . trim($_POST['phone']) . " \n";
        }
      }
      // Check the question field
      if( trim($_POST['question']) == '' ) {
        $question = "They had no questions at this time \n\n ";
      } else {
        $message .= "Questions: " . trim($_POST['question']) . " \n";
      }

      if( empty($_POST['id']) ) {
        $message .= "Listing ID: No specific listing \n";
      } else {
        $message .= "Listing ID: " . trim($_POST['id']) . " \n";
      }

      if( trim($_POST['fullAddress']) == '' ) {
        $message .= "Listing Address: No specific listing \n";
      } else {
        $message .= "Listing Address: " . $_POST['fullAddress'] . " \n";
      }

      $message .= "\n";
      $message .= "This message was sent from the contact form at: \n" . $_SERVER['HTTP_REFERER'] . " \n";

    if( empty($error) ) {

      $api_whoami = PLS_Plugin_API::get_user_details();
      $user_email = @pls_get_option('pls-user-email');

      // Check what email to send the form to...
      if ( !empty( $user_email ) ) {
        $email = $user_email;
      } elseif (!empty($api_whoami['user']['email'])) {
        $email = $api_whoami['user']['email'];
      } else {
        $email = $api_whoami['email'];
      }
      if (trim($_POST['send_to_email']) == true) {
        $email = $_POST['send_to_email'];
      }

      if (trim($_POST['email_confirmation']) == true) {
        wp_mail($email, 'Email confirmation was sent to ' . $_POST['email'] . ' from ' . home_url(), $message);
      } elseif ($email) {
        $placester_Mail = wp_mail($email, 'Prospective client from ' . home_url(), $message);
      }
      
      $name = $_POST['name'];
      PLS_Membership::create_person(array('metadata' => array('name' => $name, 'email' => $_POST['email'] ) )) ;


      if (trim($_POST['email_confirmation']) == true) {

        ob_start();
          include(get_template_directory() . '/custom/contact-form-email.php');
          $message_to_submitter = ob_get_clean();
              
        wp_mail( $_POST['email'], 'Form Submitted', $message_to_submitter );
      }
    
      echo "sent";
    } else {
      echo $error;
    }
    die;
  }
}
