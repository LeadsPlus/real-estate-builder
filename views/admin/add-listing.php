<div class="wrap">
	<form name="" action="" method="" id=""> 
		<div id="poststuff" class="metabox-holder has-right-sidebar">
			<div id="side-info-column" class="inner-sidebar"> <!-- Right Sidebar -->
				<div id="side-sortables" class="meta-box-sortables ui-sortable">
					<?php PL_Router::load_builder_partial('publish-box-sidebar.php'); ?>
					<?php PL_Router::load_builder_partial('admin-box.php', array('title' => 'Amenities')) ?>
					<?php PL_Router::load_builder_partial('admin-box.php', array('title' => 'Neighbourhood Amenities')) ?>
				</div>
			</div>
			<div id="post-body">
				<div id="post-body-content">
					<?php echo PL_Form::item('listing_types', PL_Config::PL_API_LISTINGS('create', 'args', 'compound_type'), 'POST') ?>
					<?php echo PL_Form::item('property_type-sublet', PL_Config::PL_API_LISTINGS('create', 'args', 'property_type-sublet'), 'POST') ?>
					<?php PL_Router::load_builder_partial('admin-box.php', array('title' => 'Location', 'content' => PL_Form::generate_form( PL_Config::PL_API_LISTINGS('create', 'args', 'location'), array('method'=>'POST', 'include_submit' => false, 'wrap_form' => false, 'echo_form' => false) ) ) ) ?>
					<?php PL_Router::load_builder_partial('admin-box.php', array('title' => 'Basic Details', 'content' => PL_Form::generate_form( PL_Config::bundler('PL_API_LISTINGS', $keys = array('create', 'args'), $bundle = array('', 'images') ),array('method'=>'POST', 'include_submit' => false, 'wrap_form' => false, 'echo_form' => false) ) ) ) ?>
					<?php PL_Router::load_builder_partial('admin-box.php', array('title' => 'Images', 'content' => PL_Form::item('images', PL_Config::PL_API_LISTINGS('create', 'args', 'images'), 'POST' ) ) ) ?>
					<?php PL_Router::load_builder_partial('admin-box.php', array('title' => 'Advanced Details')) ?>
					<?php //PL_Router::load_builder_partial('wysiwyg.php');?>
				</div>
			</div>
		</div>
		<br class="clear">
	</form>
</div>



<?php 


/*
	//if id, then we're editing.
	if (isset($_GET['id'])) {
		// edit args are the same as create.
		$create = PL_Config::PL_API_LISTINGS('create');
		$args = $create['args'];

		$listings = PL_Config::PL_API_LISTINGS('update');
		$_GET = PL_Listing_Helper::details();
		PL_Form::generate_form($args, $listings['request']['url'], $listings['request']['type'], "pls_admin_add_listing", true);	
	} else {
		// we're creating!
		$listings = PL_Config::PL_API_LISTINGS('create');
		PL_Form::generate_form($listings['args'], $listings['request']['url'], $listings['request']['type'], "pls_admin_add_listing", true);	
	}
	*/