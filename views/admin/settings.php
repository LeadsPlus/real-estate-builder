<?php extract(PL_Helper_User::whoami()); ?>
<?php extract(PL_Page_Helper::get_types()); ?>
<?php extract(PL_Helper_User::get_cached_items()); ?>
<?php extract(PL_Helper_User::get_default_country()); ?>
<?php extract(array('filters' => PL_Helper_User::get_global_filters())); ?>
<?php extract(array('places_api_key' => PL_Option_Helper::get_google_places_key() ) ) ; ?>
<?php extract(array('error_logging' => PL_Option_Helper::get_log_errors())); ?>
<?php extract(array('block_address' => PL_Option_Helper::get_block_address())); ?>
<?php $_POST = $filters; ?>
	<div class="wrap">
		<?php if (PL_Option_Helper::api_key() && isset($email)): ?>
			<div class="header-wrapper">
				<h2>This plugin is linked to <?php echo $email ?> <span class="check-icon"></span></h2>	
				<a class="button-secondary" href='https://placester.com/user/login'>Login to Placester.com</a>
				<a class="button-secondary" href='https://placester.com/user/password/new'>Forgot Password?</a>	
				<a class="button-secondary" id="new_email" >Change to a New Email Address</a>	
				<a class="button-secondary" id="existing_placester" href="#">Change to an Existing Placester Account</a>	
			</div>
			<div class="clear"></div>
			<form action="">
				<div id="" class="meta-box-sortables ui-sortable">
					<div id="div" class="postbox ">
						<div class="handlediv" title="Click to toggle"><br></div>
						<h3 class="hndle">
							<span>Placester.com Account Details</span>
						</h3>
						<div class="inside">
							<p>Here's your account details being pulled directly from Placester.com. You may edit your <a href="https://placester.com/user/profile">personal information</a> and  <a href="https://placester.com/company/settings">company information</a> any time on Placester.com. Some themes may automatically use this information (to save you time entering data). However, you can always enter information directly into a theme so you have more control over the look of your website.</p>
							<div class="personal-column">
								<h3>Personal Details</h3>
								<div class="third">
									<?php if (isset($user['headshot'])): ?>
										<img src="<?php echo $user['headshot'] ?>" alt="" width=100 height=90>
									<?php else: ?>
										<img src="" alt="">
									<?php endif ?>
								</div>
								<div class="third">
									<ul>
										<li><b><?php echo $user['first_name'] . " " . $user['last_name']; ?></b></li>
										<li><?php echo $user['email'] ?></li>
										<li><?php echo $user['phone'] ?></li>
										<li><?php echo $user['website'] ?></li>
									</ul>
								</div>
							</div>
							<div class="company-column">
								<h3>Company Details</h3>
								<div class="third">
									<?php if (isset($logo)): ?>
										<img src="<?php echo $logo ?>" alt="" width=100 height=90>
									<?php else: ?>
										<img src="" alt="">
									<?php endif ?>
								</div>
								<div class="third">
									<ul>
										<li><b><?php echo $name; ?></b></li>
										<li><?php echo $email; ?></li>
										<li><?php echo $phone ?></li>
										<li><?php echo $website ?></li>
									</ul>
								</div>
								<div class="third">
									<ul>
										<li><?php echo $location['address']; ?><?php echo isset($location['unit']) ? ', Unit: ' . $location['unit'] : '';  ?></li>
										<li><?php echo $location['locality'] . ' ,' .  $location['region'] . ' ' . $location['postal']; ?></li>
										<li><?php echo $location['country']; ?></li>
									</ul>
								</div>
							</div>
						</div>
						<div class="clear"></div>
					</div>	
				</div>
			</form>
		<?php else: ?>
			<h2>This plugin is not set up! Click anywhere to start</h2>
			<form action="">
				<div id="" class="meta-box-sortables ui-sortable">
					<div id="div" class="postbox ">
						<div class="handlediv" title="Click to toggle"><br></div>
						<h3 class="hndle">
							<span>Placester.com Account Details</span>
						</h3>
						<div class="inside">
							<div class="not-set-up"><h2>Plugin not Set Up! <a href="#" id="settings_get_started_signup">Get Started.</a></h2></div>
						</div>
						<div class="clear"></div>
					</div>	
				</div>
			</form>
			<?php do_action('sign-up-action'); ?>
			<?php self::load_builder_partial('sign-up.php'); ?>
		<?php endif ?>
			<?php 
			/*
				<div class="header-wrapper">
					<h2>You've generated <span id="num_user_accounts"><?php echo $num_cached_items; ?></span> potential clients with your website.</h2>
					<a class="button-secondary" href="http://placester.com/people" >View Clients on Placester.com</a>		
				</div>
				<p> Learn more about cacheing <a href="#">here</a></p>

			*/
			 ?>
			

			<div class="header-wrapper">
				<h2>You have <span id="num_placester_pages"><?php echo $total_pages; ?></span> listing pages created.</h2>	
				<a class="button-secondary" id="delete_pages" >Delete all pages</a>	
				<div class="ajax_message" id="regenerate_message"></div>
			</div>
			<div class="clear"></div>
			<p>Listing pages are found by indexed by search engines like Google and Bing. Once you're listing pages are indexed they can be found by searchers.</p>

			<div class="header-wrapper">
				<h2>You currently have <span id="num_cached_items"><?php echo $num_cached_items; ?></span> request(s) cached.</h2>
				<a class="button-secondary" id="clear_cache" >Empty the Cache</a>		
				<div id="cache_message"></div>
			</div>
			<p>Cacheing speeds up your real estate website by storing data locally rather then pulling it in from Placester everytime it's needed. If you've recently updated a lot of your data, and don't see it appearing in your website try emptying the cache. Learn more about cacheing <a href="#">here</a></p>

			<div class="header-wrapper">
				<h2>Set Default Country</h2>
				<select name="" class="set_default_country" id="set_default_country_select">
					<?php foreach (PL_Listing_Helper::supported_countries() as $key => $value): ?>
						<?php if ($key === $default_country): ?>
							<option value="<?php echo $key ?>" selected><?php echo $value ?></option>		
						<?php else: ?>
							<option value="<?php echo $key ?>"><?php echo $value ?></option>	
						<?php endif ?>
						
					<?php endforeach ?>
				</select>	
				<a class="button-secondary" id="set_default_country" >Set Default</a>		
				<div id="default_country_message"></div>
			</div>
			<p>Setting the default country will change the default option in the country selector everywhere in the plguin. This is most convenient when creating a website with listings in a specific country.</p>

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
				<?php //pls_dump($filters) ?> 
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

			<div class="header-wrapper">
				<h2>Google Places API Key</h2>
				<div id="default_googe_places_message"></div>
			</div>
			<div class="clear"></div>
			<p><strong>Add a Google Places API Key to enable lifestyle search for your clients!</strong> Here's how you can get a key. 1) Navigate to the <a href="https://code.google.com/apis/console/?pli=1">google api console</a>. 2) Login with your google account. 3) In the left menu, navigate to the services section. Scroll down to the Places Services and click the "on" switch. 4) Scroll back tot he top, and click on the "API Access" option on the left side of the page. 5) At the bottom of the page click "Create New Server Key". A pop up will appear 6) Click the create button in the pop up (you don't need to enter anything). 7) Copy the new api key. Labeled "API Key" and paste it into the form below and click save. Reach out at <a mailto="support@placester.com">support@placester.com</a> if you have any questions or problems and we'll be happy to help you. </p>
			<div>
				<label for="google_places_api">Google Places API Key</label>
				<input type="text" id="google_places_api" value="<?php echo $places_api_key ?>">
				<a href="#" id="google_places_api_button" class="button">Update</a>
			</div>

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

			<div class="header-wrapper">
				<h2>Listings Settings</h2>
				<div class="ajax_message" id="block_address_messages"></div>
			</div>
			<div class="clear"></div>
			<ul>
				<li>
					<input id="block_address" type="checkbox" name="block_address" <?php echo $block_address ? 'checked="checked"' : '' ?>>
					<label for="block_address">Use <b>Block Addresses</b> rather then exact addresses. Using block addresses will switch over all the addresses in your website to the nearest block, rather then exact address.</label>
				</li>
			</ul>

			<div class="header-wrapper">
				<h2>Other Settings</h2>
				<div id="error_logging_message"></div>
			</div>
			<ul>
				<li>
					<input id="error_logging_click" type="checkbox" name="error_logging" <?php echo $error_logging ? 'checked="checked"' : '' ?>>
					<label for="error_logging">You can help improve Placester. Allow the Real Estate Website Builder Plugin to anonymously report errors and usage information so we can fix errors and add new features.</label>
				</li>
			</ul>

			
		</div>
	<?php PL_Router::load_builder_partial('existing-placester.php') ?>