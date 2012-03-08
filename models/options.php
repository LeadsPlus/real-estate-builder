<?php 


class PL_Options {
	
	public function get ($option) {
		return get_option($option);
	}

	public function set ($option, $value) {
		if (get_option($option)) {
			return update_option($option, $value);
		} else {
			return add_option($option, $value);
		}
		
	}

}