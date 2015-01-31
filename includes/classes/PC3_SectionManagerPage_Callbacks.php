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
                'type'              => 'text',
                'default'           => 'FAKE ENTRY',
                'label'             => 1 . ' :',
                'attributes'        => array(
                    'size' => 20,
                    'readonly'  => false,
                ),

                //'delimiter'         => '<br />',
            )
        );

        // field_definition_{instantiated class name}_{section id}_{field_id}
        add_filter( 'field_definition_PC3_SectionManagerPage_my_section_1_callback_example', array( $this, 'field_definition_PC3_SectionManagerPage_my_section_1_callback_example' ) );

    }

    /*
     *
     * array( // Multiple text fields
                'field_id'          => 'text_multiple',
                'title'             => __( 'Multiple', 'admin-page-framework-demo' ),
                'help'              => __( 'Multiple text fields can be set by passing an array to the <code>label</code> argument.', 'admin-page-framework-demo' ),
                'type'              => 'text',
                'default'           => __( 'Hello world!', 'admin-page-framework-demo' ),
                'label'             => __( 'First', 'admin-page-framework-demo' ) . ': ',
                'attributes'        => array(
                    'size' => 20,
                ),
                'capability'        => 'manage_options',
                'delimiter'         => '<br />',
                array(
                    'default'       => 'Foo bar',
                    'label'         => __( 'Second', 'admin-page-framework-demo' ) . ': ',
                    'attributes'    => array(
                        'size' => 40,
                    )
                ),
                array(
                    'default'       => __( 'Yes, we can', 'admin-page-framework-demo' ),
                    'label'         => __( 'Third', 'admin-page-framework-demo' ) . ': ',
                    'attributes'    => array(
                        'size' => 60,
                    )
                ),
                'description'       => __( 'These are multiple text fields. To include multiple input fields associated with one field ID, use the numeric keys in the field definition array.', 'admin-page-framework-demo' ),
            ),
     *
     *
     *
     */

    /*
     * Field callback methods - for field definitions that require heavy tasks should be defined with the callback methods.
     */
    public function field_definition_PC3_SectionManagerPage_my_section_1_callback_example( $aField ) { // field_definition_{instantiated class name}_{section id}_{field_id}

        $aField['title']        = __( 'Section Titles', 'one-page-sections' );
        $aField['description']  = sprintf( __( 'This description is inserted with the callback method: <code>%1$s</code>.', 'one-page-sections' ), __METHOD__ );


        $secs_array = array(
            array(
                'default'       => 'Bottom',
                'label'         => 1904,
                'attributes'    => array(
                    'size'      => 25,
                    'readonly'  => true
                )
            ),
            array(
                'default'       => 'Middle',
                'label'         => 1903,
                'attributes'    => array(
                    'size'      => 30,
                    'readonly'  => true
                )
            ),
            array(
                'default'       => 'Top',
                'label'         => 1902,
                'attributes'    => array(
                    'size'      => 35,
                    'readonly'  => true
                )
            )
        );

        $secs_array = $this->_getPostTitles();
        foreach($secs_array as $sec) {

            array_push($aField, $sec);
        }

        //$aField        = $this->_getPostTitles();
        return $aField;

    }

    private function _getPostTitles( $sPostTypeSlug='pc3_section' ) {

        $_aArgs         = array(
            'post_type' => $sPostTypeSlug,
        );
        $_oResults      = new WP_Query( $_aArgs );

        $_aSectionTextFields = array();
        $size = 20;

        foreach( $_oResults->posts as $_iIndex => $_oPost ) {
            $size +=10;
            array_push($_aSectionTextFields, $this->_returnSectionArray($_oPost->post_title, $_oPost->ID, $size));
        }
        return $_aSectionTextFields;

    }

    private function _returnSectionArray( $post_title, $label, $size ) {

        return array (

            'default'           => $post_title,
            'label'             => intval($label) . ' :',
            'attributes'        => array(
                'size' => intval($size),
                'readonly'  => true
            )
        );


    }


}