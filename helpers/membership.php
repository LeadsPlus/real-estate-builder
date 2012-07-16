<?php 

PL_Membership_Helper::init();
class PL_Membership_Helper {

	function init () {
		add_action( 'wp_ajax_set_client_settings', array('PL_Membership_Helper', 'set_client_settings'  )); 
		add_action( 'wp', array(__CLASS__, 'admin_bar'  )); 
	}

	function admin_bar() {
		if (current_user_can( 'placester_lead' )) {
			add_filter('show_admin_bar', '__return_false');
		}
	}

	function get_client_settings () {
		$send_client_message = get_option('pls_send_client_option');
		$send_client_message_text = get_option('pls_send_client_text');
		if (!$send_client_message_text) {
			$send_client_message_text = "Hey %client_email%,\n";
			$send_client_message_text .= "\n";
			$send_client_message_text .= "Thanks for signing up for an account on %website_url%. We update the site regularly with new listings. Feel free to reach out at %email_address% with any questions. We\'d be more then happy to help.\n";
			$send_client_message_text .= "\n";
			$send_client_message_text .= "Best,\n";
			$send_client_message_text .= "%full_name%\n";
		}
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

	function parse_client_message ($client) {
		$settings = self::get_client_settings();
		$send_client_message_text = $settings['send_client_message_text'];
		$admin_details = PL_Helper_User::whoami();
		$replacements = array('%client_email%' => $client['username'], '%email_address%' => $admin_details['user']['email'], '%full_name%' => $admin_details['user']['first_name'] . ' ' . $admin_details['user']['last_name'], '%first_name%' => $admin_details['user']['first_name'], '%website_url%' => site_url()); 
		foreach ($replacements as $key => $value) {
			$send_client_message_text = str_replace($key, $value, $send_client_message_text);
			// pl_dump($send_client_message_text);
		}
		// pl_dump($send_client_message_text);
		return $send_client_message_text;
	}

}