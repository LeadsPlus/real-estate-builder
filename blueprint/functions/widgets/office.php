<?php

class PLS_Widget_Office extends WP_Widget {
	
	function PLS_Widget_Office() {

		$widget_ops = array( 'classname' => 'example', 'description' => 'Change the title of the "Our Office" widget.' );

		/* Widget control settings. */
		$control_ops = array( 'width' => 200, 'height' => 350 );

		/* Create the widget. */
		$this->WP_Widget( 'OurOfficeWidget', 'Placester: Our Office Widget', $widget_ops, $control_ops );
				
	}
	function widget( $args, $instance ) {
		// Widget output
		?>
		<?php extract($args); ?>

    <?php $agent = PLS_Plugin_API::get_user_details(); ?>
    
		<section class="widget pls-map widget-pls-map" itemscope itemtype="http://schema.org/LocalBusiness">
			<?php $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']); ?>
			<?php $subtitle = empty($instance['subtitle']) ? ' ' : apply_filters('subtitle', $instance['subtitle']); ?>
			<?php $street_address = empty($instance['street_address']) ? ' ' : apply_filters('street_address', $instance['street_address']); ?>
			<?php $city_state_zip = empty($instance['city_state_zip']) ? ' ' : apply_filters('city_state_zip', $instance['city_state_zip']); ?>
			<?php $lat = empty($instance['lat']) ? ' ' : apply_filters('lat', $instance['lat']); ?>
			<?php $lng = empty($instance['lng']) ? ' ' : apply_filters('lng', $instance['lng']); ?>
			<?php $width = empty($instance['width']) ? ' ' : apply_filters('width', $instance['width']); ?>
			<?php $height = empty($instance['height']) ? ' ' : apply_filters('height', $instance['height']); ?>

			<h3><?php echo $title; ?></h3>

			<section id="map-widget">

        <script src="http://maps.googleapis.com/maps/api/js?sensor=false" type="text/javascript"></script>

				<script type="text/javascript">
					jQuery(document).ready(function () {

								var myOptions = {
									center: new google.maps.LatLng(<?php echo $lat; ?>, <?php echo $lng; ?>),
									zoom: 12,
									mapTypeId: google.maps.MapTypeId.ROADMAP
								};
								
								var map = new google.maps.Map(document.getElementById("simple_map"),
									myOptions);
								
								var position = new google.maps.LatLng(<?php echo $lat; ?>, <?php echo $lng; ?>);
								var marker = new google.maps.Marker({
									position: position,
									map: map,
								});
						});
				</script>

			<div id="simple_map" style="width: <?php echo $width; ?>px; height: <?php echo $height; ?>px;"></div>

        <span itemprop="name" style="display:none;"><?php echo $agent['provider']['name'];  ?></span>
				<p class="office p4" itemprop="description"><?php echo $subtitle; ?></p>
				<p class="address h5" itemprop="address"><?php echo $street_address; ?><br><?php echo $city_state_zip; ?></p>

			</section><!-- /#map-widget -->

		</section><!-- /.widget-pls-quick-search -->
		<?php
	}
	function update( $new_instance, $old_instance ) {
		// Save widget options
		$instance = $old_instance;
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['subtitle'] = strip_tags($new_instance['subtitle']);
			$instance['street_address'] = strip_tags($new_instance['street_address']);
			$instance['city_state_zip'] = strip_tags($new_instance['city_state_zip']);
			$instance['lat'] = strip_tags($new_instance['lat']);
			$instance['lng'] = strip_tags($new_instance['lng']);
			$instance['width'] = strip_tags($new_instance['width']);
			$instance['height'] = strip_tags($new_instance['height']);

		return $instance;
	}
	function form( $instance ) {
		// Output admin widget options form
			$instance = wp_parse_args( (array) $instance, array( 'title' => 'Our Office', 'subtitle' => 'Stop by our office, we would love to see you!', 'street_address' => 'One Broadway', 'city_state_zip' => 'Cambridge, MA, 02142', 'lat' => '42.3630663', 'lng' => '-71.0838938', 'width' => '240', 'height' => '130' ) );
			$title = strip_tags($instance['title']);
			$subtitle = strip_tags($instance['subtitle']);
			$street_address = strip_tags($instance['street_address']);
			$city_state_zip = strip_tags($instance['city_state_zip']);
			$lat = strip_tags($instance['lat']);
			$lng = strip_tags($instance['lng']);
			$width = strip_tags($instance['width']);
			$height = strip_tags($instance['height']);
	    ?>

      <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php echo 'Title' ?>: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
      <p><label for="<?php echo $this->get_field_id('subtitle'); ?>"><?php echo 'Subtitle' ?>: <input class="widefat" id="<?php echo $this->get_field_id('subtitle'); ?>" name="<?php echo $this->get_field_name('subtitle'); ?>" type="text" value="<?php echo esc_attr($subtitle); ?>" /></label></p>
      <p><label for="<?php echo $this->get_field_id('street_address'); ?>"><?php echo 'Street Address' ?>: <input class="widefat" id="<?php echo $this->get_field_id('street_address'); ?>" name="<?php echo $this->get_field_name('street_address'); ?>" type="text" value="<?php echo esc_attr($street_address); ?>" /></label></p>
      <p><label for="<?php echo $this->get_field_id('city_state_zip'); ?>"><?php echo 'City, State, Zip' ?>: <input class="widefat" id="<?php echo $this->get_field_id('city_state_zip'); ?>" name="<?php echo $this->get_field_name('city_state_zip'); ?>" type="text" value="<?php echo esc_attr($city_state_zip); ?>" /></label></p>
      <p><label for="<?php echo $this->get_field_id('lat'); ?>"><?php echo 'Latitude' ?>: <input class="widefat" id="<?php echo $this->get_field_id('lat'); ?>" name="<?php echo $this->get_field_name('lat'); ?>" type="text" value="<?php echo esc_attr($lat); ?>" /></label></p>
      <p><label for="<?php echo $this->get_field_id('lng'); ?>"><?php echo 'Longitude' ?>: <input class="widefat" id="<?php echo $this->get_field_id('lng'); ?>" name="<?php echo $this->get_field_name('lng'); ?>" type="text" value="<?php echo esc_attr($lng); ?>" /></label></p>
      <p><label for="<?php echo $this->get_field_id('width'); ?>"><?php echo 'Width' ?>: <input class="widefat" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo esc_attr($width); ?>" /></label></p>
      <p><label for="<?php echo $this->get_field_id('height'); ?>"><?php echo 'Height' ?>: <input class="widefat" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo esc_attr($height); ?>" /></label></p>


	    <?php
	}
}