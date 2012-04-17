	<?php extract(PL_Integration_Helper::get()) ?>
	<div class="wrap">
		<?php if (!empty($integration_status['integration'][0]['id'])): ?>
			<div class="header-wrapper">
				<h2>You have an integration pending</h2>
			</div>
			<div class="clear"></div>
			<div class="integration-wrapper">
				<?php echo PL_Router::load_builder_partial('admin-box-top.php', array('title' => 'Pending Integration Status')); ?>
					<?php foreach ($integration_status['integration'] as $integration): ?>
						<div class="integration">
							<h3><?php echo $integration['url'] ?> <span>(status: <?php echo $integration['status'] ?>)</span></h3>
							<ul>
								<li>
									<div>Date Sumbitted:</div> 
									<div><?php echo date_format(date_create($integration['created_at']), "jS F, Y g:i A." ); ?></div>
								</li>
								<li>
									<div>Last Updated:</div>
									<div><?php echo date_format(date_create($integration['updated_at']), "jS F, Y g:i A."); ?></div>
								</li>
								<li>
									<div>Url:</div>
									<div><?php echo $integration['url'] ?></div>
								</li>
								<li>
									<div>Username:</div>
									<div><?php echo $integration['username'] ?></div>
								</li>
								<li>
									<div>User Agent Username:</div>
									<div><?php echo $integration['ua_username'] ?></div>
								</li>
								<li>
									<div>Request Id: </div>
									<div><?php echo $integration['id'] ?> <span class="small">(for support)</span></div>
								</li>
							</ul>
						</div>	
					<?php endforeach ?>
				<?php echo PL_Router::load_builder_partial('admin-box-bottom.php'); ?>
				<p>Looking for multiple MLS integrations? Drop us a note at <a mailto="support@placester.com">support@placester.com</a> or give us a ring at (800) 728-8391 and we'll get you set up.</p>
			</div>
			<div class="help_prompt integration">
					<h3>Need Help?</h3>
					<ul>
						<li>Not sure where to get this information?</li>
						<li>Having trouble submitting the form?</li>
						<li>Have questions about how MLS integrations work?</li>
					</ul>
					<div class="real_person">
						<h3>Talk to a real person.</h3>
						<h4>Call us at 1 (800) 728-8391</h4>
						<h4>Email us at  <a mailto="support@placester.com"> support@pplacester.com</a></h4>
					</div>
				</div>
		<?php endif ?>
		<?php if (!empty($integration_status['whoami']['provider']['id'])): ?>
			<div class="header-wrapper">
				<h2>Your website is linked to <?php echo $integration_status['whoami']['provider']['name'] ?></h2>
			</div>
			<p class="import-message">(Last import was <?php echo date_format(date_create($integration_status['whoami']['provider']['last_import']), "jS F, Y g:i A.") ?>)</p>
			<?php echo PL_Router::load_builder_partial('admin-box-top.php', array('title' => 'Listing Stats')); ?>
				<div class="c4">
					<p class="large-number"><?php echo number_format($integration_status['listings']['total']); ?></p>
					<p class="label">Listings</p>
				</div>
				<div class="c4">
					<p class="large-number"><?php echo count($integration_status['locations']['locality']) ?></p>
					<p class="label">Cities</p>
				</div>
				<div class="c4">
					<p class="large-number"><?php echo count($integration_status['locations']['postal']) ?></p>
					<p class="label">Zips</p>
				</div>
				<div class="c4 omega">
					<p class="large-number"><?php echo count($integration_status['locations']['region']) ?></p>
					<p class="label">States</p>
				</div>
			<?php echo PL_Router::load_builder_partial('admin-box-bottom.php'); ?>
			<p>Looking for multiple MLS integrations? Drop us a note at <a mailto="support@placester.com">support@placester.com</a> or give us a ring at (800) 728-8391 and we'll get you set up.</p>
		<?php endif ?>
		<?php if (empty($integration_status['integration'][0]['id']) && empty($integration_status['whoami']['provider']['id']) ): ?>
			<div class="header-wrapper">
				<h2>Link your Website to your local MLS</h2>
			</div>
			<div class="clear"></div>
			<p>The Real Estate Website Builder plugin can pull listings from your local MLS using a widely supported format called RETS. Once activated, the plugin will automatically update your website with listings as they are added, edited, and removed. All regulatory and compliance concerns will be handled automatically so long as you are using a theme built for the real estate website builder plugin (see <a href="https://placester.com/wordpress-themes/">here</a> for a complete list). Please note that MLS integrations require a <a href="https://placester.com/pricing/">Premium Subscription</a> to Placester which is $45 there is a 60 day, no-credit card free trial avialable to make sure you are happy with the service.  Fill out the form below to get started.</p>
			<div class="clear"></div>
			<h3 class="get_started">Fill out the form to get started</h3>
			<div class="ajax_message" id="rets_form_message"></div>
			<div class="rets_form">
				<?php PL_Form::generate_form(PL_Config::PL_API_INTEGRATION('create', 'args'), array('method' => "POST", 'title' => false, 'include_submit' => true, 'wrap_form' => true) );  ?>	
			</div>
			<div class="help_prompt">
				<h3>What to expect after submitting the form:</h3>
				<ul>
					<li>A call from your personal support rep introducing themselves</li>
					<li>If Placester has already integrated with your MLS, you can expect listings in 1-2 days</li>
					<li>If Placester hasn't yet integrated with your MLS, you can expect listings in 5-7 business days</li>
					<li>Your personal support rep will guide you through the entire process.</li>
				</ul>
				<h3>Need Help?</h3>
				<ul>
					<li>Not sure where to get this information?</li>
					<li>Having trouble submitting the form?</li>
					<li>Have questions about how MLS integrations work?</li>
				</ul>
				<div class="real_person">
					<h3>Talk to a real person.</h3>
					<h4>Call us at 1 (800) 728-8391</h4>
					<h4>Email us at  <a mailto="support@placester.com"> support@pplacester.com</a></h4>
				</div>
			</div>
			<div class="clear"></div>	
		<?php endif ?>
		<?php echo PL_Router::load_builder_partial('free-trial.php'); ?>		
	</div>