<?php
/**
 * Created by PhpStorm.
 * User: Paul
 * Date: 30/01/2015
 * Time: 21:31
 */

class PC3_SectionManagerPage_Callbacks {

    /*
     * @TODO: Order of sections isn't preserved because of the dynamic callback thing.
     * @TODO: Comments
     * @TODO: Less hard-coding
     * THUS, we need some metadata keeping track of the order of the fields?
     * Maybe a hidden field that has a "{position}:{id}" storage?
     *
     */

    /**
     * Stores the caller class name, set in the constructor.
     */
    public $sClassName = 'PC3_SectionManagerPage';

    /**
     * The page slug to add the tab and form elements.
     */
    public $sPageSlug   = 'manage_sections';

    /**
     * The section slug to add to the tab.
     */
    public $sSectionID  = 'my_section_1';

    /**
     * Variable keeps track of whether or not sections were returned
     */
    private $bIfSections = false;

    /**
     * Sets up hooks and properties.
     */
    public function __construct( $sClassName='', $sPageSlug='' ) {

        $this->sClassName   = $sClassName ? $sClassName : $this->sClassName;
        $this->sPageSlug    = $sPageSlug ? $sPageSlug : $this->sPageSlug;

        // load_ + page slug
        add_action( 'load_' . $this->sPageSlug, array( $this, 'replyToLoadPage' ) );

    }

    /**
     * Triggered when the tab is loaded.
     */
    public function replyToLoadPage( $oAdminPage ) {

        /**
         * Fields to be defined with callback methods - pass only the required keys: 'field_id', 'section_id', and the 'type'.
         *
         * Create a hidden field with an announcement that no Sections were found.
         * If Sections are found, then this gets overridden
         */
        $oAdminPage->addSettingFields(
            $this->sSectionID,  // target section id
            array(
                'field_id'          => 'callback_example',
                'title'             => __( 'Section Titles', 'one-page-sections' ),
                'type'              => 'hidden',
                'default'           => '',
                // 'hidden' =>    true // <-- the field row can be hidden with this option.
                'label'             =>
                    __( 'Sorry, but I couldn\'t find any sections.  <br>:(', 'one-page-sections' ),
                'description'       => __( 'Maybe try <a href="http://pcraig3.dev/web/wp/wp-admin/post-new.php?post_type=pc3_section">adding a Section</a>?', 'one-page-sections' )
            )
        );

        // field_definition_{instantiated class name}_{section id}_{field_id}
        add_filter( 'field_definition_PC3_SectionManagerPage_my_section_1_callback_example', array( $this, 'field_definition_PC3_SectionManagerPage_my_section_1_callback_example' ) );


        // field_definition_{instantiated class name}_{section id}_{field_id}
        //{field_id} = submit_button
        add_filter( 'field_definition_PC3_SectionManagerPage_my_section_1_submit_button', array( $this, 'field_definition_PC3_SectionManagerPage_my_section_1_submit_button' ) );
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
    public function field_definition_PC3_SectionManagerPage_my_section_1_submit_button( $aField ) {

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
    public function field_definition_PC3_SectionManagerPage_my_section_1_callback_example( $aField ) { // field_definition_{instantiated class name}_{section id}_{field_id}

        $aPosts = $this->_getPosts( 'pc3_section' );

        //return unmodified field if no sections were found
        if( empty( $aPosts ) )
            return $aField;

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
     * Iterate through Post objects, turning each into an array that an APF Field will understand
     * Return all arrays in another array.
     *
     * @param $oPosts object    WordPress Post objects
     * @return array            an array of WordPress Post arrays
     */
    private function _formatPostsForField( $oPosts ) {

        $_aSectionTextFields = array();

        foreach( $oPosts as $_iIndex => $_oPost ) {
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

    /*
     *
     * private method ghost_function($array_gotten_after_submit)
     *
     * //where array looks like: callback_example] => Array ( [0] => 1902, [1] => 1903, [2] => 1904 )
     *update_option( 'pc3_plugin_name', $array_gotten_after_submit );
     *
     * }
     *
     * And that way we can call the option back.
     *
     */

}