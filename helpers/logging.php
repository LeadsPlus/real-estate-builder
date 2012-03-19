<?php 

PL_Logging::init();
class PL_Logging {

	static $hook;

	 function init() {
	 	$logging_option = PL_Option_Helper::get_log_errors();
	 	if ($logging_option) {
			add_action('admin_footer', array(__CLASS__, 'events'));
			add_action('admin_enqueue_scripts', array(__CLASS__, 'record_page'));
			register_activation_hook( PL_PARENT_DIR, 'activation' );
		}
	 }

	 function record_page ($hook) {
	 	self::$hook = $hook;
	 }

	 function events () {
	 	$hook = self::$hook;
	 	$pages = array('placester_page_placester_properties', 'placester_page_placester_property_add', 'placester_page_placester_settings', 'placester_page_placester_support');
		if (!in_array($hook, $pages)) { return; }

	 	ob_start();
	 	?>
	 		<script type="text/javascript">
	 			// (function(d,c){var a,b,g,e;a=d.createElement("script");a.type="text/javascript";
			  //   a.async=!0;a.src=("https:"===d.location.protocol?"https:":"http:")+
			  //   '//mixpanel.com/site_media/js/api/mixpanel.2.js';b=d.getElementsByTagName("script")[0];
			  //   b.parentNode.insertBefore(a,b);c._i=[];c.init=function(a,d,f){var b=c;
			  //   "undefined"!==typeof f?b=c[f]=[]:f="mixpanel";g=['disable','track','track_links',
			  //   'track_forms','register','register_once','unregister','identify','name_tag','set_config'];
			  //   for(e=0;e<g.length;e++)(function(a){b[a]=function(){b.push([a].concat(
			  //   Array.prototype.slice.call(arguments,0)))}})(g[e]);c._i.push([a,d,f])};window.mixpanel=c}
			  //   )(document,[]);
			  //   mixpanel.init("9186cdb540264089399036dd672afb10");
	 		</script>	
	 	<?php

	 	if (!PL_Option_Helper::api_key()) {
	 		?>
		 		<script type="text/javascript">
		 			if ( $('#ui-dialog-title-signup_wizard').is(':visible') ) {
		 				// mixpanel.track("signup_overlay");			
		 			}
		 			// mixpanel.track("signup_overlay");			
		 			// mixpanel.track_links($(".ui-dialog-buttonset"), "add_property_button");
		 		</script>	
		 	<?php	
	 	}

	 	if ($hook == 'placester_page_placester_property_add') {
		 	?>
		 		<script type="text/javascript">
	 				// mixpanel.track("add_property");		
		 			// mixpanel.track_links($("#add_listing_publish"), "add_property_button");
		 		</script>	
		 	<?php	
	 	}
	 	echo ob_get_clean();
	 }

	 function activation () {
	 	self::events();

	 	ob_start();
	 	?>
	 		<script type="text/javascript">
	 			// mixpanel.track("activation");
	 		</script>	
	 	<?php
	 	echo ob_get_clean();

	 }
}
