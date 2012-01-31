<?php

/**
 * Admin interface: Contact tab
 * Utilities
 */

/**
 * Returns property value or null is it's not exists
 *
 * @param object $o
 * @param string $property
 * @return string
 */
function p($o, $property)
{
    if (!isset($o) || !isset($o->$property))
        return null;
    return $o->$property;
}



/**
 * Prints address fields in html <table> rows 
 * with possible validation error messages
 *
 * @param string $option_name_prefix
 * @param object $data
 * @param object $validation_data
 * @param string $property
 */
function row_address($option_name_prefix, $data, $validation_data, $property, 
    $description = '')
{
    $location_data = p($data, $property);
    $location_validation = p($validation_data, $property);
    if (is_array($location_validation))
        $location_validation = $location_validation[0];

    row_textbox('Address', $option_name_prefix . 'address', 
        $location_data, $location_validation, 'address', $description);
    row_textbox('Unit', $option_name_prefix . 'unit', 
        $location_data, $location_validation, 'unit');
    row_textbox('City', $option_name_prefix . 'city', 
        $location_data, $location_validation, 'city');
    row_textbox('State', $option_name_prefix . 'state', 
        $location_data, $location_validation, 'state');
    $default_country = get_option('placester_default_country'); 
    global $placester_countries;
    column_dropdown('Country', $placester_countries, $option_name_prefix . 'country', $location_data, $location_validation, 'country', '', 1, $default_country);
    row_textbox('Zip', $option_name_prefix . 'zip', 
        $location_data, $location_validation, 'zip');
}

/**
 * Prints dropdown with possible selected value $value.
 *
 * @param string $property_name
 * @param array $possible_values
 * @param string $value
 * @param string $width
 */
 function column_dropdown($label, $possible_values, $property_name, 
    $value_object, $validation_object, $property, $width = '', $colspan = '1', $default_value ='')
{
    $value = p($value_object, $property);

    $value = empty($value) ? $default_value : $value;
    ?>
    <th scope="row"><label for="<?php echo $property_name ?>"><?php echo $label ?></label></th>
    <td colspan="<?php echo $colspan ?>">
      <?php 
      control_dropdown($property_name, $possible_values, $value, $width);
      if (isset($validation_message))
      {
          echo '<br/><div class="placester_error">';
          echo $validation_message;
          echo '</div>';
      }
      ?>
    </td>
    <?php
}    

/**
 * Prints a dropdown
 */
function control_dropdown($property_name, $possible_values, $value, 
    $width = '')
{
    ?>
    <select class="heading form-input-tip" 
      name="<?php echo $property_name ?>" 
      id="<?php echo $property_name ?>" 
      style="width: <?php echo $width ?>" 
      class="heading form-input-tip">
    <?php

    foreach ($possible_values as $key => $name)
    {
        if (!is_array($value))
            $is_selected = ($key == $value);
        else
        {
            $is_selected = false;
            $possible_values_exploded = explode(',', $key);

            if ($possible_values_exploded == $value) {
                $is_selected = true;
            }
        }

        echo '<option value="' . $key . '"' . ($is_selected ? ' selected' : '') . 
            '>' . $name . '</option>';
    }

    echo '</select>';
}

/**
 * Prints image upload textbox in html <table> row
 * with possible validation error messages
 *
 * @param string $label
 * @param string $option_name
 * @param object $value_object, 
 * @param object $validation_objet
 * @param string $property
 */
function row_image($label, $option_name, $value_object, 
    $validation_objet, $property, $description = '')
{
    $id = p($value_object, $property);
    $img = '';
    if (strlen($id) > 0)
    {
        $thumbnail = wp_get_attachment_image_src($id, 'thumbnail');
        $img = '<img src="' . $thumbnail[0] . '" />';
    }

    ?>
    <tr valign="top">
      <th scope="row"><label><?php echo $label ?></label></th>
      <td>
        <input type="file" name="file" id="<?php echo $option_name ?>_file" 
          style="float: left" />
        <input type="button" id="<?php echo $option_name ?>_button" 
          class="button file_upload" value="Upload" style="float: left" />
        <input type="hidden" name="<?php echo $option_name ?>" 
          id="<?php echo $option_name ?>" value="<?php echo $id ?>" />
        <?php
        if (strlen($description) > 0)
            echo '<br class="clear" /><span class="description">' . $description . '</span>';
        ?>
      </td>
    </tr>
    <tr valign="top">
      <th></th>
      <td><div id="<?php echo $option_name ?>_thumbnail"><?php echo $img ?></div></td>
    </tr>
    <?php
}



/**
 * Prints textbox in html <table> row
 * with possible validation error messages
 *
 * @param string $label
 * @param string $option_name
 * @param object $value_object, 
 * @param object $validation_object
 * @param string $property
 */
function row_textbox($label, $option_name, $value_object, $validation_object, $property, $description = '')
{
    ?>
    <tr valign="top">
      <th scope="row"><label for="<?php echo $option_name ?>"><?php echo $label ?></label></th>
      <td>
        <input type="text" name="<?php echo $option_name ?>"
          id="<?php echo $option_name ?>"
          value="<?php echo htmlspecialchars(p($value_object, $property)) ?>" 
          class="heading form-input-tip" 
          style="width:100%" />
        <?php 
        $validation_message = p($validation_object, $property);
        if (is_array($validation_message))
            $validation_message = $validation_message[0];

        if (isset($validation_message))
        {
            echo '<br/><div style="color:red">';
            echo $validation_message;
            echo '</div>';
        }

        if (strlen($description) > 0)
            echo '<br /><span class="description">' . $description . '</span>';
        ?>
      </td>
    </tr>
    <?php
}



/**
 * Prints textarea in html <table> row
 * with possible validation error messages
 *
 * @param string $label
 * @param string $option_name
 * @param object $value_object, 
 * @param object $validation_object
 * @param string $property
 */
function row_textarea($label, $option_name, $value_object, 
    $validation_object, $property, $description = '')
{
    ?>
    <tr valign="top">
      <th scope="row"><label for="<?php echo $option_name ?>"><?php echo $label ?></label></th>
      <td>
        <textarea name="<?php echo $option_name ?>" rows="5" 
          class="heading form-input-tip" 
          style="width:100%"><?php 
          echo htmlspecialchars(p($value_object, $property)) 
          ?></textarea>
        <?php 
        $validation_message = p($validation_object, $property);
        if (isset($validation_message))
        {
            echo '<br/><div style="color:red">';
            echo $validation_message;
            echo '</div>';
        }

        if (strlen($description) > 0)
            echo '<br /><span class="description">' . $description . '</span>';
        ?>
      </td>
    </tr>
    <?php
}



/**
 * Combines company / user object with data posted by POST method
 * from client (as a result of data modification)
 *
 * @param object $company
 * @param object $user
 */
function details_compine_with_http(&$company, &$user)
{
    foreach ($_POST as $key => $value)
    {
        if (substr($key, 0, 14) == 'user_location_')
        {
            $subfield = substr($key, 14);
            $user->location->$subfield = trim( $value );
        }
        else if (substr($key, 0, 5) == 'user_')
        {
            $field = substr($key, 5);
            $user->$field = trim( $value );
        }
        if (substr($key, 0, 17) == 'company_location_')
        {
            $subfield = substr($key, 17);
            $company->location->$subfield = trim( $value );
        }
        else if (substr($key, 0, 8) == 'company_')
        {
            $field = substr($key, 8);
            $company->$field = trim( $value );
        }
    }
}



/**
 * Prints contact details html form
 * with possible validation error messages
 *
 * @param object $company
 * @param object $user
 * @param object $error_validation_data, 
 */
function details($company, $user, $error_validation_data)
{
    // if the user is currently using an agency_api key
    // we'll need to throw up a warning message on the user
    // details section of the contact information.
    // This retrieves the type of api we are currently
    // using. Either, "user" or "agency"
    $api_key_type = get_option( 'placester_api_key_type' );
    
    // default flag for warning message is false.
    $show_organization_warning = false;
    
    // if not a user api key, show warning message.
    if ($api_key_type && $api_key_type != "user") {
      $show_organization_warning = true;  
    }

    $v_company = new StdClass;
    $v_user = new StdClass;
    if (property_exists($error_validation_data, 'company'))
        $v_company = $error_validation_data->company;
    if (property_exists($error_validation_data, 'user'))
        $v_user = $error_validation_data->user;
    $api_key_type = get_option( 'placester_api_key_type' );
    ?>

    <div style="width: 49%; float: left; margin-right: 10px">
      <?php placester_postbox_header('Basic Details', null, $show_organization_warning); ?>
      <table class="form-table">
          <?php 
          row_textbox('Email', 'user_email', $user, $v_user, 'email',
              'The email address you wish to be contacted at by leads.' .
              'Depending on your theme, this email address and name will ' .
              'be used to be displayed outwardly to the public. '.
              'Check your theme for specific details'); 
          row_textbox('First Name', 'user_first_name', $user, $v_user, 'first_name');
          row_textbox('Last Name', 'user_last_name', $user, $v_user, 'last_name'); 
          ?>
      </table>    
      <p class="submit clear">
        <input type="submit" name="<?php echo CONTACT_SIGNUP_FORM ? 'signup_finish' : 'edit_finish' ?>" class="button-primary" 
          value="Save All Changes" />
      </p>        
      <?php placester_postbox_footer(); 
      ?>      
      <?php
        placester_postbox_header('Personal Details', null, $show_organization_warning); 
      ?>
      <table class="form-table">
          <?php 
          row_textbox('Phone', 'user_phone', $user, $v_user, 'phone',
              'Depending on your theme and it\'s settings, you may want ' .
              'to list your personal phone number for clients to call.'); 
          row_textbox('Website', 'user_website', $user, $v_user, 'website');
          /*
          row_address('user_location_', $user, $v_user, 'location',
              'Depending on your needs, your theme and it\'s settings you may ' .
              'wish to display a personal or secondary address as well. ' .
              'Enter that information here.'); 
          */
          row_textarea('Bio', 'user_description', $user, $v_user, 'description',
              'A short description of you.  Depending on your theme and it\'s ' .
              'settings this might be used throughout the site - typically on an ' .
              'about us or contact us page.'); 
          ?>
          <?php pls_dump($user);
          pls_dump($company); ?>
          <tr valign="top">
            <th scope="row"><label>Bio</label></th>
            <td>
              <?php if (isset($company->user) && isset($company->user->headshot)): ?>
                <img width=200 src="<?php echo $company->user->headshot; ?>" alt=""><br>  
              <?php else: ?>
                <strong>No Headshot Uploaded.</strong><a href="https://placester.com/user/profile">Upload a Headshot</a><br>
              <?php endif ?>
              <span class="description">Your default headshot set in your <a href="https://placester.com/user/login/">Placester account</a>. Set or change it <a href="https://placester.com/user/profile">here</a> (you'll need to login). If your current theme supports a headshot, you can override this headshot by uploading another in the theme settings menu.</span>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><label>Personal Headshot</label></th>
            <td>
              <?php if (isset($company->user) && isset($company->user->headshot)): ?>
                <img width=200 src="<?php echo $company->user->headshot; ?>" alt=""><br>  
              <?php else: ?>
                <strong>No Headshot Uploaded.</strong><a href="https://placester.com/user/profile">Upload a Headshot</a><br>
              <?php endif ?>
              <span class="description">Your default headshot set in your <a href="https://placester.com/user/login/">Placester account</a>. Set or change it <a href="https://placester.com/user/profile">here</a> (you'll need to login). If your current theme supports a headshot, you can override this headshot by uploading another in the theme settings menu.</span>
            </td>
          </tr>
        </table>
        <p class="submit">
           <input type="submit" name="<?php echo CONTACT_SIGNUP_FORM ? 'signup_finish' : 'edit_finish' ?>" 
               class="button-primary" value="Save All Changes" />
        </p>
        <?php placester_postbox_footer(); ?>
    </div>

    <div style="width: 49%; float: left; margin-right: 10px">

      <?php placester_postbox_header('Company'); ?>
    </span>
      <table class="form-table">
        <?php 
        row_textbox('Company Name', 'company_name', $company, $v_company, 'name',
            'The name of your company. These will be used by ' .
            'your theme to display to the public.'); 
        row_textbox('Phone', 'company_phone', $company, $v_company, 'phone',
            'The phone number of your company. ' .
            'Depending on your theme, this will be displayed publicly and ' .
            'thus will receive calls from time to time.'); 
        row_address('company_location_', $company, $v_company, 'location',
            'The address of your office. This could be the location of your ' .
            'office, just a mailing address or both.  To see how it\'s used.'); 
        
        row_textarea('Description', 'company_description', $company, $v_company, 
            'description',
            'A description of your company. Typically this will be used in ' .
            'the about us section and used to give potential clients a bit ' .
            'more information about your company.'); 
        ?>
        <tr valign="top">
          <th scope="row"><label>Company Logo</label></th>
          <td>
            <?php if (isset($company->logo)): ?>
              <img width=200 src="<?php echo $company->logo; ?>" alt=""><br>
            <?php else: ?>
              <strong>No Logo Uploaded.</strong> <a href="https://placester.com/company/settings">Upload a Logo</a><br>
            <?php endif ?>
            <span class="description">Your default logo set in your <a href="https://placester.com/user/login/">Placester account</a>. Set or change it <a href="https://placester.com/company/settings">here</a> (you'll need to login). If your current theme supports a logo, you can override this logo by uploading another in the theme settings menu.</span>
          </td>
        </tr>
      </table>
      <p class="submit">
          <input type="submit" name="<?php echo CONTACT_SIGNUP_FORM ? 'signup_finish' : 'edit_finish' ?>" 
              class="button-primary" value="Save All Changes" />
      </p>
      <?php placester_postbox_footer(); ?>
    </div>
    
    <?php  
}
