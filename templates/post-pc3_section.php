<?php
/**
* The template for displaying sections.
 * Basically we want very little other than a container so that more advanced markup can be done in the editor.
*
 * @since      0.4.0
 *
 * @package    One_Page_Sections
 * @subpackage One_Page_Sections/templates
 */
?>

<li>
    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a><p><?php the_content(); ?></p>
</li>