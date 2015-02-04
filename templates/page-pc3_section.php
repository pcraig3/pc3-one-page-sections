<?php
/**
 * The template for the page on which all of the pages are displayed.
 * Based on the WordPress 2015/2013 "page.php" templates.
 * Basically we want very little other than a container to hold a series of otehr containers.
 * Idea is just to loop through sections and display them.
 *
 * @since      0.5.0
 *
 * @package    One_Page_Sections
 * @subpackage One_Page_Sections/templates
 */

get_header(); ?>

<div id="primary" class="content-area">
    <div id="content" class="site-content" role="main">

        <?php
            // Start the loop.
        while ( have_posts() ) : the_post();
        ?>

        <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

            <div class="entry-content" id="back_to_top">
                <?php

                $_aArgs         = array(
                    'post_type' => 'pc3_section',
                    'orderby'   => 'meta_value_num',
                    'meta_key'  => 'order',
                    'order'     => 'ASC',
                    'post_status' => 'any',
                    'posts_per_page' => -1
                );
                $_oResults      = new WP_Query( $_aArgs );

                $myposts = $_oResults->posts;

                foreach ( $myposts as $post ) : setup_postdata( $post );

                    do_shortcode('[pc3_locate_template]');

                endforeach;
                wp_reset_postdata();?>


            </div>
        </div>

            <?php

            // End the loop.
        endwhile;
        ?>

    </div><!-- .site-main -->
</div><!-- .content-area -->

<?php get_footer(); ?>
