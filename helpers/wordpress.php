<?php 

class PL_WordPress_Helper {


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