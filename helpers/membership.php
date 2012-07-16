<?php 

PL_Membership_Helper::init();
class PL_Membership_Helper {

	function init () {
		add_action( 'wp_ajax_set_client_settings', array('PL_Membership_Helper', 'set_client_settings'  )); 
	}

	function get_client_settings () {
		$send_client_message = get_option('pls_send_client_option');
		$send_client_message_text = get_option('pls_send_client_text');
		return array('send_client_message' => $send_client_message, 'send_client_message_text' => $send_client_message_text);
	}

	function set_client_settings () {
		$send_client_message = isset($_POST['send_client_message']) ? $_POST['send_client_message'] : false;
		$send_client_message_text = isset($_POST['send_client_message_text']) ? $_POST['send_client_message_text'] : false;
		PL_Options::set('pls_send_client_option', $send_client_message);
		PL_Options::set('pls_send_client_text', $send_client_message_text);
		echo json_encode(array('result' => true, 'message' => 'You\'ve successfully updated your options'));
		die();
	}

}