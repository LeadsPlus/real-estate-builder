
/**
 * Links upload textboxes with jquery upload script
 */
var map_geocoded_address;
jQuery(document).ready(function()
{
    jQuery('.file_upload').click(function()
    {
        var id = jQuery(this).attr('id');
        id = id.substr(0, id.length - 7);
        jQuery('#' + id + '_file').upload(
            'admin.php?page=placester_settings&ajax_action=upload', 
            function(res)
            {
                jQuery('#' + id + '_thumbnail').html(
                    '<img src="' + res.thumbnail + '" />');
                jQuery('#' + id).val(res.id);
            },
            'json');
    });

   jQuery("#refresh_user_data")
        .click(function(e) {
            var answer = confirm("This will reset all you information. Do you want to continue?")
            if (!answer) {
                e.preventDefault();
            }
        });

    // Select center of map
    lat = $('#placester_center_latitude').val();
    lng = $('#placester_center_longitude').val();
    zm = $('#placester_map_zoom').val();

    var latlng = new google.maps.LatLng(lat, lng);
    var myOptions = {
      zoom: parseInt(zm),
      center: latlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    window.map = new google.maps.Map(document.getElementById("map"),
        myOptions);

    google.maps.event.addListener(window.map, 'zoom_changed', function() {
        jQuery("#placester_map_zoom").val(window.map.getZoom());
    });

    window.geocoder = new google.maps.Geocoder();

        // if (jQuery("#location_coords_latitude").val() != "" && 
        //     jQuery("#location_coords_longitude").val() != "")
            // map_create_marker(
            //     new google.maps.LatLng(jQuery("#location_coords_latitude").val(),
            //         jQuery("#location_coords_longitude").val()));


    jQuery("#placester_map_center_address").change(map_geocode_address);

    jQuery("#placester_map_center_address").keydown(map_geocode_address_delayed);

    map_geocoded_address = jQuery("#placester_map_center_address");

});


/*
 * Geocodes address and moves map to that position.
 */
function map_geocode_address()
{
    var address = jQuery("#placester_map_center_address").val(); 
    if (address == map_geocoded_address)
        return;
    map_geocoded_address = address;
    
    geocoder.geocode(
        { 
            "address": address
        }, 
        function(results, status) 
        {
            if (status == google.maps.GeocoderStatus.OK) 
                loc = results[0].geometry.location;
                map.setCenter(loc);

                 jQuery("#placester_center_latitude").val(loc.lat());
                 jQuery("#placester_center_longitude").val(loc.lng());

                // map_create_marker(results[0].geometry.location);
        });
}

var map_timeout = null;
/*
 * Queues geocoding call with some small delay.
 * Cancels pending geocoding caused by call before that.
 * Good for case in user is modifying something right now.
 */
function map_geocode_address_delayed()
{
    if (map_timeout != null)
    {
        clearTimeout(map_timeout);
        map_timeout = null;
    }
    map_timeout = setTimeout('map_geocode_address()', 1000);
}



/**
 * tinymce stuff
 */
jQuery(document).ready(function($) {

    var id = 'placester_map_info_template';
    jQuery('#'+id+'_toggleVisual').click(function() {
            tinyMCE.execCommand('mceAddControl', false, id);
    });
    jQuery('#'+id+'_toggleHTML').click(function() {
            tinyMCE.execCommand('mceRemoveControl', false, id);
    });

    var id2 = 'placester_list_details_template';
    jQuery('#'+id2+'_toggleVisual').click(function() {
            tinyMCE.execCommand('mceAddControl', false, id2);
    }); 
    jQuery('#'+id2+'_toggleHTML').click(function() {
            tinyMCE.execCommand('mceRemoveControl', false, id2);
    });

    var id3 = 'placester_snippet_layout';
    jQuery('#'+id3+'_toggleVisual').click(function() {
            tinyMCE.execCommand('mceAddControl', false, id3);
    }); 
    jQuery('#'+id3+'_toggleHTML').click(function() {
            tinyMCE.execCommand('mceRemoveControl', false, id3);
    });

    var id4 = 'placester_listing_layout';
    jQuery('#'+id4+'_toggleVisual').click(function() {
            tinyMCE.execCommand('mceAddControl', false, id4);
    }); 
    jQuery('#'+id4+'_toggleHTML').click(function() {
            tinyMCE.execCommand('mceRemoveControl', false, id4);
    });

});



/**
 * Reaction to entering post slug 
 */
jQuery(document).ready(function($) {

   if ($('#placester_url_slug').val().length <= 0) {
       $('#url_target').html('SOMETHING');
   } else {
       $('#url_target').html($('#placester_url_slug').val());
   };
   
    $('#placester_url_slug').keyup( function () {
       $('#url_target').html($('#placester_url_slug').val()); 
    });

});
