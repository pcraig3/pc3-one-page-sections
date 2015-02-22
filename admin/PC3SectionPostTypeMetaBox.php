<?php
/**
 * Register a new meta box to our custom post type, Sections.
 * At present, only used for debug information.
 *
 * For a crash course in adding meta-boxes to posts with the APF, check out this link
 * @see http://admin-page-framework.michaeluno.jp/tutorials/11-add-a-meta-box-for-posts/
 *
 * @since      0.6.0
 *
 * @package    One_Page_Sections
 * @subpackage One_Page_Sections/admin
 */
class Admin_PC3SectionPostTypeMetaBox extends PC3_AdminPageFramework_MetaBox  {

    public function do_Admin_PC3SectionPostTypeMetaBox( $oMetaBox ) {

        global $post;

        echo '<pre>';
        var_dump( get_post_meta($post->ID) );
        echo '</pre>';

    }
}