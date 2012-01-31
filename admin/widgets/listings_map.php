<?php
class Placester_Listing_Map_Widget extends WP_Widget {
	function Placester_Listing_Map_Widget() {
		$options = array(
			'classname' => 'Placester_Listing_Map_Widget',
			'description' => 'Does not work on the search page',
			);
		$this->WP_Widget( 'Placester_Listing_Map_Widget', 'Placester | Sidebar - Listings Map', $options );
	}
	
	function form($instance) {
		$defaults = array( 'title' => 'Listings Map' );
		$instance = wp_parse_args( (array)$instance, $defaults );
		$title = $instance['title'];
?>
Title: <input class='widefat' name='<?php echo $this->get_field_name( 'title' ); ?>' type='text' value='<?php echo esc_attr( $title ); ?>' /><br />
</p>
<p style="font-size: 0.9em;">
Warning: This widget does not work on pages that already display the listings map.
</p>
<?php
	}
    //save the widget settings
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags( $new_instance['title'] );
        return $instance;
    }
    //display the widget
    function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters( 'widget_title', $instance['title'] );
?>
        <section class="side-ctnr">
        <h3><?php echo $title; ?></h3>
        <section class="common-side-cont"><?php 
        try { placester_listings_map(); }
        catch (PlaceSterNoApiKeyException $e) { display_no_api_key_error(); }
    ?></section>
        <div class="separator"></div>
        </section>
<?php 
    }
}
 
add_action('init', 'placester_listings_map_widget');
// Style
function placester_listings_map_widget() {
    global $i_am_a_placester_theme;
    wp_register_style( 'placester.map.widget', 
        plugins_url( '/placester/css/listings_map.widget.css' ) );

    if ( empty($i_am_a_placester_theme) )
        wp_enqueue_style( 'placester.map.widget' );
}

