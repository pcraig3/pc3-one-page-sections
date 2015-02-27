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

    private $wpQueryFacade;
    private $cssFileEditor;

    //@TODO: field names for page :/
    function __construct() {

        $_aParameters = apply_filters( 'pc3_container_args', array(
            'section__slug'                 => 'pc3_section',
            'section__meta_key'             => 'order',
            'page__sections'                => 'one-page-sections',
            'page__manage'                  => 'manage_sections',
            'template__post'                => 'post-pc3_section.php',
            'template__page'                => 'page-pc3_section.php',
            'debug'                         => true
        ) );

        $this->aParameters = $_aParameters;
    }

    public function getParameter( $sParameter ) {

        //null if parameter doesn't exist
        $sParameter = $this->aParameters[$sParameter];

        if( is_null( $sParameter ) )
            self::throwExceptionIfParameterNotFound($sParameter);

        return $sParameter;
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

    //@TODO: ahem, https://github.com/toppa/Toppa-libs/blob/master/ToppaFunctions.php
    public static function throwExceptionIfParameterNotFound($expectedString) {
        if (!is_string($expectedString)) {
            throw new Exception(__('\'' . $expectedString . '\' not a valid config parameter', 'one-page-sections'));
        }
        return true;
    }
}