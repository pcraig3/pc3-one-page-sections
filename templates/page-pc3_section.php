<?php
/**
 * The template for the page on which all of the sections are displayed.
 * Based on the WordPress 2015/2013 "page.php" templates.
 * Basically we want very little other than a container to hold a series of other containers.
 * Idea is just to loop through sections and display them.
 *
 * @since      0.9.0
 *
 * @package    One_Page_Sections
 * @subpackage One_Page_Sections/templates
 */

get_header();

global $wp_query;
?>

<div id="primary" class="content-area">
    <div id="content" class="site-content" role="main">

        <?php
            // Start the loop.
        while ( have_posts() ) : the_post();
        ?>

        <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <div class="entry-content <?php echo do_shortcode('[pc3_get_parameter]'); ?>__wrapper" id="back_to_top">
                <?php

                $pc3_section = do_shortcode('[pc3_get_parameter]');

                //a `pre_get_posts` filter adds our sections to the main wp_query->{section__slug} if this page is queried
                if( ! is_null( $wp_query->$pc3_section ) ) {
                    $aPosts = $wp_query->$pc3_section;

                    foreach ($aPosts as $post) : setup_postdata($post);

                        do_shortcode('[pc3_locate_section_template]');

                    endforeach;
                }
                else
                    echo '<p>Sorry, no sections were found.</p>';

                ?>


            </div>
        </div>

            <?php

            // End the loop.
        endwhile;
        ?>

    </div><!-- .site-main -->
</div><!-- .content-area -->

<?php get_footer(); ?>
