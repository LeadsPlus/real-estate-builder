<div id="signup_wizard">
	<p class="wizard-explination">You need to complete the set up wizard you can start using the Real Estate Website Builder plugin. It takes 2 mintues and ensures your real estate website will work properly. Here are the steps:</p>	
	<div class="clear"></div>
	<p>This plugin turns your WordPress blog into a full featured real estate website. It allows you to create, edit, search, and display real estate listings. To get started, we need to confirm your email address. This email address will be used to save all the properties you enter.</p>
	<div id="api_key_validation"></div>
	<div id="api_key_success"></div>
	<?php PL_Form::generate_form( PL_Config::PL_API_USERS('setup', 'args'), array('method'=>'POST', 'include_submit' => false, 'wrap_form' => true) ); ?>	
</div>
