<?php

//get the debug thing to work
class Lib_PC3AdminPageField {

    private $sFieldID;
    private $sTitle;
    private $sDefaultVal;
    private $sContainerParameterKey;

    function __construct( $sFieldID, $sTitle, $sContainerParameterKey = '', $sDefaultVal = null ) {

        $this->sFieldID = $sFieldID;
        $this->sTitle = $sTitle;

        $this->sContainerParameterKey = ( $sContainerParameterKey !== '' ) ? $sContainerParameterKey : $sFieldID;
        $this->$sDefaultVal = $sDefaultVal;
    }

    //has a method that returns a settings array
    //has a callback method
    //has a field_id
    //has a default/init. value

    public function getFieldArray() {

        $aSetUpField =  array( // Repeatable radio buttons
            'field_id'      => $this->sFieldID,
            'title'         => __( $this->sTitle, 'one-page-sections' ),
            'type'          => 'radio',
            'label'         => array(
                0 => 'No',
                1 => 'Yes'
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