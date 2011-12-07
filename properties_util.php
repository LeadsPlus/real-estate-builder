<?php

/**
 * Common code by all processors of AJAX get properties requests
 */
$list_details_template = '';
$map_details_template = '';
$placester_post_slug = '';
$new_ids = array();
$featured_ids = array();

/**
 * Initializes internal variables for future use by other functions in this file
 */
function init_templates() {
    global $list_details_template;
    global $map_details_template;
    global $placester_post_slug;
    global $new_ids;
    global $featured_ids;

    $list_details_template = get_option( 'placester_list_details_template' );
    $map_details_template = get_option( 'placester_map_info_template' );
    $placester_post_slug = placester_post_slug();
    $new_ids = get_option( 'placester_properties_new_ids' );
    if ( ! is_array( $new_ids ) )
        $new_ids = array();
        
    $featured_ids = get_option( 'placester_properties_featured_ids' );
    if ( ! is_array( $featured_ids ) )
        $featured_ids = array();
}



/**
 * Converts one property object to array of values returned by AJAX request
 *
 * @param object $row
 * @param array $fields
 * @param bool $is_simple_array
 * @return array
 */
function convert_row( $row, $fields, $is_simple_array ) {
    global $list_details_template;
    global $map_details_template;
    global $new_ids;
    global $featured_ids;

    $item = array();
    foreach ( $fields as $field ) {
        $v = 'n/a';
        if ( $field == 'available_on' ||
                $field == 'bathrooms' ||
                $field == 'bedrooms' ||
                $field == 'amenities' ||
                $field == 'description' ||
                $field == 'half_baths' ||
                $field == 'id' ||
                $field == 'price' ) {
            $v = ! empty( $row->$field ) ? $row->$field : '';
        } else if ( $field == 'contact.email' ||
                $field == 'contact.phone' ) {
            $subfield = substr( $field, 8 );
            $v = $row->contact->$subfield;
        } else if ( $field == 'location.address' ||
                $field == 'location.city' ||
                $field == 'location.state' ||
                $field == 'location.unit' ||
                $field == 'location.zip' ) {
            $subfield = substr( $field, 9 );
            $v = $row->location->$subfield;
        } else if ( $field == 'location.coords.latitude' ||
                $field == 'location.coords.longitude' ) {
            $subfield = substr( $field, 16 );
            $v = $row->location->coords->$subfield;
        } else if ( $field == 'has_images' ) {
            $v = ( count( $row->images ) > 0 ? 'true' : 'false' );
        } else if ( $field == 'url' ) {
            $v = placester_get_property_url( $row->id );
        } else if ( $field == 'images' ) {
            $v = array();
            $images = $row->images;
            if ( count($images) > 0 )
                foreach ( $images as $image_item )
                    array_push( $v, $image_item->url );
        } else if ( $field == 'list_details' )
            $v = placester_expand_template( $list_details_template, $row );
        else if ( $field == 'map_details' )
            $v = placester_expand_template( $map_details_template, $row );
        else if ( $field == 'is_new' )
            $v = in_array( $row->id, $new_ids );
        else if ( $field == 'is_featured' )
            $v = in_array( $row->id, $featured_ids );
        else if ( $field == 'featured_image' ) {
            $images = $row->images;
            if( count($images) > 0 )
                $v = $images[0]->url;
        }

        if ( $is_simple_array )
            $item[] = $v;
        else {
            $field_parts = explode( '.', $field );
            $item_for_value = &$item;
            for ( $n = 0; $n < count( $field_parts ) - 1; $n++ ) {
                $field_part = $field_parts[$n];
                if ( ! isset( $item_for_value[$field_part] ) )
                    $item_for_value[$field_part] = array();
                $item_for_value = &$item_for_value[$field_part];
            }

            $field_part = $field_parts[count( $field_parts ) - 1];
            $item_for_value[$field_part] = $v;
        }
    }

    return $item;
}
