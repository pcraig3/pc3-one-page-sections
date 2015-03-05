<?php
/**
 * Class defines our sortable 'section' fields, which dictate the order in which sections are rendered
 *
 * @since      0.9.0
 * @package    One_Page_Sections
 * @subpackage One_Page_Sections/admin
 * @author     Paul Craig <paul@pcraig3.ca>
 */
class Admin_PC3PageSortableSectionsField extends Lib_PC3AdminPageSettingField {

    /**
     * @param string $sFieldID              the field_id of this setting field
     * @param string $sContainerParameterKey
     *                          if a string is provided, the value of this SettingField will be added to
     *                          our container parameters under the string provided
     *                          if a `null` (or empty string) value is provided, this SettingField will *not* be added to our container
     * @param array $aFieldParameters       parameters to override our defaults
     */
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