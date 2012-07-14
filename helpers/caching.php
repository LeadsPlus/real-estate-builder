<?php 
PL_Caching_Helper::init();
class PL_Caching_Helper {

	public static function init () {
		add_action('wp_ajax_get_cache_items', array(__CLASS__, 'items_for_datatable' ) );
	}

	public static function items_for_datatable () {
		$response = array();
		//exact addresses should be shown. 
		
		// Get listings from model
		$cached_items = PL_Cache::items($_POST);
		// pl_dump($cached_items);

		$items = array();
		foreach ($cached_items as $key => $item) {
			$option_name_explode = explode('_', $item['option_name']);

			$items[$key][] = $item['option_id'];
			$items[$key][] = $option_name_explode[4];
			$items[$key][] = $option_name_explode[3];
			$items[$key][] = $item['option_value'];
			$items[$key][] = '<a href="#" id="'.$item['option_name'].'" class="delete_cache">Delete</a>';
		}

		// Required for datatables.js to function properly.
		// $response['sEcho'] = $_POST['sEcho'];
		$response['aaData'] = $items;
		$response['iTotalRecords'] = count($cached_items);
		$response['iTotalDisplayRecords'] = count($cached_items);
		echo json_encode($response);

		//wordpress echos out a 0 randomly. die prevents it.
		die();
	}

	public static function num_items () {
    	$items = PL_Cache::items();
		return array('num_cached_items' => count($items));
	}

}