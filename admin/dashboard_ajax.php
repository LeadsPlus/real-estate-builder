<?php

/**
 * Called by AJAX to upload image from Settings page
 */

if (!current_user_can("edit_themes"))
    die("permission denied");

require_once( ABSPATH . WPINC . '/default-widgets.php' );

$url = 'http://feeds.feedburner.com/placester/news';
$rss = @fetch_feed( $url );

if ( is_wp_error($rss) ) {
	if ( is_admin() || current_user_can('manage_options') ) {
		echo '<div class="rss-widget"><p>';
		printf(__('<strong>RSS Error</strong>: %s'), $rss->get_error_message());
		echo '</p></div>';
	}
} elseif ( !$rss->get_item_quantity() ) {
	$rss->__destruct();
	unset($rss);
	return false;
} else {
	echo '<div class="rss-widget">';
	wp_widget_rss_output( $rss, array('items' => 10) );
	echo '</div>';
	$rss->__destruct();
	unset($rss);
}
