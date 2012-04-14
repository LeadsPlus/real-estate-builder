	<div class="wrap">
		<div class="header-wrapper">
			<h2>Link your Website to your local MLS</h2>
			<div class="ajax_message" id="regenerate_message"></div>
		</div>
		<div class="clear"></div>
		<p>The Real Estate Website Builder plugin can pull listings from your local MLS using a widely supported format called RETS. Once activated, the plugin will automatically update your website with listings as they are added, edited, and removed. All regulatory and compliance concerns will be handled automatically so long as you are using a theme built for the real estate website builder plugin (see <a href="https://placester.com/wordpress-themes/">here</a> for a complete list). Please note that MLS integrations require a <a href="https://placester.com/pricing/">Premium Subscription</a> to Placester which is $45 there is a 60 day, no-credit card free trial avialable to make sure you are happy with the service.  Fill out the form below to get started.</p>
		<div class="clear"></div>
		<h3 class="get_started">Fill out the form to get started</h3>
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
	</div>