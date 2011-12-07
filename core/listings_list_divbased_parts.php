<?php

/**
 * Utilities for all div-based property lists
 */

/**
 * Filter called to get HTML of divbased list.
 *
 * @param array $data - {dom_id, html}
 * @return array filtered data
 */
function placester_listings_divbased_html_default( $data ) {
    $html = '<div id="' . $data['dom_id'] . '"></div>';

    $data['html'] = $html;
    return $data;
}

add_filter( 'placester_listings_divbased_html', 
    'placester_listings_divbased_html_default' ); 



/**
 * Returns HTML of divbased list.
 *
 * @param string $dom_id
 * @param array $fields_array
 * @return string
 */
function placester_listings_list_divbased_html( $dom_id, $fields_array ) {
    $a = apply_filters( 'placester_listings_divbased_html', 
        array(
            'dom_id' => $dom_id,
            'fields' => $fields_array
        ) );

    return $a['html'];
}



/**
 * Parses parameters gived to listings_* function and returns essential 
 * data for rendering
 *
 * @param array $parameters
 * @return array
 */
function placester_list_divbased_parse( $parameters ) {
    // Row renderer
    $default_row_renderer = ! isset( $parameters['js_row_renderer'] );
    if ( $default_row_renderer )
        $row_renderer = 'placesterListLone_createRowHtml';
    else
        $row_renderer = $parameters['js_row_renderer'];

    // Pager
    $pager_data = array();
    if ( isset( $parameters['pager'] ) )
        $pager_data = $parameters['pager'];

    $default_pager_renderer = ! isset( $pager_data['js_renderer'] );
    if ( $default_pager_renderer )
        $pager_renderer = 'placesterListLone_pagerHtml';
    else
        $pager_renderer = $pager_data['js_renderer'];

    $pager_rows_per_page = 20;
    if ( isset( $pager_data['rows_per_page'] ) )
        $pager_rows_per_page = $pager_data['rows_per_page'];

    $default_pager_render_in_dom_element = 
        ! isset( $pager_data['render_in_dom_element'] );
    if ( $default_pager_render_in_dom_element )
        $pager_render_in_dom_element = 'placester_listings_pager';
    else
        $pager_render_in_dom_element = $pager_data['render_in_dom_element'];


    return array(
        $default_row_renderer,
        $row_renderer,
        $default_pager_renderer,
        $pager_data,
        $pager_renderer,
        $pager_rows_per_page,
        $default_pager_render_in_dom_element,
        $pager_render_in_dom_element);
}



/**
 * Prints JS function - default row renderer
 * It actually only complains that JS renderer must be overriden by real call
 */
function placester_print_default_row_renderer() {
    ?>
    /**
     * Default row renderer
     */
    function placesterListLone_createRowHtml(row)
    {
        return '<div>row_renderer option should be defined</div>';
    }
    <?php
}



/**
 * Returns pager renderer - specified by configuration data or default value
 *
 * @param array $data
 * @param string $item_name
 * @param string $default_value
 * @return string
 */
function placester_print_default_pager_renderer_default( $data, $item_name, 
        $default_value ) {
    if ( isset( $data[$item_name] ) )
        return $data[$item_name];

    return $default_value;
}



/**
 * Returns pager renderer button configuration - specified by 
 * configuration data or default value
 *
 * @param array $data
 * @param string $item_name
 * @param string $default_array
 * @return array
 */
function placester_print_default_pager_renderer_button( $data, $item_name, 
        $defaults_array ) {
    $is_page = isset( $data[$item_name] );
    $ret_array = array();
    if ( $is_page ) { 
        $o = $data[$item_name];
        if ( isset( $o['visible'] ) && !$o['visible'] )
            $is_page = false;
        foreach ( $defaults_array as $k => $v ) {
            if ( isset( $o[$k] ) )
                $ret_array[$k] = $o[$k];
            else
                $ret_array[$k] = $v;
        }
    }

    $ret_array['visible'] = $is_page;
    return $ret_array;
}



/**
 * Prints default pager renderer for divbased list
 *
 * @param array $data
 */
function placester_print_default_pager_renderer( $data ) {
    $css_current_button = placester_print_default_pager_renderer_default( $data, 
        'css_current_button', 'current' );
    $css_not_current_button = placester_print_default_pager_renderer_default( 
        $data, 'css_not_current_button', '' );

    $first_page = placester_print_default_pager_renderer_button( $data, 
        'first_page', array( 'label' => 'First Page' ) );
    $prev_page = placester_print_default_pager_renderer_button( $data, 
        'previous_page', array( 'label' => 'Previous Page' ) );
    $next_page = placester_print_default_pager_renderer_button( $data, 
        'next_page', array( 'label' => 'Next Page' ) );
    $last_page = placester_print_default_pager_renderer_button( $data, 
        'last_page', array( 'label' => 'Last Page' ) );
    $numeric_links = placester_print_default_pager_renderer_button( $data, 
        'numeric_links', 
        array(
            'max_count' => 20, 
            'more_label' => '...',
            'css_outer' => ''
        ) );

    ?>
    /**
     * Returns HTML of "page link" element
     *
     * @param string label
     * @param int page_number
     * @param int current_page_number
     * @param string on_click_function_name
     * @return string
     */
    function placesterListLone_pagerHtml_item(label, page_number, 
        current_page_number, on_click_function_name)
    {
        var o;
        if (page_number == current_page_number)
            o = '<a class="'+label+'_button <?php echo $css_current_button ?>" >' + label + '</a>';
        else
            o = '<a href="javascript:' + on_click_function_name +'(' + 
                page_number + ')" class="'+label+'_button <?php echo $css_not_current_button ?>" ' +
                '>' + label + '</a>';
        return o;
    }



    /**
     * Returns HTML of pager
     *
     * @param int pages_count
     * @param int current_page_number
     * @param string on_click_function_name
     * @return string
     */
    function placesterListLone_pagerHtml(pages_count, 
        current_page_number, on_click_function_name)
    {
        if (pages_count <= 1)
            return '';

        var output = '';

        <?php
        // Render "First Page"
        if ( $first_page['visible'] ) {
            ?>
            output += placesterListLone_pagerHtml_item(
                '<?php echo $first_page['label'] ?>', 1, 
                current_page_number, on_click_function_name);
            <?php
        }

        // Render "Previous Page"
        if ( $prev_page['visible'] ) {
            ?>
            prev_page_number = current_page_number - 1;
            if (prev_page_number < 1)
                prev_page_number = current_page_number;
            output += placesterListLone_pagerHtml_item(
                '<?php echo $prev_page['label'] ?>', prev_page_number, 
                current_page_number, on_click_function_name);
            <?php
        }

        // Render numberic links
        if ( $numeric_links['visible'] ) {
            if ( strlen( $numeric_links['css_outer'] ) > 0 )
                echo 'output += \'<div class="' . $numeric_links['css_outer'] . '">\';';

            ?>
            var min_number = 1;
            var max_number = pages_count;

            var max_items_count = <?php echo $numeric_links['max_count'] ?>;
            if (pages_count > max_items_count)
            {
                min_number = current_page_number - Math.round(max_items_count / 2);
                if (min_number < 1)
                    min_number = 1;
                max_number = min_number + max_items_count;
                if (max_number > pages_count)
                    max_number = pages_count;
            }

            if (min_number > 1)
                output += '<?php echo $numeric_links['more_label'] ?>';

            for (var n = min_number; n <= max_number; n++)
            {
                output += placesterListLone_pagerHtml_item(n, n, 
                    current_page_number, on_click_function_name);
            }

            if (max_number < pages_count)
                output += '<?php echo $numeric_links['more_label'] ?>';

            <?php

            if ( strlen( $numeric_links['css_outer'] ) > 0 )
                echo 'output += \'</div>\';';
        }

        // Render "Next Page"
        if ( $next_page['visible'] ) {
            ?>
            next_page_number = current_page_number + 1;
            if (next_page_number > pages_count)
                next_page_number = current_page_number;
            output += placesterListLone_pagerHtml_item(
                '<?php echo $next_page['label'] ?>', next_page_number, 
                current_page_number, on_click_function_name);
            <?php
        }

        // Render "Last Page"
        if ( $last_page['visible'] ) {
            ?>
            output += placesterListLone_pagerHtml_item(
                '<?php echo $last_page['label'] ?>', pages_count, 
                current_page_number, on_click_function_name);
            <?php
        }
        ?>
        return output;
    }
    <?php
}



/**
 * Prints default "loading" element for divbased list
 */
function placester_print_default_loading_element( $args ) {

    $defaults = array( 
        'echo' => true
    );

    extract( wp_parse_args( $args, $defaults ), EXTR_SKIP );

    $loading_id = get_option( 'placester_map_tile_loading_image' );
    $loading = wp_get_attachment_image_src( $loading_id, 'full' );

    $loading_image_url = $loading[0];
    $return = '<div id="placester_listings_loading" style="display: none"><img src="' . $loading_image_url . '" alt="" /></div>';

    if ( $echo ) 
        echo $return;
    else 
        return $return;
}
