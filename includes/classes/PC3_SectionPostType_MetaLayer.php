<?php
/**
 * Created by PhpStorm.
 * User: Paul
 * Date: 04/02/2015
 * Time: 09:53
 */

class PC3_SectionPostType_MetaLayer {

    //@TODO: pull the variable through
    //@var pc3_section
    private $sPostTypeSlug = 'pc3_section';

    private $sPageClass = 'PC3_SectionManagerPage';
    private $sPageOptionsFormField = 'manage_sections__sections';

    private $sMetaKey = 'order';


    public function __construct() {

        add_action( 'save_post_' . $this->sPostTypeSlug, array( $this, $this->sPostTypeSlug . '_save_post_' ) );
        add_action( 'wp_trash_post', array( $this,  $this->sPostTypeSlug . '_wp_trash_post' ) );

        add_action( 'submit_after_' . $this->sPageClass, array( $this,  $this->sPostTypeSlug . '_submit_after_' ) );
    }

    public function pc3_section_test() {

        update_post_meta(1935, 'test', 'john');
    }

    //I think we can get by with setting order to number of posts.
    public function pc3_section_save_post_( $post_id ) {

        //don't fire if post hasn't been actively saved or published by user
        if( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) || 'auto-draft' === get_post_status( $post_id ) ||
            'trash' === get_post_status( $post_id ))
            return;

        //don't fire if post already has an 'order' meta parameter
        if( get_post_meta( $post_id, 'order', true ) )
            return;

        //@TODO: Move this somewhere else
        $args = array(
            'post_type' => $this->sPostTypeSlug,
            'orderby'   => 'meta_value_num',
            'meta_key'  => $this->sMetaKey,
            'order'     => 'DESC',
            'post_status ' => 'any',
            'posts_per_page' => 1
        );
        $query = new WP_Query( $args );

        $last_post = array_shift( $query->posts );

        //returns an string OR null
        $order = get_post_meta($last_post->ID, 'order', true );

        update_post_meta( $post_id, $this->sMetaKey, ++$order );
    }

    public function pc3_section_wp_trash_post( $post_id ) {

        if ( $this->sPostTypeSlug !== get_post_type( $post_id ) )
            return;

        delete_post_meta( $post_id, $this->sMetaKey );


        //at this point, get all of the current orders and then just redo the array keys
        $_aArgs         = array(
            'post_type' => 'pc3_section',
            'orderby'   => 'meta_value_num',
            'meta_key'  => 'order',
            'order'     => 'ASC',
            'post_status' => 'any',
            'posts_per_page' => -1
        );
        $_oResults      = new WP_Query( $_aArgs );

        $_aSections = $_oResults->posts;

        $_iMax = count( $_aSections );

        for($i = 0; $i < $_iMax; $i++)
            update_post_meta( $_aSections[$i]->ID, $this->sMetaKey, $i );
    }

    //sort of loses its lustre with this method name
    //public function submit_after_PC3_SectionManagerPage_pc3_section_update_meta() {
    public function pc3_section_submit_after_() {

        $_bAllPostsExist = true;

        $_aOrdersPostIds = PC3_AdminPageFramework::getOption( $this->sPageClass , $this->sPageOptionsFormField );

        $_aPostIdsOrders = array_flip( $_aOrdersPostIds );

        //Okay, so check that all posts exist
        //can't just use the return value of update_post_meta because of
        //*It also returns false if the value submitted is the same as the value that is already in the database.*
        //http://codex.wordpress.org/Function_Reference/update_post_meta

        //if any posts _don't_ exist (maybe they were deleted after the page was loaded)
        //then save the values as they are and reindex them in order.

        foreach( $_aPostIdsOrders as $_sPostID => $_sOrder ) {

            //do this regardless of whether or not post exists.  If post doesn't exist, method simply fails.
            update_post_meta($_sPostID, $this->sMetaKey, $_sOrder);

            if( $_bAllPostsExist )
                $_bAllPostsExist = $this->post_exists( $_sPostID );
        }

        //@TODO: reindex sections somehow
        //if( ! $_bAllPostsExist )
            //reindex sections

    }

    /**
     * Determines if a post, identified by the specified ID, exist
     * within the WordPress database.
     *
     * Note that this function uses the 'acme_' prefix to serve as an
     * example for how to use the function within a theme. If this were
     * to be within a class, then the prefix would not be necessary.
     *
     * @TODO: MOVE OUT OF HERE
     *
     * @see https://tommcfarlin.com/wordpress-post-exists-by-id/
     *
     * @param    int    $id    The ID of the post to check
     * @return   bool          True if the post exists; otherwise, false.
     * @since    1.0.0
     */
    function post_exists( $id ) {
        return is_string( get_post_status( $id ) );
    }
}