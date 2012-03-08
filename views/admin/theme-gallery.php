<?php //pls_dump(fetch_feed('https://placester.com/themes/feed/')) ?>
<?php //pls_dump(wp_widget_rss_output('https://placester.com/themes/feed/')) ?>
<?php  

	$rss =fetch_feed('https://placester.com/themes/feed/');

	if ( !$rss->get_item_quantity() ) {
		echo '<ul><li>' . __( 'An error has occurred; the feed is probably down. Try again later.' ) . '</li></ul>';
		$rss->__destruct();
		unset($rss);
		return;
	}

	echo '<ul>';
	$items = 100;
	foreach ( $rss->get_items(0, $items) as $item ) {
		$link = $item->get_link();
		pls_dump($item);
		while ( stristr($link, 'http') != $link )
			$link = substr($link, 1);
		$link = esc_url(strip_tags($link));
		$title = esc_attr(strip_tags($item->get_title()));
		if ( empty($title) )
			$title = __('Untitled');

		$desc = str_replace( array("\n", "\r"), ' ', esc_attr( strip_tags( @html_entity_decode( $item->get_description(), ENT_QUOTES, get_option('blog_charset') ) ) ) );
		$desc = wp_html_excerpt( $desc, 360 );

		// Append ellipsis. Change existing [...] to [&hellip;].
		if ( '[...]' == substr( $desc, -5 ) )
			$desc = substr( $desc, 0, -5 ) . '[&hellip;]';
		elseif ( '[&hellip;]' != substr( $desc, -10 ) )
			$desc .= ' [&hellip;]';

		$desc = esc_html( $desc );


		if ( $link == '' ) {
			echo "<li>$title{$date}{$summary}{$author}</li>";
		} else {
			echo "<li><a class='rsswidget' href='$link' title='$desc'>$title</a></li>";
		}
	}
	echo '</ul>';
	$rss->__destruct();
	unset($rss);


