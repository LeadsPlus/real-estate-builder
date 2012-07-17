<?php extract(PL_Page_Helper::get_types()); ?>

<div class="wrap">
	<?php echo PL_Helper_Header::pl_settings_subpages(); ?>
	<div class="settings_option_wrapper">
		<div class="header-wrapper">
			<h2>You have <span id="num_placester_pages"><?php echo $total_pages; ?></span> listing pages created.</h2>	
			<a class="button-secondary" id="delete_pages" >Delete all pages</a>	
			<div class="ajax_message" id="regenerate_message"></div>
		</div>
		<div class="clear"></div>
		<p>Listing pages are found by indexed by search engines like Google and Bing. Once you're listing pages are indexed they can be found by searchers.</p>
	</div>
	<div class="pages_list">
		<div class="pages" id="list_of_pages">
			<table id="list_of_pages_list" class="widefat post fixed list_of_pages_list" cellspacing="0">
			    <thead>
			      <tr>
			        <th><span>Page ID</span></th>
			        <th><span>Date</span></th>
			        <th><span>Property ID</span></th>
			        <th><span>Page Title</span></th>
			        <th><span>Excerpt</span></th>
			        <th><span>Content</span></th>
			        <th><span></span></th>
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
			        <th></th>
			      </tr>
			    </tfoot>
			  </table>
		</div>
	</div>
</div>