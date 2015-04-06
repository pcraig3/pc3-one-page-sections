<?php

/**
 * My attempt at a dependency injection container.
 * Heavily modelled on one used by Mike Toppa in his Shashin plugin
 *
 * @see: https://github.com/toppa/Shashin/blob/master/lib/ShashinContainer.php
 * @see: http://www.toppa.com/2013/dependency-injection-for-wordpress-plugins/
 *
 * @since      0.8.0
 *
 * @package    One_Page_Sections
 * @subpackage One_Page_Sections/lib
 */
class Lib_PC3Container {

    /**
     * Array of parameters needed in various places by our plugin
     *
     * @since   0.8.0
     * @var     array
     */
    private $aParameters = array();

    private $wpQueryFacade;
    private $templateLoader;
    private $functionsFacade;

    /**
     * Initialises our array of parameters with a bunch of default values.
     * Change them here, and they should change everywhere in the application
     * (with the notable exception of some function names, but c'est la vie)
     *
     * @since   0.9.1
     */
    function __construct() {

        $this->aParameters = array(
            'section__slug'                 => 'pc3_section',
            'section__meta_key'             => 'order',
            'page__manage'                  => 'manage_sections',
            'page__settings'                => 'pc3_settings',
            'template__post'                => 'post-pc3_section.php',
            'template__page'                => 'page-pc3_section.php',
            'css--default_content'          =>  '/* Enter custom CSS rules here for your One Page Sections page */',

            //both of these are overwritten in `One_Page_Sections->define_admin_hooks`
            'page__sections'                => 'one-page-sections',
            'debug'                         => 0,
            'vendor__sticky'                => 1,
            'vendor__page_scroll_to_id'     => 1,
            'vendor__pure'                  => 1,
        );
    }

    /**
     * input string is used as the key for the `aParameters` array
     * if `aParameters[$sParameter]` returns a value, it is returned
     * else, an exception is thrown
     *
     * @since   0.9.0
     *
     * @param string $sParameter    $key of parameter
     * @return mixed                $value of parameter
     * @throws Exception            if $key doesn't correspond to a parameter $value, an exception is thrown
     */
    public function getParameter( $sParameter ) {

        //null if parameter doesn't exist
        $sParameter = $this->aParameters[$sParameter];

        if( is_null( $sParameter ) )
            $this->getFunctionsFacade()->throwExceptionIfParameterNotFound( $sParameter );

        return $sParameter;
    }

    /**
     * Iterates through an array of `Lib_PC3AdminPageSettingField`s and calls
     * `->addParameter()` on each of them
     *
     * @since   0.9.0
     *
     * @param array $aFields                array of `Lib_PC3AdminPageSettingField`s
     * @param array $aAdminPageClassnames   array of AdminPage Classnames
     */
    public function addSettingFieldsAsParameters( array $aFields, array $aAdminPageClassnames ) {

        while( ! empty( $aFields ) )
            $this->addParameter( array_shift( $aFields ), $aAdminPageClassnames);
    }

    /**
     * Method adds the values of `Lib_PC3AdminPageSettingField`s to our array of parameters, possibly overwriting
     * existing defaults.
     *
     * Algorithm followed:
     * 1.   The `Lib_PC3AdminPageSettingField` key is checked for a non-null, non-empty `containerParameterKey`
     * 2.   If an acceptable key is found, attempt to retrieve existing options
     *      2.1.    The `PC3_AdminPageFramework::getOption` method needs the ID of the field, as well as the classname
     *              of the page it is registered to, so iterate through the array of AdminPage classnames and try to
     *              return a value for this fieldID
     *      2.2.    If a value is returned, stop iterating and move on.
     * 3.   If no value is returned, get the default value for this field
     * 4.   If there is no default value, throw an exception.
     * 5.   Else, add the value to the array of parameters under the provided `containerParameterKey`
     *
     * @since   0.9.0
     *
     * @param Lib_PC3AdminPageSettingField $oField
     * @param array $aAdminPageClassnames
     */
    private function addParameter( Lib_PC3AdminPageSettingField $oField, array $aAdminPageClassnames ) {

        //get name
        $sKey = $oField->getContainerParameterKey();

        if( is_null( $sKey ) || $sKey === '' )
            return;

        //get value
        $sVal = null;

        while( ! empty( $aAdminPageClassnames ) && is_null( $sVal ))
            $sVal = PC3_AdminPageFramework::getOption( array_shift( $aAdminPageClassnames ), $oField->getFieldID() );

        //if not, get default value
        if( is_null( $sVal ) )
            $sVal = $oField->getDefaultVal();

        if( is_null( $sVal ) )
            $this->getFunctionsFacade()->throwExceptionIfDefaultValueNotFound( $oField );

        $this->aParameters[$sKey] = $sVal;
    }

    /**
     * prints `$aParameters` to the screen if $aParameters['debug'] is not '0'
     *
     * @since   0.9.0
     */
    public function printParametersToScreen() {

        if( 0 !== intval( $this->getParameter('debug') ) )
            var_dump( $this->aParameters );
    }

    /**
     * @since   0.9.0
     *
     * @return Lib_PC3WPQueryFacade
     */
    public function getWPQueryFacade() {

            if (!isset($this->wpQueryFacade)) {

                $this->wpQueryFacade = new Lib_PC3WPQueryFacade(
                    $this->getParameter('section__slug'),
                    $this->getParameter('section__meta_key')
                );
            }

            return $this->wpQueryFacade;
    }

    /**
     * @since   0.9.0
     *
     * @return Lib_PC3TemplateLoader
     */
    public function getPC3TemplateLoader() {

        if (!isset($this->templateLoader)) {

            $this->templateLoader = new Lib_PC3TemplateLoader();
        }

        return $this->templateLoader;
    }

    /**
     * @since   0.9.0
     *
     * @return Lib_PC3FunctionsFacade
     */
    public function getFunctionsFacade() {

        if (!isset($this->functionsFacade)) {

            $this->functionsFacade = new Lib_PC3FunctionsFacade();
        }

        return $this->functionsFacade;
    }
}