<?php 


class PL_Options {
	
	public function get ($option, $default = false) {
		return get_option($option, $default);
	}

	public function set ($option, $value) {
		if (get_option($option, NULL) !== NULL) {
			return update_option($option, $value);
		} else {
			return add_option($option, $value);
		}
		
	}

}