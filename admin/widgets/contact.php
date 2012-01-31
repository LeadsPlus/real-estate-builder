<?php 
class Placester_Contact_Widget extends WP_Widget {

  function Placester_Contact_Widget() {
      $widget_ops = array(
          'classname' => 'Placester_Contact_Widget', 
          'description' => __( 'Works only on the Property Details Page.') 
      );
    $this->WP_Widget( 'Placester_Contact_Widget', 'Placester Property Contact Form', $widget_ops );
  }

  //Front end contact form
  function form($instance){
    //Defaults
    $instance = wp_parse_args( (array) $instance, array('title'=>'', 'modern' => 0) );

    $title = htmlspecialchars($instance['title']);

    extract($instance, EXTR_SKIP);

    $checked = $instance['modern'] == 1 ? 'checked' : '';

    // Output the options
    echo '<p><label for="' . $this->get_field_name('title') . '">' . __('Title:') . '</label><input class="widefat" type="text" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" value="' . $title . '" /></p>';

    echo '<p><input class="checkbox" type="checkbox" id="' . $this->get_field_id('modern') . '" name="' . $this->get_field_name('modern') . '"' . $checked . ' style="margin-right: 5px;"/><label for="' . $this->get_field_id('modern') . '">' . __('Use placeholders instead of labels') . '</label></p>';
?>
 <p style="font-size: 0.9em;">
    Warning: This widget is designed to be used to send queries about a certain listing and therefore only works on the Property Details Page.
</p>     
    <?php 
  }
  
  // Update settings
  function update($new_instance, $old_instance){
    $instance = $old_instance;
    $instance['title'] = strip_tags(stripslashes($new_instance['title']));
    $instance['modern'] = isset($new_instance['modern']) ? 1 : 0;
    return $instance;
  }
  
  // Admin widget
  function widget($args, $instance) {
    global $post;
    if(isset($post->post_type) && $post->post_type == 'property') {
        $data = placester_property_get($post->post_name);
    extract($args);
    $title = apply_filters('widget_title', empty($instance['title']) ? '&nbsp;' : $instance['title']);
    $modern = @$instance['modern'] ? 1 : 0;
    $template_url = get_bloginfo('template_url');

    // Create the title, if there is one

    // Create form
    echo '<section class="side-ctnr placester_contact">' . "\n";
    if ( $title ) echo '<h3>' . $title . '</h3>' . "\n";
?>
    <section class="common-side-cont clearfix">
        <form name="widget_contact" action="" method="post">
<?php
    // For HTML5 enabled themes
    if ( $modern == 0 ) {
?>
          <label class="required" for="email">Email Address (required)</label><input class="required" type="email" name="email"/>
          <label class="required" for="firstName">First Name (required)</label><input class="required" type="text" name="firstName"/>
          <label class="required" for="lastName">Last Name (required)</label><input class="required" type="text" name="lastName"/>
          <label for="question">Any questions for us?</label><textarea rows="5" name="question"></textarea>
          <input type="hidden" name="placesterEmail" value="<?php echo $data->contact->email;  ?>">
          <input type="hidden" name="id" value="<?php echo $data->id;  ?>">
          <input type="hidden" name="fullAddress" value="<?php echo $data->location->full_address;  ?>">
<?php
    } else {
?>
          <input class="required" placeholder="Email Address (required)" type="email" name="email"/>
          <input class="required" placeholder="First Name (required)" type="text" name="firstName"/>
          <input class="required" placeholder="Last Name (required)" type="text" name="lastName"/>
          <textarea rows="5" placeholder="Any questions for us?" name="question"></textarea>
          <input type="hidden" name="placesterEmail" value="<?php echo $data->contact->email;  ?>">
          <input type="hidden" name="id" value="<?php echo $data->id;  ?>">
          <input type="hidden" name="fullAddress" value="<?php echo $data->location->full_address;  ?>">
<?php 
    }
    // Submit button, success and error messages for ajax callback
?>
        <input type="submit" value="Send it" />
    </form>
    <div class="placester_loading"></div>
    </section>  
    <div class="msg">Thank you for the email, we\'ll get back to you shortly</div>
    <div class="separator"></div>
    </section>
<?php
        }
  }


} // End Class

add_action('init', 'placester_contact_widget');
// Style
function placester_contact_widget() {
    $myStyleUrl = WP_PLUGIN_URL . '/placester/css/contact.widget.ajax.css';
    wp_enqueue_style( 'contactwidgetcss', $myStyleUrl );
    $myScriptUrl = WP_PLUGIN_URL . '/placester/js/contact.widget.ajax.js';
    wp_enqueue_script( 'contactwidgetjs', $myScriptUrl, array('jquery') );

    // Get current page protocol
    $protocol = isset( $_SERVER['HTTPS'] ) ? 'https://' : 'http://';

    $params = array(
        'ajaxurl' => admin_url( 'admin-ajax.php', $protocol ),
    );
    wp_localize_script( 'contactwidgetjs', 'contactwidgetjs', $params );
}

// Ajax function
add_action( 'wp_ajax_placester_contact', 'ajax_placester_contact' );
function ajax_placester_contact() {
    if( !empty($_POST) ) {

      $error = "";

      // Check to make sure sure that a valid email address is submitted
      if( trim($_POST['email']) == '' )  {
        $error .= "An email address is required<br/>";
      } else if ( !eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$", trim($_POST['email'])) ) {
        $error .= "A valid email address is required<br/>";
      } else {
        $email = trim($_POST['email']);
      }

      // Check to make sure that the first name field is not empty
      if( trim($_POST['firstName']) == '' ) {
        $error .= "Your first name is required<br/>";
      } else {
        $firstName = trim($_POST['firstName']);
      }

      // Check to make sure that the last name field is not empty
      if( trim($_POST['lastName']) == '' ) {
        $error .= "Your last name is required<br/>";
      } else {
        $lastName = trim($_POST['lastName']);
      }

      // Check the question field
      if( trim($_POST['question']) == '' ) {
        $question = "They had no questions at this time";
      } else {
        $question = $_POST['question'];
      }

      // Check the hidden fields
      if( trim($_POST['placesterEmail']) == '' ) {
        $error .= "What do you think you're doing?";
      } else {
        $placesterEmail = $_POST['placesterEmail'];
      }

      if( trim($_POST['id']) == '' ) {
        $error .= "What do you think you're doing?";
      } else {
        $id = $_POST['id'];
      }

      if( trim($_POST['fullAddress']) == '' ) {
        $error .= "What do you think you're doing?";
      } else {
        $fullAddress = $_POST['fullAddress'];
      }

    if( empty($error) ) {

      $message  = "Someone wants to get in touch with you. \n\n" .
                  "Listing number: " . $id . "\n" .
                  "Listing address: " . $fullAddress . "\n\n" .
                  "Name: " . $firstName . " " . $lastName . "\n" .
                  "Email: " . $email . "\n" .
                  "Question: " . $question . "\n\n";
      // Mail it

      $placester_Mail = wp_mail($placesterEmail, 'Contact on listing', $message);

      echo "sent";
    } else {
      echo $error;
    }
    die;
  }
}
