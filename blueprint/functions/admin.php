<?php
/**
 * The admin functions file. This file is for setting up any basic features 
 * and holding additional admin helper functions.
 *
 * @package PlacesterBlueprint
 * @subpackage Functions
 */

/**
 * Adds and admin notice if the Placester plugin is not installed
 * 
 * @uses pls_is_plugin_active() Returns wether the Placester plugin is active.
 * @uses pls_h() Generates html.
 * @since 0.0.1
 */
add_action( 'admin_notices', 'pls_no_plugin_notice' );
function pls_no_plugin_notice() {

    if ( ! pls_is_plugin_active() ) {
        echo pls_h( 
            'div',
            array( 'class' => 'error' ),
            pls_h( 
                'p',
                'You are currently running a Placester enabled theme without the Placester plugin active. This will not work. Please download and install the plugin. <a class="button" style="color: #000" href="' . admin_url( 'plugin-install.php?tab=search&type=term&s=placester&plugin-search-input=Search+Plugins' ) . '">Download Placester Plugin</a>'
            )
        );
    }
}
