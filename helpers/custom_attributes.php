<?php 

class PL_Custom_Attribute_Helper {

	function get_translations () {
		$dictionary = PL_Option_Helper::get_translations();
		self::update_translations($dictionary);
		return $dictionary;
	}

	private function update_translations () {
		$old_dictionary = PL_Option_Helper::get_translations();
		$new_dictionary = PL_Custom_Attributes::get();
		foreach ($new_dictionary as $item) {
			if (isset($old_dictionary[$item['key']])) {
				continue;
			} else {
				$old_dictionary[$item['key']] = $item['name'];
			}
		}
		PL_Option_Helper::set_translations($old_dictionary);	
		return $old_dictionary;
	}
}