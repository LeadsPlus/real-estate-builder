<div class="wrap">
	<?php echo PL_Helper_Header::pl_settings_subpages(); ?>
	<div>
		<div class="header-wrapper">
			<h2>Neighborhood Areas</h2>
			<div class="ajax_message" id="neighborhood_messages"></div>
		</div>
		<div class="clear"></div>
		<p>Use the map below to outline neighborhoods. Once you've outlined and saved a neighborhood you can allow your visitors to use it to search or associated it with a neighborhood page.</p>
		<div class="polygon_wrapper">
			<div class="show_neighborhood_areas">
				<span>Show Created Neighborhoods:</span>
				<?php echo PL_Taxonomy_Helper::taxonomies_as_checkboxes(); ?>
				<a id="clear_created_neighborhoods"href="#">Hide All</a>
			</div>
			<div class="ajax_message" id="polygon_ajax_messages"></div>
			<div class="clear"></div>
			<div id="polygon_map"></div>
			<div class="map_address">
				<label for="map_address_input">Address</label>
				<input type="text" id="map_address_input">
				<a href="#" id="start_map_address_search" class="button">Search</a>
			</div>
			<div class="polygon_list">
				<?php echo PL_Router::load_builder_partial('settings-polygon-create.php'); ?>
				<h3>Neighborhoods</h3>
				<div class="polygons" id="list_of_polygons">
					<table id="polygon_listings_list" class="widefat post fixed placester_properties" cellspacing="0">
					    <thead>
					      <tr>
					        <th><span>Name</span></th>
					        <th><span>Type</span></th>
					        <th><span>Neighborhood</span></th>
					        <th><span></span></th>
					        <th><span></span></th>
					      </tr>
					    </thead>
					    <tbody></tbody>
					    <tfoot>
					      <tr>
					        <th></th>
					        <th></th>
					        <th></th>
					        <th></th>
					        <th></th>
					      </tr>
					    </tfoot>
					  </table>
				</div>
			</div>
		</div>
	</div>
</div>