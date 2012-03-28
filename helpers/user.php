<?php 

PL_Helper_User::init();
class PL_Helper_User {
	
	public function init() {
		add_action('sign-up-action', array(__CLASS__, 'set_admin_email' ) );
		add_action('wp_ajax_set_placester_api_key', array(__CLASS__, 'set_placester_api_key' ) );
		add_action('wp_ajax_existing_api_key_view', array(__CLASS__, 'existing_api_key_view' ) );
		add_action('wp_ajax_new_api_key_view', array(__CLASS__, 'new_api_key_view' ) );
		add_action('wp_ajax_create_account', array(__CLASS__, 'create_account' ) );
		add_action('wp_ajax_user_empty_cache', array(__CLASS__, 'empty_cache' ) );
		add_action('wp_ajax_user_save_global_filters', array(__CLASS__, 'set_global_filters' ) );
		add_action('wp_ajax_ajax_log_errors', array(__CLASS__, 'ajax_log_errors' ) );
		add_action('wp_ajax_ajax_block_address', array(__CLASS__, 'ajax_block_address' ) );
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

	public function get_global_filters () {
		$response = PL_Option_Helper::get_global_filters();
		return array('filters' => $response);
	}

	public function set_global_filters () {
		unset($_POST['action']);
		$global_search_filters = PL_Validate::request($_POST, PL_Config::PL_API_LISTINGS('get', 'args'));
		$response = PL_Option_Helper::set_global_filters(array('filters' => $global_search_filters));
		if ($response) {
			echo json_encode(array('result' => true, 'message' => 'You successfully updated the global search filters'));
		} else {
			echo json_encode(array('result' => false, 'message' => 'Change not saved or not change detected. Please try again.'));
		}
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

	public function get_cached_items() {
		return array('num_cached_items' => PL_Http::num_items_cached());
	}

	public function empty_cache() {
		PL_Http::clear_cache();
		echo json_encode(array('result' => true, 'message' => 'You\'ve successfully cleared your cache'));
		die();
	}

	public function ajax_log_errors () {
		if ( $_POST['report_errors'] == 'true') {
			$report_errors = 1;
		} else {
			$report_errors = 0;
		}
		$api_response = PL_Option_Helper::set_log_errors($report_errors);
		if ($api_response) {
			if ($report_errors) {
				echo json_encode(array('result' => true, 'message' => 'You successfully turned on error reporting'));
			} else {
				echo json_encode(array('result' => true, 'message' => 'You successfully turned off errror reporting'));
			}
		} else {
			echo json_encode(array('result' => false, 'message' => 'There was an error. Please try again.'));
		}
		die();
	}

	public function ajax_block_address () {
		if ( $_POST['use_block_address'] == 'true') {
			$block_address = 1;
		} else {
			$block_address = 0;
		}
		$api_response = PL_Option_Helper::set_block_address($block_address);
		if ($api_response) {
			PL_Http::clear_cache();
			PL_Pages::delete_all();		
			if ($block_address) {
				echo json_encode(array('result' => true, 'message' => 'You successfully turned on block addresses'));
			} else {
				echo json_encode(array('result' => true, 'message' => 'You successfully turned off block addresses'));
			}
		} else {
			echo json_encode( array('result' => false, 'message' => 'There was an error. Please try again.') );
		}
		die();
	}
}	