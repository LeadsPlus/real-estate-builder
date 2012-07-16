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
					<textarea class="full-size" name="send_client_message_text" id="send_client_message_text" cols="30" rows="10" ><?php echo $send_client_message_text ?></textarea>
				</div>
				<div class="form-row">
					<input type="submit" id="save" class="button-primary" value="Save">
				</div>	
			</form>
		<?php echo PL_Router::load_builder_partial('admin-box-bottom.php'); ?>
	</div>
</div>