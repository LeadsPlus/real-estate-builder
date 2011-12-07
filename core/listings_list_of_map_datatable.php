<?php

/**
 * Body of "placester_listings_list_of_map()" function
 * For datatable-based mode
 */

include( 'listings_list_datatable_parts.php' );



$datatable_options = placester_listings_datatable_options( $parameters );

// Fields configuration
$fields_config_array = array();
$n = 0;
foreach ( $ui_fields as $field => $config ) {
    $vars = array();
    if ( isset( $config['js_renderer'] ) )
        array_push( $vars, 
            '"fnRender": function (row_data) ' .
            '{ return ' . $config['js_renderer'] . '(row_data.aData[' . $n . ']); }' );
    if ( isset( $config['width'] ) )
        array_push( $vars, "'sWidth': '" . $config['width']. "'" );

    array_push( $fields_config_array, '{' . implode($vars, ',') . '}' );
    $n++;
}

// Modify fields requested from web service
array_push( $webservice_fields, 'id' );
array_push( $fields_config_array, "{'bVisible': false}" );

if ( $is_list_details )
{
    array_push( $webservice_fields, 'list_details' );
    array_push( $fields_config_array, "{'bVisible': false}" );
}


$fields_config = implode( ',', $fields_config_array );
$datatable_options['aoColumns'] = "[$fields_config]";

// JS fields
$fields = implode( ',', $webservice_fields );
$js_fields = '';
foreach ( $webservice_fields as $field )
    $js_fields .= ( strlen( $js_fields ) > 0 ? ',' : '' ) .
        'markersData[n].' . $field;

//
// Render options
//
$datatable_options_string = '';
foreach ( $datatable_options as $key => $value )
    $datatable_options_string .= 
        ( strlen( $datatable_options_string ) > 0 ? ',' : '' ) . 
        "'$key': $value";

?>
<script src="<?php echo $base_url ?>/js/jquery.dataTables.js"></script>
<script>

var placesterListOfMap_datatable;
var placesterListOfMap_lastMarkersData = null;



/**
 * Returns list of fields must be requested to render list.
 * Called by map to know what to request additionally to what map requires.
 *
 * @return string
 */
function placesterListOfMap_markerDataFields()
{
    return ',<?php echo $fields ?>';
}



var placesterListOfMap_openedId = '';

/**
 * Hanlder for marker click on the map (called by map's click handler).
 *
 * @param array markerData
 */
function placesterListOfMap_markerClick(markerData)
{
    placesterListOfMap_closeCurrent();

    var nodes = placesterListOfMap_datatable.fnGetNodes();
    for (n = 0; n < nodes.length; n++)
    {
        var node = nodes[n];
        var data = placesterListOfMap_datatable.fnGetData(node);
        var data_id = data[data.length - 2];
        if (data_id == markerData.id)
        {
            var list_details = data[data.length - 1];
            placesterListOfMap_datatable.fnOpen(node, list_details);
            placesterListOfMap_openedId = data_id;
        }
    }
}



/**
 * Closes currently expanded row of map-linked list
 */
function placesterListOfMap_closeCurrent()
{
    if (placesterListOfMap_openedId.length <= 0)
        return;

    var nodes = placesterListOfMap_datatable.fnGetNodes();
    for (n = 0; n < nodes.length; n++)
    {
        node = nodes[n];
        data = placesterListOfMap_datatable.fnGetData(node);
        data_id = data[data.length - 2];
        if (data_id == placesterListOfMap_openedId)
            placesterListOfMap_datatable.fnClose(node);
    }

    placesterListOfMap_openedId = '';
}



/**
 * Reloads list according to actual map state (visibility of markers)
 *
 * @param array markersData
 */
function placesterListOfMap_reloadMarkers(markersData)
{
    if (markersData == null)
        markersData = placesterListOfMap_lastMarkersData;
    else
        placesterListOfMap_lastMarkersData = markersData;

    bounds = placesterMap_map.getBounds();

    placesterListOfMap_datatable.fnClearTable(false);

    for(n = 0; n < markersData.length; n++)
    {
        if (markersData[n] == null)
            continue;

        p = new google.maps.LatLng(markersData[n].location.coords.latitude, 
            markersData[n].location.coords.longitude);
        if (bounds.contains(p))
        {
            data = [<?php echo $js_fields ?>];
            pos = placesterListOfMap_datatable.fnAddData(
                data, false);

            data_id = data[data.length - 2];
            if (data_id == placesterListOfMap_openedId)
            {
                placesterListOfMap_datatable.fnOpen(
                    placesterListOfMap_datatable.fnGetNodes(pos),
                    data[data.length - 1]);
            }
        }
    }
    placesterListOfMap_datatable.fnDraw();
}



/**
 * Initializes map-linked datatable list
 */
$(document).ready(function() 
{
    placesterListOfMap_datatable = $('#placester_listings_list_of_map').dataTable(
        {<?php echo $datatable_options_string ?>});

    <?php

    if ( $is_list_details )
        echo placester_listings_list_of_map_js_onclick(
            'placester_listings_list_of_map', 
            'placesterListOfMap_datatable' );

    ?>
});
</script>

<?php 

echo placester_listings_list_datatable_html( 'placester_listings_list_of_map', 
    $ui_fields );
