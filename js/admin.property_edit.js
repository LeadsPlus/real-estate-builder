jQuery(document).ready(function() {
    $delete_all_button = jQuery( '#placester_delete_all_images' );
    if ( jQuery('#placester_listing_images .img').size() == 0 ) {
        $delete_all_button.hide();
    }     

    // 'Delete all images' button click event handler
    $delete_all_button.live('click', function(e){
        $postbox = jQuery(this).closest('.postbox');
        if ( !confirm('Are you sure you want to delete all images?') || ( $postbox.find('.img').size() == 0) )
            return false;

        e.preventDefault();

        images = new Array();
        jQuery(this).closest('.postbox').find('.img .remove').each(function() {
            url_vars = jQuery.getUrlVars( jQuery(this).attr('href') );
            images.push(url_vars['delete']);
        });
        data = {
            action: 'listing_image_delete',
            image: images,
            property_id: url_vars['id'],
        };

        spinner = '<img src="' + params.spinner + '" alt="spinner" />';
        jQuery('#placester_listing_images').html(spinner);

        // Send AJAX request
        jQuery.post(uploadify_settings.wpajaxurl, data, function(response) {
            message = '';

            response = jQuery.parseJSON(response);

            if ( parseInt(response.s) ) {
                message = response.s + ' images have been successfully deleted.';
                if ( parseInt(response.s) == 1 ) {
                    message = 'The image has been successfully deleted.'
                }
                if ( !parseInt(response.e) ) {
                    response = '<p>This listing has no images added.</p>';
                    jQuery('#placester_listing_images').html(response);
                }
            }
            if ( parseInt(response.e) ) {
                message = message + ' ' + response.e + ' images could not be deleted.';

            }
        
            $messagebox = jQuery('<div id="placester-updated-image" style="display:none" class="updated"><p>' + message + '</p></div>');

            $messagebox
                .insertBefore($postbox)
                .fadeIn();

            if ( !parseInt(response.e) ) {
                $messagebox
                    .delay(5000)
                    .fadeOut('fast', function(){
                        jQuery(this).remove();
                    });
                $delete_all_button.fadeOut();
            }
        });  
    });

    jQuery('#placester_listing_images .img .remove').live('click', function(e){
        e.preventDefault();
        $this = jQuery(this);
        url_vars = jQuery.getUrlVars($this.attr('href'));
        data = {
            action: 'listing_image_delete',
            image: url_vars['delete'],
            property_id: url_vars['id'],
        };
        
        spinner = '<img class="spinner" src="' + params.spinner + '" alt="spinner" />';
        $image = $this.closest('.img');
        $postbox = $this.closest('.postbox');
        $this
            .before(spinner)
            .remove();
                            
        jQuery.post(uploadify_settings.wpajaxurl, data, function(response) {
            if (response) {
                $image
                    .fadeOut('400', function(){
                        jQuery(this).remove();
                        if (jQuery('#placester_listing_images img').size() < 1) {
                            response = '<p>This listing has no images added.</p>';
                            jQuery('#placester_listing_images').html(response);
                            $delete_all_button.fadeOut();
                        }
                    });
                jQuery('<div id="placester-updated-image" style="display:none" class="updated"><p>The image has been successfully removed.</p></div>')
                    .insertBefore($postbox)
                    .fadeIn()
                    .delay(2000)
                    .fadeOut('fast', function(){
                        jQuery(this).remove();
                    });
            }
        });  

    });

     // Refresh button click event handler
     jQuery('#placester_images_box a.refresh').live('click', function(e) {
        e.preventDefault();
        jQuery('#placester-updated-image').fadeOut('200', function() {
            jQuery(this).remove();
        });
        data = {
            action: 'update_images',
            property_id: uploadify_settings.property_id
        };
        jQuery('#placester_listing_images').html('<img src="' + uploadify_settings.loader + '" alt="loader" />');
        jQuery.get(uploadify_settings.wpajaxurl, data, function(response) {
            if (!response) {
                response = '<p>This listing has no images added.</p>';
                $delete_all_button.hide();
            } else {
                $delete_all_button.fadeIn();
            }

                jQuery('#placester_listing_images').html(response);
                
        });  
    }); 
    
});


var images_popup_object = null;

/*
 * Utility to get current HTTP GET parameters
 */
jQuery.extend(
{
    getUrlVars: function( href )
    {
        var vars = [];
        var href = typeof(href) != 'undefined' ? href : window.location.href;
        var p = href.indexOf('?');
        if (p >= 0)
        {
            var hashes = href.slice(p + 1).split('&');
            for(var i = 0; i < hashes.length; i++)
            {
              var hash = hashes[i].split('=');
              vars.push(unescape(hash[0]));
              vars[unescape(hash[0])] = unescape(hash[1]);
            }
        }
        return vars;
    }
});

/*
 * Lightbox linkage with image. popup iframe code calls that function
 * to display lightbox with image content. Iframe cannt do that since lightbox must be 
 * fullframe
 */
function show_image(url)
{ 
    jQuery("#lightbox_link")
        .attr('href', url)
        .trigger('click');
}
