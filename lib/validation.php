<?php 

class PL_Validate {
	
	public function attributes ($args, $defaults) {
		$merged_args = wp_parse_args($args, $defaults);
		foreach ($merged_args as $key => $value) {
			if( is_array($value) && isset($defaults[$key]) ) {
				$merged_args[$key] = wp_parse_args($value, $defaults[$key]);
			}
		}
		return $merged_args;
	}

	// build request, respect incoming args, populate defaults as passed via configs
	public function request ($args, $config) {
 		foreach ($config as $arg => $value) {
 			if( !isset($args[$arg]) && is_array($value) && isset($value['default']) ) {
 				$args[$arg] = $value['default'];
 			}
 		}
		return $args;
	}
	
//ends class
}