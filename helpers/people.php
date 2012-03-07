<?php 

PL_People_Helper::init();
class PL_People_Helper {

	public function init() {
		add_action('wp_ajax_add_person', array(__CLASS__, 'add_person_ajax' ) );
	}

	public function add_person($args = array()) {
		return PL_People::create($args);
	}	

	public function add_person_ajax() {
		$api_response = PL_People::create($_POST);
		echo json_encode($api_response);
		die();
	}	

	public function update_person_details ($person_details) {
		$placester_person = self::person_details();
		return PL_People::update(array_merge(array('id' => $placester_person['id']), $person_details));
	}

	public function person_details () {
		$wp_user = PL_Membership::get_user();
		$placester_id = get_user_meta($wp_user->ID, 'placester_api_id');
		if (is_array($placester_id)) { $placester_id = implode($placester_id, ''); }
		return PL_People::details(array('id' => $placester_id));
	}

	public function associate_property($property_id) {
		$placester_person = self::person_details();
		$new_favorites = array($property_id);
		if (is_array($placester_person['fav_listings'])) {
			foreach ($placester_person['fav_listings'] as $fav_listings) {
				$new_favorites[] = $fav_listings['id'];
			}
		}
		return PL_People::update(array('id' => $placester_person['id'], 'fav_listing_ids' => $new_favorites ) );
	}	

	public function unassociate_property($args) {
		return PL_People::update($args);
	}	
}