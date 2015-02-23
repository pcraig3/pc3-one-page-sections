<?php
/**
* The template for displaying sections.
 * Basically we want very little other than a container so that more advanced markup can be done in the editor.
*
 * @since      0.5.0
 *
 * @package    One_Page_Sections
 * @subpackage One_Page_Sections/templates
 */

global $post;

$section__slug = do_shortcode('[pc3_get_parameter]');
?>

<section class="<?php echo $section__slug; ?> <?php echo $section__slug; ?>__<?php echo $post->post_name; ?>" id="<?php echo $section__slug; ?>__<?php echo $post->post_name; ?>">
   <?php the_content(); ?>
</section>

