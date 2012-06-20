<?php 

class PL_Education_Helper {

	function get_schools ($args = array()) {
		$args = wp_parse_args($args, array('edu_key' => false, 'area_search' =>  false, 'lat_lng_search' => false, 'search_params' => '', 'maxResult' => 10 ) );
		if ($args['area_search']) {
			if (isset($args['area_search']['zip'] ) )  {
				$args['search_params'] .= '&zip=' . $args['area_search']['zip'];
			}
			if (isset($args['area_search']['city']) ) {
				$args['search_params'] .= '&city=' . urlencode($args['area_search']['city']);
			}
			if (isset($args['area_search']['county']) ) {
				$args['search_params'] .= '&county=' . $args['area_search']['county'];
			}
			if (isset($args['area_search']['state']) ) {
				$args['search_params'] .= '&state=' . $args['area_search']['state'];
			}
			if (isset($args['area_search']['distance']) ) {
				$args['search_params'] .= '&distance=' . $args['area_search']['distance'];
			}
		} elseif ($args['lat_lng_search'] && isset($args['lat_lng_search']['latitude']) && isset($args['lat_lng_search']['longitude']) && $args['lat_lng_search']['distance'] ) {
			$args['search_params'] .= '&latitude=' . $args['lat_lng_search']['latitude'];
			$args['search_params'] .= '&longitude=' . $args['lat_lng_search']['longitude'];
			$args['search_params'] .= '&distance=' . $args['lat_lng_search']['distance'];
		}
		$response = PL_Education::search($args);
		if ($response) {
			return $response;
		} else {
			return array();
		}
	}
//end class
}