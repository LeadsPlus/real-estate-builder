<?php

/**
 * Common plugin utilities
 */

/**
 * Returns slug of property posts
 *
 * @return string
 */
function placester_post_slug()
{
    $url_slug = get_option('placester_url_slug');
    if (strlen($url_slug) <= 0)
        $url_slug = 'listing';
    return $url_slug;
}


/**
 * "No API Key" exception 
 * 
 * @internal
 */
class PlaceSterNoApiKeyException extends Exception
{
    /*
     * Constructor
     *
     * @param string $message
     */
    function __construct($message)
    {
        parent::__construct($message);
    }
}


/**
 * Returns API key. throws exception if not set
 *
 * @return string
 */
function placester_get_api_key()
{
    $api_key = get_option('placester_api_key');
    if (strlen($api_key) <= 0)
        throw new PlaceSterNoApiKeyException('API key not specified on settings page');
    return $api_key;
}



/**
 * Returns if API key is specified
 *
 * @return bool
 */
function placester_is_api_key_specified()
{
    try
    {
        placester_get_api_key();
        return true;
    }
    catch (PlaceSterNoApiKeyException $e) 
    {}
  
    return false;
}



function placester_get_post_id( $property_id ) {
    global $wpdb;

    $sql = $wpdb->prepare(
        'SELECT ID, post_modified ' .
        'FROM ' . $wpdb->prefix . 'posts ' .
        "WHERE post_type = 'property' AND post_name = %s " .
        'LIMIT 0, 1', $property_id);

    $row = $wpdb->get_row($sql);
    $post_id = 0;
    if ($row) {
        $post_id = $row->ID;
        $modified_timestamp = strtotime($row->post_modified);
        if ($modified_timestamp > time() - 3600 * 48)
            return $post_id;
    }

    try {
        $data = placester_property_get($property_id);
        if($data) {
            $post = array(
                 'post_type'   => 'property',
                 'post_title'  => $data->location->full_address,
                 'post_name'   => $property_id,
                 'post_status' => 'publish',
                 'post_author' => 1,
                 'post_content'=> json_encode($data),
                 'filter'      => 'db'
              );

            if ($post_id <= 0)
                $post_id = wp_insert_post($post);
            else
            {	
                $post['ID'] = $post_id;
                $post_id = wp_update_post($post);
            }
        }
    }
    catch (Exception $e) {
    }

    return $post_id;
}

/* 
 * Updates the listing post type in the Wordpress database
 * If the post does not exist, it gets added.
 *
 * @param string $property_id - the Placester property id
 * @return int - the database row id
 */
function placester_update_listing( $property_id ) {
	global $wpdb;

    $sql = $wpdb->prepare(
        'SELECT ID, post_modified ' .
        'FROM ' . $wpdb->prefix . 'posts ' .
        "WHERE post_type = 'property' AND post_name = %s " .
        'LIMIT 0, 1', $property_id);
 
	$row = $wpdb->get_row($sql);
    $post_id = 0;
    if ($row)
        $post_id = $row->ID;

	try {
        $data = placester_property_get($property_id);
        if($data) {
            $post = array(
                 'post_type'   => 'property',
                 'post_title'  => $data->location->full_address,
                 'post_name'   => $property_id,
                 'post_status' => 'publish',
                 'post_author' => 1,
                 'post_content'=> json_encode($data),
                 'filter'      => 'db'
              );

            if ($post_id <= 0)
                $post_id = wp_insert_post($post);
            else
            {	
                $post['ID'] = $post_id;
                $post_id = wp_update_post($post);
            }
        } 
    }
    catch (Exception $e) {
    }

    return $post_id;
}



/**
 * Returns property of object (1 level)
 *
 * @param object $o
 * @param string $property
 * @return string
 * @internal
 */
function placester_p1($o, $property)
{
    if (!property_exists($o, $property))
        return '';
    return $o->$property;
}



/**
 * Returns property of object (via 2 levels)
 *
 * @param object $o
 * @param string $property1
 * @param string $property2
 * @return string
 * @internal
 */
function placester_p2($o, $property1, $property2)
{
    if (!property_exists($o, $property1))
        return '';
    $o = $o->$property1;
    if (!property_exists($o, $property2))
        return '';
    return $o->$property2;
}



/**
 * Returns property of object (via 3 levels)
 *
 * @param object $o
 * @param string $property1
 * @param string $property2
 * @param string $property3
 * @return string
 */
function placester_p3($o, $property1, $property2, $property3)
{
    if (!property_exists($o, $property1))
        return '';
    $o = $o->$property1;
    if (!property_exists($o, $property2))
        return '';
    $o = $o->$property2;
    if (!property_exists($o, $property3))
        return '';
    return $o->$property3;
}



/**
 * Expands template with parameter values.
 * Each [field_name] replaced with value of that field
 *
 * @param string $template
 * @param object $i
 * @return string
 */
function placester_expand_template($template, $i)
{
    $field_names =
        array
        (
            "/\\[available_on\\]/",
            "/\\[bathrooms\\]/",
            "/\\[bedrooms\\]/",
            "/\\[contact.email\\]/",
            "/\\[contact.phone\\]/",
            "/\\[description\\]/",
            "/\\[half_baths\\]/",
            "/\\[id\\]/",
            "/\\[location.address\\]/",
            "/\\[location.city\\]/",
            "/\\[location.coords.latitude\\]/",
            "/\\[location.coords.longitude\\]/",
            "/\\[location.state\\]/",
            "/\\[location.unit\\]/",
            "/\\[location.zip\\]/",
            "/\\[price\\]/",
            "/\\[url\\]/"
        );
    $field_values =
        array
        (
            placester_p1($i, 'available_on'),
            placester_p1($i, 'bathrooms'),
            placester_p1($i, 'bedrooms'),
            placester_p2($i, 'contact', 'email'),
            placester_p2($i, 'contact', 'phone'),
            placester_p1($i, 'description'),
            placester_p1($i, 'half_baths'),
            placester_p1($i, 'id'),
            placester_p2($i, 'location', 'address'),
            placester_p2($i, 'location', 'city'),
            placester_p3($i, 'location', 'coords', 'latitude'),
            placester_p3($i, 'location', 'coords', 'longitude'),
            placester_p2($i, 'location', 'state'),
            placester_p2($i, 'location', 'unit'),
            placester_p2($i, 'location', 'zip'),
            placester_p1($i, 'price'),
            placester_get_property_url(placester_p1($i, 'id'))
        );                   

    $values = array();
    foreach ($field_values as $v) 
        $values[count($values)] = preg_replace("/[$]/", "\\\\$", $v);

    $output = preg_replace($field_names, $values, $template);

    return $output;
}



global $placester_warn_on_api_key;



/**
 * Prints warning message if API key not set
 */
function placester_warn_on_api_key()
{
    if (!placester_is_api_key_specified())
    {
        global $placester_warn_on_api_key;
        if (!$placester_warn_on_api_key)
        {
            $placester_warn_on_api_key = true;
            echo '<div style="color: red; border: 1px solid red; padding: 10px">';
            echo 'API key not specified';
            echo '</div>';
        }

        return true;
    }

    return false;
}



/**
 * Returns URL of property page
 *
 * @return string
 */
function placester_get_property_url($id) {
    global $placester_post_slug;
    global $wp_rewrite;

    if ($wp_rewrite->using_permalinks()) {
        return site_url() . '/' . $placester_post_slug . '/' . $id;
    }


    return site_url() . '/?post_type=property&property=' . $id;
}

/**
 * Adds filters to property list request specified in admin section.
 * If a filter has been already set, it makes sure that it does not include the 
 * excluded fields from admin.
 *
 * @param array $filter The filters defined until this point.
 */
function placester_add_admin_filters( &$filter ) {

    /** Define an anonymous function that preserves the subset of allowed 
     * filters for a given custom filter array. */
    $add_admin_filter_by_type = create_function(
        '$types, &$filter_types',
        'if ( isset( $filter_types ) && ! empty( $filter_types ) ) {
            if ( ! empty( $types ) && is_array( $types ) ) {
                foreach ( $filter_types as $key => $type )
                    if ( ! in_array( $type, $types ) )
                        unset( $filter_types[$key] );
            }
            return;
        } 
        $filter_types = $types;'
    );

    /** 
     * TODO: API requires a property_type string, not a property_types array, 
     * so not sure what is the deal with this.
     */
    $property_types = get_option('placester_display_property_types');
    $add_admin_filter_by_type( $property_types, $filter['property_types'] );

    $listing_types = get_option('placester_display_listing_types');
    $add_admin_filter_by_type( $listing_types, $filter['listing_types'] );

    $zoning_types = get_option('placester_display_zoning_types');
    $add_admin_filter_by_type( $zoning_types, $filter['zoning_types'] );

    $purchase_types = get_option('placester_display_purchase_types');
    $add_admin_filter_by_type( $purchase_types, $filter['purchase_types'] );
}

/**
 * Returns IDs of properties marked as "New"
 *
 * @return array
 */
function placester_properties_new_ids()
{
    $new_ids = get_option('placester_properties_new_ids');
    if (!is_array($new_ids))
        $new_ids = array();
    return $new_ids;
}


/**
 * Returns IDs of properties marked as "Featured"
 *
 * @return array
 */
function placester_properties_featured_ids()
{
    $featured_ids = get_option('placester_properties_featured_ids');
    if (!is_array($featured_ids))
        $featured_ids = array();

    return $featured_ids;
}



/*
 * Resets all featured/new settings
 */
function unset_all_featured_new_properties() {

        $v = array();
        update_option('placester_properties_featured_ids', $v);
        update_option('placester_properties_new_ids', $v);    
}


/**
 * Returns value of property, when property can be "property1.property2"
 * meaning $o->property1->property2 value
 *
 * @return string
 */
function placester_get_property_value($o, $property_name)
{
    $parts = explode('/', $property_name);
    for ($n = 0; $n < count($parts) - 1; $n++)
    {
        $p = $parts[$n];
        if (!isset($o->$p))
            return null;

        $o = $o->$p;
    }

    $p = $parts[count($parts) - 1];
    if (!isset($o->$p))
        return null;

    return $o->$p;
}



/**
 * Returns the variable if set, null otherwise
 * 
 * @param mixed $var 
 * @return mixed
 */
function placester_get_if_set( $var, $null = false ) {
    if ( isset( $var ) )
        return $var;
    elseif ( $null ) 
        return null;
    else 
        return '';
}

/**
 * Sets value of property, when property can be "property1.property2"
 * meaning $o->property1->property2 value
 *
 * @return string
 */
function placester_set_property_value($o, $property_name, $value)
{
    $parts = explode('/', $property_name);
    $my = $o;

    for ($n = 0; $n < count($parts) - 1; $n++)
    {
        $p = $parts[$n];
        if (!isset($my->$p))
            $my->$p = new StdClass;
        $my = $my->$p;
    }

    $p = $parts[count($parts) - 1];
    $my->$p = $value;
}



/**
 * Cuts empty entries of array
 */
function placester_cut_empty_fields(&$request)
{
    foreach ($request as $key => $value)
    {
        if (empty($request[$key]))
            unset($request[$key]);
    }
}

/**
 * Checks if user is registered
 *
 * @return bool
 */
 function is_user_registered ()
 {
     $api_key = get_option('placester_api_key');
     
    if (strlen($api_key) <= 0) {
         return FALSE;
     } else {
         return TRUE;
     }
     
 }

 
 /**
  *     Checks to see if the user has a provider
  */
function placester_provider_check()
{
    $api_key = get_option('placester_api_key');

    if (strlen($api_key) > 0) {
        $api_key_info = placester_apikey_info($api_key);
        // var_dump($api_key_info);
        if (!empty($api_key_info) && isset($api_key_info->provider)) {
            return array("name" => $api_key_info->provider->name, "url" => $api_key_info->provider->url);
        } else {
            return false;
        }
        
    }
}

/**
 *      Checks to see if the company is_verified
 *      Displays a warning message if not.
 */
function placester_verified_check()
{
    $api_key = get_option('placester_api_key');

    if (strlen($api_key) > 0) {
        $api_key_info = placester_apikey_info($api_key);
        // var_dump($api_key_info);
        if (!empty($api_key_info) && !$api_key_info->is_verified && current_user_can( 'edit_plugins' )) {
            echo '<div class="updated inline"><p>You don\'t have enough contact information in your account to distribute your listings around the web. Placester requires you to verify your email address and enter a phone number so leads have accurate contact information.  Enter that information here: <input type="button" class="button " value="Contact Settings" onclick="document.location.href = \'/wp-admin/admin.php?page=placester_contact\';">. If you\'ve already entered that information, your account will be verified in 24 hours.</p></div>';        
        } 
    }
}

/**
 * Prints the lead invititation aleterts
 * 
 */
add_action('admin_notices', 'placester_lead_invite_message', 10); 
function placester_lead_invite_message() {
    if ( current_user_can( 'placester_lead' ) ) {
        $current_user = wp_get_current_user();
        // TODO this method does not work when the user changes his email
        $invitations = get_transient( 'pl_rm_invite_' . $current_user->user_email );
        if ( $invitations && is_array( $invitations ) ) {
            foreach( $invitations as $inviter_id => $value ) {
                $inviter = get_userdata( $inviter_id );
                placester_warning_message( $inviter->user_nicename . ' wants to add you as a roommate. <a href="#" class="accept-invite button" style="margin-left: 15px;">Accept</a><a href="#" class="decline-invite button" style="margin-left: 15px;">Decline</a>', $inviter->ID, false);
            }
        }

    }
}

add_action('admin_notices', 'placester_theme_compatibility',10); 
function placester_theme_compatibility() 
{

    $api_key = get_option( 'placester_api_key' );
        if ( empty( $api_key ) ) {
            placester_warning_message(
                'Thanks for installing the Placester Plugin. <strong>To get started you need to add an email address</strong> to the <a href="admin.php?page=placester_contact">Contact Information</a> tab.',
                'warning_no_api_key', true, null, null, true);
        }
            
}


// function placester_check_theme ()
// {
//     $path = pathinfo(get_bloginfo('template_directory'));
    
//     $all_files = recursive_directory_search("../wp-content/themes/" . $path['filename']);
    
//     $theme->hash = @md5(implode($all_files, ' '), 0 );
//     $theme->domain = $_SERVER['HTTP_HOST'];
//     $theme->name = pathinfo(get_bloginfo('template_directory'));

    
//     placester_theme_check($theme);
    
// }
//add_action("switch_theme", 'placester_check_theme', 1);

function recursive_directory_search( $path = '.')
{ 
    $files = array();
    if ($dh = opendir($path)) {
        while( false !== ( $file = readdir( $dh ) ) ){ 
            if($file !== "." && $file !== ".." && is_dir($path . "/" . $file) ) {
                $files[] = recursive_directory_search($path . "/" . $file); 
            } else { 
                if ($file !== "." && $file !== "..") {
                    $files[] = $file . ' ' . filemtime($path . "/" . $file); 
                }         
            }
        } 
        closedir( $dh ); 
    }
    return $files;
}



/**
 * Returns list of craigslist templates
 *
 * @return array
 */
function placester_get_templates() {
    $base_url = plugins_url( '/templates', dirname(__FILE__) );
    $dir = opendir( dirname( __FILE__ ) . '/../templates' );

    $files = array();
    while ( $file = readdir( $dir ) ) {
        if ( file_exists( dirname( __FILE__ ) . '/../templates/' . $file . '/template.php' ) )
            $files[] = $file;
    }

    sort( $files );

    $templates = array();
    foreach ( $files as $file ) {
        $url = $base_url . '/' . $file;
        $templates[] = array( 
            'name' => $file,
            'system_name' => $file,
            'active' => placester_is_template_active( $file ),
            'thumbnail_url' => $url . '/thumbnail.png' );
    }

    closedir($dir);

    $user_query = new WP_Query();
    $user_query->query( 'post_type=placester_template&nopaging=true&orderby=title&order=ASC' );

    while( $user_query->have_posts() ) {
        global $post;
        $user_query->the_post();
        $templates[] = array( 
            'name' => $post->post_title,
            'system_name' => 'user_' . $post->ID,
            'active' => true,
            'thumbnail_url' => get_post_meta( $post->ID, 'thumbnail_url', true ) );
    }

    return $templates;
}



function placester_get_template_content( $name ) {
    $content = '';
    if ( substr( $name, 0, 5 ) == 'user_') {
        $post_id = substr( $name, 5 );
        $post = get_post( $post_id );
        $content = $post->post_content;

        $thumbnail_url = get_post_meta( $post_id, 'thumbnail_url', true);
    } else {
        $template_url = plugins_url( '/templates/' . $name, dirname( __FILE__ ) );
        $filename = dirname( __FILE__ ) . '/../templates/' . $name . '/template.php';
        $thumbnail_url = plugins_url( '/templates/' . $name . '/thumbnail.png', 
            dirname( __FILE__ ) );

        ob_start();
        require( $filename );
        $content = ob_get_contents();
        ob_end_clean();
    }

    return array( $content, $thumbnail_url );
}

add_action('pre_get_posts', 'placester_description_filter');

function placester_description_filter() {

    global $post;
    
    if ( isset( $post ) && $post->post_type == 'property' ) {
        if(is_home())
            add_filter('the_content', 'listing_basic_details');
        elseif(is_single())
            add_filter('the_content', 'single_page_details');    
    }
    
}

function single_page_details() {
    global $post;

    if($post->post_type == 'property') {
        $content = get_option('placester_listing_layout');
        if(isset($content) && $content != '') return $content;

        $data = json_decode(stripslashes($post->post_content));
        $user_details = placester_get_user_details();
        $post_content = '<div class="single-attachment">' .
            '<div id="content" role="main" >
                <div class="about-user-wrapper" style="float: right; width: 300px">
                    <div class="about-user-content">
                        <div class="about-user-details">
                            <h3>Call For More Info:</h3>'
                            . do_shortcode('[logo]') .                        
                            '<p>' . do_shortcode('[first_name]') . ' ' . do_shortcode('[last_name]') . '<br />'
                                . $user_details->phone . '<br />'
                               . do_shortcode('[email]') .
                            '<p>' . do_shortcode('[user_address]') . '<br />' . do_shortcode('[user_city]') . ", " . do_shortcode('[user_state]') . " " . do_shortcode('[user_zip]') . '</p>
                        </div>
                    </div>
                </div>
                <div class="property-location-wrapper">
                    <h3>Property Location:</h3>
                    <div class="property-address" style="float: left">
                        <p>
                            ' . do_shortcode('[listing_address]') . '<br />'
                            . do_shortcode('[listing_city]') . ', ' . do_shortcode('[listing_state]') . ' ' . do_shortcode('[listing_zip]') . '
                        </p>
                        '. listing_beds_baths_price().'
                    </div>
                </div>
                <div class="basic-property-details-wrapper" style="float: left; clear: both;">
                    <div class="basic-property-description">
                        <h3>Property Description</h3>'
                        . do_shortcode('[listing_description]') .
                    '</div>
                    <div class="basic-property-images">'
                         . do_shortcode('[listing_images]') .
                    '</div>
                </div></div></div>
            <div class="cl"></div>
        ';
        
        return $post_content;
    } else {
    return $post->post_content;
    }
}

function listing_basic_details() {
    global $post;

    if($post->post_type == 'property') {

        $content = get_option('placester_snippet_layout');
        if(isset($content) && $content != '') return $content;
            
        $post_content = '<div><div style="float:left; padding-right: 40px;">' . do_shortcode('[listing_image]') . '</div><div style="float:left">' . 
            listing_beds_baths_price() . '</div></div>';

        return $post_content;
    }
}

function listing_beds_baths_price () 
{
    return '<h3>Basic Details</h3>
    <p>
        Beds: ' .do_shortcode('[bedrooms]') . '<br />
        Baths: ' . do_shortcode('[bathrooms]') . '<br />
        Rent: ' . do_shortcode('[price]') . '<br />
        Date Available: ' . do_shortcode('[available_on]') . '<br />
    </p>';
}

function placester_get_coordinates($data) {
    if (!empty($data->location->coords->latitude) && !empty($data->location->coords->longitude)):
        $post_content = '<div class="map-wrapper"><div id="map-container"></div></div>';
        return $post_content;
    endif;
}

function placester_office_geocoded($data) {
    if (!empty($data->location->coords->latitude) && !empty($data->location->coords->longitude)): 

        $post_content = 
        '
        <script src="http://maps.google.com/maps/api/js?sensor=false&amp;v=3.1"></script>
        <script>
            var latLng = new google.maps.LatLng(' . $data->location->coords->latitude . ', '. $data->location->coords->longitude .');
            window.map = new google.maps.Map(document.getElementById("map-container"),
              {
                zoom: 14,
                center: latLng,
                disableDefaultUI: true,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
              });
            setTimeout(function () {
                var marker = new google.maps.Marker({
                    position: new google.maps.LatLng(' . $data->location->coords->latitude .', '. $data->location->coords->longitude . '),
                    map: window.map
                });}, 500);
        </script>';
        return $post_content;
    endif; 
}

/**
 * Used for the search widget to get listing locations
 * @param $location (city, state, zip)
 */
function placester_display_location($location)
{
    try {
        $locations = placester_location_list();
        sort($locations->$location);
        foreach ($locations->$location as $locale) {
             var_dump($_GET['location'][$location]);
              echo  '<option value="' . $locale . '"'; 
              if(isset($_GET['location'][$location])) {

                if ($_GET['location'][$location] == $locale) {
                    echo "selected='selected'"; 
                }
            }
                echo '>' . $locale . '</option>';
             
        }
    }
    catch (PlaceSterNoApiKeyException $e) {
    }
}

/**
 * Verifies if the "Activate client accounts"
 * setting is checked
 * 
 * @return bool True if active, False otherwise
 */
function placester_is_membership_active() {
    $is_active = get_option( 'placester_activate_client_accounts' );
    return get_option( 'placester_activate_client_accounts' );
}

function placester_remove_listings() {
    global $wpdb;
    $posts_table = $wpdb->prefix . 'posts';
    $myrows = $wpdb->get_results( "DELETE FROM $posts_table WHERE post_type = 'property'" );
}

function get_plugin_dir( $subpath = false ) {
    // Extract current plugin dir
    $basename = plugin_basename( __FILE__ );
    $exploded = explode( '/', $basename );    

    return ($subpath) ?  WP_PLUGIN_DIR . '/' . $exploded[0] . '/' . $subpath . '/' : WP_PLUGIN_DIR . '/' . $exploded[0];
}

/**
 * Returns the property address mode 
 *
 * Verifies the 'placester_display_block' option which is set 
 * in the plugin settings, and returns the correct address mode.
 * 
 * @return string The property address mode
 */
function placester_get_property_address_mode() {
    $placester_display_block_address = get_option('placester_display_block_address');
    if ( $placester_display_block_address ) 
        return 'polygon';
    else
        return 'exact';
}

if ( !function_exists('get_plugin_url') ) {
    function get_plugin_url( $subpath = false ) {
        // Extract current plugin dir
        $basename = plugin_basename( __FILE__ );
        $exploded = explode( '/', $basename );    

        return ($subpath) ?  WP_PLUGIN_URL . '/' . $exploded[0] . '/' . $subpath . '/' : WP_PLUGIN_URL . '/' . $exploded[0];
    }
}

function placester_get_placeholder_if_empty( $string, $placeholder='' ) {
    return !empty( $string ) ? $string : ( !empty( $placeholder ) ? 'PLACEHOLDER_' . strtoupper( str_replace( ' ', '_', $placeholder ) ) : 'PLACEHOLDER_TEXT' );
}

function placester_get_template_url() {
    return get_plugin_url( "templates/" . $_GET['template_iframe'] );
}

/**
 * Crops a text while preserving the html.
 *
 * @param string $s The input string. Must be one character or longer.
 * @param integer $start Start of the crop.
 * @param integer $length Length of the crop.
 * @param mixed	$strict If this is defined, then the last word will be complete. If this is set to 2 then the last sentence will be completed.
 * @param string $suffix A string to suffix the value, only if it has been chopped.
 *
 * @return string The cropped string.
 *
 * @link http://perplexed.co.uk/290_html_substr_crop_html_text.htm
 */
function pl_html_substr( $s, $start, $length = NULL, $strict = false, $suffix = NULL ) {

    if ( is_null( $length ) )
        $length = strlen( $s ); 

    /** Function body that crops the text. */
    $f = 'static $startlen = 0; 
        if ( $startlen >= ' . $length . ' ) return "><"; 
        $html_str = html_entity_decode( $a[1] );
        $subsrt = max( 0, ( ' . $start . ' - $startlen ) );
        $sublen = ' . ( empty( $strict ) ? '( ' . $length . ' - $startlen )' : 'max( @strpos( $html_str, "' . ( $strict == 2 ? '.' : ' ' ) . '", (' . $length . ' - $startlen + $subsrt - 1 ) ), ' . $length . ' - $startlen )' ) . ';
        $new_str = substr( $html_str, $subsrt, $sublen ); 
        $startlen += $new_str_len = strlen( $new_str );
        $suffix = ' . ( !empty( $suffix ) ? '( $new_str_len === $sublen ? "' . $suffix . '" : "" )' : ' "" ' ) . ';
        return ">" . htmlentities( $new_str, ENT_QUOTES, "UTF-8" ) . "$suffix<";';

    return preg_replace( 
        array( "#<[^/][^>]+>(?R)*</[^>]+>#", "#(<(b|h)r\s?/?>){2,}$#is"), 
        "", 
        trim( 
            rtrim( 
                ltrim( 
                    preg_replace_callback( 
                        "#>([^<]+)<#", 
                        create_function( '$a', $f ), 
                        ">$s<" 
                    ), 
                    ">" ), 
                "<" ) 
            )
        );
}
