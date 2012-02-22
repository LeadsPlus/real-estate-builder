<?php extract(PL_Helper_User::whoami()); ?>
<?php PL_Router::load_builder_partial('existing-placester.php') ?>
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
							<p>Here's your account details being pulled directly from Placester.com. You may edit your <a href="https://placester.com/user/profile">personal information</a> and  <a href="https://placester.com/company/settings">company information</a> any time on Placester.com. Some themes may automatically use this information to save you time entering data. However, you should always enter information directly into a theme when available so you have more control over the look of your website.</p>
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
	</div>