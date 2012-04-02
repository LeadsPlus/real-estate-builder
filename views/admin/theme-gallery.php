<?php //pls_dump(fetch_feed('https://placester.com/themes/feed/')) ?>
<?php //pls_dump(wp_widget_rss_output('https://placester.com/themes/feed/')) ?>
<?php  

	$response = wp_remote_get("http://placester.com/wp-admin/admin-ajax.php?action=get_themes_api", array('timeout' => 10));

?>
<form class="search-form filter-form" action="" method="get">
	<!-- <div class="theme-search-wrapper">
		<h3 class="theme-search-header">Search for themes:</h3>
		<ul class="subsubsub">
			<li class="theme-install-dashboard"><a href="" class="">Agency Themes</a> |</li>
			<li class="theme-install-dashboard"><a href="" class="">Single Property</a></li>
		</ul>	
	</div> -->
	<br class="clear">
	<table id="availablethemes" cellspacing="0" cellpadding="0">
		<tbody id="the-list" class="list:themes">
			<tr>
				<?php foreach (json_decode($response['body'], true) as $key => $theme): ?>
					<td class="available-theme top left">
						<a href="<?php echo $theme['demo_link'] ?>" class="thickbox thickbox-preview screenshot">
							<?php echo $theme['thumbnail'] ?>
						</a>
						<h3><?php echo $theme['title'] ?> <a href="https://www.placester.com" title="Visit author homepage">The Placester Team</a></h3>
						<p class="description"><?php echo $theme['excerpt'] ?></p>
						<span class="action-links">
							<a id="install_theme" href="<?php echo implode($theme['download_link'],'') ?>" class="" title="">Install</a>
							|
							<a href="themes.php?action=activate&amp;template=chapman&amp;stylesheet=chapman&amp;_wpnonce=b9a7560b6c" class="activatelink" title="Activate “Arthur Chapman Real Estate”">Activate</a>
							| 
							<a href="http://foundation.wpmulti.com/?preview=1&amp;template=chapman&amp;stylesheet=chapman&amp;preview_iframe=1&amp;TB_iframe=true&amp;width=640&amp;height=328" class="thickbox thickbox-preview" title="Preview “Arthur Chapman Real Estate”">Preview</a>
						</span>
						<!-- <p>Tags: blue, red, green, right-sidebar, fixed-width, custom-menu</p> -->
					</td>
				<?php endforeach ?>
			</tr>
		</tbody>
	</table>		
</form>

<div style="display:none">
	<div id="install_theme_overlay">
		<h1>Oh really?</h1>
		<div id="theme_install_message"></div>
	</div>
</div>


<?php /*

<p class="search-box">
		<label class="screen-reader-text" for="theme-search-input">Search Installed Themes:</label>
		<input type="text" id="theme-search-input" name="s" value="">
		<input type="submit" name="" id="search-submit" class="button" value="Search Installed Themes">	<a id="filter-click" href="?filter=1">Feature Filter</a>
	</p>

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


*/ ?>



<?php /* 

<style type="text/css">
	.support_wrapper {
	width: 600px;
	margin: 50px auto;
    font-family: "HelveticaNeue-Light","Helvetica Neue Light","Helvetica Neue",sans-serif;
    font-weight: normal;
    text-shadow: rgba(255, 255, 255, 1) 0 1px 0;
    text-align: center;
}
.support_wrapper h1 {
	font-size: 40px;
	font-family: "HelveticaNeue-Light","Helvetica Neue Light","Helvetica Neue",sans-serif;
    font-weight: normal;
    text-shadow: rgba(255, 255, 255, 1) 0 1px 0;
    margin-bottom: 40px;
}
.support_wrapper h3 {
	font-size: 26px;
	font-family: "HelveticaNeue-Light","Helvetica Neue Light","Helvetica Neue",sans-serif;
    font-weight: normal;
    text-shadow: rgba(255, 255, 255, 1) 0 1px 0
}
</style>
<div class="support_wrapper">
	<h1>Looking for Real Estate Themes?</h1>
	<h3>Check them at the <a id="theme_gallery_placester" href="https://placester.com/themes/">theme gallery</a></h3>
</div>

*/ ?>