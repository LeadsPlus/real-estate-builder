<?php 

PL_Page_Helper::init();
class PL_Page_Helper {

	function init() {
		add_action('wp_ajax_ajax_delete_all', array(__CLASS__, 'ajax_delete_all' ) );
		add_action('wp_ajax_get_pages', array(__CLASS__, 'get_pages_datatable' ) );
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

	function get_pages_datatable ($placester_id) {
		$response = array();
		
		// Get listings from model
		$pages = PL_Pages::get();
		// pl_dump($pages);

		$items = array();

		if (!empty($pages)) {
			foreach ($pages as $key => $page) {

				$items[$key][] = $page['ID'];
				$items[$key][] = $page['post_date'];
				$items[$key][] = $page['post_name'];
				$items[$key][] = $page['post_title'];
				$items[$key][] = '<div class="overflow">' . $page['post_excerpt'] . '</div>';
				$items[$key][] = '<div class="overflow">' . $page['post_content'] . '</div>';
				$items[$key][] = '<a href="'.$page['guid'].'">View</a> | <a href="#" id="'.$page['ID'].'" class="delete_cache">Delete</a>';
			}
		}
		

		// Required for datatables.js to function properly.
		// $response['sEcho'] = $_POST['sEcho'];
		$response['aaData'] = $items;
		$response['iTotalRecords'] = count($pages);
		$response['iTotalDisplayRecords'] = count($pages);
		echo json_encode($response);

		//wordpress echos out a 0 randomly. die prevents it.
		die();
	}

}