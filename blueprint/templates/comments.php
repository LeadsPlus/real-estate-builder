<?php
/**
 * Comments Template
 *
 * Lists comments and calls the comment form. Individual comments have their own templates. The 
 * hierarchy for these templates is $comment_type.php, comment.php.
 *
 * @package PlacesterBlueprint
 * @subpackage Template
 */

/* Kill the page if trying to access this template directly. */
if ( 'comments.php' == basename( $_SERVER['SCRIPT_FILENAME'] ) )
	die( 'Please do not load this page directly. Thanks!' );

/* If a post password is required or no comments are given and comments/pings are closed, return. */
if ( post_password_required() || ( ! have_comments() && ! comments_open() && ! pings_open() ) )
	return;
?>

<div id="comments-template">

	<div class="comments-wrap">

		<section id="comments">

			<?php if ( have_comments() ) : ?>

				<h3 id="comments-number" class="comments-header"><?php comments_number( 'No Responses', 'One Response', '% Responses' ); ?></h3>
				
				<?php pls_do_atomic( 'before_comment_list' ); ?>

				<?php if ( get_option( 'page_comments' ) ) : ?>

					<div class="comments-nav">

						<span class="page-numbers"><?php printf( 'Page %1$s of %2$s', ( get_query_var( 'cpage' ) ? absint( get_query_var( 'cpage' ) ) : 1 ), get_comment_pages_count() ); ?></span>

						<?php previous_comments_link(); next_comments_link(); ?>

					</div><!-- .comments-nav -->

				<?php endif; ?>

				<ol class="comment-list">
					<?php wp_list_comments( array( 'style' => 'ol', 'type' => 'all', 'avatar_size' => 80 ) ); ?>
				</ol><!-- .comment-list -->
				
				<?php pls_do_atomic( 'before_comment_list' ); ?>

			<?php endif; ?>

			<?php if ( pings_open() && !comments_open() ) : ?>

				<p class="comments-closed pings-open">
					<?php printf( 'Comments are closed, but <a href="%1$s" title="Trackback URL for this post">trackbacks</a> and pingbacks are open.', get_trackback_url() ); ?>
				</p><!-- .comments-closed .pings-open -->

			<?php elseif ( !comments_open() ) : ?>

				<p class="comments-closed">
					<?php 'Comments are closed.'; ?>
				</p><!-- .comments-closed -->

			<?php endif; ?>

		</section><!-- #comments -->

		<?php comment_form(); // Loads the comment form. ?>

	</div><!-- .comments-wrap -->

</div><!-- #comments-template -->
