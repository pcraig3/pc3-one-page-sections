<?php
/**
 * Class defines a 'CSS editor' field which we use to change the content of a custom CSS file.
 *
 * @since      0.9.0
 * @package    One_Page_Sections
 * @subpackage One_Page_Sections/admin
 * @author     Paul Craig <paul@pcraig3.ca>
 */
class Admin_PC3PageACEEditorField extends Lib_PC3PageSettingField {

    public function __construct( $sFieldID, array $aFieldParameters = array() ) {

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

        parent::__construct($sFieldID, null, $aMergedFieldParameters);

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