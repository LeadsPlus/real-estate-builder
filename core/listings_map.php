<?php

/**
 * Body of "placester_listings_map()" function
 * This file is processed only when function is really called
 */

// Check if api key specified
if ( placester_warn_on_api_key() )
    return;

//
// Load images
// 
$marker_id = get_option( 'placester_map_marker_image' );
$marker = wp_get_attachment_image_src( $marker_id, 'full' );
if ( $marker === FALSE ) {
    // Default image
    $marker_url = 'http://www.google.com/intl/en_us/mapfiles/ms/micons/red-dot.png';
    $marker_width = '32';
    $marker_height =  '32';
} else {
    $marker_url = $marker[0];
    $marker_width = $marker[1];
    $marker_height =  $marker[2];
}

$marker_hover_id = get_option( 'placester_map_marker_hover_image' );
$marker_hover = wp_get_attachment_image_src( $marker_hover_id, 'full' );

if ( $marker_hover === FALSE ) {
    // Default image
    if ( $marker === FALSE ) {
        $marker_hover_url = 'http://www.google.com/intl/en_us/mapfiles/ms/micons/yellow-dot.png';
        $marker_hover_width = '32';
        $marker_hover_height =  '32';
    } else {
        $marker_hover_url =  $marker_url;
        $marker_hover_width = $marker_width;
        $marker_hover_height = $marker_height;
    }
} else {
    $marker_hover_url = $marker_hover[0];
    $marker_hover_width = $marker_hover[1];
    $marker_hover_height =  $marker_hover[2];
}

$loading_id = get_option( 'placester_map_tile_loading_image' );
$loading = wp_get_attachment_image_src( $loading_id, 'full' );

$loading_image_url = $loading[0];

$base_url = WP_PLUGIN_URL . '/placester';

// JS handler: marker click
$default_marker_click = ! isset( $parameters['js_on_marker_click'] );
if ( $default_marker_click )
    $js_on_marker_click = 'placesterMap_showInfoWindow';
else
    $js_on_marker_click = $parameters['js_on_marker_click'];

// JS handler: marker class
$default_get_marker_class = ! isset( $parameters['js_get_marker_class'] );
if ( $default_get_marker_class )
    $js_get_marker_class = 'placesterMap_getMarkerClass';
else
    $js_get_marker_class = $parameters['js_get_marker_class'];

// Set latidude/longitude to the parameter if set, else if an address exists hold off til 
// geocoding that, else use the default setting

$default_zoom = isset( $parameters['zoom'] ) ? $parameters['zoom'] : get_option( 'placester_map_zoom' ); 
?>
<script src="http://maps.google.com/maps/api/js?sensor=false&amp;v=3.1"></script>
<script src="<?php echo $base_url ?>/js/FastMarkerOverlay.js"></script>  
<script src="<?php echo $base_url ?>/js/DynamicMarker.js"></script>
<style>

.marker {
  height: <?php echo $marker_height ?>px;
  width: <?php echo $marker_width ?>px;
  background-image: url('<?php echo $marker_url ?>');
  cursor: pointer;
}
.marker:hover {
  height: <?php echo $marker_hover_height ?>px;
  width: <?php echo $marker_hover_width ?>px;
  background-image: url('<?php echo $marker_hover_url ?>');
  cursor: pointer;
}
</style>
<script>

var placesterMap_map;
var placesterMap_markerFactory;
var placesterMap_overlayMap;
var placesterMap_filter_query = '';

/**
 * Called when user selected new filter conditions.
 * New filtered data are requested and list is refreshed.
 *
 * @param string filter_query
 */
function placesterMap_setFilter(filter_query)
{
    placesterMap_filter_query = filter_query;

    if (placesterMap_map != null)
        placesterMap_overlayMap.refresh(true);
}



/**
 * Returns html of "loading" element for map
 *
 * @return string
 */
function placesterMap_getLoadingHtml()
{
    ret = '<div style="width: 256px; height: 255px; ' +
        'background: #CDDAE3; opacity: 0.5; opacity:0.5; display: table-cell; ' +
        'vertical-align: middle; text-align: center">' +
        '<img src="<?php echo $loading_image_url ?>" /></div>';
    return ret;
}



/**
 * Returns URL of webservice to request data
 *
 * @param int zoom
 * @param double min_latitude
 * @param double min_longitude
 * @param double max_latitude
 * @param double max_longitude
 * @return string
 */
function placesterMap_getWebServiceUrl(zoom, min_latitude, min_longitude, 
    max_latitude, max_longitude)
{
    fields = 'location.coords.latitude,location.coords.longitude,map_details';
    if (typeof(placesterListOfMap_markerDataFields) != 'undefined')
        fields += placesterListOfMap_markerDataFields();

    ret = 
        '<?php echo $base_url ?>/properties_map.php?' +
        'box[min_latitude]=' + min_latitude + '&' +
        'box[max_latitude]=' + max_latitude + '&' +
        'box[min_longitude]=' + min_longitude + '&' +
        'box[max_longitude]=' + max_longitude + '&' +
        'fields=' + fields + 
        placesterMap_filter_query;
    return ret;
}



/**
 * Handler, called on marker click
 *
 * @param array markerData
 */
function placesterMap_markerClick(markerData)
{
    <?php echo $js_on_marker_click ?>(markerData);
    if (typeof(placesterListOfMap_markerClick) != 'undefined')
        placesterListOfMap_markerClick(markerData);
}



/**
 * Called when row is selected on map-linked list
 *
 * @param string propertyId
 * @param bool no_notify_list
 */
function placesterMap_markerClickById(propertyId, no_notify_list)
{
    markersData = placesterMap_overlayMap.getMarkersData();
    for (n = 0; n < markersData.length; n++)
    {
        markerData = markersData[n];
        if (markerData.id == propertyId)
        {
            <?php echo $js_on_marker_click ?>(markerData);
            if (!no_notify_list)
                placesterMap_markerClick(markerData);
        }
    }
}



<?php
//
// different default implementations of JS handlers
// 
if ( $default_marker_click ):
    ?>
    var placesterMap_infoWindow = null;

    /**
     * Shows popup when marker is clicked
     *
     * @param array markerData
     */
    function placesterMap_showInfoWindow(markerData)
    {
        if (placesterMap_infoWindow != null)
            placesterMap_infoWindow.close();
        placesterMap_infoWindow = new google.maps.InfoWindow(
            {
                content: markerData.map_details,
                position: new google.maps.LatLng(
                              markerData.location.coords.latitude, 
                              markerData.location.coords.longitude)
            });
        placesterMap_infoWindow.open(placesterMap_map);
    }
    <?php
endif;

if ( $default_get_marker_class ):
    ?>
    /**
     * Returns class of marker
     *
     * @param array markerData
     * @return string
     */
    function placesterMap_getMarkerClass(markerData)
    { return 'marker'; }
    <?php
endif;
?>



/**
 * Called to refresh data in map-linked list with actual visible markers
 *
 * @param array markerData
 */
function placesterMap_reloadMarkers(markersData)
{
    if (typeof(placesterListOfMap_reloadMarkers) != 'undefined')
        placesterListOfMap_reloadMarkers(markersData);
}

function placesterMap_geocode(address) {
    geocoder = new google.maps.Geocoder();
    geocoder.geocode(
        { 
            "address": address
        }, 
        function(results, status) 
        {
            if (status == google.maps.GeocoderStatus.OK) {
                loc = results[0].geometry.location;
                result.lat = loc.lat();
                result.lng = loc.lng();
            }
            else 
                return false;
        });

    return result;
}

function placesterMap_generateMap(center_coord) {
    var latLng = new google.maps.LatLng(center_coord.lat, center_coord.lng);

    placesterMap_map = new google.maps.Map(document.getElementById('placester_listings_map_map'),
        {
            zoom: <?php echo $default_zoom; ?>,
            center: latLng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
    placesterMap_markerFactory = new dynamicMarker.MarkerFactory(
        { 
            getMarkerClassFunctor: <?php echo $js_get_marker_class ?>,
            markerClickFunctor: placesterMap_markerClick,
            markerHoverFunctor: function(markerData) {},
            getWebServiceUrlFunctor: placesterMap_getWebServiceUrl,
            getLoadingHtmlFunctor: placesterMap_getLoadingHtml,
            reloadMarkersFunctor: placesterMap_reloadMarkers,
            markerWidth: <?php echo $marker_width ?>,
            markerHeight: <?php echo $marker_height ?>
        });
    placesterMap_overlayMap = new dynamicMarker.MarkersOverlay(
        {
            map: placesterMap_map, 
            markerFactory: placesterMap_markerFactory
        });
}
/**
 * Initialization of map
 */
$('#placester_listings_map_map').ready(function() {
    var center_coord = new Object;
<?php
if ( isset( $parameters['center'] ) ) {
?>

    geocoder = new google.maps.Geocoder();
    geocoder.geocode( 
        { "address": "<?php echo $parameters['center'] ?>" }, 
        function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                loc = results[0].geometry.location;
                center_coord.lat = loc.lat();
                center_coord.lng = loc.lng();
                placesterMap_generateMap(center_coord);
            }
        });
<?php
} elseif ( isset($parameters['center_latitude']) && isset($parameters['center_latitude']) ) {
?>
        center_coord.lat = <?php echo $parameters['center_latitude'] ?>;
        center_coord.lng = <?php echo $parameters['center_longitude'] ?>;
        placesterMap_generateMap(center_coord);
<?php 
} else {
?>
        center_coord.lat = <?php echo get_option('placester_center_latitude') ?>;
        center_coord.lng = <?php echo get_option('placester_center_longitude') ?>;
        placesterMap_generateMap(center_coord);
<?php 
}
?>
});
</script>
