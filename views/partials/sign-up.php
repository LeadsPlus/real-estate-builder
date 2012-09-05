<div id="signup_wizard">
	<p class="message">You need to complete the set up wizard you can start using the Real Estate Website Builder plugin. It takes 2 minutes and ensures your real estate website will work properly.</p>	
	<div class="clear"></div>
	<p>This plugin turns your WordPress blog into a full featured real estate website. It allows you to create, edit, search, and display real estate listings. To get started, we need to confirm your email address. This email address will be used to save all the properties you enter.</p>
	<div id="api_key_validation"></div>
	<div id="api_key_success"></div>
	<div id="confirm_email">
		<?php PL_Form::generate_form( PL_Config::PL_API_USERS('setup', 'args'), array('method'=>'POST', 'include_submit' => false, 'wrap_form' => true) ); ?>		
	</div>
	
</div>
