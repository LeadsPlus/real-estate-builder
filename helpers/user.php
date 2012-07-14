<?php 

PL_Helper_User::init();
class PL_Helper_User {
	
	public function init() {
		add_action('sign-up-action', array(__CLASS__, 'set_admin_email' ) );
		add_action('wp_ajax_set_placester_api_key', array(__CLASS__, 'set_placester_api_key' ) );
		add_action('wp_ajax_existing_api_key_view', array(__CLASS__, 'existing_api_key_view' ) );
		add_action('wp_ajax_new_api_key_view', array(__CLASS__, 'new_api_key_view' ) );
		add_action('wp_ajax_create_account', array(__CLASS__, 'create_account' ) );
		add_action('wp_ajax_user_save_global_filters', array(__CLASS__, 'set_global_filters' ) );
		add_action('wp_ajax_user_remove_all_global_filters', array(__CLASS__, 'remove_all_global_filters' ) );
		add_action('wp_ajax_ajax_log_errors', array(__CLASS__, 'ajax_log_errors' ) );
		add_action('wp_ajax_ajax_block_address', array(__CLASS__, 'ajax_block_address' ) );
		add_action('wp_ajax_ajax_default_address', array(__CLASS__, 'set_default_country' ) );
		add_action('wp_ajax_whoami', array(__CLASS__, 'ajax_whoami' ) );
		add_action('wp_ajax_subscriptions', array(__CLASS__, 'ajax_subscriptions' ) );
		add_action('wp_ajax_start_subscription_trial', array(__CLASS__, 'start_subscription_trial' ) );
		add_action('wp_ajax_update_user', array(__CLASS__, 'ajax_update_user' ) );
		add_action('wp_ajax_update_google_places', array(__CLASS__, 'update_google_places' ) );
	}

	public static function set_admin_email (){
		$_POST['email'] = get_option('admin_email');
	}

	public static function ajax_subscriptions() {
		echo json_encode(PL_User::subscriptions());
		die();
	}

	public static function start_subscription_trial() {
		echo json_encode(PL_User::start_subscription_trial());
		die();
	}

	public static function update_google_places () {
		if (isset($_POST['places_key'])) {
			$response = PL_Option_Helper::set_google_places_key($_POST['places_key']);
			echo json_encode($response);
		}
		die();
	}

	public static function ajax_whoami() {
		echo json_encode(PL_User::whoami());
		die();
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

	public function ajax_update_user () {
		$response = array('result' => false, 'message' => 'There was an error. Please try again.');
		$whoami = self::whoami();
		$_POST['id'] = $whoami['user']['id'];
		$_POST['email'] = $whoami['user']['email'];
		$api_response = self::update_user($_POST);
		if ($api_response['id']) {
			$response = array('result' => true, 'message' => 'Account successfully updated.');
			echo json_encode($response);
		} elseif ($api_response['validations']) {
			echo json_encode($api_response);
		}
		die();
	}

	public function update_user ($args = array()) {
		$response = PL_User::update($args);
		return $response;
	}

	public function remove_all_global_filters () {
		$response = PL_Option_Helper::set_global_filters(array('filters' => array()));
		if ($response) {
			echo json_encode(array('result' => true, 'message' => 'You successfully removed all global search filters'));
		} else {
			echo json_encode(array('result' => false, 'message' => 'Change not saved or no change detected. Please try again.'));
		}
		die();
	}

	public function get_global_filters () {
		$response = PL_Option_Helper::get_global_filters();
		return $response;
	}

	public function set_global_filters ($args = array()) {
		if (empty($args) ) {
			unset($_POST['action']);
			$args = $_POST;
		}
		
		$global_search_filters = PL_Validate::request($args, PL_Config::PL_API_LISTINGS('get', 'args'));
		// pls_dump($global_search_filters);
		foreach ($global_search_filters as $key => $filter) {
			foreach ($filter as $subkey => $subfilter) {
				if (!is_array($subfilter) && (count($filter) > 1) ) {
					$global_search_filters[$key . '_match'] = 'in';
				} elseif (count($subfilter) > 1) {
					$global_search_filters[$key][$subkey . '_match'] = 'in';
				}
			}
		}
		// pls_dump($global_search_filters);
		$response = PL_Option_Helper::set_global_filters(array('filters' => $global_search_filters));
		if ($response) {
			echo json_encode(array('result' => true, 'message' => 'You successfully updated the global search filters'));
		} else {
			echo json_encode(array('result' => false, 'message' => 'Change not saved or no change detected. Please try again.'));
		}
		echo json_encode(PL_WordPress_Helper::report_filters());
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

	public function set_default_country () {
		if (isset($_POST['country'])) {
			$response = PL_Option_Helper::set_default_country($_POST['country']);
			if ($response) {
				echo json_encode(array('result' => true, 'message' => 'You successfully saved the default country'));
			} else {
				echo json_encode(array('result' => true, 'message' => 'Thats already your default country'));
			}
		} else {
			echo json_encode( array('result' => false, 'message' => 'There was an error. Country was not provided') );
		}
		die();
	}

	public function get_default_country () {
		$response = PL_Option_Helper::get_default_country();
		if (empty($response)) {
			return array('default_country' => 'US');
		} 
		return array('default_country' => $response);
		
	}
}	