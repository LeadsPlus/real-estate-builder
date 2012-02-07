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
		$columns = array('location.address', 'location.locality', 'location.region', 'location.postal', 'zoning_types', 'purchase_types', 'listing_types', 'property_type', 'cur_data.beds', 'cur_data.baths', 'cur_data.price', 'cur_data.sqft', 'cur_data.avail_on');
		$_POST['sort_by'] = $columns[$_POST['iSortCol_0']];
		$_POST['sort_type'] = $_POST['sSortDir_0'];

		// Need Pagination
		$_POST['limit'] = $_POST['iDisplayLength'];
		$_POST['skip'] = $_POST['iDisplayStart'];		
		$api_response = PL_Listing::get($_POST);
		
		$listings = array();
		foreach ($api_response['listings'] as $key => $listing) {
			$listings[$key][] = $api_response['listings'][$key]["location"]["address"];
			$listings[$key][] = $api_response['listings'][$key]["location"]["locality"];
			$listings[$key][] = $api_response['listings'][$key]["location"]["region"];
			$listings[$key][] = $api_response['listings'][$key]["location"]["postal"];
			$listings[$key][] = implode($api_response['listings'][$key]["zoning_types"], ', ');
			$listings[$key][] = implode($api_response['listings'][$key]["purchase_types"], ', ');
			$listings[$key][] = implode($api_response['listings'][$key]["listing_types"], ', ');
			$listings[$key][] = $api_response['listings'][$key]["property_type"];
			$listings[$key][] = $api_response['listings'][$key]["cur_data"]["beds"];
			$listings[$key][] = $api_response['listings'][$key]["cur_data"]["baths"];
			$listings[$key][] = $api_response['listings'][$key]["cur_data"]["price"];
			$listings[$key][] = $api_response['listings'][$key]["cur_data"]["sqft"];
			$listings[$key][] = $api_response['listings'][$key]["cur_data"]["avail_on"];
		}
		$response['sEcho'] = $_POST['sEcho'];
		$response['aaData'] = $listings;
		$response['iTotalRecords'] = $api_response['total'];
		$response['iTotalDisplayRecords'] = $api_response['total'];
		echo json_encode($response);
		die();
	}	

//end of class
}