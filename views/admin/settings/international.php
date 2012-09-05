<?php extract(PL_Helper_User::get_default_country()); ?>
<div class="wrap">
	<?php echo PL_Helper_Header::pl_settings_subpages(); ?>
	<div class="settings_option_wrapper">
		<div class="header-wrapper">
			<h2>Set Default Country</h2>
			<select name="" class="set_default_country" id="set_default_country_select">
				<?php foreach (PL_Listing_Helper::supported_countries() as $key => $value): ?>
					<?php if ($key === $default_country): ?>
						<option value="<?php echo $key ?>" selected><?php echo $value ?></option>		
					<?php else: ?>
						<option value="<?php echo $key ?>"><?php echo $value ?></option>	
					<?php endif ?>
					
				<?php endforeach ?>
			</select>	
			<a class="button-secondary" id="set_default_country" >Set Default</a>		
			<div id="default_country_message"></div>
		</div>
		<p>Setting the default country will change the default option in the country selector everywhere in the plugin. This is most convenient when creating a website with listings in a specific country.</p>
	</div>
</div>