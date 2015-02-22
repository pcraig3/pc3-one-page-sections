<?php
/**
 * Created by PhpStorm.
 * User: Paul
 * Date: 21/02/2015
 * Time: 20:45
 */

class Lib_PC3Container {

    private $aParameters = array();

    //@TODO: field names for page :/

    //@TODO: mayhaps some post template variables

    function __construct( array $defaults = null ) {

        $_aParameters = apply_filters( 'pc3_container_args', array(
            'custom_post_type__name'        => 'pc3_section',
            'custom_post_type__meta_key'    => 'order',
            'page__manage'                  => 'manage_sections'
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

    //@TODO: ahem, https://github.com/toppa/Toppa-libs/blob/master/ToppaFunctions.php
    public static function throwExceptionIfParameterNotFound($expectedString) {
        if (!is_string($expectedString)) {
            throw new Exception(__('\'' . $expectedString . '\' not a valid config parameter', 'one-page-sections'));
        }
        return true;
    }
}