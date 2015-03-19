<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the dashboard.
 *
 * @link       http://example.com
 * @since      0.1.0
 *
 * @package    One_Page_Sections
 * @subpackage One_Page_Sections/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, dashboard-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      0.1.0
 * @package    One_Page_Sections
 * @subpackage One_Page_Sections/includes
 * @author     Paul Craig <paul@pcraig3.ca>
 */
class One_Page_Sections {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    0.1.0
     * @access   protected
     * @var      One_Page_Sections_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    0.1.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    0.1.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * The service container.
     * Contains a lot of plugin-wide variables, as well as several service objects we need.
     *
     * @since    0.8.2
     * @access   protected
     * @var      Lib_PC3Container $container    service container contains lots of important stuff
     */
    protected $container;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the Dashboard and
     * the public-facing side of the site.
     *
     * @since    0.9.0
     */
    public function __construct() {

        $this->plugin_name = 'one-page-sections';
        $this->version = '0.9.0';

        $this->load_dependencies();
        //autoloader loads all new classes classes
        new PC3AutoLoader('/' . ONE_PAGE_SECTIONS_BASENAME);
        $this->container = new Lib_PC3Container();


        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();

    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - One_Page_Sections_Loader. Orchestrates the hooks of the plugin.
     * - One_Page_Sections_i18n. Defines internationalization functionality.
     * - One_Page_Sections_Admin. Defines all hooks for the dashboard.
     * - One_Page_Sections_Public. Defines all hooks for the public side of the site.
     * - PC3_AdminPageFramework. Library we're using to quickly do up admin pages.
     * - AceCustomFieldType. Library allows us to easily do (CSS) editor windows in our admin pages.
     * - Gamajo_Template_Loader. Easily include templates which can be overwritten by individual users.
     * - PC3AutoLoader. Loads the rest of our classes.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    0.8.0
     * @access   private
     */
    private function load_dependencies() {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-one-page-sections-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-one-page-sections-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the Dashboard.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-one-page-sections-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-one-page-sections-public.php';

        /**
         * Include the AdminPageFramework
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/pc3-admin-page-framework.min.php';

        /**
         * Include the Code Editor ACE Custom field type (extends the AdminPageFramework)
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/AceCustomFieldType/AceCustomFieldType.php';

        /**
         * Include the Gamajo_Template_Loader
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/Gamajo-Template-Loader/class-gamajo-template-loader.php';

        /**
         * Include our autoloader
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'lib/PC3AutoLoader.php';

        $this->loader = new One_Page_Sections_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the One_Page_Sections_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    0.1.0
     * @access   private
     */
    private function set_locale() {

        $plugin_i18n = new One_Page_Sections_i18n();
        $plugin_i18n->set_domain( $this->get_plugin_name() );

        $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

    }

    /**
     * Register all of the hooks related to the dashboard functionality
     * of the plugin.
     *
     * @since    0.9.0
     * @access   private
     */
    private function define_admin_hooks() {

        $plugin_admin = new One_Page_Sections_Admin(
            $this->get_plugin_name(),
            $this->get_version(),
            $this->container
        );

        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
        $this->loader->add_action( 'admin_head', $plugin_admin, 'pc3_sections_page_remove_metaboxes');
        //$this->loader->add_filter( 'upgrader_post_install', $plugin_admin, 'pc3_upgrade_replace_things');

        if ( class_exists( 'PC3_AdminPageFramework' ) ) {

            /**
             * This isn't brilliant, but it's better than what was here before
             *
             * Idea is to initialize all of the settings fields as objects so that we can
             *      1. Pass arrays of fields to different objects
             *      2. Add their values to our $container
             *      3. Type-hint where needed so that we're not always relying on string matching
             */

            $aAdminPages = array(
                'Admin_PC3SectionManagerPage',
                'Admin_PC3SectionSettingsPage'
            );

            $oSelectPageField = new Admin_PC3PageSelectPageField(
                'field__select_page',
                'page__sections'
            );

            $oSortableSectionsField = new Admin_PC3PageSortableSectionsField(
                'field__sortable_sections',
                null
            );

            $oEditorField = new Admin_PC3PageACEEditorField(
                'field__editor'
            );

            $oDebugField = new Admin_PC3PageRadioBinaryField(
                'field__debug',
                'debug',
                array(
                    'title'     => 'Debug Flag',
                    'default'   => 0
                )
            );

            $oSubmitField = new Admin_PC3PageSubmitField(
                'field__submit'
            );

            $aManageSectionsPageFields = array( $oSelectPageField, $oSortableSectionsField, $oEditorField, $oSubmitField );
            $aSectionSettingsPageFields = array( $oDebugField, $oSubmitField );

            $this->container->addSettingFieldsAsParameters( $aManageSectionsPageFields, $aAdminPages );
            $this->container->addSettingFieldsAsParameters( $aSectionSettingsPageFields, $aAdminPages );


            //not brilliant, but workable
            if( 0 !== intval( $this->container->getParameter('debug') ) ) {

                new Admin_PC3SectionPostTypeMetaBox(
                    null,   // meta box ID - can be null.
                    __('Debug', 'one-page-sections'), // title
                    array($this->container->getParameter('section__slug')),             // post type slugs: post, page, etc.
                    'side',                             // context
                    'default'                           // priority
                );
            }

            $sectionManagerPage = new Admin_PC3SectionManagerPage(
                $this->container->getParameter('page__manage'),
                $aManageSectionsPageFields,
                $this->container->getParameter('debug'),
                $this->container->getParameter('section__slug')
            );

            //($sPageClass, $aSettingFields
            //$oCSSFileEditor, $oWPQueryFacade) {
            new Admin_PC3SectionManagerPageCallbacks(
                get_class( $sectionManagerPage ),
                $aManageSectionsPageFields,
                $this->container->getCSSFileEditor(),
                $this->container->getWPQueryFacade()
            );

            //($sPageClass, $sSectionSlug, $sMetaKey,
            //$oSortableSectionsField, $oEditorField,
            //$oCSSFileEditor, $oWPQueryFacade) {
            new Admin_PC3SectionPostTypeMetaLayer(
                get_class( $sectionManagerPage ),
                $this->container->getParameter('section__slug'),
                $this->container->getParameter('section__meta_key'),
                $oSortableSectionsField,
                $oEditorField,
                $this->container->getCSSFileEditor(),
                $this->container->getWPQueryFacade()
            );

            $pc3SettingsPage = new Admin_PC3SectionSettingsPage(
                $this->container->getParameter('page__settings'),
                $aSectionSettingsPageFields,
                $this->container->getParameter('debug')
            );

            //($sPageClass, $aSettingFields) {
            new Admin_PC3SectionSettingsPageCallbacks(
                get_class( $pc3SettingsPage ),
                $aSectionSettingsPageFields
            );
        }
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    0.9.0
     * @access   private
     */
    private function define_public_hooks() {

        $plugin_public = new One_Page_Sections_Public(
            $this->get_plugin_name(),
            $this->get_version(),
            $this->container
        );

        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

        $this->loader->add_filter( 'template_include', $plugin_public, 'pc3_set_section_page_template' );
        $this->loader->add_filter( 'the_content', $plugin_public, 'pc3_remove_autop_for_posttype', 0 );
        $this->loader->add_filter( 'pre_get_posts', $plugin_public, 'pc3_inject_pc3_sections_into_main_query' );

        if ( class_exists( 'PC3_AdminPageFramework' ) ) {

            new Public_PC3SectionPostType(
                $this->container->getParameter('section__slug'),
                $this->container->getParameter('section__meta_key')
            );

        }
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    0.1.0
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     0.1.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     0.1.0
     * @return    One_Page_Sections_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     0.1.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }

}