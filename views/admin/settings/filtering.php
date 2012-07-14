<?php extract(array('filters' => PL_Helper_User::get_global_filters())); ?>
<?php $_POST = $filters; ?>
<div class="wrap">
	<?php echo PL_Helper_Header::pl_settings_subpages(); ?>

	<div class="settings_option_wrapper">
		<div id="global_filter_wrapper" class="<?php echo !empty($filters) ? 'filters_active' : '' ?>">
			<div class="header-wrapper">
				<h2>Global Listing Search Filters</h2>
				<a class="button-secondary" id="remove_global_filters" >Remove All Filters</a>
				<?php if (!empty($filters)): ?>
					<div class="global_filter_active">Global Filters are Active!</div>
				<?php endif ?>
				<div id="global_filter_message_remove"></div>
			</div>
			<p>Global listing search filters limit all the search results returned to your website. This is helpful if you have listings of many different types or locations created but only want this website to display a subset of them. For example, to only show properties in Boston.</p>
			<div class="global_filters tagchecklist">
				<?php if (!empty($filters)): ?>
					<p class="label">Active Filters:</p>	
				<?php endif ?>
				<form action="" id="active_filters">
					<?php PL_Settings_Helper::display_global_filters(); ?>
				</form>	
			</div>
			<div class="clear"></div>
			<div class="search_filter_content">
				<div class="global_filter_col">
					<?php PL_Listing_Helper::get_listing_attributes(); ?>	
				</div>
				<div class="global_filter_col form_item">
					<form action="" id="gloal_filter_form">
						<?php echo PL_Form::item('compound_type', PL_Config::PL_API_LISTINGS('create', 'args', 'compound_type'), 'POST'); ?>
						<?php PL_Form::generate_form( PL_Config::bundler('PL_API_LISTINGS', array('create', 'args'), array('property_type-sublet','property_type-res_sale','property_type-res_rental','property_type-vac_rental','property_type-comm_sale','property_type-comm_rental')), array('method'=>'POST', 'include_submit' => false, 'wrap_form' => false, 'echo_form' => true) ); ?>
						<?php PL_Form::generate_form( PL_Config::PL_API_LISTINGS('get', 'args'), array('method'=>'POST', 'include_submit' => false, 'wrap_form' => false, 'echo_form' => true, 'title' => false, 'id' => 'gloal_filter_form' ) ); ?>		
					</form>
				</div>
				<div class="global_filter_col filter_button">
					<a class="button-secondary" id="add-single-filter">Add Filter</a>	
				</div>
				<div class="global_filter_col" id="global_filter_message"></div>
			</div>	
		</div>
	</div>
</div>