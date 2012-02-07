var placester_template_edit_mode = false;
var template_chosen = false;

jQuery(document).ready(function() {        

    // Copy to clipboard ---------------------------------------------------------------------------------
    ZeroClipboard.setMoviePath( placester_templates.plugin_url + '/js/zeroclipboard/ZeroClipboard.swf' );
    var clip = new ZeroClipboard.Client();
    clip.setHandCursor( true );
    clip.glue( 'copy_craigslist', 'copy_craigslist_container' );

    // Add copy to clipboard button click event
    clip.addEventListener( 'onMouseDown', function(){
        if (template_chosen) {
            template_html = document.getElementById("preview_iframe").contentWindow.document.body.innerHTML;
            clip.setText(template_html);
        }
    });

    // Add copy to clipboard button complete event
    clip.addEventListener( 'complete', function(client, text) {
        var complete_alert = '<div id="complete_alert" style="display: none">The code has been copied to your clipboard.<br />Use CTRL-V on Windows or CMD-V on Mac to paste it.</div>';
        jQuery('.template_preview')
            .append(complete_alert)
            .find('#complete_alert')
                .fadeIn('fast')
                .delay(5000)
                .fadeOut('slow', function() {
                    jQuery(this).remove();
                });
    } );
    jQuery('#copy_craigslist_container').hide();
    // #End Copy to clipboard ---------------------------------------------------------------------------------

    if (jQuery('#current_template_name').val().length <= 0)
       placester_set_active_template(false);


    jQuery('.template_item').click(function()
    {
        $(this).closest('.template_menu').find('.active').removeClass('active');
        $(this).addClass('active');
        placester_template_edit_mode = false;
        jQuery('#edit_template').val('Edit Template');

        jQuery('#current_template_name').val(jQuery(this).attr('system_name'));
        placester_set_active_template(jQuery(this).attr('active') == '1');

        placester_load_iframe();
        
    });


    jQuery('#edit_template').click(function()
    {
        if (!placester_template_edit_mode)
        {
            placester_template_edit_mode = true;
            placester_load_iframe();
            jQuery('#edit_template').css({'display': 'none'});

            if (jQuery('#current_template_name').val().substr(0, 5) == 'user_')
                jQuery('#save_template_user_panel').css({'display': ''});
            else
                jQuery('#save_template_panel').css({'display': ''});
        }
    });

    jQuery('#save_template').click(function()
    {
        v = jQuery('#preview_iframe').contents().
            find('#textarea_content').val();
        jQuery('#save_thumbnail_url').val(jQuery('#preview_iframe').contents().
            find('#thumbnail_url').val());
        jQuery('#save_template_content').val(v);
    });
    jQuery('#save_template_as').click(function()
    {
        v = jQuery('#preview_iframe').contents().
            find('#textarea_content').val();
        jQuery('#save_thumbnail_url').val(jQuery('#preview_iframe').contents().
            find('#thumbnail_url').val());
        jQuery('#save_template_content').val(v);
    });
});



function placester_load_iframe()
{
    var url = 'admin.php?page=placester_properties&craigslist_template=1&template_iframe=' +
        jQuery('#current_template_name').val() + '&property_id=' + placester_templates.property_id;
    if (placester_template_edit_mode)
        url += "&mode=edit";
    jQuery('#preview_iframe')
        .attr('src', url)
        // .html('<img src="http://www.gseforsale.aero/images/ajax-loader.gif />')
        .load(function() {
            jQuery(this).contents()
                .find('#template_container')
                    .fadeIn();  
            if (!template_chosen) {
                template_chosen = true;
                jQuery('#copy_craigslist_container').fadeIn();
            }       
        });
}


function placester_set_active_template(is_active)
{
    jQuery('#edit_template').css({'display': ''});
    jQuery('#save_template_panel').css({'display': 'none'});
    jQuery('#save_template_user_panel').css({'display': 'none'});

    var v = is_active ? '' : 'disabled';
    jQuery('#edit_template').attr('disabled', v);
}

