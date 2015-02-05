<?php
/**
 * Created by PhpStorm.
 * User: Paul
 * Date: 05/02/2015
 * Time: 11:25
 */

class PC3_WPQueryLayer {

    /**
     * Determines if a post, identified by the specified ID, exists
     * within the WordPress database.
     **
     * @see https://tommcfarlin.com/wordpress-post-exists-by-id/
     *
     * @param    int    $_iPostID   The ID of the post to check
     * @return   bool               True if the post exists; otherwise, false.
     * @since    0.6.0
     */
    public static function isPostExists( $_iPostID ) {

        return is_string( get_post_status( $_iPostID ) );
    }

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

        $_aArgs = array_merge( $_aDefaultArgs, $_aUserArgs );

        $_oResults = new WP_Query( $_aArgs );

        return $_oResults->posts;
    }

}