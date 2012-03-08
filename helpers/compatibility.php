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
function placester_get_user_details() {}
function placester_get_property_url ($placester_id) {return PL_Page_Helper::get_url($placester_id); }