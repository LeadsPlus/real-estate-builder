	jQuery(document).ready(function() {

	  jQuery('#file_upload').uploadify({
          'uploader'  : uploadify_settings.ajaxurl + '/js/uploadify/uploadify.swf',
          'script'    : uploadify_settings.ajaxurl + '/js/uploadify/uploadify.php',
        'scriptData'  : {'property_id': uploadify_settings.property_id,'api_key':uploadify_settings.api_key},
          'cancelImg' : uploadify_settings.ajaxurl + '/js/uploadify/cancel.png',
          'folder'    : '../wp-content/uploads',//uploadify_settings.root,
          'fileExt'   : '*.jpg;*.jpeg;*.gif;*.png',
          'fileDataName'    : 'images', // The name of the file collection object in the backend upload script
          'auto'      : true,
          'multi' : true,
          'buttonImg' : uploadify_settings.ajaxurl + '/images/upload-btn.png',
          'rollover' : true,
          'onComplete'  : function(event, ID, fileObj, response, data) {
              // The response is whatever is echoed in uploadify.php
              // Currently, it is the property id
              after_uploadify(response);

              jQuery('#placester-updated-image').fadeOut('400', function() {
                  jQuery(this).remove();
              });

              $postbox = jQuery('#placester_listing_images').closest('.postbox');
              $('<div id="placester-updated-image" style="display:none" class="updated"><p>The image has been successfully uploaded. Refresh to see it in the box.</p></div>')
                .insertBefore($postbox)
                .fadeIn();
          }
          
	  });
	});

var after_uploadify = function(pid) {
    data = {
        action: 'after_uploadify',
        property_id: pid
    };
    jquery.post(uploadify_settings.wpajaxurl, data, function(response) {
    });  
}
 jQuery(document).ready(function() {

     // $("#placester_listing_images .img").live("mouseover mouseout", function(event) {
     //     console.log($(this));
     //     if ( event.type == "mouseover") {

     //         if (!($(this).hasClass('hovered')))
     //     $(this).stop().addClass('hovered').find('.info').fadeTo('fast', 1);
     //        // .find('a')
     //        //     .fadeIn(100)
     //        // .parent()
     //     } else {
     //     $(this).stop().removeClass('hovered').find('.info').fadeTo('fast', 0);
     //        // .find('a')
     //        //     .fadeOut(100)
     //        // .parent()
     //     }
     // });
 });
