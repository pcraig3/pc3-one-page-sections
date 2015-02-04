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


    public function __construct() {

        //add_action( 'save_post_' . $this->slug, array( $this, 'update_meta_order' ) );

        add_action( 'submit_after_' . $this->sPageClass, array( $this, 'submit_after_' ) );
    }

    public function test_meta_order () {

        update_post_meta(1935, 'test', 'john');
    }

    //I think we can get by with setting order to number of posts.
    public function save_post_pc3_section_update_meta_order( $post_id ) {



        static $recursing = false;
        if ( ! $recursing ) {
            $recursing = true;
            $post = array(
                'ID'       => $post_id,
                'content' => ''
            );
            wp_update_post( $post );
            $recursing = false;
        }

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

        $_aOrdersPostIds = PC3_AdminPageFramework::getOption( $this->sPageClass , $this->sPageOptionsFormField );

        $_aPostIdsOrders = array_flip( $_aOrdersPostIds );

        //reindex posts method

        //@TODO: maybe check if post ids exist
        foreach( $_aPostIdsOrders as $_sPostID => $_sOrder )
            update_post_meta( $_sPostID, 'order', $_sOrder);

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
     * @param    int    $id    The ID of the post to check
     * @return   bool          True if the post exists; otherwise, false.
     * @since    1.0.0
     */
    function post_exists( $id ) {
        return is_string( get_post_status( $id ) );
    }
}