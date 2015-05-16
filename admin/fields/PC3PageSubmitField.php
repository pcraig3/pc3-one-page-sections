<?php

/**
 * Class defines a 'Submit' button setting field.
 *
 * @since      0.9.0
 * @package    One_Page_Sections
 * @subpackage One_Page_Sections/admin
 * @author     Paul Craig <paul@pcraig3.ca>
 */
class Admin_Fields_PC3PageSubmitField extends Lib_PC3AdminPageSettingField {

    /**
     * @since      0.9.0
     *
     * @param string $sFieldID              the field_id of this setting field
     * @param array $aFieldParameters       parameters to override our defaults
     */
    public function __construct( $sFieldID, array $aFieldParameters = array() ) {

        $aDefaultFieldParameters = array( // Repeatable radio buttons
            'type'          => 'submit',
            'attributes'    => array(
                'disabled'  => 'disabled',
                'class'     => 'button'
            )
        );

        $aMergedFieldParameters = array_merge( $aDefaultFieldParameters, $aFieldParameters );

        parent::__construct($sFieldID, null, $aMergedFieldParameters);
    }
}