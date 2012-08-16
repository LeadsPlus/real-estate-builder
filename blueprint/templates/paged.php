<?php
/**
 *
 * This is the template for the blog page
 *
 * @package PlacesterBlueprint
 * @subpackage Template
 */
?>

<?php PLS_Route::get_template_part('loop-meta'); // Loads the loop-meta.php template. ?>

<?php $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; ?>

<?php query_posts( 'post_type=post&paged=' . $paged ); // Get the blog posts ?>

<?php PLS_Route::get_template_part( 'loop-entries' ) ?>
