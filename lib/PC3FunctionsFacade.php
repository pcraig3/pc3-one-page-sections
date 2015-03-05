<?php

/**
 * My attempt at a functions facade
 * Heavily modelled on one used by Mike Toppa in his Shashin plugin
 *
 * @see: https://github.com/toppa/Shashin/blob/master/lib/ShashinFunctions.php
 *
 * @since      0.9.0
 *
 * @package    One_Page_Sections
 * @subpackage One_Page_Sections/lib
 */
class Lib_PC3FunctionsFacade {


    //function that accepts a post object and a string.
    //looks for if the post object's ID or title or name matches.
    public function isMatchesPostObject($sNeedle, $oHaystack) {

        $bMatched = false;

        if( ! is_null( $oHaystack )  ) {

            if( intval( $oHaystack->ID ) === intval( $sNeedle ) ||
                $oHaystack->post_name === $sNeedle ||
                $oHaystack->post_title === $sNeedle ) {

                $bMatched = true;
            }
        }

        return $bMatched;
    }

    public function getSiteUrl($pathToAppendToUrl = null, $scheme = null) {
        if (!$scheme && isset($_SERVER['HTTPS'])) {
            $scheme = 'https';
        }

        return site_url($pathToAppendToUrl, $scheme);
    }

    public function getAdminUrl($pathToAppendToUrl = null, $scheme = null) {
        if (!$scheme && is_ssl()) {
            $scheme = 'https';
        }

        elseif (!$scheme) {
            $scheme = 'admin';
        }

        return admin_url($pathToAppendToUrl, $scheme);
    }

    public function getUrlForCustomizableFile($fileName, $baseFile, $relativePath = null) {
        if (file_exists(get_stylesheet_directory() . '/' . $fileName)) {
            $url = get_bloginfo('stylesheet_directory') . '/' . $fileName;
        }

        else {
            $url = $this->getPluginsUrl($relativePath . $fileName, $baseFile);
        }

        return $url;
    }

    public function getPluginsUrl($relativePath, $baseFile) {
        return plugins_url($relativePath, $baseFile);
    }

    public function getPluginsPath() {
        return WP_PLUGIN_DIR;
    }

    public function getBasePath() {
        return ABSPATH;
    }

    public function getPluginDirectoryName($path) {
        return dirname(plugin_basename($path));
    }

    public function localizeScript($handle, $objectName, $data) {
        wp_localize_script($handle, $objectName, $data);
    }

    public function getGlobalWPQueryObject() {
        global $wp_query;
        return $wp_query;
    }

    public function getGlobalPostObject() {
        global $post;
        return $post;
    }


    public function setShortcodeAttributes(array $shortcodeDefaults, $userShortcode) {
        return shortcode_atts($shortcodeDefaults, $userShortcode);
    }

    /*
     * WordPress function checks for invalid UTF-8, Convert single < characters to entity,
     * strip all tags,remove line breaks, tabs and extra white space, strip octets.
     */
    public function sanitizeString($string) {
        return sanitize_text_field($string);
    }

    /*
     * Encodes < > & " ' (less than, greater than, ampersand, double quote, single quote).
     * Will never double encode entities.
     */
    public function htmlSpecialCharsOnce($string) {
        return esc_attr($string);
    }

    public function escHtml($string) {
        return esc_html($string);
    }

    public function dateI18n($dateFormat, $timestamp = false, $convertToGmt = false) {
        return date_i18n($dateFormat, $timestamp, $convertToGmt);
    }

    // File system functions
    public function checkFileExists($path) {
        return file_exists($path);
    }

    /**
     * Takes a string, throws an exception.
     *
     * @see: https://github.com/toppa/Toppa-libs/blob/master/ToppaFunctions.php
     *
     * @since   0.9.0
     *
     * @param $expectedString
     * @throws Exception
     */
    public function throwExceptionIfParameterNotFound($expectedString) {

        throw new Exception(__('"' . $expectedString . '" not a valid config parameter', 'one-page-sections'));
    }

    /**
     * Takes a Field object and throws an exception.
     *
     * @since   0.9.0
     *
     * @param Lib_PC3AdminPageSettingField $oField
     * @throws Exception
     */
    public function throwExceptionIfDefaultValueNotFound( Lib_PC3AdminPageSettingField $oField ) {

        throw new Exception(__('No default value found for "' . get_class( $oField )  .
            '" object with `fieldID` of "' . $oField->getFieldID() . '".', 'one-page-sections'));
    }
}