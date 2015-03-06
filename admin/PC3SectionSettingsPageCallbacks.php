<?php
/**
 * The file that handles the logic for the callbacks on the Settings Page
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

    /**
     * @since      0.9.0
     *
     * @param string $sPageClass                    Classname of the page these callbacks are registered to
     * @param array $aSettingFields                 Setting fields for our admin page
     */
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
            foreach( $this->aSettingFields as &$oSettingField )

                // field_definition_{instantiated class name}_{section id}_{field_id}
                if( is_callable( array( $this,  'field_definition_' . $this->sPageClass . '_' . $oSettingField->getFieldID() ) )) {

                    call_user_func_array( array( $this,  'field_definition_' . $this->sPageClass . '_' . $oSettingField->getFieldID() ), array( &$oSettingField ) );
                }
    }

    /**
     * By default, the submit button is disabled.  Callback enables the button.
     * Note: method follows following naming pattern: field_definition_{instantiated class name}_{section id}_{field_id}
     *
     * @since      0.9.0
     * @param $oSettingField $aField    the field with an id of 'field__submit'
     * @return mixed array              the field
     */
    public function field_definition_Admin_PC3SectionSettingsPage_field__submit( &$oSettingField  ) {

        $aNewParameters = array(
            'attributes' => array(
                'class' => 'button button-primary'
            )
        );

        $oSettingField->setFieldParameters( $aNewParameters );
        return $oSettingField->setUpField();
    }

    }