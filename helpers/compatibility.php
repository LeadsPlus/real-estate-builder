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
class Placester_Contact_Widget { function widget() {}}
function placester_get_user_details() {}