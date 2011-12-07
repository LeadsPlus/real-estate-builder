<?php

/**
 * Body of "placester_listings_list()" function
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

if ( $is_list_details ) {
    array_push( $webservice_fields, 'list_details' );
    array_push( $fields_config_array, '{"bVisible": false}' );
}

// Additional datatable parameters
$fields = implode( ',', $webservice_fields );
$datasource_url = $base_url . '/properties_datatable.php?fields=' . $fields;

// Additional datatable parameters
$datatable_options['bProcessing'] = 'true';
$datatable_options['bServerSide'] = 'true';
$datatable_options['bDestroy'] = 'true';
$datatable_options['sAjaxSource'] = 'placesterListLone_datasource_url';

$fields_config = implode( ',', $fields_config_array );
$datatable_options['aoColumns'] = "[$fields_config]";

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

var placesterListLone_datatable;
var placesterListLone_datasource_base_url = '<?php echo $datasource_url ?>';
var placesterListLone_datasource_url = placesterListLone_datasource_base_url;



/**
 * Called when user selected new filter conditions.
 * New filtered data are requested and list is refreshed.
 *
 * @param string filter_query
 */
function placesterListLone_setFilter(filter_query)
{
    placesterListLone_datasource_url = placesterListLone_datasource_base_url + filter_query;

    if (placesterListLone_datatable != null)
        placesterListLone_create();
}



/**
 * Creates or recreates datatable object
 */
function placesterListLone_create()
{
    if (placesterListLone_datatable != null)
        placesterListLone_datatable.fnClearTable(false);

    placesterListLone_datatable = $('#placester_listings_list').dataTable(
        {<?php echo $datatable_options_string ?>});
}



/**
 * Initializes list
 */
$(document).ready(function() 
{
    placesterListLone_create();
    <?php

    if ($is_list_details)
        echo placester_listings_list_lone_js_onclick(
            'placester_listings_list', 
            'placesterListLone_datatable' );

    ?>
});
</script>

<?php 
echo placester_listings_list_datatable_html( 'placester_listings_list', 
    $ui_fields ); 
