<?php

/**
 * Shortcodes for use in post content
 */

/**
 * Shortcodes for the listing
 */
function placester_listing_shortcode_info() {
    global $post;
    $data = json_decode(stripslashes($post->post_content));
    return $data;
}

function placester_listings_map_shortcode( $atts ) {

    $defaults = array(
        'max_price' => 5000,
        'min_price' => 200,
    );

    $args = shortcode_atts( $defaults, $atts );

    $filter_query = '';

    if ( isset( $atts['available_on'] ) ) $filter_query .= '&available_on=' . date( 'd-m-Y', strtotime( '1st' . $atts['available_on'] ) );
    if ( isset( $atts['bathrooms'] ) ) $filter_query .= '&bathrooms=' . $atts['bathrooms'];
    if ( isset( $atts['bedrooms'] ) ) $filter_query .= '&bedrooms=' . $atts['bedrooms'];
    if ( isset( $atts['city'] ) ) $filter_query .= '&location[city]=' . $atts['city'];
    $filter_query .= '&max_price=' . $args['max_price'] . '&min_price=' . $args['min_price'];

?>
    <script type="text/javascript">
    jQuery("#placester_listings_map_map").ready(function() {
        filter_query = '<?php echo $filter_query; ?>';
        placesterMap_setFilter(filter_query);
    });
    </script>
<?php
    $return = '<section class="map">';

    $map_parameters = array();
    $param_keys = array( 'center', 'center_latitude', 'center_longitude', 'zoom' );
    foreach ( $param_keys as $key ) {
        if ( array_key_exists( $key, $atts ) ) {
            $map_parameters[$key] = $atts[$key];
        }
    }
    try {
        $return .= placester_listings_map( $map_parameters, true );
    }
    catch (PlaceSterNoApiKeyException $e) {
        display_no_api_key_error();
    }
    $return .= '</section>'; 
    return $return;
}


function placester_listings_search( $atts ) {

    $defaults = array(
        'max_price' => 5000,
        'min_price' => 200,
        'rows_per_page' => 5,
        'sort_by' => 'bathrooms',
        'show_sort' => 1,
    );

    $args = wp_parse_args( $atts, $defaults );

    $filter_query = '';

    if ( isset( $args['available_on'] ) ) $filter_query .= '&available_on=' . date( 'd-m-Y', strtotime( '1st' . $args['available_on'] ) );
    if ( isset( $args['bathrooms'] ) ) $filter_query .= '&bathrooms=' . $args['bathrooms'];
    if ( isset( $args['bedrooms'] ) ) $filter_query .= '&bedrooms=' . $args['bedrooms'];
    if ( isset( $args['city'] ) ) $filter_query .= '&location[city]=' . $args['city'];
    if ( isset( $args['state'] ) ) $filter_query .= '&location[state]=' . $args['state'];
    if ( isset( $args['zip'] ) ) $filter_query .= '&location[zip]=' . $args['zip'];
    $filter_query .= '&max_price=' . $args['max_price'] . '&min_price=' . $args['min_price'];

    // See if snippet format is defined
    $snippet_layout = get_option('placester_snippet_layout');

?>
    <script type="text/javascript">
    function placesterListLone_createRowHtml(row)
    {
        var null_image =  '<?php echo WP_PLUGIN_URL . '/placester/images/null/property3-73-37.png' ?>';
        if (row.images.length > 0) {
            var images_array = ('' + row.images).split(',');
            var image = '';

            if (images_array.length > 0 && images_array[0].length > 0)
            {
                image = '<img src="' + images_array[0] + '" width=100 />';
            }        
        } else {
            var image = '<img src="' + null_image + '" width=100 />';
        };
        <?php if ( $snippet_layout != '' ) { ?>
        s = '<?php echo $snippet_layout; ?>';
        s = s.replace("\[bedrooms\]", row.bedrooms);
        s = s.replace("\[bathrooms\]", row.bathrooms);
        s = s.replace("\[price\]", row.price);
        s = s.replace("\[available_on\]", row.available_on);
        s = s.replace("\[listing_address\]", row.location.address);
        s = s.replace("\[listing_city\]", row.location.city);
        s = s.replace("\[listing_state\]", row.location.state);
        s = s.replace("\[listing_zip\]", row.location.zip);
        s = s.replace("\[listing_description\]", row.description);
        s = s.replace("\[listing_image\]", image);
        
        /* s = s.replace("\[listing_unit\]", row.location.unit);  
        /* s = s.replace("\[listing_neighborhood\]", row.); 
        /* s = s.replace("\[listing_map\]", <?php do_shortcode("[listing_map]"); ?>); */
        /* s = s.replace("\[listing_images\]", row.bedrooms); */     
        
        <?php } else { ?>
        s = '  <li class="single-item clearfix">' +
            '  <div class="thumbs"><a href="' + row.url + '" >' +
            image + 
            '  </a></div>' +
            '  <div class="item-details">' +
            '  <a href="'+ row.url +'" class="feat-title">' + row.location.address + ', ' + row.location.city + ', ' + row.location.state + '</a>' +
            '  <ul class="item-details clearfix">' +
            '  <li>Bedrooms: ' + row.bedrooms + '</li>' +
            '  <li>Available: ' + row.available_on+'</li>' +
            '  <li>Bathrooms: ' + row.bathrooms + '</li>' +
            '  <li>Price: ' + row.price + '</li>' +
            '  </ul>' +
            '  <a href="' + row.url + '" class="seemore">See More Details</a>' +
            '  </div>' +
            '  </li>';
        <?php } ?>
        return s;
    }

    function custom_empty_listings_loader (dom_object) {
        var empty_property_search = '<div><h5>No results</h5><p>Sorry, no listings match that search. Maybe try something a bit broader? Or just give us a call and we\'ll personally help you find the right place.</p></div>'
            dom_object.html(empty_property_search);
    }

    jQuery("#placester_listings_list").ready(function() {
        filter_query = '<?php echo $filter_query; ?>';
        placesterListLone_setFilter(filter_query);

        jQuery('#sort_list').change(function() {
            var v = $('#sort_list').val();
            a = v.split(' ');
            placesterListLone_setSorting(a[0], a[1]);
        });
    });
    </script>
<?php
    $list_args = array(
        'table_type' => 'html',
        'sort_by' => 'bathrooms',
        'js_row_renderer' => 'placesterListLone_createRowHtml',
        'loading' => array(
            'render_in_dom_element' => 'my_loader_div'
        ),
        'attributes' => array(
            'bathrooms',
            'price',
            'images',
            'description',
            'url',
            'location.city',
            'location.state',
            'location.address',
            'location.zip',
            'bedrooms',
            'id',
            'available_on'
        )
    );

    $pagination = '';
    if ( $args['rows_per_page'] ) {
        $list_args['pager'] = array(
            'render_in_dom_element' => 'pagination_loads_here',
            'rows_per_page' => $args['rows_per_page'],
            'css_current_button' => 'prev-btn-passive',
            'css_not_current_button' => 'next-btn-active',
            'first_page' => array( 'visible' => false, 'label' => 'First'), 
            'previous_page' => array( 'visible' => true, 'label' => 'Prev' ),
            'numeric_links' => array(
                'visible' => false, 
                'max_count' => 10,
                'more_label' => '..more..',
                'css_outer' => 'pager_numberic_block'
            ),
            'next_page' => array(
                'visible' => true,
                'label' => 'Next',
            ),
            'last_page' => array(
                'visible' => false,
                'label' => 'Last'
            )
        );

        $pagination = 
            '<section id="pagination_loads_here" class="pagination">' . "\n" .
            '   <a href="#" class="prev-btn-passive">Prev</a>' . "\n" .
            '   <a href="#" class="next-btn-active">Next</a>' . "\n" .
            '   <div class="clr"></div>' . "\n" .
            '</section>';
    }
    $sort_widget = '';
    if ( $args['show_sort'] ) {
        $sort_widget = 
            '<div>' . "\n" .  
            '	<div class="selLabel2 sort-by">Sort by</div>' . "\n" .
            '    <div class="cselect2">' . "\n" .
            '        <select id="sort_list" class="sparkbox-custom">' . "\n" .
            '          <option value="bathrooms asc">bathrooms</option>' . "\n";
        $sort_widget .= ( $args['sort_by'] == 'price' ) ? '<option  selected="selected" value="price asc">price</option>' . "\n" : '<option value="price asc">price</option>' . "\n"; 
        $sort_widget .= 
            '        </select>' . "\n" .
            '    </div>' . "\n" .                    
            '</div>';
    } 

    return '<div class="placester_search_results">' . $sort_widget . '<ul class="item-list">' . placester_listings_list( $list_args, true ) . '</ul>' . $pagination . '</div>';
}

function placester_bedrooms() {
    $data = placester_listing_shortcode_info();
    if(!empty($data->bedrooms))
        return $data->bedrooms;
}

function placester_bathrooms() {
    $data = placester_listing_shortcode_info();
    if(!empty($data->bathrooms))
        return $data->bathrooms;
 }

function placester_price() {
    $data = placester_listing_shortcode_info();
    if(!empty($data->price))
        return $data->price;
}

function placester_available_on() {
    $data = placester_listing_shortcode_info();
    if(!empty($data->available_on))
        return $data->available_on;
}

function placester_listing_address() {
    $data = placester_listing_shortcode_info();
    if(!empty($data->location->address))
        return $data->location->address;
}    

function placester_listing_city() {
    $data = placester_listing_shortcode_info();
    if(!empty($data->location->city))
        return $data->location->city;
}

function placester_listing_state() {
    $data = placester_listing_shortcode_info();
    if(!empty($data->location->state))
        return $data->location->state;
}

function placester_listing_unit() {
    $data = placester_listing_shortcode_info();
    if(!empty($data->location->unit))
        return $data->location->unit;
}

function placester_listing_zip() {
    $data = placester_listing_shortcode_info();
    if(!empty($data->location->zip))
        return $data->location->zip;
}

function placester_listing_neighborhood() {
    $data = placester_listing_shortcode_info();
    if(!empty($data->location->neighborhood))
        return $data->location->neighborhood;
}

function placester_listing_map() {
    $data = placester_listing_shortcode_info();
    if (isset($data->location->coords->latitude) && !empty($data->location->coords->latitude)): 
    $post_content  = '<div id="property_details_map" style="height: 200px; width: 340px"></div>';
    $post_content .= '
    <script type="text/javascript" charset="utf-8" src="http://maps.google.com/maps/api/js?sensor=false"></script>
    <script type="text/javascript" charset="utf-8">
        var latLng = new google.maps.LatLng(' . $data->location->coords->latitude.','. $data->location->coords->longitude . ')
        window.map = new google.maps.Map(document.getElementById("property_details_map"),
          {
            zoom: 14,
            center: latLng,
            disableDefaultUI: true,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
          });
    
    
        setTimeout(function () {
            var marker = new google.maps.Marker({
                position: latLng,
                map: window.map,
            });
                        
        }, 500)   
    </script>';
    return $post_content;
    endif;
}

function placester_listing_description() {
    $data = placester_listing_shortcode_info();
    if(!empty($data->description))
        return $data->description;
}

function placester_listing_images() {
    $data = placester_listing_shortcode_info();
    if (!empty($data->images)) {
        $post_image = '';
        foreach($data->images as $image) {
            $post_image .= '<a class="placester_fancybox" href="' . $image->url . '"><img src="' . $image->url . '" alt width="150" height="150" /></a>';
        }
    return '<p class="images">' . $post_image . '</p>';
    }
}

function placester_listing_single_image() {
    $data = placester_listing_shortcode_info();
    $base_url = WP_PLUGIN_URL . '/placester';
    if (!empty($data->images)) {
        $image = '<a class="" href="' . get_permalink() . '"><img src="' . $data->images[0]->url . '" alt width="150" height="150" /></a>';
    } else {
        $image = '<a class="" href="' . get_permalink() . '"><img src="' . $base_url . '/images/null/property3-73-37.png" alt width="150" height="150" /></a>'; ;
    }

    return $image;
}
 
add_shortcode('listings_search', 'placester_listings_search');
add_shortcode('listings_map', 'placester_listings_map_shortcode');
add_shortcode('bedrooms', 'placester_bedrooms');
add_shortcode('bathrooms', 'placester_bathrooms');
add_shortcode('price', 'placester_price');
add_shortcode('available_on', 'placester_available_on');
add_shortcode('listing_address', 'placester_listing_address');
add_shortcode('listing_city', 'placester_listing_city');
add_shortcode('listing_state', 'placester_listing_state');
add_shortcode('listing_unit', 'placester_listing_unit');
add_shortcode('listing_zip', 'placester_listing_zip');
add_shortcode('listing_neighborhood', 'placester_listing_neighborhood');
add_shortcode('listing_map', 'placester_listing_map');
add_shortcode('listing_description', 'placester_listing_description');
add_shortcode('listing_images', 'placester_listing_images');
add_shortcode('listing_image', 'placester_listing_single_image');

/**
 * Shortcodes for the user
 */

function placester_user_logo() {
    $user = placester_get_user_details();
    if($user->logo_url)
        return '<p style="float: left; width: 110px; padding-right: 10px;"><img src="' . $user->logo_url . '" /></p>'; 
}

function placester_user_first_name() {
    $user = placester_get_user_details();
    if($user->first_name)
        return $user->first_name; 
}

function placester_user_last_name() {
    $user = placester_get_user_details();
    if($user->last_name)
        return $user->last_name; 
}

function placester_user_phone() {
    $user = placester_get_user_details();
    if($user->phone)
        return $user->phone; 
}

function placester_user_email() {
    $data = placester_listing_shortcode_info();
    if($data->contact->email)
        return '<a href="mailto:' . $data->contact->email . '?subject=feedback">Email me</a>';
}

function placester_user_address() {
    $user = placester_get_user_details();
    if($user->location->address)
        return $user->location->address; 
}

function placester_user_unit() {
    $user = placester_get_user_details();
    if($user->location->unit)
        return $user->location->unit; 
}

function placester_user_city() {
    $user = placester_get_user_details();
    if($user->location->city)
        return $user->location->city; 
}

function placester_user_state() {
    $user = placester_get_user_details();
    if($user->location->state)
        return $user->location->state; 
}

function placester_user_zip() {
    $user = placester_get_user_details();
    if($user->location->zip)
        return $user->location->zip; 
}

function placester_user_description() {
    $user = placester_get_user_details();
    if($user->description)
        return $user->description; 
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
    );

    $args = wp_parse_args( $atts, $defaults );
    extract( $args, EXTR_SKIP );

    global $wp_query;
    $property_id = $wp_query->query['property'];
    
    $is_lead = current_user_can( 'placester_lead' );
    if ( !$is_lead ) {
        return;
    }

    $add_link_attr = array(
        'href' => "#{$property_id}",
        'id' => 'pl_add_favorite',
        'class' => 'pl_prop_fav_link'
    );
    $remove_link_attr = array(
        'href' => "#{$property_id}",
        'id' => 'pl_remove_favorite',
        'class' => 'pl_prop_fav_link'
    );
    // Add extra classes if user not loggend in or 
    // doesn't have a lead account
    if ( !is_user_logged_in() ) {
        $add_link_attr['class'] .= ' guest'; 
        // TODO redirect add get message that informs the user
        // that they need an accoutn to add that property to fav
        $add_link_attr['href'] = site_url('wp-login.php?action=register&role=lead'); 
        $add_link_attr['target'] = "_blank"; 
    } else {
        $is_favorite = placester_is_favorite_property( $property_id );
        // Return the remove link if favorite
        if ( $is_favorite )
            $add_link_attr['style'] = "display:none;";
    }

    if ( !isset($add_link_attr['style']) ) {
        $remove_link_attr['style'] = "display:none;";
    }

    $add_link = placester_html( 
        'a',
        $add_link_attr,
        $add_text
    );                     
    $remove_link = placester_html( 
        'a',
        $remove_link_attr,
        $remove_text
    );                     
    return placester_html( 
        'div',
        array(
            'id' => 'pl_add_remove_lead_favorites'
        ),
        $add_link . 
        $remove_link .
        placester_html(
            'img',
            array(
                'src' => $spinner,
                'class' => "pl_spinner",
                'alt' => "ajax-spinner",
                'style' => 'display:none; margin-left: 5px;'
            ) 
        )
    );
}

/**
 * Adds "Login | Register" if not logged in
 * or "Logout | My account" if logged in 
 *
 * TODO If logged in and not lead display something informing them 
 * of what they need to do to register a lead account
 */
function placester_lead_control_panel( $atts ) {
    $defaults = array(
        'loginout' => true,
        'profile' => true,
        'register' => true,
    );

    $args = wp_parse_args( $atts, $defaults );
    extract( $args, EXTR_SKIP );

    $is_lead = current_user_can( 'placester_lead' );

    /** The login or logout link. */
	if ( ! is_user_logged_in() )
		$loginout_link = '<a id="pl_login_link" href="#pl_login_form">Log in</a>';
	else
		$loginout_link = '<a href="' . esc_url( wp_logout_url($_SERVER['REQUEST_URI']) ) . '" id="pl_logout_link">Log out</a>';

    /** The register link. */
    $register_link = '<a id="pl_register_lead_link" href="#pl_lead_register_form">Register</a>';

    /** The profile link. */
    $profile_link = '<a id="pl_lead_profile_link" target="_blank" href="' . admin_url( 'admin.php?page=placester_lead_profile' ) . '">My Account</a>';

    $loginout_link = ( $loginout ) ? $loginout_link : '';
    $register_link = ( $register ) ? ( empty($loginout_link) ? $register_link : ' | ' . $register_link ) : '';
    $profile_link = ( $profile ) ? ( empty($loginout_link) ? $profile_link : ' | ' . $profile_link ) : '';

    if ( ! is_user_logged_in() ) {
        $args = array( 
            'echo' => false,
            'form_id' => 'pl_login_form',
        );
        /** Get the login form. */
        $login_form = wp_login_form( $args );

        return $loginout_link . $register_link . PLS_Membership::generate_lead_reg_form() . "<div style='display:none;'>{$login_form}</div>";
    } else {

        /** Remove the link to the profile if the current user is not a lead. */
        $extra = $is_lead ? $profile_link : "";

        return $loginout_link . $extra;
    }
}

add_shortcode('logo', 'placester_user_logo');
add_shortcode('first_name', 'placester_user_first_name');
add_shortcode('last_name', 'placester_user_last_name');
add_shortcode('phone', 'placester_user_phone');
add_shortcode('email', 'placester_user_email');
add_shortcode('user_address', 'placester_user_address');
add_shortcode('user_unit', 'placester_user_unit');
add_shortcode('user_city', 'placester_user_city');
add_shortcode('user_state', 'placester_user_state');
add_shortcode('user_zip', 'placester_user_zip');
add_shortcode('user_description', 'placester_user_description');

if ( placester_is_membership_active() ) {
    add_shortcode('favorite_link_toggle', 'placester_favorite_link_toggle');
    add_shortcode('lead_user_navigation', 'placester_lead_control_panel');
}

