<div id="signup_wizard" style="display:none">
	<p class="wizard-explination">You need to complete the set up wizard you can start using the Real Estate Website Builder plugin. It takes 2 mintues and ensures your real estate website will work properly. Here are the steps:</p>	
	<div id="steps" class="steps">
		<ul>
			<li id="1" class="active"><span>Step 1</span>: Confirm Email Address<span class="right-arrow">></span></li>
			<li id="2"><span>Step 2</span>: Select MLS Integration<span class="right-arrow">></span></li>
			<li id="3"><span>Step 3</span>: Confirm</li>
		</ul>	
	</div>
	<div class="clear"></div>
	<p>This plugin turns your WordPress blog into a full featured real estate website. It allows you to create, edit, search, and display real estate listings. To get started, we need to confirm your email address. This email address will be used to save all the properties you enter.</p>
	<div id="api_validation_message"></div>
	<?php PL_Form::generate_form( PL_Config::PL_API_USERS('setup', 'args'), array('method'=>'POST', 'include_submit' => false, 'wrap_form' => true) ); ?>	

</div>
