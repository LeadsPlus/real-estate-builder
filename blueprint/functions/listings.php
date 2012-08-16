<?php 

PLS_Listing_Helper::init();
class PLS_Listing_Helper {
	
	function init() {
		add_action('wp_ajax_pls_listings_for_options', array(__CLASS__,'listings_for_options'));
	}

	function listings_for_options () {
		$api_response = PLS_Plugin_API::get_property_list($_POST);
		$formatted_listings = '';
		if ($api_response['listings']) {
			foreach ($api_response['listings'] as $listing) {
			  if ( !empty($listing['location']['unit']) ) {
				  $formatted_listings .= '<option value="' . $listing['id'] . '" >' . $listing['location']['address'] . ', #' . $listing['location']['unit'] . ', ' . $listing['location']['locality'] . ', ' . $listing['location']['region'] . '</option>';
				} else {
				  $formatted_listings .= '<option value="' . $listing['id'] . '" >' . $listing['location']['address'] . ', ' . $listing['location']['locality'] . ', ' . $listing['location']['region'] . '</option>';
				}
			}
		} else {
		$formatted_listings .= "No Results. Broaden your search.";
		}
		echo json_encode($formatted_listings);
		die();
	}

	function get_featured ($featured_option_id) {
		$option_ids = pls_get_option($featured_option_id);
		if (!empty( $option_ids ) ) {
			$property_ids = array_keys($option_ids);
			$api_response = PLS_Plugin_API::get_listings_details_list(array('property_ids' => $property_ids));
			return $api_response;	
		} else {
			return array('listings' => array());
		}
		
	}

	function get_compliance ($args) {
		$message = PLS_Plugin_API::mls_message($args);
		if ($message && !empty($message) && isset($args['context'])) {
			$_POST['compliance_message'] = $message;
			PLS_Route::router(array($args['context'] . '-compliance.php'), true, false);
		}
		return false;
	}
}