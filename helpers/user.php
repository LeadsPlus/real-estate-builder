<?php 

PL_Helper_User::init();
class PL_Helper_User {
	
	public function init() {
		add_action('sign-up-action', array(__CLASS__, 'admin_email' ) );
		add_action('wp_ajax_check_placester_api_key', array(__CLASS__, 'check_placester_api_key' ) );
	}

	public static function admin_email (){
		$_POST['email'] = get_option('admin_email');
	}

	public static function whoami($args = array()) {
		return PL_User::whoami($args);
	}

	public function check_placester_api_key() {
		$result = PL_Option_Helper::set_api_key($_POST['api_key']);
		echo json_encode($result);
		die();
	}
}