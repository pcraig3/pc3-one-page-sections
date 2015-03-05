<?php
/**
 * The file that handles the logic for the callbacks on the Settings Page
 *
 * Submit button is enabled, just
 *
 * @since      0.9.0
 * @package    One_Page_Sections
 * @subpackage One_Page_Sections/admin
 * @author     Paul Craig <paul@pcraig3.ca>
 */
class Admin_PC3SectionSettingsPageCallbacks {

    /**
     * @since      0.9.0
     *
     * Stores the caller class name, set in the constructor.
     */
    public $sPageClass;

    public $aSettingFields;

    private $oCurrentSetting;

    public function __construct( $sPageClass, array $aSettingFields ) {

        $this->sPageClass   = $sPageClass;
        $this->aSettingFields = $aSettingFields;


        // load_ + page slug
        add_action( 'load_' . $this->sPageClass, array( $this, 'replyToLoadPage' ) );
    }

    /**
     * Triggered just after the Settings page is loaded.
     *
     * @since      0.9.0
     */
    public function replyToLoadPage( $oAdminPage ) {

        if( ! empty( $this->aSettingFields ) )
            foreach( $this->aSettingFields as $this->oCurrentSetting )

                // field_definition_{instantiated class name}_{section id}_{field_id}
                if( is_callable( array( $this,  'field_definition_' . $this->sPageClass . '_' . $this->oCurrentSetting->getFieldID() ) )) {

                    add_filter('field_definition_' . $this->sPageClass . '_' . $this->oCurrentSetting->getFieldID(),
                        array($this, 'field_definition_' . $this->sPageClass . '_' . $this->oCurrentSetting->getFieldID() ) );
                }

    }

    /**
     * @TODO: Callback method passed the submit_button field after the Sections have (or haven't) been returned
     * If sections have been found, Submit button is usable.  Otherwise, it remains disabled
     *
     * Note: method follows following naming pattern: field_definition_{instantiated class name}_{section id}_{field_id}
     *
     * @since      0.3.0
     * @param $aField array    the field with an id of 'manage_sections__submit'
     * @return mixed array     the field
     */
    public function field_definition_Admin_PC3SectionSettingsPage_field__submit( $aField ) {

        $aNewParameters = array(
            'attributes' => array(
                'class' => 'button button-primary'
            )
        );

        $this->oCurrentSetting->setFieldParameters( $aNewParameters );
        return $this->oCurrentSetting->setUpField();
    }
}