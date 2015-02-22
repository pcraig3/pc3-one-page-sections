<?php

/**
 * @TODO:
 *
 * @see: https://github.com/audiotheme/cue/blob/develop/includes/class-cue-template-loader.php
 * @see: https://github.com/GaryJones/Gamajo-Template-Loader
 *
 * @since      0.4.0
 *
 * @package    One_Page_Sections
 * @subpackage One_Page_Sections/includes/lib
 */
class Lib_PC3TemplateLoader extends Gamajo_Template_Loader {
    /**
     * Prefix for filter names.
     *
     * @var string
     */
    protected $filter_prefix = 'pc3';
    /**
     * Directory name where custom templates for this plugin should be found in the theme.
     *
     * @var string
     */
    protected $theme_template_directory = 'pc3';
    /**
     * Reference to the root directory path of this plugin.
     *
     * @var string
     */
    protected $plugin_directory = ONE_PAGE_SECTIONS_DIR_PATH;
}