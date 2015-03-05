<?php

/**
 * Class Lib_PC3PageSettingField
 *
 * @TODO
 */
abstract class Lib_PC3PageSettingField {

    protected $sFieldID;
    protected $aFieldParameters;
    protected $sContainerParameterKey;

    protected function __construct( $sFieldID, $sContainerParameterKey = null, array $aFieldParameters = array() ) {

        $this->sFieldID = $sFieldID;
        $this->sContainerParameterKey = $sContainerParameterKey;

        $this->aFieldParameters = $aFieldParameters;
    }

    public function getContainerParameterKey() {

        return $this->sContainerParameterKey;
    }

    public function getFieldID() {

        return $this->sFieldID;
    }

    public function getDefaultVal() {

        return $this->aFieldParameters['default'];
    }

    public function setUpField() {

        $aSetUpField = $this->aFieldParameters;
        $aSetUpField['field_id'] = $this->sFieldID;

        return $aSetUpField;
    }

}