<?php

/**
 * abstract AdminPageSetting class.
 * SettingFields extending this one can be passed to `Lib_PC3AdminPage` objects and they will be added to the page.
 *
 * @since      0.9.0
 * @package    One_Page_Sections
 * @subpackage One_Page_Sections/admin
 * @author     Paul Craig <paul@pcraig3.ca>
 */
abstract class Lib_PC3AdminPageSettingField {

    /**
     * The ID used to used to uniquely identify this field within a page.
     *
     * @since   0.9.0
     * @var     string
     */
    protected $sFieldID;

    /**
     * Associative array which describes everything about this field.
     * Things like a field's title, default value, type, etc.
     * -- everything except the field's ID is contained in the `$aFieldParameters` array
     *
     * @since   0.9.0
     * @var     array
     */
    protected $aFieldParameters;


    /**
     * String value becomes the $key for retrieving this `Lib_PC3AdminPageSettingField`'s $value from the container object
     *
     * Right, so the `Lib_PC3Container` object contains a bunch of application-wide variable parameters
     * that we don't want to make global.  Some of them are defined in its constructor.
     * However, we also want some of our parameters to correspond to the value of specific `Lib_PC3AdminPageSettingField`s
     * A string value provided as a `$sContainerParameterKey` will become its $key, and its value will be its $value in our container.
     * For example, if $sContainerParameterKey = 'key', then $container->getParameter('key') would return the value of this field
     * This way, we can use values from our `Lib_PC3AdminPageSettingField`s thoughtout our application without making them `global`
     *
     * If a null (or empty string) value is provided, this `Lib_PC3AdminPageSettingField`'s value will *not* be added to our container
     *
     * @since   0.9.0
     * @var     string|null
     */
    protected $sContainerParameterKey;

    /**
     * @since   0.9.0
     *
     * @param string $sFieldID              the field_id of this setting field
     * @param string $sContainerParameterKey
     *                          if a string is provided, the value of this SettingField will be added to
     *                          our container parameters under the string provided
     *                          if a `null` (or empty string) value is provided, this SettingField will *not* be added to our container
     * @param array $aFieldParameters       parameters to override our defaults
     */
    protected function __construct( $sFieldID, $sContainerParameterKey = null, array $aFieldParameters = array() ) {

        $this->sFieldID = $sFieldID;
        $this->sContainerParameterKey = $sContainerParameterKey;
        $this->aFieldParameters = $aFieldParameters;
    }

    /**
     * @since   0.9.0
     *
     * @return null|string
     */
    public function getContainerParameterKey() {

        return $this->sContainerParameterKey;
    }

    /**
     * @since   0.9.0
     *
     * @return string
     */
    public function getFieldID() {

        return $this->sFieldID;
    }

    /**
     * @since   0.9.0
     *
     * @return mixed
     */
    public function getDefaultVal() {

        return $this->aFieldParameters['default'];
    }

    /**
     * Add a 'field_id' key/value to the array of fieldParameters before returning them
     *
     * @since   0.9.0
     *
     * @return array
     */
    public function setUpField() {

        $aSetUpField = $this->aFieldParameters;
        $aSetUpField['field_id'] = $this->sFieldID;

        return $aSetUpField;
    }

    /**
     * Accept a new array and merge it with our current array of field parameters.
     * Any keys in the input array which match the keys current array will overwrite their values
     *
     * @since   0.9.0
     *
     * @param array $aNewFieldParameters    new values to add to this settingField, possibly overwriting existing values
     * @return array
     */
    public function setFieldParameters( array $aNewFieldParameters ) {

        $this->aFieldParameters = array_merge( $this->aFieldParameters, $aNewFieldParameters );
    }

}