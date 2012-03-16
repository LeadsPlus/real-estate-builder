<?php 

function placester_get_api_key () { return PL_Option_Helper::api_key(); }
function placester_post_slug () { return PL_Option_Helper::post_slug(); }
function placester_register_filter_form() {return;}
function placester_location_list () {
	$locations->city = array();
	$locations->state = array();
	$locations->zip = array();
 	return $locations;
}
function placester_listings_list () {}
function placester_get_user_details() {
	$whoami = PL_Helper_User::whoami();
	if ($whoami['user']) {
		$user = $whoami['user'];
		$user_object->first_name = $user['first_name'];
		$user_object->last_name = $user['last_name'];
		$user_object->email = $user['email'];
		$user_object->phone = $user['phone'];
		$user_object->logo_url = $user['headshot'];
		$user_object->description = '';
	} else {
		$user_object->first_name = '';
		$user_object->last_name = '';
		$user_object->phone = '';
		$user_object->email = '';
		$user_object->logo_url = '';
		$user_object->description = '';
	}
	return $user_object;
}
function get_company_details () {
	$whoami = PL_Helper_User::whoami();
	$company_object->description = $whoami['slogan'];
	$company_object->logo_url = $whoami['logo'];
	$company_object->name = $whoami['name'];
	$company_object->phone = $whoami['phone'];
	$company_object->email = $whoami['email'];
	return $company_object;
}
function placester_get_property_url ($placester_id) {return PL_Page_Helper::get_url($placester_id); }
function placester_property_list () {
	$featured_listings->properties = array();
	$api_response = PL_Listing_Helper::results(array());
	foreach ($api_response['listings'] as $key => $listing) {
		$featured_listings->properties[$key]->id = $listing['id'];
		$featured_listings->properties[$key]->location->full_address = $listing['location']['full_address'];
		$featured_listings->properties[$key]->images = $listing['images'];
		$featured_listings->properties[$key]->bedrooms = $listing['cur_data']['beds'];
		$featured_listings->properties[$key]->bathrooms = $listing['cur_data']['baths'];
		$featured_listings->properties[$key]->price = $listing['cur_data']['price'];
		$featured_listings->properties[$key]->available_on = $listing['cur_data']['avail_on'];
	}
	return $featured_listings;
}