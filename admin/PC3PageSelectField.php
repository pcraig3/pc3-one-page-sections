<?php
/**
 * Class defines a Select field meant to contain the names of pages on our site.
 *
 * @since      0.9.0
 * @package    One_Page_Sections
 * @subpackage One_Page_Sections/admin
 * @author     Paul Craig <paul@pcraig3.ca>
 */
class Admin_PC3PageSelectField extends Lib_PC3PageSettingField {

    public function __construct( $sFieldID, $sContainerParameterKey, array $aFieldParameters = array() ) {

        $aDefaultFieldParameters = array( // Single Drop-down List
            'title'         => __( 'One Page Sections Page', 'one-page-sections' ),
            'type'          => 'select',
            'label'         => array(
                0 => __( '---', 'one-page-sections' ),
            ),
            'description' => __( 'This select field should be filled with the names of pages from your site.',
                    'one-page-sections' )
                . ' ' . __( 'Please create at least one Page.', 'one-page-sections' ),
            'default' => 'one-page-sections'
        );

        $aMergedFieldParameters = array_merge( $aDefaultFieldParameters, $aFieldParameters );

        parent::__construct($sFieldID, $sContainerParameterKey, $aMergedFieldParameters);
    }
}