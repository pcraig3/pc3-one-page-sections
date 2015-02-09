<?php
/**
 * Register a new custom post type, Sections.
 *
 * For a crash course in APF, check out this link
 * @see http://admin-page-framework.michaeluno.jp/tutorials/12-create-a-custom-post-type-and-custom-taxonomy/
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
class PC3_SectionPostType extends PC3_AdminPageFramework_PostType {

    /**
     * Automatically called with the 'wp_loaded' hook.
     *
 	 * @since    0.2.0
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
            'description'           => __( 'Sections for a one-page layout', 'one-page-sections' ),
            'public'                => false,
            'exclude_from_search'   => true,
            'publicly_queryable'    => true,
            'show_ui'               => true,
            'show_in_nav_menus'     => true,
            'rewrite'               => false,
            'query_var'             => false,
            'menu_position'         => 9,
            'menu_icon'             => 'dashicons-align-center',
            'supports'              => array( 'editor', 'title', 'thumbnail', 'post-formats' ),
            'has_archive' =>            false,
        ) );

        $this->setArguments( $args );

    }

    /*
     * Modifies the columns of post listing table.
     *
     * @remark  columns_{post type slug}
     */
    /**
     * Modifies the columns of post listing table.
     * In our case, we're adding the 'order' column,
     * representing the order that the sections will be rendered in on the front-end
     *
     * @since    0.6.0
     *
     * @param $aHeaderColumns
     * @return array
     */
    public function columns_pc3_section( $aHeaderColumns ) {

        return array(
            'cb'    => '<input type="checkbox" />', // Checkbox for bulk actions.
            'title' => __( 'Title', 'one-page-sections' ), // Post title. Includes "edit", "quick edit", "trash" and "view" links. If $mode (set from $_REQUEST['mode']) is 'excerpt', a post excerpt is included between the title and links.
            'date' => __( 'Date', 'one-page-sections' ),
            'order' => __( 'Order', 'one-page-sections' )
        )
            // + $aHeaderColumns // uncomment this to enable the default columns.
            ;

    }

    /**
     * Controls the rendering of the 'order' column cell contents of the post listing table
     *
     * @since    0.6.0
     *
     * @param string $sCell the markup that will become each individual column cell
     * @param int $iPostID  the post_id
     * @return string       the markup for the column cell
     */
    public function cell_pc3_section_order( $sCell, $iPostID ) { // cell_{post type}_{column key}

        $_sOrder = get_post_meta( $iPostID, 'order', true );

        if( strlen( $_sOrder ) < 1 )
            $_sOrder = '-';

        return $sCell
        . '<div class="pc3_section__order-container">'
        . '<p>' . esc_html($_sOrder)
        . '</p>'
        . '</div>';
    }

}