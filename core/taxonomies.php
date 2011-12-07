<?php
/**
 * Create Custom Taxonomies
 */
    
function placester_taxonomies() {
 
    $placester_taxonomies = array(
        array(
            'tax'       => 'bedrooms',
            'terms'     => array( '1' , '2' , '3' , '4', '5', '6', '7', '8', '9', '10' ),
            'term_desc' => 'Number of bedrooms',
            'name'      => 'Bedrooms',
        ),
        array(
            'tax'       => 'bathrooms',
            'terms'     => array( '1' , '2' , '3' , '4', '5', '6', '7', '8', '9', '10' ),
            'term_desc' => 'Number of bathrooms',
            'name'      => 'Bathrooms',
        ),
        array(
            'tax'       => 'rent',
            'terms'     => array(
                            '0-500', 
                            '501-1000',
                            '1001-1500',
                            '1501-2000',
                            '2001-2500',
                            '2500+',
                            ),
            'term_desc' => 'Rent amount',
            'name'      => 'Rent',
        ),
        array(
            'tax'       => 'city',
            'parent'    => FALSE,
            'name'      => 'City',
        ),
        array(
            'tax'       => 'state',
            'parent'    => FALSE,
            'name'      => 'State',
        ),
        array(
            'tax'       => 'zip',
            'parent'    => FALSE,
            'name'      => 'Zip Code',
        ),
        array(
            'tax'       => 'type',
            'terms'     => array( 'featured', 'new', 'rental', 'sale', 'commercial', 'residential' ),
            'name'      => 'Types',
            'term_desc' => 'Miscellaneous property type',
            'parent'    => FALSE,
        ),
    );
  
    foreach ( $placester_taxonomies as $taxonomy ) {
  
        $terms = '';
        $parent = TRUE;
  
        extract ( $taxonomy );
  
        $singular_name = rtrim( $name, 's');
  
        $labels = array(
            'name'          => _x( $name, 'taxonomy general name' ),
            'singular_name' => _x( $singular_name, 'taxonomy singular name' ),
            'search_items'  =>  __( 'Search ' . $name ),
            'popular_items' => __( 'Popular ' . $name ),
            'all_items'     => __( $name ),
            'edit_item'     => __( 'Edit ' . $name ), 
            'update_item'   => __( 'Update ' . $name ),
            'add_new_item'  => __( 'Add ' . $singular_name ),
            'new_item_name' => __( 'New ' . $singular_name ),
            'menu_name'     => __( $name ),
         );
  
        register_taxonomy($tax, 'property',
        array(
            'labels'        => $labels,
            'label'         => $tax,
            'query_var'     => TRUE,
            'rewrite'       => TRUE,
            'show_ui'       => TRUE,
            'hierarchical'  => $parent,
            'rewrite'       => FALSE,
  
        ) );
  
        if ( is_array( $terms ) ) {
              
            foreach ($terms as $term) {
  
                wp_insert_term( 
                    $term ,
                    $tax, 
                    array('description' => $term_desc )
                );  
            }
        }
    }
  
}
 
add_action( 'init', 'placester_taxonomies' );