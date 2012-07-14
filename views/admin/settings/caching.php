<?php extract(PL_Caching_Helper::num_items()); ?>
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
	<div class="cache_list">
		<div class="caches" id="list_of_caches">
			<table id="list_of_caches_list" class="widefat post fixed list_of_caches_list" cellspacing="0">
			    <thead>
			      <tr>
			        <th><span>Cache ID</span></th>
			        <th><span>Hash</span></th>
			        <th><span>Type</span></th>
			        <th><span>Cache</span></th>
			        <th><span>Delete</span></th>
			      </tr>
			    </thead>
			    <tbody></tbody>
			    <tfoot>
			      <tr>
			        <th></th>
			        <th></th>
			        <th></th>
			        <th></th>
			        <th></th>
			        <th></th>
			      </tr>
			    </tfoot>
			  </table>
		</div>
	</div>
</div>