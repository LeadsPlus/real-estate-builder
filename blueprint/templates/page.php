<?php
/**
 * Page Template
 *
 * This is the default page template. It is used when a more specific template can't be found to display 
 * singular views of pages.
 *
 * @package PlacesterBlueprint
 * @subpackage Template
 */
?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

    <?php pls_do_atomic( 'before_entry' ); ?>

    <article <?php post_class() ?> id="post-<?php the_ID(); ?>">

        <?php pls_do_atomic( 'open_entry' ); ?>

        <header>
            <?php PLS_Route::get_template_part( 'loop-meta' ) ?>
        </header>

        <?php pls_do_atomic( 'before_entry_content' ); ?>

        <?php the_content( 'Read the rest of this entry &raquo;' ); ?>

        <div class="entry-meta">
                    <?php wp_link_pages( array( 'before' => '<p><strong>' . 'Pages' . ':</strong> ', 'after' => '</p>', 'next_or_number' => 'number' ) ); ?>
                    <?php the_tags( '<p class="blog-tags blog-meta"><span>' . 'Tags' . ':</span> ', ', ', '</p>'); ?>
        </div>

        <?php pls_do_atomic( 'after_entry_content' ); ?>

        <footer>

            <p class="blog-meta"> 

                <?php if ( comments_open() ) {
                    'You can <a href="#respond">skip to the end</a> and leave a response.';

                } else {
                    'Comments are currently closed.';

                } edit_post_link( 'Edit this entry', ' ', '.' ); ?>
            </p>

        </footer>

        <?php comments_template( '/comments.php', true ); ?>
        
        <?php pls_do_atomic( 'close_entry' ); ?>

    </article>

    <?php pls_do_atomic( 'after_entry' ) ?>
        
<?php endwhile; else: ?>
    
    <?php PLS_Route::get_template_part( 'loop-error' ); ?>
    
<?php endif; ?>
