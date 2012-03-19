<?php //pls_dump(fetch_feed('https://placester.com/themes/feed/')) ?>
<?php //pls_dump(wp_widget_rss_output('https://placester.com/themes/feed/')) ?>
<?php  

	$response = wp_remote_get("http://corporate.com/theme-api/", array('timeout' => 10));

	foreach (json_decode($response['body']) as $key => $theme) {
		// pls_dump($theme);
	}

	

?>


<form class="search-form filter-form" action="" method="get">
	<!-- <p class="search-box">
		<label class="screen-reader-text" for="theme-search-input">Search Installed Themes:</label>
		<input type="text" id="theme-search-input" name="s" value="">
		<input type="submit" name="" id="search-submit" class="button" value="Search Installed Themes">	<a id="filter-click" href="?filter=1">Feature Filter</a>
	</p> -->
	<br class="clear">
	<table id="availablethemes" cellspacing="0" cellpadding="0">
		<tbody id="the-list" class="list:themes">
			<tr>
				<td class="available-theme top left">
					<a href="http://foundation.wpmulti.com/?preview=1&amp;template=chapman&amp;stylesheet=chapman&amp;preview_iframe=1&amp;TB_iframe=true&amp;width=640&amp;height=328" class="thickbox thickbox-preview screenshot">
						<img src="http://foundation.wpmulti.com/wp-content/themes/chapman/screenshot.png" alt="">
					</a>
					<h3>Arthur Chapman Real Estate 1.0.0 by <a href="https://www.placester.com" title="Visit author homepage">The Placester Team</a></h3>
					<p class="description">This is a custom WordPress theme created by Placester for Arthur Chapman Real Estate. Use with <a href="https://placester.com/">Placester</a>‘s <a href="wordpress.org/extend/plugins/placester/">Real Estate Builder plugin</a>.</p>
					<span class="action-links"><a href="themes.php?action=activate&amp;template=chapman&amp;stylesheet=chapman&amp;_wpnonce=b9a7560b6c" class="activatelink" title="Activate “Arthur Chapman Real Estate”">Activate</a> | <a href="http://foundation.wpmulti.com/?preview=1&amp;template=chapman&amp;stylesheet=chapman&amp;preview_iframe=1&amp;TB_iframe=true&amp;width=640&amp;height=328" class="thickbox thickbox-preview" title="Preview “Arthur Chapman Real Estate”">Preview</a></span>
					<p>All of this theme’s files are located in <code>/themes/chapman</code>.</p>
					<p>Tags: blue, red, green, right-sidebar, fixed-width, custom-menu</p>
				</td>
			</tr>
		</tbody>
	</table>		
</form>