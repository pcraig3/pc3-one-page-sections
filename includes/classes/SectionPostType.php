<?php
/**
 * Created by PhpStorm.
 * User: Paul
 * Date: 30/01/2015
 * Time: 17:51
 */

class SectionPostType extends AdminPageFramework_PostType {

    /**
     * Automatically called with the 'wp_loaded' hook.
     */
    public function setUp() {

        $labels = apply_filters( 'pc3_section_labels', array(
            'name'                  => __( 'Sections', 'one-page-sections' ),
            'singular_name'         => __( 'Section', 'one-page-sections' ),
            'add_new'               => __( 'Add New', 'one-page-sections' ),
            'add_new_item'          => __( 'Add New Section', 'one-page-sections' ),
            'edit_item'             => __( 'Edit Section', 'one-page-sections' ),
            'new_item'              => __( 'New Section', 'one-page-sections' ),
            'view_item'             => __( 'View Section', 'one-page-sections' ),
            'search_items'          => __( 'Search Section', 'one-page-sections' ),
            'not_found'             => __( 'Yikes! No Sections found!', 'one-page-sections' ),
            'not_found_in_trash'    => __( 'No Sections in the trash', 'one-page-sections' ),
            'parent_item_colon'     => '',
            'menu_name'             => __( 'Sections', 'one-page-sections' )
        ) );

        $args = apply_filters( 'pc3_section_args', array(
            'labels'                => $labels,
            'description'           => __( 'This is the description', 'one-page-sections' ),
            'public'                => false,
            'exclude_from_search'   => true,
            'publicly_queryable'    => true,
            'show_ui'               => true,
            'show_in_nav_menus'     => true,
            'rewrite'               => false,
            'query_var'             => false,
            'menu_position'         => 9,
            'menu_icon'             => 'dashicons-align-center',
            'supports'              => array( 'editor', 'title' ),
            'has_archive' =>            false,
        ) );

        $this->setArguments( $args );
    }

}