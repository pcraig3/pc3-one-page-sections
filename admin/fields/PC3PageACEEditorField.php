<?php
/**
 * Class defines a 'CSS editor' field which we use to change the content of a custom CSS file.
 *
 * @since      0.9.1
 * @package    One_Page_Sections
 * @subpackage One_Page_Sections/admin
 * @author     Paul Craig <paul@pcraig3.ca>
 */
class Admin_Fields_PC3PageACEEditorField extends Lib_PC3AdminPageSettingField {

    /**
     * @since      0.9.1
     *
     * @param string $sFieldID          the field_id of this setting field
     * @param string $sContainerParameterKey
     *                          if a string is provided, the value of this SettingField will be added to
     *                          our container parameters under the string provided
     *                          if a `null` (or empty string) value is provided, this SettingField will *not* be added to our container
     * @param array $aFieldParameters   parameters to override our defaults
     */
    public function __construct( $sFieldID, $sContainerParameterKey, array $aFieldParameters = array() ) {

        $aDefaultFieldParameters =         array(  // Ace Custom Field
            'title'             => __('CSS Editor', 'one-page-sections' ),
            'description'       => __('Custom CSS goes here.', 'one-page-sections' ),
            'type'              => 'ace',
            //'default'           => '.abc { color: #fff; }',
            'attributes' =>  array(
                'cols'          =>  96,
                'rows'          =>  14,
            ),
            'options'    => array(
                'language'      => 'css', // available languages https://github.com/ajaxorg/ace/tree/master/lib/ace/mode
                'theme'         => 'dreamweaver', //available themes https://github.com/ajaxorg/ace/tree/master/lib/ace/theme
                'gutter'        => true,
                'readonly'      => false,
                'fontsize'      => 14,
            )
        );

        $aMergedFieldParameters = array_merge( $aDefaultFieldParameters, $aFieldParameters );

        parent::__construct($sFieldID, $sContainerParameterKey, $aMergedFieldParameters);

        $this->registerFieldTypes();
    }

    /**
     * Register custom field types.
     *
     * @since      0.8.0
     */
    private function registerFieldTypes() {

        if ( ! class_exists('AceCustomFieldType') )
            require_once ONE_PAGE_SECTIONS_DIR_PATH . 'vendor/AceCustomFieldType/AceCustomFieldType.php';

        new AceCustomFieldType();
    }
}