<?php
/**
 * @TODO:
 *
 * @since      0.7.0
 *
 * @package    One_Page_Sections
 * @subpackage One_Page_Sections/includes/classes
 */
class PC3_WPQueryLayer {

    /**
     * @TODO
     *
     * @see: http://wordpress.stackexchange.com/questions/18703/wp-query-with-post-title-like-something
     *
     * @param $_sSectionTitleOrID
     *
     * @return WP_Query
     */
    public static function getSectionByTitleOrID( $_sSectionTitleOrID ) {

        //if numeric, check postID AND title

        //else, only check title.

        //@var pc3_section
        $args = array(
            'post_type' => 'pc3_section',
            'posts_per_page' => 1,
            'pc3_section__title' => $_sSectionTitleOrID,
            'post_status' => 'any',
            'orderby'     => 'title',
            'order'       => 'ASC'
        );

        if( is_numeric( $_sSectionTitleOrID ) )
            $args['pc3_section__id'] = $_sSectionTitleOrID;

        add_filter( 'posts_where', array( 'PC3_WPQueryLayer', 'title_filter'), 10, 2 );
        $wp_query = new WP_Query($args);
        remove_filter( 'posts_where', array( 'PC3_WPQueryLayer', 'title_filter'), 10, 2 );

        return $wp_query->posts;
    }


    /**
     * @TODO
     *
     * @see: http://wordpress.stackexchange.com/questions/18703/wp-query-with-post-title-like-something
     *
     * @param $where
     * @param $wp_query
     *
     * @return string
     */
    public static function title_filter($where, &$wp_query){

        //if numeric, check postID AND title

        global $wpdb;

        $where .= ' AND ';

        if($id_to_match = $wp_query->get( 'pc3_section__id' )){
            $id_to_match = esc_sql( $id_to_match );
            $id_to_match = ' \'' . $id_to_match . '\'';
            $where .= $wpdb->posts . '.ID = '.$id_to_match . ' OR ';
        }

        if($title_to_match = $wp_query->get( 'pc3_section__title' )){
            $title_to_match = esc_sql( $title_to_match );
            $title_to_match = ' \'' . $title_to_match . '\'';
            $where .= $wpdb->posts . '.post_title = '. $title_to_match;
        }

        return $where;
    }

    /**
     * Determines if a post, identified by the specified ID, exists
     * within the WordPress database.
     **
     * @see https://tommcfarlin.com/wordpress-post-exists-by-id/
     *
     * @since    0.7.0
     *
     * @param    int    $_iPostID   The ID of the post to check
     * @return   bool               True if the post exists; otherwise, false.
     */
    public static function isPostExists( $_iPostID ) {

        return is_string( get_post_status( $_iPostID ) );
    }

    /**
     * Returns posts from WQ_Query.
     * Takes as a parameter an array of arguments which override the default arguments
     *
     * @since    0.7.0
     *
     * @param    array $_aUserArgs  User-specified arguments which override the default arguments
     * @return   array              Posts returned by our query
     */
    public static function getPosts( array $_aUserArgs ) {

        $_iDefaultPostsPerPage = get_option( 'posts_per_page' );

        if( ! $_iDefaultPostsPerPage )
            $_iDefaultPostsPerPage = 6;

        $_aDefaultArgs = array(
            'posts_per_page'   => $_iDefaultPostsPerPage,
            'offset'           => 0,
            'category'         => '',
            'category_name'    => '',
            'orderby'          => 'post_date',
            'order'            => 'DESC',
            'include'          => '',
            'exclude'          => '',
            'meta_key'         => '',
            'meta_value'       => '',
            'post_type'        => 'post',
            'post_mime_type'   => '',
            'post_parent'      => '',
            'post_status'      => 'publish',
            'suppress_filters' => true
        );

        $_oResults = new WP_Query( array_merge( $_aDefaultArgs, $_aUserArgs ) );

        return $_oResults->posts;
    }

}