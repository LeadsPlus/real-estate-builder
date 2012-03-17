<div class="wrap">
	<div id="manage_listing_message"></div>
	<form action="wp-admin/admin-ajax.php" method="<?php echo isset($_GET['id']) ? 'PUT' : 'POST' ?>" enctype="multipart/form-data" id="add_listing_form">  
		<?php if (isset($_GET['id'])): ?>
			<input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
		<?php endif ?>
		<div id="poststuff" class="metabox-holder has-right-sidebar">
			<div id="side-info-column" class="inner-sidebar"> <!-- Right Sidebar -->
				<div id="side-sortables" class="meta-box-sortables ui-sortable">
					<?php PL_Router::load_builder_partial('publish-box-sidebar.php'); ?>
					
				</div>
			</div>
			<div id="post-body">
				<div id="post-body-content">
					<div class="property-type-selects">
						<?php PL_Helper_Add_Listing::property_selects(); ?>
					</div>					
					<div class="clear"></div>
					<?php PL_Router::load_builder_partial('admin-box.php',
						array('title' => 'Location',
						  'content' => PL_Form::generate_form( 
						  	PL_Config::bundler('PL_API_LISTINGS',
						  		$keys = array('create',
						  			 'args'),
						  		$bundle = array('location')
						  	), 
						array('method'=>'POST',
							 'include_submit' => false,
							  'wrap_form' => false,
							  'echo_form' => false
						) ) ) ) ?>
					<?php PL_Router::load_builder_partial('admin-box.php',
						 array('title' => 'Residential Sales Details',
						 	'id' => 'res_sale_details_admin_ui',
						 	'style' => '',
						 	'content' => PL_Form::generate_form(
						 	 	 PL_Config::bundler('PL_API_LISTINGS',
						 	 	 	 $keys = array('create', 'args'), 
						 	 	 	 $bundle = array( 
						 	 	 	 	array('metadata' => array('beds', 'baths', 'half_baths','price', 'avail_on', 'sqft', 'lt_sz', 'lt_sz_unit', 'pk_spce','hoa_mand','hoa_fee','landr_own','style', 'ngb_trans', 'ngb_shop','ngb_swim','ngb_court','ngb_park','ngb_trails','ngb_stbles','ngb_golf', 'ngb_med', 'ngb_bike','ngb_cons','ngb_hgwy','ngb_mar','ngb_pvtsch','ngb_pubsch','ngb_uni','grnt_tops','air_cond','cent_ac','frnshed','cent_ht','frplce','hv_ceil','wlk_clst','hdwdflr','tle_flr','fm_lv_rm','lft_lyout','off_den','dng_rm','brkfst_nk','dshwsher','refrig','stve_ovn','stnstl_app','attic','basemnt','washer','dryer','lndry_in','lndry_gar','blc_deck_pt','yard','swm_pool','jacuzzi','sauna','cble_rdy','hghspd_net') 
						 	 	 	 ) 
						 	 	 )
						 	),
						 	array('method'=>'POST', 
							 	'include_submit' => false, 
							 	'wrap_form' => false, 
							 	'echo_form' => false,
							 	'title' => true
						 	) 
						 ) ) ) ?>
					<?php PL_Router::load_builder_partial('admin-box.php',
						 array('title' => 'Residential Rental Details',
						 	'id' => 'res_rental_details_admin_ui',
						 	'style' => 'display: none',
						 	'content' => PL_Form::generate_form(
						 	 	 PL_Config::bundler('PL_API_LISTINGS',
						 	 	 	 $keys = array('create', 'args'), 
						 	 	 	 $bundle = array( 
						 	 	 	 	array('metadata' => array('beds', 'baths', 'half_baths','price', 'avail_on', 'sqft', 'lt_sz', 'lt_sz_unit', 'pk_spce', 'lse_type', 'lse_trms', 'deposit', 'pk_lease', 'ngb_trans', 'ngb_shop','ngb_swim','ngb_court','ngb_park','ngb_trails','ngb_stbles','ngb_golf', 'ngb_med', 'ngb_bike','ngb_cons','ngb_hgwy','ngb_mar','ngb_pvtsch','ngb_pubsch','ngb_uni','grnt_tops','air_cond','cent_ac','frnshed','cent_ht','frplce','hv_ceil','wlk_clst','hdwdflr','tle_flr','fm_lv_rm','lft_lyout','off_den','dng_rm','brkfst_nk','dshwsher','refrig','stve_ovn','stnstl_app','attic','basemnt','washer','dryer','lndry_in','lndry_gar','blc_deck_pt','yard','swm_pool','jacuzzi','sauna','cble_rdy','hghspd_net') 
						 	 	 	 ) 
						 	 	 )
						 	),
						 	array('method'=>'POST', 
							 	'include_submit' => false, 
							 	'wrap_form' => false, 
							 	'echo_form' => false,
							 	'title' => true
						 	) 
						 ) ) ) ?>
					<?php PL_Router::load_builder_partial('admin-box.php',
						 array('title' => 'Vacation Details',
						 	'id' => 'vac_rental_details_admin_ui',
						 	'style' => 'display: none',
						 	'content' => PL_Form::generate_form(
						 	 	 PL_Config::bundler('PL_API_LISTINGS',
						 	 	 	 $keys = array('create', 'args'), 
						 	 	 	 $bundle = array( 
						 	 	 	 	array('metadata' => array('accoms','beds', 'baths', 'half_baths','price', 'avail_on', 'sqft','avail_info', 'lt_sz', 'lt_sz_unit', 'pk_spce', 'lse_type', 'lse_trms', 'deposit', 'pk_lease', 'ngb_trans', 'ngb_shop','ngb_swim','ngb_court','ngb_park','ngb_trails','ngb_stbles','ngb_golf', 'ngb_med', 'ngb_bike','ngb_cons','ngb_hgwy','ngb_mar','ngb_pvtsch','ngb_pubsch','ngb_uni','grnt_tops','air_cond','cent_ac','frnshed','cent_ht','frplce','hv_ceil','wlk_clst','hdwdflr','tle_flr','fm_lv_rm','lft_lyout','off_den','dng_rm','brkfst_nk','dshwsher','refrig','stve_ovn','stnstl_app','attic','basemnt','washer','dryer','lndry_in','lndry_gar','blc_deck_pt','yard','swm_pool','jacuzzi','sauna','cble_rdy','hghspd_net') 
						 	 	 	 ) 
						 	 	 )
						 	),
						 	array('method'=>'POST', 
							 	'include_submit' => false, 
							 	'wrap_form' => false, 
							 	'echo_form' => false,
							 	'title' => true
						 	) 
						 ) ) ) ?>
					<?php PL_Router::load_builder_partial('admin-box.php',
						 array('title' => 'Sublet Details',
						 	'id' => 'sublet_details_admin_ui',
						 	'style' => 'display: none',
						 	'content' => PL_Form::generate_form(
						 	 	 PL_Config::bundler('PL_API_LISTINGS',
						 	 	 	 $keys = array('create', 'args'), 
						 	 	 	 $bundle = array( 
						 	 	 	 	array('metadata' => array('beds', 'baths', 'half_baths','price', 'avail_on', 'sqft','cats','dogs','cond', 'lt_sz', 'lt_sz_unit', 'pk_spce', 'lse_type', 'lse_trms', 'deposit', 'pk_lease', 'ngb_trans', 'ngb_shop','ngb_swim','ngb_court','ngb_park','ngb_trails','ngb_stbles','ngb_golf', 'ngb_med', 'ngb_bike','ngb_cons','ngb_hgwy','ngb_mar','ngb_pvtsch','ngb_pubsch','ngb_uni','grnt_tops','air_cond','cent_ac','frnshed','cent_ht','frplce','hv_ceil','wlk_clst','hdwdflr','tle_flr','fm_lv_rm','lft_lyout','off_den','dng_rm','brkfst_nk','dshwsher','refrig','stve_ovn','stnstl_app','attic','basemnt','washer','dryer','lndry_in','lndry_gar','blc_deck_pt','yard','swm_pool','jacuzzi','sauna','cble_rdy','hghspd_net') 
						 	 	 	 ) 
						 	 	 )
						 	),
						 	array('method'=>'POST', 
							 	'include_submit' => false, 
							 	'wrap_form' => false, 
							 	'echo_form' => false,
							 	'title' => true
						 	) 
						 ) ) ) ?>
					<?php PL_Router::load_builder_partial('admin-box.php',
						 array('title' => 'Commercial Rental Details',
						 	'id' => 'comm_rental_details_admin_ui',
						 	'style' => 'display: none',
						 	'content' => PL_Form::generate_form(
						 	 	 PL_Config::bundler('PL_API_LISTINGS',
						 	 	 	 $keys = array('create', 'args'), 
						 	 	 	 $bundle = array( 
						 	 	 	 	array('metadata' => array('prop_name', 'cons_stts', 'bld_suit', 'avail_on', 'sqft', 'lse_trms', 'lse_type', 'sublease', 'price', 'rate_unit', 'min_div', 'max_cont', 'bld_sz', 'lt_sz', 'lt_sz_unit', 'year_blt' ) 
						 	 	 	 ) 
						 	 	 )
						 	),
						 	array('method'=>'POST', 
							 	'include_submit' => false, 
							 	'wrap_form' => false, 
							 	'echo_form' => false,
							 	'title' => true
						 	) 
						 ) ) ) ?>
					<?php PL_Router::load_builder_partial('admin-box.php',
						 array('title' => 'Commercial Sales Details',
						 	'id' => 'comm_sale_details_admin_ui',
						 	'style' => 'display: none',
						 	'content' => PL_Form::generate_form(
						 	 	 PL_Config::bundler('PL_API_LISTINGS',
						 	 	 	 $keys = array('create', 'args'), 
						 	 	 	 $bundle = array( 
						 	 	 	 	array('metadata' => array('prop_name', 'cons_stts', 'sqft', 'price', 'pk_spce', 'min_div', 'max_cont', 'bld_sz', 'lt_sz', 'lt_sz_unit', 'year_blt') 
						 	 	 	 ) 
						 	 	 )
						 	),
						 	array('method'=>'POST', 
							 	'include_submit' => false, 
							 	'wrap_form' => false, 
							 	'echo_form' => false,
							 	'title' => true
						 	) 
						 ) ) ) ?>
					<?php PL_Router::load_builder_partial('admin-box.php',
						 array('title' => 'Parking Details',
						 	'id' => 'park_rental_details_admin_ui',
						 	'style' => 'display: none',
						 	'content' => PL_Form::generate_form(
						 	 	 PL_Config::bundler('PL_API_LISTINGS',
						 	 	 	 $keys = array('create', 'args'), 
						 	 	 	 $bundle = array( 
						 	 	 	 	array('metadata' => array('park_type', 'avail_on', 'price', 'lse_trms', 'lse_type', 'deposit', 'valet', 'guard','heat','carwsh') 
						 	 	 	 ) 
						 	 	 )
						 	),
						 	array('method'=>'POST', 
							 	'include_submit' => false, 
							 	'wrap_form' => false, 
							 	'echo_form' => false,
							 	'title' => true
						 	) 
						 ) ) ) ?>
					<?php PL_Router::load_builder_partial('admin-box.php',
						array('title' => 'Images',
							 'content' => PL_Router::load_builder_partial(
							 	'add-listing-image.php',
							 	array('images' => @$_POST['images']),
							 	true
							 ) 
						) 
					) ?>
					<?php PL_Router::load_builder_partial('admin-box.php',
						 array('title' => 'Description',
						 	 'content' => PL_Form::generate_form(
						 	 	 PL_Config::bundler('PL_API_LISTINGS',
						 	 	 	 $keys = array('create', 'args'), 
						 	 	 	 $bundle = array( 
						 	 	 	 	array('metadata' => array('desc') 
						 	 	 	 ) 
						 	 	 )
						 	),
						 	array('method'=>'POST', 
							 	'include_submit' => false, 
							 	'wrap_form' => false, 
							 	'echo_form' => false
						 	) 
						 ) ) ) ?>
					<?php /* PL_Router::load_builder_partial('admin-box.php',
						 array('title' => 'Custom Details',
						 	 'content' => PL_Form::generate_form(
						 	 	 PL_Config::bundler('PL_API_LISTINGS',
						 	 	 	 $keys = array('create', 'args'), 
						 	 	 	 $bundle = array('custom_data')
						 		),
						 	array('method'=>'POST', 
							 	'include_submit' => false, 
							 	'wrap_form' => false, 
							 	'echo_form' => false
						 	) 
						 ) ) ) */ ?>
				</div>
			</div>
		</div>
		<br class="clear">
	</form>
</div>