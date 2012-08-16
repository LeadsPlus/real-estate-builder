<?php 

PLS_Membership::init();
class PLS_Membership {

	public function init () {
		add_action('wp_ajax_pls_update_client_profile', array(__CLASS__, 'update' ) );
	}

	public function update () {
		$person_details = $_POST;
		echo json_encode(PLS_Plugin_API::update_person_details($person_details));
		die();
	}

	public function create_person ($args) {
		return PLS_Plugin_API::create_person($args);
	}
}