<?php extract(PL_Page_Helper::get_types()); ?>

<div class="wrap">
	<?php echo PL_Helper_Header::pl_settings_subpages(); ?>
	<div class="settings_option_wrapper">
		<?php echo PL_Router::load_builder_partial('admin-box-top.php', array('title' => 'Client Welcome Email')); ?>
			<form id="form_client_message" action="" method="POST">
				<div class="form-row">
					<input type="checkbox" <?php echo $send_client_message ? 'checked="checked"' : ''  ?> name="send_client_message">
					<label for="">Send Client an Email After Account Registration</label>
				</div>
				<div class="form-row">
					<label for="">Message sent to Client</label>
					<div class="clear"></div>
					<textarea class="full-size" name="send_client_message_text" id="send_client_message_text" cols="30" rows="10" ><?php echo $send_client_message_text ?></textarea>
					<div class="message_keywords">
						<p>You may use the follow keywords to send personalized emails:</p>
						<ul>
							<li><span>Clients Email:</span> %client_email%</li>
							<li><span>Your Email Address:</span> %email_address%</li>
							<li><span>Your Name:</span> %full_name%</li>
							<li><span>Your First Name:</span> %first_name%</li>
							<li><span>This Websites Address:</span> %website_url%</li>
						</ul>
					</div>
				</div>
				<div class="form-row">
					<input type="submit" id="save" class="button-primary" value="Save">
				</div>	
			</form>
		<?php echo PL_Router::load_builder_partial('admin-box-bottom.php'); ?>
	</div>
</div>