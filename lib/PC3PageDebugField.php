<?php

/**
 * Class Lib_PC3PageDebugField
 *
 * @TODO
 */
class Lib_PC3PageDebugField extends Lib_PC3PageSettingField {

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

        //@TODO ARRAY_MERGE
        $aMergedFieldParameters = $aDefaultFieldParameters;

        parent::__construct($sFieldID, $sContainerParameterKey, $aMergedFieldParameters);
    }
}