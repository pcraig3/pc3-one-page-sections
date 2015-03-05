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
 * @subpackage One_Page_Sections/admin
 */
class Admin_PC3SectionPostTypeMetaLayer {

    /**
     * @since      0.6.0
     *
     * @var string
     */
    private $sPageClass;

    /**
     * @since      0.6.0
     *
     * @var string
     */
    private $sSectionSlug;

    /**
     * @since      0.6.0
     *
     * @var string
     */
    private $sMetaKey;

    /**
     * @since      0.7.0
     *
     * @var string Field id for the select drop-down list in our form
     */
    public $sSelectFieldId;

    /**
     * @since      0.3.0
     *
     * @var string Field id for the sortable sections in our form
     */
    public $sSortableFieldId;

    /**
     * @since      0.8.2
     *
     * @var string Field id for the editor field in our form
     */
    public $sEditorFieldId;

    /**
     * @since      0.3.0
     *
     * @var string Field id for the submit button in our form
     */
    public $sSubmitFieldId;

    /**
     * @since      0.8.0
     *
     * Object reads and writes to our custom CSS file
     */
    private $oCSSFileEditor;

    /**
     * @since      0.8.2
     *
     * Object executes queries on the database, mostly using the WP_Query class
     */
    private $oWPQueryFacade;

    /**
     * @param string $sPageClass                    Classname of the page these callbacks are registered to
     * @param string $sSectionSlug                  Slug of the 'Section' custom post types
     * @param string $sMetaKey                      Key for the meta value recording the 'order' of each section
     * @param string $sSelectFieldId                Field id for the select box in our form
     * @param string $sSortableFieldId              Field id for the sortable sections in our form
     * @param string $sSubmitFieldId                Field id for the submit button in our form
     * @param string $sEditorFieldId                Field id for the (CSS) editor field in our form
     * @param Lib_PC3CSSFileEditor $oCSSFileEditor  CSS Editor object overwrites CSS file with new edits
     * @param Lib_PC3WPQueryFacade $oWPQueryFacade  Query Facade returns posts from DB
     */
    public function __construct($sPageClass, $sSectionSlug, $sMetaKey,
                                $sSelectFieldId, $sSortableFieldId, $sEditorFieldId, $sSubmitFieldId,
                                Lib_PC3CSSFileEditor $oCSSFileEditor, Lib_PC3WPQueryFacade $oWPQueryFacade) {

        $this->sSectionSlug     = $sSectionSlug;
        $this->sPageClass       = $sPageClass;
        $this->sMetaKey         = $sMetaKey;

        //@TODO this is a pretty ugly solution
        $this->sSelectFieldId   = $sSelectFieldId;
        $this->sSortableFieldId = $sSortableFieldId;
        $this->sEditorFieldId   = $sEditorFieldId;
        $this->sSubmitFieldId   = $sSubmitFieldId;

        $this->oCSSFileEditor   = $oCSSFileEditor;
        $this->oWPQueryFacade   = $oWPQueryFacade;

        //@TODO: maybe move these into the Loader somehow
        add_action( 'save_post_' . $this->sSectionSlug, array( $this, $this->sSectionSlug . '_save_post_' ) );
        add_action( 'wp_trash_post', array( $this,  $this->sSectionSlug . '_wp_trash_post' ) );

        add_action( 'submit_after_' . $this->sPageClass, array( $this,  $this->sSectionSlug . '_submit_after_' ) );

        //@TODO: This is a really bad place for this
        add_action( 'submit_after_' . $this->sPageClass, array( $this,  $this->sSectionSlug . '_submit_after_css' ) );
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
        $aSections = $this->oWPQueryFacade->getSectionWithLargestOrder();

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

        if ( $this->sSectionSlug !== get_post_type( $post_id ) )
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
                $_bAllPostsExist = $this->oWPQueryFacade->isPostExists( $_sPostID );
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
            $aSections = $this->oWPQueryFacade->getSectionsByOrderASC();

        $_iMax = count( $aSections );

        for($i = 0; $i < $_iMax; $i++)
            update_post_meta( $aSections[$i]->ID, $this->sMetaKey, $i );
    }


    /**
     * @since      0.8.1
     */
    public function pc3_section_submit_after_css() {

        $_sEditorRules = PC3_AdminPageFramework::getOption( $this->sPageClass, $this->sEditorFieldId );

        $sContent = $this->oCSSFileEditor->readContentOfCustomCSSFile();

        //@TODO maybe a callback method on success
        //@TODO sanitize CSS content?
        if ( ! empty( $_sEditorRules ) && ( $sContent !== $_sEditorRules ) )
            $this->oCSSFileEditor->writeToCustomCSSFile( $_sEditorRules );


    }
}