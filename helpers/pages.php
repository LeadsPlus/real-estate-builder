<?php 

PL_Page_Helper::init();
class PL_Page_Helper {

	function init() {
		add_action('wp_ajax_ajax_delete_all', array(__CLASS__, 'ajax_delete_all' ) );
	}

	function ajax_delete_all () {
		$response = PL_Pages::delete_all();
		$reply = array('result' => false, 'message' => "There was an error. Your property pages we're removed. Try refreshing.");
		if ($response) {
			$reply = array('result' => true, 'message' => "You've successfully deleted all your property pages");
		} 
		echo json_encode($reply);
		die();
	}

	function ajax_get_types () {

	}

	function get_types () {
		$page_details = array();
		$pages = PL_Pages::get();
		// pls_dump($pages);
		$page_details['total_pages'] = count($pages);
		$page_details['pages'] = $pages;
		return $page_details;
	}

	function get_url ($placester_id) {
		$url = get_permalink(PL_Pages::details($placester_id));
		return $url;
	}
}