<?php extract(PL_Helper_User::get_cached_items()); ?>
<div class="wrap">
	<?php echo PL_Helper_Header::pl_settings_subpages(); ?>
	<div class="settings_option_wrapper">
	<div class="header-wrapper">
		<h2>You currently have <span id="num_cached_items"><?php echo $num_cached_items; ?></span> request(s) cached.</h2>
		<a class="button-secondary" id="clear_cache" >Empty the Cache</a>		
		<div id="cache_message"></div>
	</div>
	<p>Cacheing speeds up your real estate website by storing data locally rather then pulling it in from Placester everytime it's needed. If you've recently updated a lot of your data, and don't see it appearing in your website try emptying the cache. Learn more about cacheing <a href="#">here</a></p>	
	</div>	
</div>