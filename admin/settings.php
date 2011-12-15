<?php

/**
 * Admin interface: Settings tab
 */

include_once(dirname(__FILE__) . '/../core/const.php');
include_once('settings_parts.php');

$default_map_center = 'Boston, MA';
$default_lat = 42.3584308;
$default_lng = -71.0597732;
$default_zoom = 13;
//
// Save values
//
if (array_key_exists('set_default', $_POST))
{
    foreach ($_POST as $key => $value)
    {
        if (substr($key, 0, 33) == 'placester_display_property_types_' ||
            substr($key, 0, 32) == 'placester_display_listing_types_' ||
            substr($key, 0, 31) == 'placester_display_zoning_types_' ||
            substr($key, 0, 33) == 'placester_display_purchase_types_')
        {}
        elseif (substr($key, 0, 10) == 'placester_')
            update_option($key, '');
    }

    update_option('placester_display_property_types', array());
    update_option('placester_display_listing_types', array());
    update_option('placester_display_zoning_types', array());
    update_option('placester_display_purchase_types', array());
    update_option('placester_map_center_address', $default_map_center);
    update_option('placester_center_longitude', $default_lng);
    update_option('placester_center_latitude', $default_lat);
    update_option('placester_map_zoom', $default_zoom);
}

if (array_key_exists('refresh_user_data', $_POST))
{
    placester_refresh_user_data();

    // Regular view
    try
    {
        placester_admin_actualize_company_user();
    }
    catch (Exception $e)
    {
        $error_message = $e->getMessage();
    }
    
    if ( isset($error_message) )
        placester_error_message($error_message);
    else
        placester_success_message('User data has been refreshed');
}

if (array_key_exists('remove', $_POST))
{
    placester_remove_listings();
}

if (array_key_exists('apply', $_POST))
{
    $placester_display_block_address_old = get_option( 'placester_display_block_address' );

    // Save previous activate account preference
    $placester_activate_client_accounts_old = get_option( 'placester_activate_client_accounts' );

    // Flag for showing success message
    $show_success_message = TRUE;
    
    if (isset($_POST['placester_api_key']) && $_POST['placester_api_key'] != get_option('placester_api_key') ) {
        placester_clear_cache();
        unset_all_featured_new_properties();
    }
    
    update_option('placester_list_searchable', '');   // clear value

    $placester_display_property_types = array();
    $placester_display_listing_types = array();
    $placester_display_zoning_types = array();
    $placester_display_purchase_types = array();
    $placester_display_listings_blog = false;
    $placester_display_block_address = false;
    $placester_activate_client_accounts = false;

    foreach ($_POST as $key => $value) {
        if (substr($key, 0, 33) == 'placester_display_property_types_')
            array_push($placester_display_property_types, substr($key, 33));
        elseif (substr($key, 0, 32) == 'placester_display_listing_types_')
            array_push($placester_display_listing_types, substr($key, 32));
        elseif (substr($key, 0, 31) == 'placester_display_zoning_types_')
            array_push($placester_display_zoning_types, substr($key, 31));
        elseif (substr($key, 0, 33) == 'placester_display_purchase_types_')
            array_push($placester_display_purchase_types, substr($key, 33));
        elseif (substr($key, 0, 10) == 'placester_') {
            update_option($key, $value);
        }  

        // If checked, make flags true so they won't be updated again
        // Necessary to set default value
        if ( $key == 'placester_display_listings_blog' ) {
            $placester_display_listings_blog = true;
        }

        if ( $key == 'placester_display_block_address' ) {
            $placester_display_block_address = true;
        }
        if ( $key == 'placester_activate_client_accounts' ) {
            $placester_activate_client_accounts = true;
        } 
        // if ( $key == 'placester_default_country' ) {
        //     $placester_display_exact_address = true;
        // }

    }

    placester_admin_actualize_company_user();

    cut_if_fullset($placester_display_property_types, $placester_const_property_types);
    cut_if_fullset($placester_display_listing_types, $placester_const_listing_types);
    cut_if_fullset($placester_display_zoning_types, $placester_const_zoning_types);
    cut_if_fullset($placester_display_purchase_types, $placester_const_purchase_types);

    update_option('placester_display_property_types', $placester_display_property_types);
    update_option('placester_display_listing_types', $placester_display_listing_types);
    update_option('placester_display_zoning_types', $placester_display_zoning_types);
    update_option('placester_display_purchase_types', $placester_display_purchase_types);
    if ( !$placester_display_listings_blog )
        delete_option('placester_display_listings_blog');
    if ( !$placester_display_block_address )
        delete_option('placester_display_block_address');

    // Change in this option requires deleting property posts
    if ( $placester_display_block_address != $placester_display_block_address_old ) {
        global $wpdb;

        $sql = $wpdb->get_results(
            'DELETE ' .
            'FROM ' . $wpdb->prefix . 'posts ' .
            "WHERE post_type = 'property' ");
    }

    if ( !$placester_activate_client_accounts )
        delete_option('placester_activate_client_accounts');

    if ( $placester_activate_client_accounts_old != $placester_activate_client_accounts ) {
        if ( $placester_activate_client_accounts ) {
            // Get the default administrator role.
            $role =& get_role( 'administrator' );
            // Add forum capabilities to the administrator role. 
            if ( !empty( $role ) ) {
                $role->add_cap( 'add_roomates' );
                $role->add_cap( 'read_roomates' );
                $role->add_cap( 'delete_roomates' );
                $role->add_cap( 'add_favorites' );
                $role->add_cap( 'delete_roomates' );
            }
            // Create the "Property lead" role
            $lead_role = add_role( 
                'placester_lead',
                'Property Lead',
                array(
                    'add_roomates' => true,
                    'read_roomates' => true,
                    'delete_roomates' => true,
                    'add_favorites' => true,
                    'delete_roomates' => true,
                    'level_0' => true,
                    'read' => true
                )
            );
        } else {
            // Get the default administrator role.
            $role =& get_role( 'administrator' );
            // Remove lead capabilities to the administrator role. 
            if ( !empty( $role ) ) {
                $role->remove_cap( 'add_roomates' );
                $role->remove_cap( 'read_roomates' );
                $role->remove_cap( 'delete_roomates' );
                $role->remove_cap( 'add_favorites' );
                $role->remove_cap( 'delete_roomates' );
            }
            // Create the "Property lead" role
            $roles_to_delete = array(
                'placester_lead',
            );

            // Delete the roles with no users
            foreach ( $roles_to_delete as $role ) {
                $users = get_users( array( 'role' => $role ) );

                if ( count($users) <= 0 ) {
                    remove_role( $role );
                }
            }
        }
    }

    // Update property urls
    if (!empty($api_key))
    {
        $url = placester_get_property_url('{id}');
        $filter = array();
        placester_add_admin_filters($filter);
        placester_property_seturl_bulk($url, $filter);
    }
}

    function myplugin_addbuttons() {
        add_filter('mce_external_plugins', "tinyplugin_register");
    }

    function tinyplugin_register($plugin_array)
    {
        $plugin_array["tinyplugin"] = 
            plugins_url('/js/admin.settings.tinymce.js', dirname(__FILE__));
        return $plugin_array;
    }

    myplugin_addbuttons();

    if (function_exists('wp_tiny_mce')) {

     wp_tiny_mce(false, array(
        "editor_selector" => "form-input-tip",
        'width' => '100%',
        'theme_advanced_buttons1' => 'tinyplugin,formatselect,bold, italic, underline, separator, bullist, numlist,justifyleft, justifycenter, justifyright, link, unlink',
        'theme_advanced_buttons2' => 'bedrooms, bathrooms, price, available_on, address, city, state, zip',
        'theme_advanced_buttons3' => '',
        'theme_advanced_buttons4' => '',

     ));
    }


?>
<div class="wrap">
  <?php if (isset($show_success_message)) { placester_success_message("Your settings have been successfully saved"); } ?>
  <?php placester_admin_header('placester_settings') ?>
  <form method="post" action="admin.php?page=placester_settings" id="placester_form">
    <?php placester_postbox_container_header(); ?>

    <?php placester_postbox_header('API Key'); ?>
    <table class="form-table">
      <?php 
      $api_key_type = get_option( 'placester_api_key_type' );
      $label = ( $api_key_type ) ? "API Key<br /><span style='margin-top: 8px; float:left; font-size: 0.9em;'>Current: " . ucfirst($api_key_type) . " API Key</span>" : "API Key";
      row_textbox($label, 'placester_api_key',
          'This is your API key from <a href="http://placester.com">Placester</a>, ' .
          'a company dedicated to making real estate marketing painless.<br/>' .
          'If you have an API key (found on your ' .
          '<a href="http://placester.com/user/apikeys/">Placester account management page</a>' .
          ') copy and paste it in above.<br />' .
          'If you don\'t have an API key, don\'t worry. Just fill out the ' .
          'fields on the <a href="admin.php?page=placester_contact">Contact</a> page '.
          'and one will be generated automatically (and for free).'); 
      ?>
    </table>
    <p class="submit">
      <input type="submit" name="apply" class="button-primary" 
        value="Save All Changes" />
    </p>
    <?php placester_postbox_footer(); ?>

    <?php placester_postbox_header('Regenerate All Listings'); ?>
    <table class="form-table">
      <?php 
      row_hidden('Regenerate listings', 'placester_remove_listings',
          'If you would like to remove and regenerate all listings click "Regenerate Listings" below. ' . 
          'These listings will regenerate as they come up again in the search results.'); 
      ?>
    </table>
    <p class="submit">
      <input type="submit" name="remove" class="button-primary" 
        value="Regenerate All Listings" />
    </p>
    <?php placester_postbox_footer(); ?>
    <?php placester_postbox_header('Refresh User Data'); ?>
      <table class="form-table">
           <?php 
            row_hidden('Refresh User Data', 'refresh_user_data',
            'This will refresh all your user data with information from your Placester <a href="https://placester.com/user/profile"> Profile</a>. It will overwrite what you\'ve entered here, and download the logo or headshot you\'ve uploaded in Placester.'); 
          ?>
      </table>
      <p>
        <span class="submit">
            <input type="submit" name="refresh_user_data" id="refresh_user_data" class="button-primary" value="Refresh All User Data" />
        </span>            
      </p>
      
      <?php placester_postbox_footer(); ?>

    <?php placester_postbox_header('Display only'); ?>
    <div>
      <?php
      row_checkboxes('Property Types', $placester_const_property_types, 
        'placester_display_property_types');
      row_checkboxes('Listing Types', $placester_const_listing_types, 
        'placester_display_listing_types');
      row_checkboxes('Zonings', $placester_const_zoning_types, 
        'placester_display_zoning_types');
      row_checkboxes('Purchase Types', $placester_const_purchase_types, 
        'placester_display_purchase_types');
      ?>
    </div>
    <table class="form-table">
      <tr>
        <td>
          <span class="description">
            Display only settings will "pre-filter" all your listings so only those 
            meeting the criteria set on the left are returned.  This is helpful if you'd 
            like to create multiple sites for different types of listings. 
            Often, you'll want to customize the look and feel of a site to be 
            more appealing to prospective clients who are interested certain type 
            of listing.
          </span>
        </td>
      </tr>
    </table>
    <div style="clear: both"></div>
    <p class="submit">
      <input type="submit" name="apply" class="button-primary" 
        value="Save All Changes" />
    </p>        
    <?php placester_postbox_footer(); ?>

    <?php placester_postbox_header('General'); ?>
    <table class="form-table">
      <?php 
      row_textbox('URL slug', 'placester_url_slug',
          'The url slug forces a keyword or a series of keywords into the url ' .
          'of each property details page. The structure of the url is ' .
          'important for indicating what a specific page does. <br />' .
          '<strong>Tip:</strong> If your unsure what to use, try "listing" ' .
          'or "property".<br /><br />'.
          'Depending on your <a href="options-permalink.php">permalink settings</a>:' .
          '<br />' . get_bloginfo('url') . '/<span id="url_target" ' .
          'style="font-weight: bold;"></span>/4d6e805aabe10f0f1500004c');

      row_checkbox('Display listings on blog page', 'placester_display_listings_blog'); 
      ?>
    </table>
    <p class="submit">
      <input type="submit" name="apply" class="button-primary" 
        value="Save All Changes" />
    </p>
    <?php placester_postbox_footer(); ?>

    <?php placester_postbox_header('Client accounts'); ?>
    <table class="form-table">
      <?php 
      row_checkbox('Activate client accounts', 'placester_activate_client_accounts', 'Checking this would allow users to register lead accounts.'); 
      $client_info = 'You can now make use of the <strong>[favorite_link_toggle]</strong> and <strong>[lead_user_navigation]</strong> shortcodes. <br /> ' .
          'To use them, just add <strong>do_shortcode("[shortcode_name]");</strong> to your theme.<br />' . 
          'The <strong>Placester Property Favorites</strong> widget is active. It will display a list of favorite widgets when the lead is logged in.'; 
      echo "<tr class='extra_info' style='display:none;color:#666;'><th scope='row'></th><td><p>{$client_info}</p></td></tr>";
      ?>
    </table>
    <p class="submit">
      <input type="submit" name="apply" class="button-primary" 
        value="Save All Changes" />
    </p>
    <?php placester_postbox_footer(); ?>

    <?php placester_postbox_header('Other'); ?>
    <table class="form-table">
      <?php 
      row_dropdown('Default country', 'placester_default_country', $placester_countries, 'US', 'The country that will be selected by default on the "Add listing" page.'); 
      row_checkbox('Display block listings address', 'placester_display_block_address', 'Checking this would display the block address instead of the exact address.'); 

      $latlong = '<label style="width: 100px; float: left;" for="placester_center_latitude">Latitude</label><input type="text" id="placester_center_latitude" value="' . get_option('placester_center_latitude', $default_lat) . '" name="placester_center_latitude" readonly="readonly"><br />';
      $latlong .= '<label style="width: 100px; float: left;" for="placester_center_longitude">Longitude</label><input type="text" id="placester_center_longitude" value="' . get_option('placester_center_longitude', $default_lng) . '" name="placester_center_longitude" readonly="readonly"><br />';
      $latlong .= '<label style="width: 100px; float: left;" for="placester_map_zoom">Map Zoom</label><input type="text" value="' . get_option('placester_map_zoom', $default_zoom) . '" id="placester_map_zoom" name="placester_map_zoom" readonly="readonly"><br />';

      $latlong = '<div style="margin-top: 10px">' . $latlong . '</div>';

      row_textbox(
          'Map center address',
          'placester_map_center_address',
          '<div><div id="map" style="width: 450px; float: left; height: 250px; margin:  0 10px 0 0; border: 1px solid #ddd;"></div>' . $latlong . '</div>',
          $default_map_center
      );
      ?>

    </table>
    <p class="submit">
      <input type="submit" name="apply" class="button-primary" 
        value="Save All Changes" />
    </p>
    <?php placester_postbox_footer(); ?>

    <p>
    <span class="submit">
      <input type="submit" name="set_default" class="button" 
        value="Revert all settings to defaults" />
    </span>
  
    </p>
    <?php placester_postbox_container_footer(); ?>
  </form>
</div>
    

