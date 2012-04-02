<?php 

if ( ! current_user_can('install_themes') ) {
	wp_die(__('You do not have sufficient permissions to install themes for this site.'));
}
			
include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
// require_once(ABSPATH . 'wp-admin/admin-header.php');
$upgrader = new Theme_Upgrader( new Theme_Installer_Skin( compact('title', 'url', 'nonce', 'plugin', 'api') ) );
$upgrader->install($_GET['theme_url']);
// include(ABSPATH . 'wp-admin/admin-footer.php');