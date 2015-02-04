<?php
/**
 * Register a new meta box to our custom post type, Sections.
 *
 * For a crash course in adding meta-boxes to posts with the APF, check out this link
 * @see http://admin-page-framework.michaeluno.jp/tutorials/11-add-a-meta-box-for-posts/
 *
 * For acceptable parameters, check out the WordPress Codex
 * @see http://codex.wordpress.org/Function_Reference/register_post_type
 *
 *
 * @since      0.2.0
 *
 * @package    One_Page_Sections
 * @subpackage One_Page_Sections/includes/classes
 */

class PC3_SectionPostType_MetaBox extends PC3_AdminPageFramework_MetaBox  {


    /*
     * Use the setUp() method to define settings of this meta box.
     *
    public function setUp() {

        /**
         * Adds setting fields in the meta box.
         *
        $this->addSettingFields(
            array(
                'field_id'          => 'pc3_section_select_order',
                'type'              => 'select',
                'title'             => __( 'Order', 'one-page-sections' ),
                'label'         => array(
                    0 => __( 'Red', 'one-page-sections' ),
                    1 => __( 'Blue', 'one-page-sections' ),
                    2 => __( 'Yellow', 'one-page-sections' ),
                    3 => __( 'Orange', 'one-page-sections' ),
                ),
                'description' => __( 'Order the sections show up in', 'one-page-sections' )

            )
        );

    }
    */

    public function do_PC3_SectionPostType_MetaBox( $oMetaBox ) {

        global $post;

        var_dump( get_post_meta($post->ID) );
    }

}