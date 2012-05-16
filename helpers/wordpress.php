<?php 

PL_WordPress_Helper::init();
class PL_WordPress_Helper {
	
	function init () {
		add_action('switch_theme', array(__CLASS__, 'report_theme'));
	}

	function report_theme () {
		$theme = strtolower(get_current_theme());
		$response = PL_WordPress::set(array_merge(array('theme' => $theme), array('url' => site_url() ) ) );
		return $response;
	}

	function report_url () {
		$request = array('url' => site_url());
		$response = PL_WordPress::set($request);
	}

	function report_filters () {
		$response = PL_WordPress::set(array_merge(PL_Helper_User::get_global_filters(), array('url' => site_url() ) ) );
		return $response;
	}

	function remote_filter_update ($args = array()) {
		$args = wp_parse_args($args);
		PL_Helper_User::set_global_filters($args);
	}
}