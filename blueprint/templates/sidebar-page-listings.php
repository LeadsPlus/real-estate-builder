<?php
/**
 * Primary Sidebar Template
 *
 * Displays widgets for the Primary dynamic sidebar if any have been added to the sidebar through the 
 * widgets screen in the admin by the user.  Otherwise, nothing is displayed.
 *
 * @package PlacesterBlueprint
 * @subpackage Template
 */

if ( is_active_sidebar( 'listings-search' ) ) : ?>

    <?php pls_do_atomic( 'before_sidebar_primary' ); ?>

	<aside id="sidebar-primary" class="grid_4 omega sidebar">

        <?php pls_do_atomic( 'open_sidebar_primary' ); ?>

		<?php dynamic_sidebar( 'listings-search' ); ?>

        <?php pls_do_atomic( 'close_sidebar_primary' ); ?>

	</aside><!-- #sidebar-primary .aside -->

    <?php pls_do_atomic( 'after_sidebar_primary' ); ?>

<?php else: ?>

    <?php pls_do_atomic( 'before_sidebar_primary' ); ?>

	<aside id="sidebar-primary" class="grid_4 omega sidebar">

        <?php pls_do_atomic( 'open_sidebar_primary' ); ?>

        <?php PLS_Route::handle_default_sidebar(); ?>

        <?php pls_do_atomic( 'close_sidebar_primary' ); ?>

	</aside><!-- #sidebar-primary .aside -->

    <?php pls_do_atomic( 'after_sidebar_primary' ); ?>
	
<?php endif; ?>
