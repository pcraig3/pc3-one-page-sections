<?php

/**
 * Class Lib_PC3PageSubmitField
 *
 * @TODO
 */
class Lib_PC3PageSubmitField extends Lib_PC3PageSettingField {

    public function __construct( $sFieldID, array $aFieldParameters = array() ) {

        $aDefaultFieldParameters = array( // Repeatable radio buttons
            'type'          => 'submit',
            'attributes'    => array(
                'class'     => 'button button-primary'
            )
        );

        //@TODO ARRAY_MERGE
        $aMergedFieldParameters = $aDefaultFieldParameters;

        parent::__construct($sFieldID, null, $aMergedFieldParameters);
    }
}