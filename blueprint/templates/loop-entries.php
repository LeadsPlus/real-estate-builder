<?php
/**
 * Loop Entries Templates
 *
 * Loops over a list entries and displays them. It is include on the archive and blog pages.
 *
 * @package PlacesterBlueprint
 * @subpackage Template
 */
?>
<?php if (have_posts()) : ?>

    <?php while (have_posts()) : the_post(); ?>

        <?php pls_do_atomic( 'before_entry' ); ?>

        <article <?php post_class() ?> id="post-<?php the_ID(); ?>">

            <?php pls_do_atomic( 'open_entry' ); ?>

            <section class="main-post-section" itemscope itemtype="http://schema.org/BlogPosting">

              <header>

                  <h3 itemprop="name"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( 'Permalink to %1$s', the_title_attribute( 'echo=false' ) ) ?>" itemprop="url"><?php the_title(); ?></a></h3>
                  <p class="p5"><time datetime="<?php the_time('Y-m-d')?>" itemprop="datePublished"><?php the_time('F jS, Y') ?></time>
                  <span class="author" itemprop="author"><?php printf( 'by %1$s', get_the_author()) ?></span></p>

              </header>

              <?php pls_do_atomic( 'before_entry_content' ); ?>

              <div class="entry-summary" itemprop="description">
                  <?php the_excerpt(); ?>
              </div><!-- .entry-summary -->

              <div class="entry-meta">
                  <a class="more-link" href="<?php the_permalink() ?>" itemprop="url"> Continue reading <span class="meta-nav">&rarr;</span></a>
              </div><!-- .entry-meta -->

              <?php pls_do_atomic( 'after_entry_content' ); ?>

              <footer>
                  <?php the_tags( 'Tags' . ': ', ', ', '<br />'); ?> 
                  Posted in <?php the_category( ', ' ) ?>
                  | <?php edit_post_link(  'Edit', '', ' | ' ); ?>
                  <?php comments_popup_link( 'No Comments' . '&#187;', '1 Comment' . '&#187;', '% Comments' . '&#187;' ); ?>
              </footer>

            <?php pls_do_atomic( 'close_entry' ); ?>

        </article>

        <?php pls_do_atomic( 'after_entry' ); ?>

    <?php endwhile; ?>

    <nav class="posts">
        <div class="prev"><?php next_posts_link( '&laquo; Older Entries' ) ?></div>
        <div class="next"><?php previous_posts_link( 'Newer Entries &raquo;' ) ?></div>
    </nav>
    
<?php else : ?>
    
    <?php get_template_part( 'loop-error' ); ?>
    
<?php endif; ?>
