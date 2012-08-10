<?php

PL_Snippet_Helper::init();

class PL_Snippet_Helper {

	// Static?  Why or why not?
	public function init() {
		add_action( 'wp_ajax_get_snippet_body', array(__CLASS__, 'get_snippet_body_ajax' ) );
		add_action( 'wp_ajax_set_shortcode_snippet', array(__CLASS__, 'set_shortcode_snippet') );
	}	

	// Static?  Why or why not?
	public function get_snippet_body_ajax() {
		if ($_POST['shortcode'] && $_POST['snippet']) {
			$response = array( 'snippet_body' => PL_Router::load_snippet($_POST['shortcode'], $_POST['snippet']) );
			echo json_encode($response);
		} else {
			echo array();
		}
		die();
	}

	public function set_shortcode_snippet() {
		if ($_POST['shortcode'] && $_POST['snippet']) {
			$option_key = ('pls_' . $_POST['shortcode']);
			update_option($option_key, $_POST['snippet']);

			// Try to retrieve the option that was just set...
			$stored_snippet = get_option($option_key);
			echo json_encode(array('stored_snippet' => $stored_snippet));
		} else {
			echo array();
		}
		die();
	}
}

?>