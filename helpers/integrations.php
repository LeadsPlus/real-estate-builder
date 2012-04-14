<?php 

PL_Integration_Helper::init();
class PL_Integration_Helper {
	
	public function init() {
		add_action('wp_ajax_create_integration', array(__CLASS__, 'create' ) );
	}

	public function create () {
		$response = array('result' => false, 'message' => 'There was an error. Please try again.');
		$api_response = PL_Integration::create(wp_kses_data($_POST));
		if (isset($api_response['id'])) {
			$response = array('result' => true, 'message' => 'You\'ve successfully submitted you integration request. This page will update momentarily');
		} elseif (isset($api_response['validations'])) {
			$response = $api_response;
		}
		echo json_encode($response);
		die();
	}

//end of class
}