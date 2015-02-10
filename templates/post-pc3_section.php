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

global $post
?>

<section class="pc3_section pc3_section__<?php echo $post->post_name; ?>" id="pc3_section__<?php echo $post->post_name; ?>">
   <?php the_content(); ?>
</section>

