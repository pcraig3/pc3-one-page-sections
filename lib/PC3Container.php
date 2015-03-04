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

    private $aParameters = array();
    private $aModifiableParameters = array();

    private $wpQueryFacade;
    private $cssFileEditor;
    private $templateLoader;
    private $functionsFacade;

    //@TODO: field names for page :/
    function __construct() {

        $this->aParameters = array(
            'section__slug'                 => 'pc3_section',
            'section__meta_key'             => 'order',
            'page__sections'                => 'one-page-sections',
            'page__manage'                  => 'manage_sections',
            'page__settings'                => 'pc3_settings',
            'template__post'                => 'post-pc3_section.php',
            'template__page'                => 'page-pc3_section.php',
            'debug'                         => 0
        );

        //keys with values that correspond to $aParameter keys will be modifiable.
        //so, for example, if we want to be able to modify 'page__sections',
        //we could set $aModifiableParameters['set_sections_page'] => 'page__sections'
        //and then call $container->setParameter('set_sections_page', 'new-sections-page');

        $this->aModifiableParameters = array(

            'manage_sections__sections_page' => 'page__sections',
        );

    }

    public function getParameter( $sParameter ) {

        //null if parameter doesn't exist
        $sParameter = $this->aParameters[$sParameter];

        if( is_null( $sParameter ) )
            self::throwExceptionIfParameterNotFound($sParameter);

        return $sParameter;
    }

    public function printParametersToScreen() {

        //change this to $debug
        if( true )
            var_dump( $this->aParameters );
    }

    public function addParameter( Lib_PC3AdminPageField $oField, array $aAdminPageClassnames ) {

        //get name
        $sKey = $oField->getContainerParameterKey();

        //get value
        $sVal = null;

        while( ! empty( $aAdminPageClassnames ) && is_null( $sVal ))
            $sVal = PC3_AdminPageFramework::getOption( array_shift( $aAdminPageClassnames ), $oField->getFieldID() );

        //if not, get default value
        if( is_null( $sVal ) )
            $sVal = $oField->getDefaultVal();

        $this->aParameters[$sKey] = $sVal;

    }

    public function setParameter( $sParameter, $value, $ifOverwriteExisting = true) {

        //key => handle
        //value => key of aParameters.

        $ParameterKey = $this->aModifiableParameters[$sParameter];

        //first, make sure that this is a parameter which can be overwritten
        if( is_null( $ParameterKey ) )
            self::throwExceptionIfParameterNotFound( $sParameter );

        //if we don't want to overwrite a potential existing parameter, we return if a value is found for a key
        if( $ifOverwriteExisting === false )
            if( ! is_null( $this->aParameters[$ParameterKey] ))
                return false;

        $this->aParameters[$ParameterKey] = $value;
        return true;
    }

    public function getWPQueryFacade() {

            if (!isset($this->wpQueryFacade)) {

                $this->wpQueryFacade = new Lib_PC3WPQueryFacade(
                    $this->getParameter('section__slug'),
                    $this->getParameter('section__meta_key')
                );
            }

            return $this->wpQueryFacade;
    }



    public function getCSSFileEditor() {

        if (!isset($this->cssFileEditor)) {

            $this->cssFileEditor = new Lib_PC3CSSFileEditor( ONE_PAGE_SECTIONS_DIR_PATH . 'public/css/one-page-sections-public.css' );
        }

        return $this->cssFileEditor;
    }

    public function getPC3TemplateLoader() {

        if (!isset($this->templateLoader)) {

            $this->templateLoader = new Lib_PC3TemplateLoader();
        }

        return $this->templateLoader;
    }

    public function getFunctionsFacade() {

        if (!isset($this->functionsFacade)) {

            $this->functionsFacade = new Lib_PC3FunctionsFacade();
        }

        return $this->functionsFacade;
    }

    //@TODO: ahem, https://github.com/toppa/Toppa-libs/blob/master/ToppaFunctions.php
    public static function throwExceptionIfParameterNotFound($expectedString) {

            throw new Exception(__('"' . $expectedString . '" not a valid config parameter', 'one-page-sections'));
    }
}