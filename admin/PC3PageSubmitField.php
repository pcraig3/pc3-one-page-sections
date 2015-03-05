<?php

/**
 * Class defines a 'Submit' button setting field.
 *
 * @since      0.9.0
 * @package    One_Page_Sections
 * @subpackage One_Page_Sections/admin
 * @author     Paul Craig <paul@pcraig3.ca>
 */
class Admin_PC3PageSubmitField extends Lib_PC3PageSettingField {

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