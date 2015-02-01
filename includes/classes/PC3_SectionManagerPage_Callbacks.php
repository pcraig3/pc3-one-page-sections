<?php

/**
 * The file that handles the logic that populates the Section Manager Page.
 *
 * Sections are called from the WordPress database, and their titles used to populate Sortable Objects.
 * Users can re-arrange the sortable objects comme ils veulent, the idea being that the sections will
 * all show up on one page ~somewhere and in the order specified.
 *
 * If no sections are found, form displays error message and alerts users that they need to create sections
 * If no options are found, sections are listed in order of most recent.
 *
 * @since      0.1.0
 * @package    One_Page_Sections
 * @subpackage One_Page_Sections/includes
 * @author     Paul Craig <paul@pcraig3.ca>
 */
class PC3_SectionManagerPage_Callbacks {

    /**
     * Stores the caller class name, set in the constructor.
     */
    public $sClassName = 'PC3_SectionManagerPage';

    /**
     * The page slug to add the tab and form elements.
     */
    public $sPageSlug   = 'manage_sections';

    /**
     * @var string Field id for the sortable sections in our form
     */
    public $sSortableFieldId = 'manage_sections__sections';

    /**
     * @var string Field id for the submit button in our form
     */
    public $sSubmitFieldId = 'manage_sections__submit';

    /**
     * Variable keeps track of whether or not sections were returned
     */
    private $bIfSections = false;

    /**
     * Sets up hooks and properties.
     */
    public function __construct( $sClassName='', $sPageSlug='', $sSortableFieldId='', $sSubmitFieldId='' ) {

        $this->sClassName   = $sClassName ? $sClassName : $this->sClassName;
        $this->sPageSlug    = $sPageSlug ? $sPageSlug : $this->sPageSlug;
        $this->sSortableFieldId    = $sSortableFieldId ? $sSortableFieldId : $this->sSortableFieldId;
        $this->sSubmitFieldId    = $sSubmitFieldId ? $sSubmitFieldId : $this->sSubmitFieldId;

        // load_ + page slug
        add_action( 'load_' . $this->sPageSlug, array( $this, 'replyToLoadPage' ) );

    }

    /**
     * Triggered when the tab is loaded.
     */
    public function replyToLoadPage( $oAdminPage ) {

        // field_definition_{instantiated class name}_{section id}_{field_id}
        add_filter( 'field_definition_' . $this->sClassName . '_' . $this->sSortableFieldId,
            array( $this,  'field_definition_' . $this->sClassName . '_' . $this->sSortableFieldId ) );


        // field_definition_{instantiated class name}_{section id}_{field_id}
        //{field_id} = submit_button
        add_filter( 'field_definition_' . $this->sClassName . '_' . $this->sSubmitFieldId,
            array( $this,  'field_definition_' . $this->sClassName . '_' . $this->sSubmitFieldId ) );
    }

    /**
     * Callback method passed the submit_button field after the Sections have (or haven't) been returned
     * If sections have been found, Submit button is usable.  Otherwise, it remains disabled
     *
     * Note: method follows following naming pattern: field_definition_{instantiated class name}_{section id}_{field_id}
     *
     * @param $aField array    the field with an id of 'submit_button'
     * @return mixed array     the field
     */
    public function field_definition_PC3_SectionManagerPage_manage_sections__submit( $aField ) {

        if( $this->bIfSections)
            $aField['attributes'] = array(
                'class' => 'button button-primary'
            );

        return $aField;
    }

    /**
     * Callback method passed the sortable text fields.  Default field value is a label saying that no Sections were found.
     * If, however, sections are found, then field is updated with sections, and the user is free to rearrange them.
     *
     * Note: method follows following naming pattern: field_definition_{instantiated class name}_{section id}_{field_id}
     *
     * @param $aField array    the field with an id of 'callback_example'
     * @return array array     the field
     */
    public function field_definition_PC3_SectionManagerPage_manage_sections__sections( $aField ) { // field_definition_{instantiated class name}_{section id}_{field_id}

        $aPosts = $this->_getPosts( 'pc3_section' );

        //return unmodified field if no sections were found
        if( empty( $aPosts ) )
            return $aField;

        //their sorted order is saved by the AdminPageFramework, but WordPress by default returns most recent Posts first
        //this function reorganises the returned array of Posts
        $aPosts = $this->_reorderPostsBasedOnSortedOptions( $aPosts, $this->sClassName, $this->sSortableFieldId );

        //flag this as 'true'; Sections were found!
        $this->bIfSections = true;

        $aField['type']         = 'text';
        $aField['description']  = sprintf( __( 'This description is inserted with the callback method: <code>%1$s</code>.', 'one-page-sections' ), __METHOD__ );
        $aField['sortable']     = true;

        //first section must exist because array is not empty
        $_oFirstPost = array_shift( $aPosts );

        //first value must be set differently from any preceding values
        $aField = array_merge( $aField, $this->_returnSectionArray( $_oFirstPost->post_title, $_oFirstPost->ID ) );

        //stop here if only one section was found
        if( empty( $aPosts ) )
            return $aField;

        //return array of arrays containing Section values that will be inserted into our field
        $aFormattedPosts = $this->_formatPostsForField( $aPosts );

        foreach( $aFormattedPosts as $aPost ) {

            array_push( $aField, $aPost );
        }

        return $aField;
    }

    /**
     * Function returns posts based on slug.  n our case, we're planning on returning Sections.
     * ~Maybe should be somewhere else
     *
     * @param string $sPostTypeSlug
     * @return mixed
     */
    private function _getPosts( $sPostTypeSlug='pc3_section' ) {

        $_aArgs         = array(
            'post_type' => $sPostTypeSlug,
        );
        $_oResults      = new WP_Query( $_aArgs );

        return $_oResults->posts;
    }

    /**
     * Function takes an array of posts and reorders them based on options saved by the Admin Page Framework
     * ~Maybe should be somewhere else
     *
     * @param $_aPosts array        array of Posts
     * @param string $_sClassName   Class generating the page on which our fields are being inserted
     * @param string $_sFieldID     Field ID for the sortable fields
     * @return array                re-ordered array of Posts
     */
    private function _reorderPostsBasedOnSortedOptions( $_aPosts, $_sClassName = 'PC3_SectionManagerPage' , $_sFieldID = 'manage_sections__sections' ) {

        /*
         * Saved sections are returned as an indexed array filled with Post IDs.  Ex:
         *
         * array(3) {
              [0]=>
              string(4) "1902"
              [1]=>
              string(4) "1903"
              [2]=>
              string(4) "1904"
            }
         *
         */
        $_aSavedSectionIds = AdminPageFramework::getOption( $_sClassName, $_sFieldID );

        //if no posts are returned, or no saved options are found, then return immediately
        if( empty( $_aPosts ) || empty( $_aSavedSectionIds ) )
            return $_aPosts;

        $aPostsReordered = array();

        foreach( $_aSavedSectionIds as $_sSectionId ) {

            foreach( $_aPosts as $_iIndex => $_oPost ) {

                if( intval( $_sSectionId ) === intval( $_oPost->ID ) ) {

                    //if the value in the saved array of sectionIds matched the current post id,
                    //push into reordered array and remove from old array
                    array_push( $aPostsReordered, $_oPost );
                    unset( $_aPosts[$_iIndex] );
                }
            }
        }

        //all remaining posts are stuck to the end of the reordered array
        while( ! empty( $_aPosts ) )
            array_push( $aPostsReordered, array_shift( $_aPosts ) );

        return $aPostsReordered;
    }

    /**
     * Iterate through Post objects, turning each into an array that an APF Field will understand
     * Return all arrays in another array.
     *
     * @param $aPosts array     array of WordPress Post objects
     * @return array            an array of WordPress Post arrays
     */
    private function _formatPostsForField( $aPosts ) {

        $_aSectionTextFields = array();

        foreach( $aPosts as $_iIndex => $_oPost ) {
            array_push($_aSectionTextFields, $this->_returnSectionArray($_oPost->post_title, $_oPost->ID));
        }

        return $_aSectionTextFields;
    }

    /**
     * Returns an array that an APF Field will understand
     *
     * @param $post_title string    the title of a post
     * @param $label int            the id of a post
     * @return array
     */
    private function _returnSectionArray( $post_title, $label ) {

        return array (
            'value'           => intval($label),
            'label'             => $post_title,
            'attributes'        => array(
                'readonly'  => true
            )
        );
    }
}