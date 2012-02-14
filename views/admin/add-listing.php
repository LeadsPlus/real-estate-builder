
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
						
						<?php PL_Router::load_builder_partial('admin-box.php', array('title' => 'Address')) ?>
						<?php //PL_Router::load_builder_partial('wysiwyg.php');?>
					</div>
				</div>
			</div>
			<br class="clear">
		</div><!-- /poststuff -->
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
		PL_Form::generate($args, $listings['request']['url'], $listings['request']['type'], "pls_admin_add_listing", true);	
	} else {
		// we're creating!
		$listings = PL_Config::PL_API_LISTINGS('create');
		PL_Form::generate($listings['args'], $listings['request']['url'], $listings['request']['type'], "pls_admin_add_listing", true);	
	}
	*/