<?php 

PL_Themes::init();
class PL_Themes {

	public function init() {
		add_action('wp_ajax_install_theme', array(__CLASS__, 'install_theme'));
	}

	public function install_theme() {
		if ( ! current_user_can('install_themes') ) {
			wp_die(__('You do not have sufficient permissions to install themes for this site.'));
		}
			
		include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

		
		// $title = sprintf( __('Installing Theme: %s'), $api->name . ' ' . $api->version );
		// $nonce = 'install-theme_' . $theme;
		// $url = 'update.php?action=install-theme&theme=' . $theme;
		// $type = 'web'; //Install theme type, From Web or an Upload.
		
		$upgrader = new Theme_Upgrader( new Theme_Installer_Skin( compact('title', 'url', 'nonce', 'plugin', 'api') ) );
		$upgrader->install($_POST['theme_url']);
		include(ABSPATH . 'wp-admin/admin-footer.php');
	}
}