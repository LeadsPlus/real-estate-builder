<?php

/**
 * Utilities for all datatable-based property lists
 */

/**
 * Filter called to get HTML of datatable's table.
 *
 * @param array $data - {dom_id, fields, html}
 * @return array filtered data
 */
function placester_listings_datatable_html_default( $data ) {
    $html =
        '<table id="' . $data['dom_id'] . '" style="width: 100%">'.
        '  <thead>'.
        '    <tr>';

    foreach ( $data['fields'] as $field => $description )
    {
        if ( isset( $description['label'] ) )
            $label = $description['label'];
        else
            $label = $field;

        $html .= '<th>' . $label . '</th>';
    }

    $html .= 
        '      <th></th><th></th>'.
        '    </tr>'.
        '  </thead>'.
        '</table>';

    $data['html'] = $html;
    return $data;
}

add_filter( 'placester_listings_datatable_html', 
    'placester_listings_datatable_html_default' );



/**
 * Returns HTML of datatable's table.
 *
 * @param string $dom_id
 * @param array $fields_array
 * @return string
 */
function placester_listings_list_datatable_html( $dom_id, $fields_array ) {
    $a = apply_filters( 'placester_listings_datatable_html', 
        array(
            'dom_id' => $dom_id,
            'fields' => $fields_array
        ) );

    echo $a['html'];
}



/**
 * Builds options for datatable JS object.
 *
 * @param array $parameters
 * @return array
 */
function placester_listings_datatable_options( $parameters ) {
    // Table parameters
    if ( strlen( trim( get_option( 'placester_list_searchable' ) ) ) > 0 )
        $is_filter = 'true';
    else
        $is_filter = 'false';

    $datatable_options = array( 'bFilter' => $is_filter );

    if ( ! isset( $parameters['paginate'] ) )
        $parameters['paginate'] = '10';
    if ( $parameters['paginate'] > 0 )
    {
        $datatable_options['bPaginate'] = 'true';
        $datatable_options['iDisplayLength'] = $parameters['paginate'];
    }

    $datatable_options = apply_filters( 'placester_listings_datatable_options', 
        $datatable_options );

    return $datatable_options;
}



/**
 * Filter called to builds options for datatable JS object.
 *
 * @param array $datatable_options
 * @return array
 */
function placester_listings_datatable_options_default( $datatable_options ) {
    $datatable_options['bLengthChange'] = 'false';
    $datatable_options['bSort'] = 'true';
    $datatable_options['bInfo'] = 'true';
    $datatable_options['bAutoWidth'] = 'false';

    return $datatable_options;
}

add_filter( 'placester_listings_datatable_options', 
    'placester_listings_datatable_options_default' );



/**
 * Prints JS function showing details of the datatable row
 *
 * @param string $dom_id
 * @param string $js_varname
 */
function placester_listings_list_lone_js_onclick( $dom_id, $js_varname ) {
    ?>
    /**
     * Datatable's Click handler showing details of the standalone datatable row
     */
    $('#<?php echo $dom_id ?> tbody tr td').live('click', function()
    {
        var tr = this.parentNode;
        if ($(tr).attr('opened') == 'true')
        {
            <?php echo $js_varname ?>.fnClose(tr);
            $(tr).attr('opened', '');
        }
        else
        {
            a = <?php echo $js_varname ?>.fnGetData(tr);
            list_details = a[a.length - 1];
            <?php echo $js_varname ?>.fnOpen(tr, list_details);
            $(tr).attr('opened', 'true');
        }
    });
    <?php
}



/**
 * Prints JS function responds to click on datatable's list linked to map
 *
 * @param string $dom_id
 * @param string $js_varname
 */
function placester_listings_list_of_map_js_onclick( $dom_id, $js_varname ) {
    ?>
    /**
     * Datatable's Click handler showing details of the map-linked datatable row
     */
    $('#<?php echo $dom_id ?> tbody tr td').live('click', function()
    {
        var tr = this.parentNode;
        var data = <?php echo $js_varname ?>.fnGetData(tr);

        var data_id = data[data.length - 2];

        if (data_id == placesterListOfMap_openedId)
            placesterListOfMap_closeCurrent();
        else
        {
            placesterListOfMap_closeCurrent();

            var list_details = data[data.length - 1];
            <?php echo $js_varname ?>.fnOpen(tr, list_details);
            placesterListOfMap_openedId = data_id;
            
            if (typeof(placesterMap_markerClickById) != 'undefined')
                placesterMap_markerClickById(data_id, true);
        }
    });
    <?php
}
