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