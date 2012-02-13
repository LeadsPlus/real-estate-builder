<?php 

	//if id, then we're editing.
	if (isset($_GET['id'])) {
		// edit args are the same as create.
		$create = PL_Config::PL_API_LISTINGS('create');
		$args = $create['args'];

		$listings = PL_Config::PL_API_LISTINGS('update');
		$_GET = PL_Listing_Helper::details();
		PL_Form::generate($args, $listings['request']['url'], $listings['request']['type'], "pls_admin_add_listing", true);	
	} else {
		// we're creating!
		$listings = PL_Config::PL_API_LISTINGS('create');
		PL_Form::generate($listings['args'], $listings['request']['url'], $listings['request']['type'], "pls_admin_add_listing", true);	
	}
	