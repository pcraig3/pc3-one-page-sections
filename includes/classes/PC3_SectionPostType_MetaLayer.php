<?php
/**
 * Class that deals with keeping the 'order' custom fields in sync for our Sections
 * Assigns orders to newly created sections, re-indexes orders after a Section has been deleted,
 * and updates the orders of Sections when using Manage Sections page.
 *
 * Pretty dire though, some of the code in here.  Needs a bit of refactoring.
 *
 * @since      0.6.0
 *
 * @package    One_Page_Sections
 * @subpackage One_Page_Sections/includes/classes
 */
class PC3_SectionPostType_MetaLayer {

    /**
     * @since      0.6.0
     *
     * @var string
     */
    private $sPostTypeSlug = 'pc3_section';

    /**
     * @since      0.6.0
     *
     * @var string
     */
    private $sPageClass = 'PC3_SectionManagerPage';

    /**
     * @since      0.7.0
     *
     * @var string
     */
    private $sSortableFieldId = 'manage_sections__sections';

    /**
     * @since      0.6.0
     *
     * @var string
     */
    private $sMetaKey = 'order';

    /**
     * @since      0.8.0
     *
     * Object reads and writes to our custom CSS file
     */
    private $oCSSFileEditor = null;

    /**
     * @since      0.8.0
     *
     * @param string $sPostTypeSlug
     * @param string $sPageClass
     * @param string $sSortableFieldId              Field id for the sortable sections in our form
     * @param string $sMetaKey
     * @param PC3_CSSFileEditor $oCSSFileEditor     reads and writes to our custom CSS file
     */
    public function __construct($sPostTypeSlug='', $sPageClass='', $sSortableFieldId='', $sMetaKey='', PC3_CSSFileEditor $oCSSFileEditor = null) {

        $this->sPostTypeSlug   = $sPostTypeSlug ? $sPostTypeSlug : $this->sPostTypeSlug;
        $this->sPageClass    = $sPageClass ? $sPageClass : $this->sPageClass;
        $this->sSortableFieldId    = $sSortableFieldId ? $sSortableFieldId : $this->sSortableFieldId;
        $this->sMetaKey    = $sMetaKey ? $sMetaKey : $this->sMetaKey;
        $this->oCSSFileEditor    = $oCSSFileEditor ? $oCSSFileEditor : $this->oCSSFileEditor;

        // @TODO: maybe move these into the Loader somehow
        add_action( 'save_post_' . $this->sPostTypeSlug, array( $this, $this->sPostTypeSlug . '_save_post_' ) );
        add_action( 'wp_trash_post', array( $this,  $this->sPostTypeSlug . '_wp_trash_post' ) );

        add_action( 'submit_after_' . $this->sPageClass, array( $this,  $this->sPostTypeSlug . '_submit_after_' ) );

        //@TODO: This is a really bad place for this
        add_action( 'submit_after_' . $this->sPageClass, array( $this,  $this->sPostTypeSlug . '_submit_after_css' ) );
    }

    public function pc3_section_test() {

        update_post_meta(1935, 'test', 'john');
    }

    /**
     * @since      0.8.0
     *
     * @param $post_id
     */
    public function pc3_section_save_post_( $post_id ) {

        //don't fire if post hasn't been actively saved or published by user
        if( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) || 'auto-draft' === get_post_status( $post_id ) ||
            'trash' === get_post_status( $post_id ))
            return;

        //don't fire if post already has an 'order' meta parameter
        //CHECK FOR THE STRING LENGTH BECAUSE '0' IS A VALID ORDER VALUE
        if( strlen( get_post_meta( $post_id, $this->sMetaKey, true ) ) > 0 )
            return;

        //Array with one result
        $aSections = PC3_WPQueryLayer::getSectionWithLargestOrder();

        $oLastSection = array_shift( $aSections );

        //returns an string OR null
        $sOrder = get_post_meta($oLastSection->ID, $this->sMetaKey, true );

        if( ! is_null( $sOrder ) )
            update_post_meta( $post_id, $this->sMetaKey, ++$sOrder );
    }

    /**
     * @since      0.8.0
     *
     * @param $post_id
     */
    public function pc3_section_wp_trash_post( $post_id ) {

        if ( $this->sPostTypeSlug !== get_post_type( $post_id ) )
            return;

        delete_post_meta( $post_id, $this->sMetaKey );

        $this->reindexSections();
    }

    /**
     * @since      0.8.0
     */
    public function pc3_section_submit_after_() {

        $_bAllPostsExist = true;

        $_aOrdersPostIds = PC3_AdminPageFramework::getOption( $this->sPageClass , $this->sSortableFieldId );

        $_aPostIdsOrders = array_flip( $_aOrdersPostIds );

        //Okay, so check that all posts exist
        //can't just use the return value of update_post_meta because
        //"It also returns false if the value submitted is the same as the value that is already in the database."
        //http://codex.wordpress.org/Function_Reference/update_post_meta

        //if any posts _don't_ exist (maybe they were deleted after the page was loaded)
        //then save the values as they are and reindex them in order.

        foreach( $_aPostIdsOrders as $_sPostID => $_sOrder ) {

            //do this regardless of whether or not post exists.  If post doesn't exist, method simply fails.
            update_post_meta($_sPostID, $this->sMetaKey, $_sOrder);

            if( $_bAllPostsExist )
                $_bAllPostsExist = PC3_WPQueryLayer::isPostExists( $_sPostID );
        }

        //re-index the sections now so that our orders are sequential
        if( ! $_bAllPostsExist )
            $this->reindexSections();
    }

    /**
     * Function hopes to forestall any number anomalies.  Returns all Sections (or accepts an array of sections)
     * and sets their 'order' meta to their index in the array.
     * This way, 'order' meta values should always be between 0 and (count( $aSections ) - 1)
     *
     * @since      0.8.0
     *
     * @param array $aSections  an array of Section Custom Post Types
     */
    private function reindexSections( array $aSections = array() ) {

        if( empty( $aSections ) )
            $aSections = PC3_WPQueryLayer::getSectionsByOrderASC();

        $_iMax = count( $aSections );

        for($i = 0; $i < $_iMax; $i++)
            update_post_meta( $aSections[$i]->ID, $this->sMetaKey, $i );
    }


    /**
     * @since      0.8.0
     */
    public function pc3_section_submit_after_css() {

        //@TODO: @var manage_sections__editor
        $_sEditorRules = PC3_AdminPageFramework::getOption( $this->sPageClass, 'manage_sections__editor' );

        $sContent = $this->oCSSFileEditor->readContentOfCustomCSSFile();

        //@TODO maybe a callback method on success
        if ( ! empty( $_sEditorRules ) && ( $sContent !== $_sEditorRules ) )
            $this->oCSSFileEditor->writeToCustomCSSFile( esc_url($sContent) );

    }
}