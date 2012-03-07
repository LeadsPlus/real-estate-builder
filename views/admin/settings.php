<?php extract(PL_Helper_User::whoami()); ?>
<?php extract(PL_Page_Helper::get_types()); ?>
<?php extract(PL_Helper_User::get_cached_items()); ?>
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
							<div class="not-set-up"><h2>Plugin not Set Up! <a href="#">Get Started.</a></h2></div>
						</div>
						<div class="clear"></div>
					</div>	
				</div>
			</form>
		<?php endif ?>
			<div class="header-wrapper">
				<h2>You've generated <span id="num_user_accounts"><?php echo $num_cached_items; ?></span> potential clients with your website.</h2>
				<a class="button-secondary" href="http://placester.com/people" >View Clients on Placester.com</a>		
			</div>
			<p> Learn more about cacheing <a href="#">here</a></p>

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
		</div>
	<?php PL_Router::load_builder_partial('existing-placester.php') ?>