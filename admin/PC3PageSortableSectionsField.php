<?php
/**
 * Class defines our sortable 'section' fields, which dictate the order in which sections are rendered
 *
 * @since      0.9.0
 * @package    One_Page_Sections
 * @subpackage One_Page_Sections/admin
 * @author     Paul Craig <paul@pcraig3.ca>
 */
class Admin_PC3PageSortableSectionsField extends Lib_PC3PageSettingField {

    public function __construct( $sFieldID, $sContainerParameterKey, array $aFieldParameters = array() ) {

        $aDefaultFieldParameters = array(
            'title'             => __( 'Section Titles', 'one-page-sections' ),
            'type'              => 'hidden',
            'default'           => '',
            'label'             =>
                __( 'Sorry, but I couldn\'t find any sections.  <br>:(', 'one-page-sections' ),
            'description'       => __( 'Maybe try adding a Section?', 'one-page-sections' )
        );

        $aMergedFieldParameters = array_merge( $aDefaultFieldParameters, $aFieldParameters );

        parent::__construct($sFieldID, $sContainerParameterKey, $aMergedFieldParameters);
    }
}