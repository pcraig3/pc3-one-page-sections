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
 * @since      0.3.0
 * @package    One_Page_Sections
 * @subpackage One_Page_Sections/admin
 * @author     Paul Craig <paul@pcraig3.ca>
 */
class Admin_PC3SectionManagerPageCallbacks {

    /**
     * @since      0.3.0
     *
     * Stores the caller class name, set in the constructor.
     */
    public $sClassName = 'Admin_PC3SectionManagerPage';

    /**
     * @since      0.3.0
     *
     * The page slug to add the tab and form elements.
     */
    public $sPageSlug   = 'manage_sections';

    /**
     * @since      0.7.0
     *
     * @var string Field id for the select drop-down list in our form
     */
    public $sSelectFieldId = 'manage_sections__sections_page';

    /**
     * @since      0.3.0
     *
     * @var string Field id for the sortable sections in our form
     */
    public $sSortableFieldId = 'manage_sections__sections';

    /**
     * @since      0.3.0
     *
     * @var string Field id for the submit button in our form
     */
    public $sSubmitFieldId = 'manage_sections__submit';

    /**
     * @since      0.3.0
     *
     * Variable keeps track of whether or not sections were returned
     */
    private $bIfSections = false;

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
     *
     * @since      0.8.0
     *
     * @param string $sClassName                Stores the classname of the object that called this one
     * @param string $sPageSlug                 The page slug to add the tab and form elements.
     * @param string $sSortableFieldId          Field id for the sortable sections in our form
     * @param string $sSubmitFieldId            Field id for the sortable sections in our form
     * @param Lib_PC3CSSFileEditor $oCSSFileEditor reads and writes to our custom CSS file
     */
    public function __construct( $sClassName='', $sPageSlug='', $sSortableFieldId='', $sSubmitFieldId='',
                                 Lib_PC3CSSFileEditor $oCSSFileEditor, Lib_PC3WPQueryFacade $oWPQueryFacade) {

        //@TODO: drop-down form
        $this->sClassName   = $sClassName ? $sClassName : $this->sClassName;
        $this->sPageSlug    = $sPageSlug ? $sPageSlug : $this->sPageSlug;
        //$this->sSelectFieldId = $sSelectFieldId ? $sSelectFieldId : $this->sSelectFieldId;
        $this->sSortableFieldId    = $sSortableFieldId ? $sSortableFieldId : $this->sSortableFieldId;
        $this->sSubmitFieldId    = $sSubmitFieldId ? $sSubmitFieldId : $this->sSubmitFieldId;
        $this->oCSSFileEditor    = $oCSSFileEditor;
        $this->oWPQueryFacade   = $oWPQueryFacade;

        // load_ + page slug
        add_action( 'load_' . $this->sPageSlug, array( $this, 'replyToLoadPage' ) );

    }

    /**
     * Triggered when the tab is loaded.
     *
     * @since      0.7.0
     */
    public function replyToLoadPage( $oAdminPage ) {

        // field_definition_{instantiated class name}_{section id}_{field_id}
        add_filter( 'field_definition_' . $this->sClassName . '_' . $this->sSelectFieldId,
            array( $this,  'field_definition_' . $this->sClassName . '_' . $this->sSelectFieldId ) );

        // field_definition_{instantiated class name}_{section id}_{field_id}
        add_filter( 'field_definition_' . $this->sClassName . '_' . $this->sSortableFieldId,
            array( $this,  'field_definition_' . $this->sClassName . '_' . $this->sSortableFieldId ) );

        //@TODO: @var __editor
        add_filter( 'field_definition_' . $this->sClassName . '_' . 'manage_sections__editor',
            array( $this,  'field_definition_' . $this->sClassName . '_' . 'manage_sections__editor' ) );

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
     * @since      0.7.0
     * @param $aField array    the field with an id of 'submit_button'
     * @return mixed array     the field
     */
    public function field_definition_Admin_PC3SectionManagerPage_manage_sections__sections_page( $aField ) {

        $aPages = $this->oWPQueryFacade->getPosts( array(
            'post_type' => 'page',
            'orderby'   => 'title',
            'order'     => 'ASC',
            'post_status'   => 'any',
            'posts_per_page' => -1
        ) );

        if( empty( $aPages ) )
            return $aField;

        $aField['description']  = __( 'Select a Page to be used for your One Page Sections', 'one-page-sections' );
        $aField['label'] = $this->_formatPagesAsLabels( $aPages );

        return $aField;
    }

    /**
     * Callback method passed the sortable text fields.  Default field value is a label saying that no Sections were found.
     * If, however, sections are found, then field is updated with sections, and the user is free to rearrange them.
     *
     * Note: method follows following naming pattern: field_definition_{instantiated class name}_{section id}_{field_id}
     *
     * @since      0.7.0
     *
     * @param $aField array    the field with an id of 'callback_example'
     * @return array array     the field
     */
    public function field_definition_Admin_PC3SectionManagerPage_manage_sections__sections( $aField ) { // field_definition_{instantiated class name}_{section id}_{field_id}

        $aPosts = $this->oWPQueryFacade->getSectionsByOrderASC();

        //return unmodified field if no sections were found
        if( empty( $aPosts ) )
            return $aField;

        //flag this as 'true'; Sections were found!
        $this->bIfSections = true;

        $aField['type']         = 'text';
        $aField['description']  = __( 'Reorder the sections to modify the order they appear on your page.', 'one-page-sections' );
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
     * Callback method passed the content of our CSS file to fill in the form's CSS editor
     *
     * Note: method follows following naming pattern: field_definition_{instantiated class name}_{section id}_{field_id}
     *
     * @TODO Errors or something
     *
     * @since      0.8.0
     * @param $aField array    the field with an id of 'manage_sections__editor'
     * @return mixed array     the field
     */
    public function field_definition_Admin_PC3SectionManagerPage_manage_sections__editor( $aField ) {

        $sContent = $this->oCSSFileEditor->readContentOfCustomCSSFile();

        if( ! empty( $sContent ) )
            $aField['value'] = $sContent;

        return $aField;
    }

    /**
     * Callback method passed the submit_button field after the Sections have (or haven't) been returned
     * If sections have been found, Submit button is usable.  Otherwise, it remains disabled
     *
     * Note: method follows following naming pattern: field_definition_{instantiated class name}_{section id}_{field_id}
     *
     * @since      0.3.0
     * @param $aField array    the field with an id of 'manage_sections__submit'
     * @return mixed array     the field
     */
    public function field_definition_Admin_PC3SectionManagerPage_manage_sections__submit( $aField ) {

        if( $this->bIfSections )
            $aField['attributes'] = array(
                'class' => 'button button-primary'
            );

        return $aField;
    }

    /**
     * Iterate through Post objects, turning each into an entry in an array that an APF 'select' Field will understand
     *
     * @since      0.7.0
     *
     * @param $aPages array     array of WordPress Post objects
     * @return array            an array of labels for a 'select' field
     */
    private function _formatPagesAsLabels( $aPages ) {

        $_aLabels = array();

        foreach( $aPages as $_oPage )
            $_aLabels[$_oPage->ID] = $_oPage->post_title;

        return $_aLabels;
    }

    /**
     * Iterate through Post objects, turning each into an array that an APF Field will understand
     * Return all arrays in another array.
     *
     * @since      0.3.0
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
     * @since      0.3.0
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