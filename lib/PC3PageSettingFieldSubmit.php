<?php

/**
 * Class Lib_PC3PageSettingFieldSubmit
 *
 * @TODO
 */
class Lib_PC3PageSettingFieldSubmit {

    private $sFieldID;
    private $sTitle;
    private $sDefaultVal;
    private $sContainerParameterKey;

    function __construct( $sFieldID, $sTitle, $sDefaultVal = null, $sContainerParameterKey = null ) {

        $this->sFieldID = $sFieldID;
        $this->sTitle = $sTitle;

        $this->sDefaultVal = $sDefaultVal;
        $this->sContainerParameterKey = $sContainerParameterKey;
    }

    //has a method that returns a settings array
    //has a callback method
    //has a field_id
    //has a default/init. value

    public function getFieldArray() {

        $aSetUpField =  array( // Repeatable radio buttons
            'field_id'      => $this->sFieldID,
            'title'         => __( $this->sTitle, 'one-page-sections' ),
            'type'          => 'submit',
            'attributes'    => array(
                'class'     => 'button button-primary'
            )
        );

        if( ! is_null( $this->sDefaultVal ) )
            $aSetUpField['default'] = $this->sDefaultVal;


        return $aSetUpField;
    }

    public function getContainerParameterKey() {

        return $this->sContainerParameterKey;
    }

    public function getFieldID() {

        return $this->sFieldID;
    }

    public function getDefaultVal() {

        return $this->sDefaultVal;
    }
}