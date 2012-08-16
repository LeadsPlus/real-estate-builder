<?php
/**
 * Template Name: Client Profile
 *
 * This is the template for the loggedin area of clients
 *
 * @package PlacesterBlueprint
 * @subpackage Template
 */
?>
<style type="text/css">
	.dataTables_length {
		display: none;
	}

</style>
<?php if (is_user_logged_in()): ?>
<h1>Your Favorite Listings</h1>		
<div class="grid_8 alpha" id="content" role="main">
    <?php echo PLS_Partials::get_listings_list_ajax('context=listings_search&table_id=placester_fav_list&show_sort=1'); ?>
</div>
<?php else: ?>
<h1>You need to Login or Sign Up</h1>
<?php endif ?>
