<?php

/**
 * Admin interface, disptaching all the stuff
 */

/**
 * Defines plugin admin menu
 */
function placester_admin_menu() {
    // Add separator
    global $menu;
    $menu['3a'] = array( '', 'read', 'separator1', '', 'wp-menu-separator' );

    // Add Placester Menu
    add_menu_page( 
        'RE Builder',
        'RE Builder',
        'edit_pages',
        'placester', 
        'placester_admin_default_html', 
        plugins_url( '/images/logo_16.png', dirname( __FILE__ ) ), 
        '3b' /* position between 3 and 4 */ );


    // Avoid submenu to start with menu function
    global $submenu;
    $submenu['placester'] = array();

    add_submenu_page( 'placester', '', 
        'My Listings', 'edit_pages', 'placester_properties', 
        'placester_admin_properties_html' );
    add_submenu_page( 'placester', '', 
        'Add Listing', 'edit_pages', 'placester_property_add', 
        'placester_admin_property_add_html' );
    // add_submenu_page( 'placester', '', 
    //     'Documents', 'edit_pages', 'placester_documents', 
    //     'placester_admin_documents_html' );
    add_submenu_page( 'placester', '', 
        'Contact Info', 'edit_pages', 'placester_contact', 
        'placester_admin_contact_html' );
    add_submenu_page( 'placester', '', 
        'Plugin Settings', 'edit_pages', 'placester_settings', 
        'placester_admin_settings_html' );
    // add_submenu_page( 'placester', '', 
    //     'Get Themes', 'edit_pages', 'placester_themes', 
    //     'placester_admin_themes_html' );
    add_submenu_page( 'placester', '', 
        'Support', 'edit_pages', 'placester_support', 
        'placester_admin_support_html' );
    add_submenu_page( 'placester', '', 
        'Update', 'edit_pages', 'placester_update', 
        'placester_admin_update_html' );

    // Add "Favorites" and "Roommates" menus to leads
    if ( current_user_can( 'placester_lead' ) ) {
        // Add Favorites menu
        add_menu_page( 
            'Favorite Properties',
            'Favorites',
            'add_favorites', 
            'placester_favorite_properties', 
            'placester_admin_favorite_properties_html', 
            plugins_url( '/images/icons/favorites.png', dirname( __FILE__ ) ),
            '2a' 
        );
        // Add Roommates menu
        add_menu_page( 
            'Roommates',
            'Roommates',
            'add_roomates', 
            'placester_roommates', 
            'placester_admin_roommates_html', 
            plugins_url( '/images/icons/roommates.png', dirname( __FILE__ ) ),
            '2b' 
        );
        // Add Lead Profile menu
        add_menu_page( 
            'Lead Profile',
            'Lead Profile',
            'add_roomates', 
            'placester_lead_profile', 
            'placester_admin_lead_profile_html', 
            plugins_url( '/images/icons/roommates.png', dirname( __FILE__ ) ),
            '9' 
        );
    }

    // Styles, scripts
    wp_register_style( 'placester.admin', 
        plugins_url( '/css/admin.css', dirname( __FILE__ ) ) );
    wp_register_style( 'placester.admin.jquery-ui', 
        plugins_url( '/css/admin.jquery-ui.css', dirname( __FILE__ ) ) );

    wp_register_script( 'googlemaps_v3',
        'http://maps.google.com/maps/api/js?sensor=false&amp;v=3.3' );
    wp_register_script( 'zeroclipboard',
        plugins_url( '/js/zeroclipboard/ZeroClipboard.js', dirname( __FILE__ ) ) );
    wp_register_script( 'jquery.datatables',
        plugins_url( '/js/jquery.dataTables.js', dirname( __FILE__ ) ) );
    wp_register_script( 'jquery.lightbox',
        plugins_url( '/js/jquery.lightbox-0.5.min.js', dirname( __FILE__ ) ) );
    wp_register_script( 'jquery.multifile',
        plugins_url( '/js/jquery.MultiFile.pack.js', dirname( __FILE__ ) ) );
    wp_register_script( 'jquery.upload',
        plugins_url( '/js/jquery.upload.js', dirname( __FILE__ ) ) );
    wp_register_script( 'jquery-ui.datepicker',
        plugins_url( '/js/jquery-ui.datepicker.min.js', dirname( __FILE__ ) ) );

    wp_register_script( 'placester.admin.property', 
        plugins_url( '/js/admin.property.js', dirname( __FILE__ ) ) );
    wp_register_script( 'placester.admin.property_form', 
        plugins_url( '/js/admin.property_form.js', dirname( __FILE__ ) ) );
    wp_register_script( 'placester.admin.settings', 
        plugins_url( '/js/admin.settings.js', dirname( __FILE__ ) ) );

    wp_register_script( 'uploadify',
        plugins_url( '/js/uploadify/jquery.uploadify.v2.1.4.js', dirname( __FILE__ ) ) );

    wp_register_script( 'uploadify_swfobject', plugins_url( '/js/uploadify/swfobject.js', dirname( __FILE__ ) ) );
    wp_register_script( 'uploadify_settings',
        plugins_url( '/js/uploadify/uploadify_settings.js', dirname( __FILE__ ) ) );
    wp_register_script( 'uploadify_settings_add',
        plugins_url( '/js/uploadify/uploadify_settings_add.js', dirname( __FILE__ ) ) );
    wp_register_script( 'JSON-js',
        plugins_url( '/js/json2.js', dirname( __FILE__ ) ) );
    wp_register_script( 'jquery.validate',
        'http://ajax.aspnetcdn.com/ajax/jquery.validate/1.8.1/jquery.validate.min.js' );
    wp_register_script( 'placester.admin.leads',
        plugins_url( '/js/admin.leads.js', dirname( __FILE__ ) ) );
    wp_register_script( 'placester.ui',
        plugins_url( '/js/placester.ui.js', dirname( __FILE__ ) ) );

    // Styles
    wp_register_style( 'uploadify',
        plugins_url( '/js/uploadify/uploadify.css', dirname( __FILE__ ) ) );
    wp_register_style( 'placester.ui',
        plugins_url( '/css/placester.ui.css', dirname( __FILE__ ) ) );
    wp_register_style( 'placester.admin.leads',
        plugins_url( '/css/admin.leads.css', dirname( __FILE__ ) ) );
}
add_action( 'admin_menu', 'placester_admin_menu' );

add_action( 'admin_init', 'placester_admin_init');
function placester_admin_init() {
    // Scripts
    wp_enqueue_script( 'placester.admin', 
        plugins_url( '/js/placester.admin.js', dirname( __file__ ) ) );

    wp_enqueue_script( 'placester.ui' );
    // Styles
    wp_enqueue_style( 'placester.ui' );
}

/**
 * Called when hide non-placester alert button is pushed
 */
add_action( 'wp_ajax_update_theme_alert', 'update_theme_alert' );
function update_theme_alert() {
    $placester_admin_options = get_option('placester_admin_options');
    $placester_admin_options = isset($placester_admin_options) ? $placester_admin_options : array();
    $placester_admin_options['hide_theme_alert'] = true;

    echo update_option( 'placester_admin_options', $placester_admin_options );
    die;
}

require ( 'leads.php' );
require( 'leads_ajax.php' );

/**
 * Admin menu - "dashboard" page, on-load handler
 */
function placester_admin_dashboard_onload() {
    if ( isset( $_REQUEST['ajax_action'] ) ) {
        require( 'dashboard_ajax.php' );
        exit();
    }

    wp_enqueue_script( 'dashboard' );
    wp_enqueue_style( 'dashboard' ); 
    wp_enqueue_style( 'wp-admin' );

    wp_enqueue_script( 'placester.widgets', 
        'http://dhiodphkum9p1.cloudfront.net/assets/api/v1.0/widgets.js' );

    wp_enqueue_style( 'placester.admin' );
    wp_enqueue_script( 'placester.admin.widgets', 
        plugins_url( '/js/admin.widgets.js', dirname( __FILE__ ) ) );
    wp_enqueue_style( 'placester.admin.widgets', 
        plugins_url( '/css/admin.widgets.css', dirname( __FILE__ ) ) );
}

add_action( 'load-placester_page_placester_dashboard', 
    'placester_admin_dashboard_onload' );



/**
 * Admin menu - defult page, really never called
 */
function placester_admin_default_html()
{}


/** ---------------------------
 *  Favorites page
 *  --------------------------- */

/**
 * Admin menu - "Favorites" page
 * Only applies to the lead role
 */
function placester_admin_favorite_properties_html() {
    require( 'leads_favorites.php' );
}

/**
 * Admin menu - "Favorites" page, on-load handler
 */
add_action( 'load-toplevel_page_placester_favorite_properties', 
    'placester_admin_favorite_properties_onload' );
function placester_admin_favorite_properties_onload() {
    wp_enqueue_script( 'jquery.validate' );
    wp_enqueue_script( 'placester.admin.leads');
    // Styles
    wp_enqueue_style( 'placester.admin.leads');
}

/** ---------------------------
 *  Roommates page
 *  --------------------------- */

/**
 * Admin menu - "Roommates" page
 * Only applies to the lead role
 */
function placester_admin_roommates_html() {
    require( 'leads_roommates.php' );
}

/**
 * Admin menu - "Roommates" page, on-load handler
 */
add_action( 'load-toplevel_page_placester_roommates', 
    'placester_admin_roommates_onload' );
function placester_admin_roommates_onload() {
    // Scripts
    wp_enqueue_script( 'jquery.validate' );
    wp_enqueue_script( 'placester.admin.leads');
    // Styles
    wp_enqueue_style( 'placester.admin.leads');
}

/** ---------------------------
 *  Lead profile page
 *  --------------------------- */

/**
 * Admin menu - "Roommates" page
 * Only applies to the lead role
 */
function placester_admin_lead_profile_html() {
    require( 'lead_profile.php' );
}

/**
 * Admin menu - "Roommates" page, on-load handler
 */
add_action( 'load-toplevel_page_placester_lead_profile', 
    'placester_admin_lead_profile_onload' );
function placester_admin_lead_profile_onload() {
    // Scripts
    wp_enqueue_script( 'jquery.validate' );
    wp_enqueue_script( 'placester.admin.leads');
    // Styles
    wp_enqueue_style( 'placester.admin.leads');
}

/** ---------------------------
 *  Documents page
 *  --------------------------- */

/**
 * Admin menu - "Documents" page
 */
function placester_admin_documents_html() {
    require( 'documents.php' );
}

/**
 * Admin menu - "Documents" page, on-load handler
 */
add_action( 'load-placester_page_placester_documents', 
    'placester_admin_documents_onload' );
function placester_admin_documents_onload() {

    // Scripts
    wp_register_script( 'placester.pdfobject', // TODO replace with min
        plugins_url( '/pdf/includes/pdfobject/code/pdfobject.js', dirname( __FILE__ ) ) );
    wp_register_script( 'placester.admin.documents',
        plugins_url( '/js/admin.documents.js', dirname( __FILE__ ) ) );
    wp_enqueue_script( 'JSON-js' );
    wp_enqueue_script( 'jquery.validate' );
    wp_enqueue_script( 'placester.pdfobject' );
    wp_enqueue_script( 'placester.admin.documents');
    wp_enqueue_script( 'jquery.multifile' );

    // Styles
    wp_register_style( 'placester.admin.documents',
        plugins_url( '/css/admin.documents.css', dirname( __FILE__ ) ) );
    wp_enqueue_style( 'placester.admin' );
    wp_enqueue_style( 'placester.admin.documents');


    if ( isset($_GET['id']) ) {
        $doc_id = $_GET['id'];
        $page_sizes_meta = get_post_meta( $doc_id, '_page_sizes', true );

    $params = array(
        'pdf_url' => get_plugin_url() . "/pdf/uploads/{$doc_id}/pages/1.pdf",
        'page_count' => count( $page_sizes_meta )
    );
        $params['property_id'] = $doc_id;

        if ( isset($_GET['p_action']) && ($_GET['p_action'] == 'edit') ) {
            $doc = get_post( $doc_id );
/*
echo "<pre>x\n";
print_r($doc);
echo "\n" . "#end x</pre>\n";  
 */
                // $params[] 
        }
    
        wp_localize_script( 'placester.admin.documents', 'docinfo', $params );
    }


}
require( 'documents_ajax.php' );

/** ---------------------------
 *  Contact page
 *  --------------------------- */

/**
 * Admin menu - "Contact" page
 */
function placester_admin_contact_html() {
    require( 'contact.php' );
}
/**
 * Admin menu - "Contact" page, on-load handler
 */
add_action( 'load-placester_page_placester_contact', 
    'placester_admin_contact_onload' );
function placester_admin_contact_onload() {
    if ( isset( $_REQUEST['ajax_action'] ) ) {
        require( 'contact_ajax.php' );
        exit();
    }

    wp_enqueue_script( 'jquery.upload' );
    wp_enqueue_script( 'placester.admin.contact',
        plugins_url( '/js/admin.contact.js', dirname( __FILE__ ) ) );

    wp_enqueue_style( 'placester.admin' );
}

/** ---------------------------
 *  My listings page
 *  --------------------------- */

/**
 * Admin menu - "Properties" page
 */
function placester_admin_properties_html() {
    if ( isset( $_REQUEST['craigslist_template'] ) )
        require( dirname( __FILE__ ) . '/templates.php' );
    else if ( isset( $_REQUEST['id'] ) )
        require( 'property_edit.php' );
    else
        require( 'properties.php' );
}

/**
 * Called after an uploadify image has been uploaded
 * 
 * Data sent in uploadify_settings.js
 */
add_action( 'wp_ajax_after_uploadify', 'ajax_after_uploadify' );
function ajax_after_uploadify() {
    // Clear the cache
    placester_clear_cache();

    // Update the wordpress listing post
    $property_id = trim( $_POST['property_id'] );
    placester_update_listing( $property_id );
    die; 
}

/**
 * Called when a image delete button has been pressed 
 * in a edit listing form
 * 
 */
add_action( 'wp_ajax_listing_image_delete', 'ajax_listing_image_delete' );
function ajax_listing_image_delete() {
    $property_id = trim( $_POST['property_id'] );
    $images = is_array( $_POST['image'] ) ? $_POST['image'] : trim( $_POST['image'] );

    if ( is_array($images) ) {
        $errors = 0;
        $successes = 0;
        foreach ($images as $image) {
            $r = placester_property_image_delete($property_id, $image);
            if ( !$r ) {
                $successes++;
            } else {
                $errors++;
            }      
            if ($successes) {
                placester_update_listing( $property_id );
            }
        }
        echo '{"s":"' . $successes . '","e":"' . $errors . '"}';
    } else {
        $r = placester_property_image_delete($property_id, $images);
        if ( !$r ) {
            echo 1;
            placester_update_listing( $property_id );
        } else {
            echo 0;
        }      
    }

    die; 
}

/**
 * Called to update the listing image list in the backend
 */
add_action( 'wp_ajax_update_images', 'ajax_update_images' );
function ajax_update_images() {
    $p = placester_property_get( $_GET['property_id'] );
    $form_url = admin_url() . 'admin.php?page=placester_properties&id=' . $p->id;
    if ( $p->images ) {
        foreach ( $p->images as $i )
        {
?>
            <div class="img">
                <img src="<?php echo $i->url ?>"/>
                <a href="<?php echo $form_url ?>&delete=<?php echo urlencode($i->id) ?>" class="remove">
                Delete
                </a>
            </div>
<?php
        }
    }
    die; 
}

/**
 * Admin menu - "Properties" page, on-load handler
 */
add_action( 'load-placester_page_placester_properties', 
    'placester_admin_properties_onload' );
function placester_admin_properties_onload() {
    if ( isset( $_REQUEST['craigslist_template'] ) ) {
        if ( isset( $_REQUEST['template_iframe'] ) ) {
             require( dirname(__FILE__) . '/template-iframe.php' );
             exit();
        }

        wp_enqueue_script( 'googlemaps_v3' );
        wp_enqueue_script( 'zeroclipboard' );
        wp_enqueue_script( 'placester.admin.templates',
            plugins_url( '/js/admin.templates.js', dirname( __FILE__ ) ) );

        wp_enqueue_style( 'placester.admin' );

        $params = array(
            'plugin_url' => get_plugin_url()
        );
        if ( isset($_GET['id']) ) 
            $params['property_id'] = $_GET['id'];

        wp_localize_script( 'placester.admin.templates', 'placester_templates', $params );
    } else {
        // if ( isset( $_REQUEST['popup'] ) ) {
        //     wp_enqueue_script( 'jquery.multifile' );

        //     // require( 'property_edit_images_popup.php' );
        //     // exit();
        // }

        wp_enqueue_script( 'swfobject' );
        wp_enqueue_script( 'uploadify' );
        wp_enqueue_script( 'uploadify_settings' );

        wp_enqueue_style( 'placester.admin' );

        // Get current page protocol
        $protocol = isset( $_SERVER['HTTPS'] ) ? 'https://' : 'http://';
        // Output admin-ajax.php URL with same protocol as current page
        $upload_dir = wp_upload_dir();
        $api_key = get_option('placester_api_key');

        $params = array(
            'ajaxurl' => get_plugin_url(),
            'wpajaxurl' => admin_url( 'admin-ajax.php', $protocol ),
            'root' => $upload_dir['path'],
            'loader' => site_url('wp-admin/images/wpspin_light.gif'),//get_plugin_url() . '/images/lightbox-ico-loading.gif',
            'api_key' => $api_key
        );
        if ( isset($_GET['id']) ) 
            $params['property_id'] = $_GET['id'];    
        wp_localize_script( 'uploadify_settings', 'uploadify_settings', $params );

        if ( isset( $_REQUEST['id'] ) ) {
            wp_enqueue_style( 'placester.admin.jquery-ui' );
            wp_enqueue_script( 'googlemaps_v3' );
            wp_enqueue_script( 'jquery.lightbox' );
            wp_enqueue_script( 'jquery.multifile' );
            wp_enqueue_script( 'jquery-ui.datepicker' );
            wp_enqueue_script( 'placester.admin.property' );
            wp_enqueue_script( 'placester.admin.property_form' );
            wp_enqueue_script( 'placester.admin.property_edit',
                plugins_url( '/js/admin.property_edit.js', dirname( __FILE__ ) ) );

            $params = array(
                'spinner' => site_url('wp-admin/images/wpspin_light.gif')
            );
            wp_localize_script( 'placester.admin.property_edit', 'params', $params );
        }

        else {
            // If on the table view
            wp_enqueue_style( 'placester.admin' );
            wp_enqueue_script( 'jquery.datatables' );
            wp_enqueue_script('placester.admin.properties',
                plugins_url( '/js/admin.properties.js', dirname( __FILE__ ) ) );
        }


    }
}
require('property_table_ajax.php');


/** ---------------------------
 *  Add listing page
 *  --------------------------- */

/**
 * Admin menu - "Add Listing" page
 */
function placester_admin_property_add_html() {
    require( 'property_add.php' );
}

/**
 * Admin menu - "Add Listing" page, on-load handler
 */
function placester_admin_property_add_onload() {

        wp_enqueue_script( 'swfobject' );
        wp_enqueue_script( 'uploadify' );
        wp_enqueue_script( 'uploadify_settings_add' );

        wp_enqueue_style( 'placester.admin' );

        // Get current page protocol
        $protocol = isset( $_SERVER['HTTPS'] ) ? 'https://' : 'http://';
        // Output admin-ajax.php URL with same protocol as current page
        $upload_dir = wp_upload_dir();
        $api_key = get_option('placester_api_key');

        $params = array(
            'ajaxurl' => get_plugin_url(),
            'wpajaxurl' => admin_url( 'admin-ajax.php', $protocol ),
            'root' => $upload_dir['path'],
            'loader' => site_url('wp-admin/images/wpspin_light.gif'),//get_plugin_url() . '/images/lightbox-ico-loading.gif',
            'api_key' => $api_key
        );
        if ( isset($_GET['id']) ) 
            $params['property_id'] = $_GET['id'];    

        wp_localize_script( 'uploadify_settings_add', 'uploadify_settings', $params );

    wp_enqueue_style( 'placester.admin.jquery-ui' );

    wp_enqueue_script( 'googlemaps_v3' );
    wp_enqueue_script( 'jquery.multifile' );
    wp_enqueue_script( 'jquery-ui.core' );
    wp_enqueue_script( 'jquery-ui.datepicker' );
    wp_enqueue_script( 'placester.admin.property' );
    wp_enqueue_script( 'placester.admin.property_form' );
}

add_action( 'load-placester_page_placester_property_add', 
    'placester_admin_property_add_onload' );

/** ---------------------------
 *  Settings page
 *  --------------------------- */

/**
 * Admin menu - "Settings" page
 */
function placester_admin_settings_html() {
    require( 'settings.php' );
}

/**
 * Admin menu - "Settings" page, on-load handler
 */
function placester_admin_settings_onload() {
    if ( isset( $_REQUEST['ajax_action'] ) ) {
        require( 'settings_ajax.php' );
        exit();
    }
    wp_enqueue_script( 'googlemaps_v3' );
    wp_enqueue_script( 'jquery.upload' );
    wp_enqueue_script( 'placester.admin.settings' );

    wp_enqueue_style( 'placester.admin' );
    
}

add_action( 'load-placester_page_placester_settings', 
    'placester_admin_settings_onload' );

/** ---------------------------
 *  Support page
 *  --------------------------- */

/**
 * Admin menu - "Support" page
 */
function placester_admin_support_html() {
    require( 'support.php' );
}

/**
 * Admin menu - "Support" page, on-load handler
 */
function placester_admin_support_onload() {
    if ( isset( $_REQUEST['ajax_action'] ) ) {
        require( 'support_ajax.php' );
        exit();
    }

    wp_enqueue_script( 'placester.admin.support',
        plugins_url( '/js/admin.support.js', dirname( __FILE__ ) ) );

    wp_enqueue_style( 'placester.admin' );
}

add_action( 'load-placester_page_placester_support', 
    'placester_admin_support_onload' );



/**
 * Admin menu - "Get Themes" page
 */
function placester_admin_themes_html() {
    require( dirname( __FILE__ ) . '/themes.php' );
}



/**
 * Admin menu - "Get Themes" page, on-load handler
 */
function placester_admin_themes_onload() {
    wp_enqueue_style( 'theme-install' );
    wp_enqueue_script( 'theme-install' );
    add_thickbox();
    wp_enqueue_script( 'theme-preview' );

    wp_enqueue_style( 'placester.admin' );
}

add_action( 'load-placester_page_placester_themes', 
    'placester_admin_themes_onload' );


/**
 * Admin menu - "Update" page
 */
function placester_admin_update_html() {
    require( dirname( __FILE__ ) . '/update.php' );
}

/**
 * Admin menu - "Update" page, on-load handler
 */
function placester_admin_update_onload() {
    wp_enqueue_style( 'placester.admin' );
}
add_action( 'load-placester_page_placester_update', 
    'placester_admin_update_onload' );


/**
 * Admin utilities
 */

/**
 * Prints error message
 *
 * @param string $message
 */
function placester_error_message( $message ) {
    ?>
    <div class="error inline">
      <p><?php echo $message ?></p>
    </div>
    <?php
}



/**
 * Prints warning message
 *
 * @param string $message
 */
function placester_warning_message( $message, $id = '', $inline = true ) {
    ?>
        <div <?php if ($id) echo 'id="' . $id . '" '; ?>class="updated <?php if ($inline) echo ' inline'; ?>">
      <p><?php echo $message ?></p>
    </div>
    <?php
}



/**
 * Prints info message
 *
 * @param string $message
 */
function placester_info_message( $e ) {
    ?>
    <div class="updated inline">
      <p><?php echo $e->getMessage(); ?></p>
    </div>
    <?php
}



/**
 * Prints success message
 *
 * @param string $message
 */
function placester_success_message( $message ) {
    ?>
    <div class="updated">
      <p><?php echo $message; ?></p>
    </div>
    <?php
}


/**
 * Header of all admin pages - shows tabs-like list
 *
 * @param string $current_page
 */
function placester_admin_header( $current_page, $title_postfix = '' ) {
    $api_key = get_option( 'placester_api_key' );
    if ( empty( $api_key ) )
        placester_warning_message(
            'You need to add your contact details before you can continue. ' .
            ' Navigate to the <a href="admin.php?page=placester_contact">' .
            'personal tab</a> and add an email address to start.',
            'warning_no_api_key');

    global $wp_rewrite;

    if ( !$wp_rewrite->using_permalinks() ) {
        placester_warning_message(
            'For best performance <input type="button" class="button " value="Enable Fancy Permalinks" onclick="document.location.href = \'/wp-admin/options-permalink.php\';">' .
            'following the directions appropriate for your ' .
            '<a href="http://codex.wordpress.org/Using_Permalinks#Choosing_your_permalink_structure">' .
            'WordPress ' . get_bloginfo( 'version' ) .
            '</a>');
    }
    
    /**
     *      Check to see if the agency is verified.
     */
    placester_verified_check()
            

    ?>
    <div id="icon-options-general" class="icon32 placester_icon"><br /></div>
    <h2 id="placester-admin-menu">
      <?php
      $current_title = '';
      $v = '';

      global $submenu;
      foreach ( $submenu['placester'] as $i ) {
          $title = $i[0];
          $slug = $i[2];
          $style = '';
          if ( $slug == $current_page ) {
              $style = 'nav-tab-active';
              $current_title = $title;
          }

          $v .= "<a href='admin.php?page=$slug' style='font-size: 15px' class='nav-tab $style'>$title</a>";
      }

      echo $current_title;
      echo $title_postfix;
      echo '&nbsp;&nbsp;&nbsp;';
      echo $v;
      ?>
    </h2>
    <?php
}



/**
 * Reloads company / user data from webservice to plugin local data storage
 */
function placester_admin_actualize_company_user() {
    $api_key = get_option( 'placester_api_key' );

    if ( strlen( $api_key ) <= 0 ) {
        update_option( 'placester_user_id', '' );
        update_option( 'placester_company_id', '' );
        update_option( 'placester_user', new StdClass );
        update_option( 'placester_company', new StdClass );
        delete_option( 'placester_api_key_type' );
    } else {
        $r = placester_apikey_info( $api_key );

        $saved_api_key_type = get_option( 'placester_api_key_type' );

        if (isset($r) && isset($r->api_key_id)) {
            update_option( 'placester_api_id', $r->api_key_id);
        }
        
        $company = $r;
        if ( isset( $company ) )
            $old_company = get_company_details();
        if ( isset( $old_company->logo ) )
            $company->logo = $old_company->logo;
        if ( isset( $old_company->description ) )
            $company->description = $old_company->description;

        $api_key_type = 'user';
        if ( isset($r->id) ) {
            if ( isset($r->user->id) ) {
                $user = placester_user_get( $r->id, $r->user->id );
                update_option( 'placester_user_id', $r->user->id );
            } else {
                $api_key_type = 'agency';
            }
            update_option( 'placester_company_id', $r->id );
        }
        // Update the API key type
        if ( $saved_api_key_type != $api_key_type ) 
            update_option( 'placester_api_key_type', $api_key_type );

        if ( isset($r->user) ) {
            $user = $r->user;

            $old_user = placester_get_user_details();
            if ( isset( $old_user->logo ) )
                $user->logo = $old_user->logo;
            if ( isset( $old_user->description ) )
                $user->description = $old_user->description;

            update_option( 'placester_user', $user );
        }

        update_option( 'placester_company', $company );

    }
}



/**
 * Create a potbox widget
 */
function placester_postbox_container_header( $styles = 'width: 100%' ) {
    ?>
    <div class="postbox-container" style="<?php echo $styles; ?>">
        <div class="metabox-holder">	
            <div class="meta-box-sortables ui-sortable">
    	
    <?php
}



function placester_postbox_container_footer() {
	?>
   			</div>
   		</div>
   	</div>
		
	<?php
}



function placester_postbox( $id, $title, $content ) {
?>
	<div id="<?php echo $id; ?>" class="postbox">
		<div class="handlediv" title="Click to toggle"><br /></div>
		<h3 class="hndle"><span><?php echo $title; ?></span></h3>
		<div class="inside">
			<?php echo $content; ?>
		</div>
	</div>
<?php
}




function placester_postbox_header( $title, $id = '' ) {
    ?>
    <div id="<?php echo $id; ?>" class="postbox">
    	<div class="handlediv" title="Click to toggle"><br /></div>
    	<h3 class="hndle"><span><?php echo $title; ?></span></h3>
    	<div class="inside">
    <?php
}



function placester_postbox_footer() {
    ?>
		</div>
	</div>
    <?php
}



/**
 * Checks is specified template is active according to current theme
 *
 * @param string $name
 */
function placester_is_template_active($name) {
    if ( substr( $name, 0, 5) == 'user_' )
        return true;

    $themes = get_allowed_themes();
    $theme_names = array();
    foreach ($themes as $t)
        $theme_names[] = $t['Stylesheet'];

    foreach ($theme_names as $theme) {
        if ( substr( $name, 0, strlen( $theme ) + 1) == $theme . '_' )
            return true;
    }

    return false;
}
