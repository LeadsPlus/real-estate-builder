<?php 

PL_Listing_Helper::init();

class PL_Listing_Helper {

	public function init() {
		add_action('wp_ajax_datatable_ajax', array(__CLASS__, 'datatable_ajax' ) );
	}
	
	public function results( $params = array() ) {
		$listings = PL_Listing::get($_GET);	
		return $listings;
	}

	function datatable_ajax() {
		$response = array();

		// Sorting
		$columns = array('images','location.address', 'location.locality', 'location.region', 'location.postal', 'zoning_types', 'purchase_types', 'listing_types', 'property_type', 'cur_data.beds', 'cur_data.baths', 'cur_data.price', 'cur_data.sqft', 'cur_data.avail_on');
		$_POST['sort_by'] = $columns[$_POST['iSortCol_0']];
		$_POST['sort_type'] = $_POST['sSortDir_0'];

		// Pagination
		$_POST['limit'] = $_POST['iDisplayLength'];
		$_POST['offset'] = $_POST['iDisplayStart'];		
		
		// Get listings from model
		$api_response = PL_Listing::get($_POST);
		
		// build response for datatables.js
		$listings = array();
		foreach ($api_response['listings'] as $key => $listing) {
			
			$images = $listing['images'];
			$listings[$key][] = ((is_array($images) && isset($images[0])) ? '<img width=75 height=75 src="' . $images[0]['url'] . '" />' : 'empty');
			$listings[$key][] = $listing["location"]["address"];
			$listings[$key][] = $listing["location"]["locality"];
			$listings[$key][] = $listing["location"]["region"];
			$listings[$key][] = $listing["location"]["postal"];
			$listings[$key][] = implode($listing["zoning_types"], ', ');
			$listings[$key][] = implode($listing["purchase_types"], ', ');
			$listings[$key][] = implode($listing["listing_types"], ', ');
			$listings[$key][] = $listing["property_type"];
			$listings[$key][] = $listing["cur_data"]["beds"];
			$listings[$key][] = $listing["cur_data"]["baths"];
			$listings[$key][] = $listing["cur_data"]["price"];
			$listings[$key][] = $listing["cur_data"]["sqft"];
			$listings[$key][] = $listing["cur_data"]["avail_on"];
		}

		// Required for datatables.js to function properly.
		$response['sEcho'] = $_POST['sEcho'];
		$response['aaData'] = $listings;
		$response['iTotalRecords'] = $api_response['total'];
		$response['iTotalDisplayRecords'] = $api_response['total'];
		echo json_encode($response);

		//wordpress echos out a 0 randomly. die prevents it.
		die();
	}	

//end of class
}