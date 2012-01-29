<?php 

class PL_Listing_Helper {
	
	public function results( $params = array() ) {
		
		// $listing_args = PL_Config::PL_API_LISTINGS('get.args');
		// pls_dump($_GET);

		$listings = PL_Listing::get($_GET);
		return $listings;
	}

//end of class
}