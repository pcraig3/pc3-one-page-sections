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
class Admin_PC3PageDebugField extends Lib_PC3PageSettingField {

    public function __construct( $sFieldID, $sContainerParameterKey, array $aFieldParameters = array() ) {

        $aDefaultFieldParameters = array( // Repeatable radio buttons
            'title' => __('Debug', 'one-page-sections'),
            'type' => 'radio',
            'label' => array(
                0 => 'No',
                1 => 'Yes',
            ),
            'default' => 0
        );

        $aMergedFieldParameters = array_merge( $aDefaultFieldParameters, $aFieldParameters );

        parent::__construct($sFieldID, $sContainerParameterKey, $aMergedFieldParameters);
    }
}