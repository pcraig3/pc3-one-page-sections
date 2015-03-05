<?php

/**
 * @TODO: Commenting
 *
 * @since      0.9.0
 * @package    One_Page_Sections
 * @subpackage One_Page_Sections/admin
 * @author     Paul Craig <paul@pcraig3.ca>
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

    public function setFieldParameters( array $aNewFieldParameters ) {

        $this->aFieldParameters = array_merge( $this->aFieldParameters, $aNewFieldParameters );
    }

}