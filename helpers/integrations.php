<?php 

PL_Integration_Helper::init();
class PL_Integration_Helper {
	
	public function init() {
		add_action('wp_ajax_create_integration', array(__CLASS__, 'create' ) );
	}

	public function create () {
		$response = array('result' => false, 'message' => 'There was an error. Please try again.');
		$api_response = PL_Integration::create(wp_kses_data($_POST));
		// pls_dump($api_response);
		if (isset($api_response['id'])) {
			$response = array('result' => true, 'message' => 'You\'ve successfully submitted you integration request. This page will update momentarily');
		} elseif (isset($api_response['validations'])) {
			$response = $api_response;
		} elseif (isset($api_response['code']) && $api_response['code'] == '102') {
			$response = array('result' => false, 'message' => 'You are already integrated with an MLS. To enable multiple integrations call sales at (800) 728-8391');
		}
		echo json_encode($response);
		die();
	}

	public function get () {
		$response = array();
		$integration = PL_Integration::get();
		$whoami = PL_User::whoami();
		$listings = PL_Listing::get();
		return array('integration_status' => array('integration' => $integration, 'whoami' => $whoami, 'listings' => $listings));
	}

//end of class
}