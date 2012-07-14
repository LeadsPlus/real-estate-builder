<?php extract(PL_Page_Helper::get_types()); ?>

<div class="wrap">
	<?php echo PL_Helper_Header::pl_settings_subpages(); ?>
	<div>
			<div class="header-wrapper">
				<h2>You have <span id="num_placester_pages"><?php echo $total_pages; ?></span> listing pages created.</h2>	
				<a class="button-secondary" id="delete_pages" >Delete all pages</a>	
				<div class="ajax_message" id="regenerate_message"></div>
			</div>
			<div class="clear"></div>
			<p>Listing pages are found by indexed by search engines like Google and Bing. Once you're listing pages are indexed they can be found by searchers.</p>
	</div>
</div>