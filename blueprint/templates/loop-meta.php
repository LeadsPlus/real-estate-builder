<?php
/**
 * Loop Meta Template
 *
 * Displays information at the top of the page about archive and search results when viewing those pages.  
 * This is not shown on the front page or singular views.
 *
 * @package PlacesterBlueprint
 * @subpackage Template
 */
?>
	<?php if ( is_home() && ! is_front_page() ) : ?>
		<div class="loop-meta">

			<h1 class="loop-title"><?php echo get_post_field( 'post_title', get_queried_object_id() ); ?></h1>

			<div class="loop-description">
				<?php echo apply_filters( 'the_excerpt', get_post_field( 'post_excerpt', get_queried_object_id() ) ); ?>
			</div><!-- .loop-description -->

		</div><!-- .loop-meta -->

	<?php elseif ( is_category() ) : ?>

        <h2><?php single_cat_title(); ?></h2>

	<?php elseif ( is_tag() ) : ?>

        <h2><?php single_tag_title(); ?></h2>

	<?php elseif ( is_tax() ) : ?>

        

	<?php elseif ( is_author() ) : ?>

		<?php $user_id = get_query_var( 'author' ); ?>

        <h2><?php single_term_title( the_author_meta( 'display_name', $user_id ) ); ?></h2>

	<?php elseif ( is_search() ) : ?>

        <h2><?php printf( 'Search results for "%s"', esc_attr( get_search_query() ) ); ?></h2>

	<?php elseif ( is_date() ) : ?>

        <h2><?php 'Blog archives by date'; ?></h2>

	<?php elseif ( function_exists( 'is_post_type_archive' ) && is_post_type_archive() ) : ?>

        <h2><?php post_type_archive_title(); ?></h2>

	<?php elseif ( is_archive() ) : ?>

        <h2><?php 'Archives'; ?></h2>

	<?php elseif ( is_single() ) : ?>

        <h2><?php the_title(); ?></h2>

	<?php elseif ( is_page() ) : ?>

        <h2><?php the_title(); ?></h2>

	<?php endif; ?>

