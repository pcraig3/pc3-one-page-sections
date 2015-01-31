<?php
/**
 * Created by PhpStorm.
 * User: Paul
 * Date: 30/01/2015
 * Time: 21:31
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
     * The tab slug to add to the page.
     *
    public $sTabSlug    = 'callbacks';

    /**
     * The section slug to add to the tab.
     */
    public $sSectionID  = 'my_section_1';

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
         */
        $oAdminPage->addSettingFields(
            $this->sSectionID,  // target section id
            array(
                'field_id'          => 'callback_example',
                'type'              => 'select',
            )
        );

        // field_definition_{instantiated class name}_{section id}_{field_id}
        add_filter( 'field_definition_PC3_SectionManagerPage_my_section_1_callback_example', array( $this, 'field_definition_PC3_SectionManagerPage_my_section_1_callback_example' ) );

    }

    /*
     * Field callback methods - for field definitions that require heavy tasks should be defined with the callback methods.
     */
    public function field_definition_PC3_SectionManagerPage_my_section_1_callback_example( $aField ) { // field_definition_{instantiated class name}_{section id}_{field_id}

        $aField['title']        = __( 'Post Titles', 'one-page-sections' );
        $aField['description']  = sprintf( __( 'This description is inserted with the callback method: <code>%1$s</code>.', 'one-page-sections' ), __METHOD__ );
        $aField['label']        = $this->_getPostTitles();
        return $aField;

    }

    private function _getPostTitles( $sPostTypeSlug='pc3_section' ) {

        $_aArgs         = array(
            'post_type' => $sPostTypeSlug,
        );
        $_oResults      = new WP_Query( $_aArgs );
        $_aPostTitles   = array();
        foreach( $_oResults->posts as $_iIndex => $_oPost ) {
            $_aPostTitles[ $_oPost->ID ] = $_oPost->post_title;
        }
        return $_aPostTitles;

    }


}