<?php 

PL_Listing_Helper::init();

class PL_Listing_Helper {

	public function init() {
		add_action('wp_ajax_datatable_ajax', array(__CLASS__, 'datatable_ajax' ) );
		add_action('wp_ajax_add_listing', array(__CLASS__, 'add_listing_ajax' ) );
		add_action('wp_ajax_update_listing', array(__CLASS__, 'update_listing_ajax' ) );
		add_action('wp_ajax_add_temp_image', array(__CLASS__, 'add_temp_image' ) );
		add_action('wp_ajax_filter_options', array(__CLASS__, 'filter_options' ) );
		add_action('wp_ajax_delete_listing', array(__CLASS__, 'delete_listing_ajax' ) );
	}
	
	public function results($args = array()) {
		if (!is_array($args)) {
			$args = wp_parse_args($args);
		} elseif (empty($args)) {
			$args = $_GET;
		}
		//respect global filters
		$global_filters = PL_Helper_User::get_global_filters();
		$args = wp_parse_args($global_filters['filters'], $args);

		$listings = PL_Listing::get($args);	
		foreach ($listings['listings'] as $key => $listing) {
			$listings['listings'][$key]['cur_data']['url'] = PL_Page_Helper::get_url($listing['id']);
			$listings['listings'][$key]['location']['full_address'] = $listing['location']['address'] . ' ' . $listing['location']['locality'] . ' ' . $listing['location']['region'];
		}
		return $listings;
	}

	public function many_details($args) {
		extract(wp_parse_args($args, array('property_ids' => array(), 'limit' => '50', 'offset' => '0')));
		$response = array();
		if (empty($property_ids)) {
			return array('listings' => array(), 'total' => 0);
		}
		$use_property_ids = array_slice($property_ids, $offset, $limit);
		foreach ($use_property_ids as $id) {
			$listing = self::details(array('id' => $id) );
			$listing['cur_data']['url'] = PL_Page_Helper::get_url($listing['id']);
			$listing['location']['full_address'] = $listing['location']['address'] . ' ' . $listing['location']['locality'] . ' ' . $listing['location']['region'];
			$response['listings'][] = $listing;
		}
		$response['total'] = count($property_ids);
		return $response;
	}

	public function details($args = array()) {
		$args['address_mode'] = 'exact';
		$listing = PL_Listing::details($args);
		//rename cur_data to metadata due to api weirdness;
		$listing['metadata'] = $listing['cur_data'];
		// unset($listing['cur_data']);
		//set compound type using combination of zoning type, purchase type, and listing type
		if (!empty($listing['zoning_types']) && !empty($listing['purchase_types']) ) {
			//residential + commercial + rental + sale combos are handled in zoning / purhcase types
			if ($listing['zoning_types'][0] == 'residential' && $listing['purchase_types'][0] == 'rental') {
				$listing['compound_type'] = 'res_rental';
			}
			if ($listing['zoning_types'][0] == 'residential' && $listing['purchase_types'][0] == 'sale') {
				$listing['compound_type'] = 'res_sale';
			}
			if ($listing['zoning_types'][0] == 'commercial' && $listing['purchase_types'][0] == 'rental') {
				$listing['compound_type'] = 'comm_rental';
			}
			if ($listing['zoning_types'][0] == 'commercial' && $listing['purchase_types'][0] == 'sale') {
				$listing['compound_type'] = 'comm_sale';
			}
		} elseif (!empty($listing['listing_types'])) {
			if ($listing['listing_types'][0] == 'sublet')  {
				$listing['compound_type'] = 'sublet';
			}
		}

		return $listing;
	}

	public function custom_attributes($args = array()) {
		$custom_attributes = PL_Custom_Attributes::get(array('attr_class' => '2'));
		return $custom_attributes;
	}

	function datatable_ajax() {
		$response = array();
		//exact addresses should be shown. 
		$_POST['address_mode'] = 'exact';

		// Sorting
		$columns = array('images','location.address', 'location.locality', 'location.region', 'location.postal', 'zoning_types', 'purchase_types', 'listing_types', 'property_type', 'cur_data.beds', 'cur_data.baths', 'cur_data.price', 'cur_data.sqft', 'cur_data.avail_on');
		$_POST['sort_by'] = $columns[$_POST['iSortCol_0']];
		$_POST['sort_type'] = $_POST['sSortDir_0'];
		
		// text searching on address
		$_POST['location']['address'] = @$_POST['sSearch'];
		$_POST['location']['address_match'] = 'like';

		// Pagination
		$_POST['limit'] = $_POST['iDisplayLength'];
		$_POST['offset'] = $_POST['iDisplayStart'];		
		
		// Get listings from model
		$api_response = PL_Listing::get($_POST);
		
		// build response for datatables.js
		$listings = array();
		foreach ($api_response['listings'] as $key => $listing) {
			$images = $listing['images'];
			$listings[$key][] = ((is_array($images) && isset($images[0])) ? '<img width=50 height=50 src="' . $images[0]['url'] . '" />' : 'empty');
			$listings[$key][] = '<a class="address" href="/wp-admin/admin.php?page=placester_property_add&id=' . $listing['id'] . '">' . $listing["location"]["address"] . ' ' . $listing["location"]["locality"] . ' ' . $listing["location"]["region"] . '</a><div class="row_actions"><a href="/wp-admin/admin.php?page=placester_property_add&id=' . $listing['id'] . '" >Edit</a><span>|</span><a href=' . PL_Page_Helper::get_url($listing['id']) . '>View</a><span>|</span><a class="red" id="pls_delete_listing" href="#" ref="'.$listing['id'].'">Delete</a></div>';
			$listings[$key][] = $listing["location"]["postal"];
			$listings[$key][] = implode($listing["zoning_types"], ', ') . ' ' . implode($listing["purchase_types"], ', ');
			$listings[$key][] = implode($listing["listing_types"], ', ');
			$listings[$key][] = $listing["property_type"];
			$listings[$key][] = $listing["cur_data"]["beds"];
			$listings[$key][] = $listing["cur_data"]["baths"];
			$listings[$key][] = $listing["cur_data"]["price"];
			$listings[$key][] = $listing["cur_data"]["sqft"];
			$listings[$key][] = date('M d, Y',strtotime($listing["cur_data"]["avail_on"]));
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
	
	public function add_listing_ajax() {
		foreach ($_POST as $key => $value) {
			if (is_int(strpos($key, 'property_type')) && $value !== 'false') {
				$_POST['property_type'] = $value;
			}
		}
		$api_response = PL_Listing::create($_POST);
		echo json_encode($api_response);
		die();
	}	

	public function update_listing_ajax() {
		foreach ($_POST as $key => $value) {
			if (is_int(strpos($key, 'property_type')) && $value !== 'false') {
				$_POST['property_type'] = $value;
			}
		}
		$api_response = PL_Listing::update($_POST);
		echo json_encode($api_response);
		die();
	}	

	public function add_temp_image() {
		$api_response = array();
		if (isset($_FILES['files'])) {
			foreach ($_FILES as $key => $image) {
				if (isset($image['name']) && is_array($image['name']) && (count($image['name']) == 1))  {
					$image['name'] = implode($image['name']);
				}
				if (isset($image['type']) && is_array($image['type']) && (count($image['type']) == 1))  {
					$image['type'] = implode($image['type']);
				}
				if (isset($image['tmp_name']) && is_array($image['tmp_name']) && (count($image['tmp_name']) == 1))  {
					$image['tmp_name'] = implode($image['tmp_name']);
				}
				if (isset($image['size']) && is_array($image['size']) && (count($image['size']) == 1))  {
					$image['size'] = implode($image['size']);
				}
				$api_response[$key] = PL_Listing::temp_image($_POST, $image['name'], $image['type'], $image['tmp_name']);
				$api_response[$key]['orig_name'] = $image['name'];
			}
			$response = array();
			if (!empty($api_response)) {
				foreach ($api_response as $key => $value) {
					$response[$key]['name']	= $value['filename'];
					$response[$key]['orig_name'] = $value['orig_name'];
					$response[$key]['url'] = $value['url'];
				}
			}
		}		
		header('Vary: Accept');
		header('Content-type: application/json');
		echo json_encode($response);
		die();
	}

	public function delete_listing_ajax () {
		$api_response = PL_Listing::delete($_POST);
		//api returns empty, with successful header. Return actual message so js doesn't explode trying to check empty.
		if (empty($api_response)) { 
			echo json_encode(array('response' => true, 'message' => 'Listing successfully deleted. This page will reload momentarily.'));	
			PL_HTTP::clear_cache();
		} elseif ( isset($api_response['code']) && $api_response['code'] == 1800 ) {
			echo json_encode(array('response' => false, 'message' => 'Cannot find listing. Try <a href="/wp-admin/admin.php?page=placester_settings">emptying your cache</a>.'));
		}
		die();
	}

	public function locations_for_options($return_only) {
		$options = array('false' => 'Any');
		$response = PL_Listing::locations();
		if ($return_only && isset($response[$return_only])) {
			foreach ($response[$return_only] as $key => $value) {
				$options[$value] = $value;
			}
			return $options;	
		} else {
			return array();	
		}
	}

	public function pricing_min_options($type = 'min') {
		$api_response = PL_Listing::get();
		$prices = array();
		foreach ($api_response['listings'] as $key => $listing) {
			$prices[] = $listing['cur_data']['price'];
		}
		sort($prices);
		if (is_array($prices) && !empty($prices)) {
			$range = ($prices[0] - end($prices))/10;
			if ($type == 'max') {
				$range = range($prices[0], end($prices), $range);
				return $range;
			} else {
				$range = range($prices[0], end($prices), $range);
				array_pop($range);
				return $range;
			}
		} else {
			return array();
		}
	}

	public function filter_options () {
		$option_name = 'pl_my_listings_filters';
		$options = get_option($option_name);
		if (isset($_POST['filter']) && isset($_POST['value']) && $options) {
			$options[$_POST['filter']] = $_POST['value'];
			update_option($option_name, $options);
		} elseif (isset($_POST['filter']) && isset($_POST['value']) && !$options) {
			$options = array($_POST['filter'] => $_POST['value']);
			add_option($option_name, $options);
		}
		echo json_encode($options);
		die();
	}

//end of class
}