<?php
/**
 * Archive Template
 *
 * The archive template is the default template used for archives pages without a more specific template. 
 *
 * @package PlacesterBlueprint
 * @subpackage Template
 */
?>
<?php PLS_Route::get_template_part( 'loop', 'meta' ); // Loads the loop-meta.php template. ?>

<?php PLS_Route::get_template_part( 'loop', 'entries' ); ?>
