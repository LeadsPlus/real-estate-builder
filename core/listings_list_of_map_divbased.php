<?php

/**
 * Body of "placester_listings_list_of_map()" function
 * For div-based mode
 */

include( 'listings_list_divbased_parts.php' );



// js fields
$fields = implode( ',', $webservice_fields );

list(
    $default_row_renderer,
    $row_renderer,
    $default_pager_renderer,
    $pager_data,
    $pager_renderer,
    $pager_rows_per_page,
    $default_pager_render_in_dom_element,
    $pager_render_in_dom_element
) = placester_list_divbased_parse( $parameters );

?>
<script src="<?php echo $base_url ?>/js/jquery.dataTables.js"></script>
<script>

var placester_datatableOfMap;
var placesterListOfMap_lastMarkersData = null;
var placesterListOfMap_page_number = 1;
var placesterListOfMap_pager_rows_per_page = <?php echo $pager_rows_per_page ?>;
var placesterListOfMap_items = [];



<?php
if ( $default_row_renderer )
    placester_print_default_row_renderer();
if ( $default_pager_renderer )
    placester_print_default_pager_renderer( $pager_data );
?>



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

    output = '';

    placesterListOfMap_items = [];
    for(var n = 0; n < markersData.length; n++)
    {
        if (markersData[n] == null)
            continue;

        p = new google.maps.LatLng(markersData[n].location.coords.latitude, 
            markersData[n].location.coords.longitude);
        if (bounds.contains(p))
        {
            placesterListOfMap_items[placesterListOfMap_items.length] = markersData[n];
        }
    }

    if (placesterListOfMap_openedId.length > 0)
        for(var n = 0; n < placesterListOfMap_items.length; n++)
        {
            if (placesterListOfMap_items[n].id == placesterListOfMap_openedId)
            {
                placesterListOfMap_page_number = Math.floor(
                    (n + placesterListOfMap_pager_rows_per_page - 1) / 
                    placesterListOfMap_pager_rows_per_page);
                break;
            }
        }

    placesterListOfMap_printCurrentPage();
}



/**
 * Switches page of list
 *
 * @param int n
 */
function placesterListOfMap_setPage(n)
{
    placesterListOfMap_page_number = n;
    placesterListOfMap_printCurrentPage();
}



var placesterListOfMap_openedId = '';

/**
 * Hanlder for marker click on the map (called by map's click handler).
 *
 * @param array markerData
 */
function placesterListOfMap_markerClick(markerData)
{
    placesterListOfMap_openedId = markerData.id;

    for(var n = 0; n < placesterListOfMap_items.length; n++)
    {
        if (placesterListOfMap_items[n].id == placesterListOfMap_openedId)
        {
            placesterListOfMap_page_number = Math.floor(
                (n + placesterListOfMap_pager_rows_per_page - 1) / 
                placesterListOfMap_pager_rows_per_page);
            break;
        }
    }

    placesterListOfMap_printCurrentPage();
}



/**
 * Closes currently expanded row of map-linked list
 */
function placesterListOfMap_closeCurrent()
{
    if (placesterListOfMap_openedId.length <= 0)
        return;

    placesterListOfMap_openedId = '';
    placesterListOfMap_printCurrentPage();
}



/**
 * Renders list
 */
function placesterListOfMap_printCurrentPage()
{
    pages = Math.floor(
        (placesterListOfMap_items.length + placesterListOfMap_pager_rows_per_page - 1) / 
        placesterListOfMap_pager_rows_per_page);
    if (placesterListOfMap_page_number > pages && pages > 0)
        placesterListOfMap_page_number = pages;

    output = '';
    pos = (placesterListOfMap_page_number - 1) * placesterListOfMap_pager_rows_per_page;
    for(var n = 0; n < placesterListOfMap_pager_rows_per_page && 
        pos < placesterListOfMap_items.length; n++, pos++)
    {
        is_highlighted = (placesterListOfMap_items[pos].id == placesterListOfMap_openedId);

        row_html = <?php echo $row_renderer ?>(placesterListOfMap_items[pos],
            is_highlighted);
        output += row_html;
    }

    $('#placester_listings_list_of_map').html(output);

    pager_output = '';
    var html = <?php echo $pager_renderer ?>(pages, placesterListOfMap_page_number,
        'placesterListOfMap_setPage');

    $('#<?php echo $pager_render_in_dom_element ?>').html(html);
}

</script>

<?php 

if ( $default_pager_render_in_dom_element )
    echo '<div id="placester_listings_pager"></div>';

echo placester_listings_list_divbased_html( 'placester_listings_list_of_map', 
    $webservice_fields );
