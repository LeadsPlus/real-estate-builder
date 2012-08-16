<?php 
global $query_string;
$args = wp_parse_args($query_string, array('mlsid' => false));
// $taxonomy = PLS_Taxonomy::get($args);
//pls_dump($args);
$args = array(
	  'mlsid' => $args['mlsid'],
	  'post_type' => 'property',
	  'post_status' => 'publish'
	);
$my_query = new WP_Query($args);


//pls_dump($my_query);
?>
<?php PLS_Route::get_template_part( 'loop', 'meta' ); // Loads the loop-meta.php template. ?>

<?php PLS_Route::get_template_part( 'single', 'property' ); ?>

