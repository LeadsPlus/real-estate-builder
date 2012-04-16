	<?php extract(PL_Integration_Helper::get()) ?>
	<div class="wrap">
		<?php if (!empty($integration_status['integration']['id'])): ?>
			<div class="header-wrapper">
				<h2>Your pending integration status</h2>
			</div>
		<?php endif ?>
		<?php if (!empty($integration_status['whoami']['provider']['id'])): ?>
			<div class="header-wrapper">
				<h2>Your website is linked to <?php echo $integration_status['whoami']['provider']['name'] ?></h2>
			</div>
			<p class="import-message">(Last import was <?php echo date_format(date_create($integration_status['whoami']['provider']['last_import']), "jS F, Y g:i A.") ?>)</p>
			<?php echo PL_Router::load_builder_partial('admin-box-top.php', array('title' => 'Listing Stats')); ?>
				<div>
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
				</div>
			<?php echo PL_Router::load_builder_partial('admin-box-bottom.php'); ?>
			<p>Looking for multiple MLS integrations? Drop us a note at <a mailto="support@placester.com">support@placester.com</a> or give us a ring at (800) 728-8391 and we'll get you set up.</p>
		<?php endif ?>
		<?php if (empty($integration_status['integration']['id']) && empty($integration_status['whoami']['provider']['id']) ): ?>
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
		<?php //pls_dump($integration_status['locations']) ?>
		<?php echo PL_Router::load_builder_partial('free-trial.php'); ?>		
	</div>