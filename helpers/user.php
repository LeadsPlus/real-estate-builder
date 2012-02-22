<?php 

PL_Helper_User::init();
class PL_Helper_User {
	
	public function init() {
		add_action('sign-up-action', array(__CLASS__, 'set_admin_email' ) );
		add_action('wp_ajax_set_placester_api_key', array(__CLASS__, 'set_placester_api_key' ) );
		add_action('wp_ajax_existing_api_key_view', array(__CLASS__, 'existing_api_key_view' ) );
		add_action('wp_ajax_new_api_key_view', array(__CLASS__, 'new_api_key_view' ) );
		add_action('wp_ajax_create_account', array(__CLASS__, 'create_account' ) );
	}

	public static function set_admin_email (){
		$_POST['email'] = get_option('admin_email');
	}

	public static function whoami($args = array()) {
		return PL_User::whoami($args);
	}

	public static function existing_api_key_view () {
		echo PL_Router::load_builder_partial('existing-placester.php');
		die();
	}

	public static function new_api_key_view() {
		self::set_admin_email();
		echo PL_Router::load_builder_partial('sign-up.php');
		die();	
	}

	public function set_placester_api_key() {
		$result = PL_Option_Helper::set_api_key($_POST['api_key']);
		echo json_encode($result);
		die();
	}

	public function create_account() {
		if ($_POST['email']) {
			$response = PL_User::create(array('email'=>$_POST['email']) );
			if ($response) {
				echo json_encode($response);
			} else {
				echo json_encode(array(false, 'There was an error. Is that a valid email address?'));
			}
		} else {
			echo json_encode(array(false, 'No Email Provided'));
		}
		die();
	}
}