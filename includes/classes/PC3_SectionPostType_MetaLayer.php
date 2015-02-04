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

        add_action( 'save_post_' . $this->sPostTypeSlug, array( $this, 'save_post_' ) );

        add_action( 'submit_after_' . $this->sPageClass, array( $this, 'submit_after_' ) );
    }

    public function test_meta_order () {

        update_post_meta(1935, 'test', 'john');
    }

    //I think we can get by with setting order to number of posts.
    public function save_post_( $post_id ) {

        //don't fire if post hasn't been actively saved or published by user
        if( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) || 'auto-draft' === get_post_status( $post_id ) )
            return;

        //don't fire if post already has an 'order' meta parameter
        if( array_shift( get_post_meta($post_id, 'order') ) )
            return;

        //@TODO: Let's isolate this please
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
        $order = array_shift( get_post_meta($last_post->ID, 'order') );

        update_post_meta( $post_id, $this->sMetaKey, ++$order );
    }

    public function remove_post_pc3_section_update_meta_order( $post_id ) {

        //*don't need $post_id*

        //at this point, get all of the current orders and then just redo the array keys

        //$sections_array = get all sections ordered_by by meta = 'order'


        //for($i = 0; $i < count($sections_array); $i++ ) {
        //  update_post_meta( $sections_array[$i]->ID, 'order', $i );

        //done.
    }

    //sort of loses its lustre with this method name
    //public function submit_after_PC3_SectionManagerPage_pc3_section_update_meta() {
    public function submit_after_() {

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