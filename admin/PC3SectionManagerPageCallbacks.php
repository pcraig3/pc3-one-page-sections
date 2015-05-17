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
    public $sPageClass;

    /**
     * An array of setting fields to be added to this page.
     *
     * @since   0.9.0
     * @var     array
     */
    private $aSettingFields;

    /**
     * @since      0.9.2
     *
     * String containing the custom CSS in the CSS editor
     */
    private $sCustomCSSContent;

    /**
     * @since      0.8.2
     *
     * Object executes queries on the database, mostly using the WP_Query class
     */
    private $oWPQueryFacade;

    /**
     * @since      0.3.0
     *
     * Variable keeps track of whether or not sections were returned
     */
    private $bIfSections = false;

    /**
     * @since      0.9.2
     *
     * @param string $sPageClass                    Classname of the page these callbacks are registered to
     * @param array $aSettingFields                 Setting fields for our admin page
     * @param string $sCustomCSSContent             User-submitted custom CSS | Developer-submitted default CSS
     * @param Lib_PC3WPQueryFacade $oWPQueryFacade  Query Facade returns posts from DB
     */
    public function __construct( $sPageClass, array $aSettingFields,
                                 $sCustomCSSContent, Lib_PC3WPQueryFacade $oWPQueryFacade) {

        $this->sPageClass   = $sPageClass;

        $this->aSettingFields   = $aSettingFields;
        $this->sCustomCSSContent   = $sCustomCSSContent;
        $this->oWPQueryFacade   = $oWPQueryFacade;

        // load_ + page slug
        add_action( 'load_' . $this->sPageClass, array( $this, 'replyToLoadPage' ) );
    }

    /**
     * Triggered when the tab is loaded.
     *
     * @since      0.7.0
     */
    public function replyToLoadPage( $oAdminPage ) {

        if( ! empty( $this->aSettingFields ) )
            foreach( $this->aSettingFields as &$oSettingField )

                // field_definition_{instantiated class name}_{section id}_{field_id}
                if( is_callable( array( $this,  'field_definition_' . $this->sPageClass . '_' . $oSettingField->getFieldID() ) )) {

                    call_user_func_array( array( $this,  'field_definition_' . $this->sPageClass . '_' . $oSettingField->getFieldID() ), array( &$oSettingField ) );
                }
    }

    /**
     * Callback method passed the submit_button field after the Sections have (or haven't) been returned
     * If sections have been found, Submit button is usable.  Otherwise, it remains disabled
     *
     * Note: method follows following naming pattern: field_definition_{instantiated class name}_{section id}_{field_id}
     *
     * @since      0.9.0
     * @param object $oSettingField     the field with an id of 'field__select_page'
     * @return mixed array     the field
     */
    public function field_definition_Admin_PC3SectionManagerPage_field__select_page( &$oSettingField ) {

        $aNewParameters = array();

        $aPages = $this->oWPQueryFacade->getPosts( array(
            'post_type' => 'page',
            'orderby'   => 'title',
            'order'     => 'ASC',
            'post_status'   => 'any',
            'posts_per_page' => -1
        ) );

        if( empty( $aPages ) ) {

            $oSettingField->setFieldParameters( $aNewParameters );
            return $oSettingField->setUpField();
        }

        $aNewParameters['description']  = __( 'Select a Page to be used for your One Page Sections', 'one-page-sections' );
        $aNewParameters['label'] = $this->_formatPagesAsLabels( $aPages );

        $oSettingField->setFieldParameters( $aNewParameters );
        return $oSettingField->setUpField();
    }

    /**
     * Callback method passed the sortable text fields.  Default field value is a label saying that no Sections were found.
     * If, however, sections are found, then field is updated with sections, and the user is free to rearrange them.
     *
     * Note: method follows following naming pattern: field_definition_{instantiated class name}_{section id}_{field_id}
     *
     * @since      0.9.0
     *
     * @param object $oSettingField     the field with an id of 'field__sortable_sections'
     * @return array array              the field
     */
    public function field_definition_Admin_PC3SectionManagerPage_field__sortable_sections( &$oSettingField ) { // field_definition_{instantiated class name}_{section id}_{field_id}

        $aNewParameters = array();

        $aPosts = $this->oWPQueryFacade->getSectionsByOrderASC();

        //return unmodified field if no sections were found
        if( empty( $aPosts ) ) {

            $oSettingField->setFieldParameters( $aNewParameters );
            return $oSettingField->setUpField();
        }

        //flag this as 'true'; Sections were found!
        $this->bIfSections = true;

        $aNewParameters['type']         = 'text';
        $aNewParameters['description']  = __( 'Reorder the sections to modify the order they appear on your page.', 'one-page-sections' );
        $aNewParameters['sortable']     = true;

        //first section must exist because array is not empty
        $_oFirstPost = array_shift( $aPosts );

        //first value must be set differently from any preceding values
        $aNewParameters = array_merge( $aNewParameters, $this->_returnSectionArray( $_oFirstPost->post_title, $_oFirstPost->ID ) );

        //stop here if only one section was found
        if( empty( $aPosts ) ) {

            $oSettingField->setFieldParameters( $aNewParameters );
            return $oSettingField->setUpField();
        }

        //return array of arrays containing Section values that will be inserted into our field
        $aFormattedPosts = $this->_formatPostsForField( $aPosts );

        foreach( $aFormattedPosts as $aPost ) {

            array_push( $aNewParameters, $aPost );
        }

        $oSettingField->setFieldParameters( $aNewParameters );
        return $oSettingField->setUpField();
    }

    /**
     * Callback method passed the content of our CSS file to fill in the form's CSS editor
     *
     * Note: method follows following naming pattern: field_definition_{instantiated class name}_{section id}_{field_id}
     *
     * @since      0.9.2
     * @param object $oSettingField     the field with an id of 'field__editor'
     * @return mixed array              the field
     */
    public function field_definition_Admin_PC3SectionManagerPage_field__editor( &$oSettingField ) {

        $oSettingField->setFieldParameters(
            array(
                'value' => $this->sCustomCSSContent
            )
        );

        return $oSettingField->setUpField();
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