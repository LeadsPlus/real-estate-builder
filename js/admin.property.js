var map_geocoded_address;
var map_readonly = false;

/*
 * Initalizes map object
 */
jQuery(document).ready(function()
{
    var latlng = new google.maps.LatLng(-34.397, 150.644);
    var myOptions = {
      zoom: 8,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    window.map = new google.maps.Map(document.getElementById("map"),
        myOptions);
    window.geocoder = new google.maps.Geocoder();

    try
    {
        if (jQuery("#location_coords_latitude").val() != "" && 
            jQuery("#location_coords_longitude").val() != "")
            map_create_marker(
                new google.maps.LatLng(jQuery("#location_coords_latitude").val(),
                    jQuery("#location_coords_longitude").val()));
    }
    catch(e)
    {}

    jQuery("#location_address").change(map_geocode_address);
    jQuery("#location_zip").change(map_geocode_address);
    jQuery("#location_city").change(map_geocode_address);
    jQuery("#location_state").change(map_geocode_address);

    jQuery("#location_address").keydown(map_geocode_address_delayed);
    jQuery("#location_zip").keydown(map_geocode_address_delayed);
    jQuery("#location_city").keydown(map_geocode_address_delayed);
    jQuery("#location_state").keydown(map_geocode_address_delayed);

    map_geocoded_address = 
        clearIfEmpty({val: jQuery("#location_address").val(), pre: false}) +
        clearIfEmpty({val: jQuery("#location_zip").val()}) +
        clearIfEmpty({val: jQuery("#location_city").val()}) +
        clearIfEmpty({val: jQuery("#location_state").val()});

    jQuery('#property_add_form').submit(function(e) {
        // $('#file_upload').uploadifyUpload();
    });

});

function clearIfEmpty( arg ) {
    defaults = {
        pre: true
    };
    arg = jQuery.extend( defaults, arg )
    string = arg.val;
    if ( typeof( arg.val ) == 'undefined' || arg.val === '' ) 
        return '';
    else if (arg.pre)
        string = ', ' + arg.val;

    return string;
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



/*
 * Geocodes address and moves map to that position.
 */
function map_geocode_address()
{
    var address = 
        clearIfEmpty({val: jQuery("#location_address").val(), pre: false}) +
        clearIfEmpty({val: jQuery("#location_zip").val()}) +
        clearIfEmpty({val: jQuery("#location_city").val()}) +
        clearIfEmpty({val: jQuery("#location_state").val()});

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
                map_create_marker(results[0].geometry.location);
            else {
                $alert_div = jQuery('<div class="error inline"><p>Geocode was not successful, address used "' + address + '"</p></div>');
                jQuery('.wrap #location_state').closest('.postbox').before($alert_div);
                $alert_div.delay(5000).hide('slow', function() { 
                    $(this).remove();
                });
            }
        });
}



/*
 * Creates marker on map by passed position
 *
 * @param object p
 */
function map_create_marker(p)
{
    map.setCenter(p);
    if (map.getZoom() <= 14)
        map.setZoom(14);

    jQuery("#location_coords_latitude").val(p.lat());
    jQuery("#location_coords_longitude").val(p.lng());
    jQuery("#map_tip").css("display", "");

    if (window.marker != null)
        window.marker.setMap(null);

    window.marker = new google.maps.Marker(
        {
            map: window.map, 
            position: p,
            draggable: true,
            cursor: "hand",
        });

    google.maps.event.addListener(marker, "drag", function() 
        {
            if (!map_readonly)
            {
                jQuery("#location_coords_latitude").val(marker.position.lat());
                jQuery("#location_coords_longitude").val(marker.position.lng());
            }
        });
}



/*
 * Connect field to datepicker control
 */
jQuery(document).ready(function()
{
    jQuery("#available_on").datepicker({dateFormat:"yy-mm-dd"});
});




/*
 * Readonly handing
 */
jQuery(document).ready(function()
{
    if (jQuery("#property_readonly").val() != null)
    {
        map_readonly = true;
        jQuery("input[type='file'],[type='submit']").attr('disabled', 'disabled');
        jQuery("input,select").focusin(function()
        {
            id = jQuery("#property_readonly").val();
            jQuery('#' + id).css({'background': '#FF8888'});
            jQuery(document).scrollTop(0);
            jQuery("input,select").attr('disabled', 'disabled');
        });
    }
});
