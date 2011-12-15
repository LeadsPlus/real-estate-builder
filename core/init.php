<?php

/**
 * Plugin initialization code, main dispatcher.
 * @file /core/init.php
 */

/**
 * Initialization.
 * Creates custom post types needed by the plugin
 * Enqueues different scripts.
 *
 * @internal
 */
add_action( 'init', 'placester_init' );
function placester_init() {
    /** Property (Listing) custom post type */
    register_post_type( 'property',
        array(
            'taxonomies'      => array(),
            'show_ui'         => false,
            'public'          => true,
            'capability_type' => 'post',
            'rewrite'         => array( 'slug' => placester_post_slug() ),
            'hierarchical'    => false
        ) );

    /** Document custom post type */
    register_post_type( 'placester_document',
        array(
            'label' => 'Documents',
            'taxonomies'      => array(),
            'public'          => false,
            'capability_type' => 'post',
        ) );

    $base_url = WP_PLUGIN_URL . '/placester';

    /** Register the scripts needed by the leads. */
    wp_register_script( 'placester.theme.leads', "{$base_url}/js/theme/placester.leads.js" );

    wp_register_style( 'placester.map.widget', 
        plugins_url( '/css/listings_map.widget.css', dirname( __FILE__ ) ) );

    /** TODO
     * When WP 3.3 is released, shorcode scripts will be able to be 
     * included only the shorcodes are used.
     * The only method currently available can be found in the attached link.
     * @link http://scribu.net/wordpress/optimal-script-loading.html
     */
    wp_register_style( 'shortcodes', $base_url . '/css/shortcodes.css' );
    wp_enqueue_style( 'shortcodes' );

    /** Fancybox. Used in frontend for membership login and register forms. */
    wp_register_script( 'fancybox', $base_url . '/js/fancybox/jquery.fancybox-1.3.4.pack.js', array('jquery'), false, true );
    wp_register_script( 'fancybox_easing', $base_url . '/js/fancybox/jquery.easing-1.3.pack.js', array( 'jquery', 'fancybox' ), false, true );
    wp_register_style( 'fancybox_style', $base_url . '/js/fancybox/jquery.fancybox-1.3.4.css' );

    /** The membershipt scripts file. */
    wp_register_script( 'placester.membership', $base_url . '/js/theme/placester.membership.js', dirname( __FILE__ ) );


    /** TODO 
     * These scripts should be removed from here. The plugin should make 
     * no assumptions about what scripts are needed in the theme. Theme 
     * developers will include the scripts they need. 
     */
    if ( ! defined( 'PL_NO_SCRIPTS' ) ) {
        wp_enqueue_script( 'jquery_tools' );
        wp_enqueue_script( 'sparkbox_select' );

        /** TODO Determine why was this included */
        // wp_register_script( 'colorbox', $base_url . '/js/jquery.colorbox.js' );
        // wp_enqueue_script( 'colorbox' );

        wp_register_script( 'jquery_tools', $base_url . '/js/jquery.tools.min.js' );
        wp_register_script( 'sparkbox_select', $base_url . '/js/jquery.sparkbox-select.js' );
        wp_enqueue_script( 'placester_fancybox', $base_url . '/js/placester.fancybox.js', array('jquery', 'fancybox', 'fancybox_easing') );

    }

    // TODO A better method would be to include this only if the shortcode is 
    // used. Sadly, do_shortcode is unhookable.
    if ( placester_is_membership_active() ) {
        wp_enqueue_script( 'placester.membership' );

        wp_enqueue_script( 'fancybox' );
        wp_enqueue_script( 'fancybox_easing' );
        wp_enqueue_style( 'fancybox_style' );
    }

    /** Flush rewrite rules */
    global $wp_rewrite;
    $wp_rewrite->flush_rules();
}

/**
 * Add things only to the backend.
 * 
 */
add_action( 'admin_init', 'pl_admin_init' );
function pl_admin_init() {

    /** Explicitly include jQuery so you can use '$' instead of 'jQuery'. */
    wp_register_script( 'jquery_lib', 'http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js');
    wp_enqueue_script( 'jquery_lib' );
}

/**
 * Executed on frontend
 * 
 */
add_action( 'template_redirect', 'placester_theme_includes' );
function placester_theme_includes() {

    wp_enqueue_script( 'placester.theme.leads' );
    // Pass the ajax url to this script 
    // Get current page protocol
    $protocol = isset( $_SERVER['HTTPS'] ) ? 'https://' : 'http://';
    // Output admin-ajax.php URL with same protocol as current page
    $params = array(
        'ajaxurl' => admin_url( 'admin-ajax.php', $protocol ),
    );
    wp_localize_script( 'placester.theme.leads', 'info', $params );

}

/**
 * Called on plugin activation.
 *
 * @internal
 */
function placester_activate() {
    // placester_init();

    // // Get the default administrator role.
    // $role =& get_role( 'administrator' );
    // // Add forum capabilities to the administrator role. 
    // if ( !empty( $role ) ) {
        // $role->add_cap( 'add_roomates' );
        // $role->add_cap( 'read_roomates' );
        // $role->add_cap( 'delete_roomates' );
        // $role->add_cap( 'add_favorites' );
        // $role->add_cap( 'delete_roomates' );
    // }
    // // Create the "Property lead" role
    // $lead_role = add_role( 
        // 'placester_lead',
        // 'Property Lead',
        // array(
            // 'add_roomates' => true,
            // 'read_roomates' => true,
            // 'delete_roomates' => true,
            // 'add_favorites' => true,
            // 'delete_roomates' => true,
            // 'level_0' => true,
            // 'read' => true
        // )
    // );

    global $wp_rewrite;
    $wp_rewrite->flush_rules();
}

/**
 * Called on plugin deactivation.
 */
function placester_deactivate() {
    // // Get the default administrator role.
    // $role =& get_role( 'administrator' );
    // // Remove lead capabilities to the administrator role. 
    // if ( !empty( $role ) ) {
        // $role->remove_cap( 'add_roomates' );
        // $role->remove_cap( 'read_roomates' );
        // $role->remove_cap( 'delete_roomates' );
        // $role->remove_cap( 'add_favorites' );
        // $role->remove_cap( 'delete_roomates' );
    // }
    // // Create the "Property lead" role
    // $roles_to_delete = array(
        // 'placester_lead',
    // );

    // // Delete the roles with no users
    // foreach ( $roles_to_delete as $role ) {
        // $users = get_users( array( 'role' => $role ) );

        // if ( count($users) <= 0 ) {
            // remove_role( $role );
        // }
    // }

    global $wp_rewrite;
    $wp_rewrite->flush_rules();

   // TODO: delete rewrite rules on uninstall
}


/**
 * When placester post object is requested - it's checked that
 * data are loaded from external storage.
 *
 * @param object $query
 * @deprecated Left to ensure no themes use this
 * @see placester_get_posts($query)
 *
 * @internal
 */
function placester_pre_get_posts( $query ) {
   $vars = $query->query_vars;

   if ( isset( $vars['post_type'] ) && $vars['post_type'] == 'property' ) {
       // Ensure we have actual post data
       $id = $vars['property'];
       $id = preg_replace( '/[^0-9a-z]/', '', $id);

       placester_get_post_id( $id );
   }
}

/**
 * Allows theme builders to create pages on theme activation.
 *
 * @param array $page_list List of pages to be created on theme activation
 */
function placester_create_pages( $page_list )
{
    // Retrieve the action, if it exists (overwrite or restore)
    $action = '';
    if (isset($_POST['action'])) 
        $action = $_POST['action'];

    $trashed = array();
    // Foreach of page defined by the theme 
    foreach ($page_list as $page_info) {
        $page = get_page_by_title($page_info['title']);

        // If the page exists and no action exists, 
        // if trashed save in an array, and update the template
        if ( isset($page->ID) && ( $action == '' ) ) {
            if ( $page->post_status == "trash" ) {
                $trashed[] = $page_info;
            }
            delete_post_meta( $page->ID, '_wp_page_template' );
            add_post_meta( $page->ID, '_wp_page_template', $page_info['template'] );        
        } else { // If the page does not exist or an action was requested
            $page = get_page_by_title($page_info['title']);
            // Do what needs to be done for each action
            switch ($action) {
            case 'overwrite':
                if ( $page->post_status == "trash" ) {
                    wp_delete_post($page->ID, true); 
                    unset($page);
                }
                break;
            case 'restore':
                if ( $page->post_status == "trash" ) {
                    wp_untrash_post( $page->ID );
                    wp_publish_post( $page->ID );
                }
                break;
            }
            // Add the page if appropiate
            if ( !isset($page->ID) )
                placester_insert_page($page_info);
        }
    } // #end foreach page

    // If pages couldn't be created because they were trashed,
    // print the admin messages.
    $trashed_count = count($trashed);
    if ( $trashed_count ) {
        $msg = 'To take full advantage of its features, this theme must have some pages created. ';
        $msg .= ($trashed_count > 1) ? '. The following needed pages exist, but have been trashed: ' : 'The following needed page exists, but has been trashed: ';
        foreach ($trashed as $index => $trashed_page) {
            $msg .= "\"" . $trashed_page['title'] . "\"";
            $msg .= ( $index+1 != count($trashed) ) ? ', ' : '.';
        }
        $msg .= ' <form action="' . admin_url() . 'themes.php" method="post" style="display:inline;"><input type="hidden" name="action" value="overwrite"><input type="submit" value="Overwrite trashed pages" /></form>';
        $msg .= ' <form action="' . admin_url() . 'themes.php" method="post" style="display:inline;"><input type="hidden" name="action" value="restore"><input type="submit" value="Restore trashed pages" /></form>';
        $css = 'form > input { cursor: pointer }';
        placester_admin_msg( $msg, 'error', true, $css );
    } 
}

/** 
 * Function that inserts a page
 *
 * @param array $args Array of ( title, template ) arrays
 *
 * @internal
 */
function placester_insert_page( $args ) {
    $args = wp_parse_args( $args );
    extract( $args );

    $page = array(
        'post_type' => 'page',
        'post_title' => $title,
        'post_status' => 'publish',
        'post_author' => 1);

    $page_id = wp_insert_post( $page );
    delete_post_meta( $page_id, '_wp_page_template' );
    add_post_meta( $page_id, '_wp_page_template', $template );
}

/**
 * Filters Placester post objects.
 * Ensures post object is a Placester listing. Listings are loaded from external storage
 *
 * @param object $query
 *
 * @internal
 */
function placester_get_posts( $query ) {
    if(!is_admin() && !$query->is_page) {
      //var_dump($query);
   $vars = $query->query_vars;
    if(empty($_REQUEST) ) {
       if ( isset( $vars['post_type'] ) && $vars['post_type'] == 'property' ) {
           // Ensure we have actual post data
           $id = isset( $vars['property'] ) ?  preg_replace( '/[^0-9a-z]/', '', $vars['property']) : NULL ;
           //$id = preg_replace( '/[^0-9a-z]/', '', $id);
           try {
           placester_get_post_id( $id );
           }
            catch (PlaceSterNoApiKeyException $e) 
            {}
       } elseif ($query->is_home && !isset( $vars['post_type'] ) ) {
           try {
           $listings = placester_property_list(NULL);
            if($listings && $listings->count > 0) {
                foreach ($listings->properties as $property) {
                    $post_id[] =  placester_get_post_id($property->id);
                    $query->set('post__in', $post_id );
                    $query->set('post_type', 'property' );
                    $query->set('meta_query', '' );
                }
            }
            }
            catch (PlaceSterNoApiKeyException $e) 
            {}
       } elseif ( $query->is_home && isset($vars['post_type']) && $vars['post_type'] == 'property' ) {
           $query->set('post_type', 'property' );
           $query->set('meta_query', '' );
       } else {}
   } else {
        try {
            $request_filter = placester_filter_parameters_from_http();
           placester_cut_empty_fields($request_filter);
       $listings = placester_property_list($request_filter);
       if($listings && $listings->count > 0) {
           foreach ($listings->properties as $property) {
                    $post_id[] = placester_get_post_id($property->id);
                }
                    $query->set('post__in', $post_id );
                    $query->set('post_type', 'property' );
                    $query->set('meta_query', '');
                     
        } elseif($listings->count = 0) {
            $query->set('post_type', 'property' );
            $query->set('meta_query', '');
                }
        } catch (PlaceSterNoApiKeyException $e)
        {}
      }
}
}

/**
 * Adds the appropriate actions before returning the post
 *
 * @internal
 */
function placeser_pre_get_posts_actions() {
    add_action( 'pre_get_posts', 'placester_pre_get_posts', 0 );

    $display_listings_on_blog = get_option( 'placester_display_listings_blog' );

    if ( $display_listings_on_blog )
        add_action( 'pre_get_posts', 'placester_get_posts', 0 );
}
add_action('init', 'placeser_pre_get_posts_actions');

/**
 * Utility function that outputs an admin notice.
 *
 * @param string $msg
 * @param string $class
 * @param bool $inline
 * @param bool $css
 *
 * @internal
 */
function placester_admin_msg( $msg = '', $class = "updated", $inline = false, $css = false ) {
    if ( $css ) {
        $css = '<style type="text/css">' . $css . '</style>';
    }
    if ( !empty( $msg ) ) {
        if ($inline)
            echo "<div class='$class fade' style=\"padding: 0.5em\">$css$msg</div>\n";
        else
            echo "<div class='$class fade'>$css$msg</div>\n";
    }
}

/**
 * Deletes expired transients in the database
 * 
 * @access public
 * @return void
 */
add_action( 'wp_scheduled_delete', 'delete_delete_expired_db_transients' );
function placester_delete_expired_db_transients() {
    global $wpdb, $_wp_using_ext_object_cache;
    if( $_wp_using_ext_object_cache )
        return;
    $time = isset ( $_SERVER['REQUEST_TIME'] ) ? (int)$_SERVER['REQUEST_TIME'] : time() ;
    $expired = $wpdb->get_col( "SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout%' AND option_value < {$time};" );
    foreach( $expired as $transient ) {
        $key = str_replace('_transient_timeout_', '', $transient);
        delete_transient($key);
    }
}
