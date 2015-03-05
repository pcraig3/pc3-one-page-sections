<?php
/**
 * Class defines a 'Debug' radio button setting field.
 * Debug flag can be turned on or off
 *
 * @since      0.9.0
 * @package    One_Page_Sections
 * @subpackage One_Page_Sections/admin
 * @author     Paul Craig <paul@pcraig3.ca>
 */
class Admin_PC3PageRadioBinaryField extends Lib_PC3AdminPageSettingField {

    /**
     * @param string $sFieldID              the field_id of this setting field
     * @param string $sContainerParameterKey
     *                          if a string is provided, the value of this SettingField will be added to
     *                          our container parameters under the string provided
     *                          if a `null` (or empty string) value is provided, this SettingField will *not* be added to our container
     * @param array $aFieldParameters       parameters to override our defaults
     */
    public function __construct( $sFieldID, $sContainerParameterKey, array $aFieldParameters = array() ) {

        $aDefaultFieldParameters = array( // Repeatable radio buttons
            'title' => __('Radio Binary Title', 'one-page-sections'),
            'type' => 'radio',
            'label' => array(
                0 => 'No',
                1 => 'Yes',
            ),
            'default' => 1
        );

        $aMergedFieldParameters = array_merge( $aDefaultFieldParameters, $aFieldParameters );

        parent::__construct($sFieldID, $sContainerParameterKey, $aMergedFieldParameters);
    }
}