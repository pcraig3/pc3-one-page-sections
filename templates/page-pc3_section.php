<?php
/**
 * Inspired by the WordPress 2015/2013 "page.php" templates.
 * Idea is just to loop through sections and display them.
 *
 */

get_header(); ?>

<div id="primary" class="content-area">
    <div id="content" class="site-content" role="main">

        <?php
        // Start the loop.
        while ( have_posts() ) : the_post();
        ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

            <header class="entry-header">
                <h1>Yes!  Alternate template loaded for page <?php the_title(); ?> with an id of <?php the_ID(); ?>.</h1>
            </header><!-- .entry-header -->

            <div class="entry-content">
                <?php the_content(); ?>
            </div>
        </article>

            <?php

            // End the loop.
        endwhile;
        ?>

    </div><!-- .site-main -->
</div><!-- .content-area -->

<?php get_footer(); ?>
