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

    protected $container;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the Dashboard and
	 * the public-facing side of the site.
	 *
	 * @since    0.8.2
	 */
	public function __construct() {

		$this->plugin_name = 'one-page-sections';
		$this->version = '0.8.2';

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
	 * @since    0.1.0
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

        if ( class_exists( 'PC3_AdminPageFramework' ) ) {

            $aAdminPages = array(
                'Admin_PC3SectionManagerPage',
                'Admin_PC3SectionSettingsPage'
            );

            $debugField = new Lib_PC3AdminPageField(
                'field__debug',
                'Debug Flag',
                'debug',
                0
            );

            $this->container->addParameter( $debugField, $aAdminPages );



            //@TODO: Move this into a callback or something
            //sets the page to be our sections_page
            $_sPageID = PC3_AdminPageFramework::getOption(
                'Admin_PC3SectionManagerPage',
                $this->container->getParameter('page__manage') . '__sections_page'
            );

            //if a value exists, than overwrite it in our container
            if( $_sPageID )
                $this->container->setParameter(
                    $this->container->getParameter('page__manage') . '__sections_page',
                    $_sPageID
                );


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
                $this->container->getParameter('section__slug'),
                $this->container->getParameter('section__meta_key'),
                '__sections_page',
                '__sections',
                '__editor',
                '__submit'
            );

            //$sPageClass, $sPageSlug,
            //$sSelectFieldId='', $sSortableFieldId='', $sEditorFieldId='', $sSubmitFieldId='',
            //Lib_PC3CSSFileEditor $oCSSFileEditor, Lib_PC3WPQueryFacade $oWPQueryFacade) {
            new Admin_PC3SectionManagerPageCallbacks(
                get_class( $sectionManagerPage ),
                $this->container->getParameter('page__manage'),
                $this->container->getParameter('page__manage') . '__sections_page',
                $this->container->getParameter('page__manage') . '__sections',
                $this->container->getParameter('page__manage') . '__editor',
                $this->container->getParameter('page__manage') . '__submit',
                $this->container->getCSSFileEditor(),
                $this->container->getWPQueryFacade()
            );

            //($sPageClass, $sSectionSlug, $sMetaKey,
            //$sSelectFieldId='', $sSortableFieldId='', $sEditorFieldId='', $sSubmitFieldId='',
            //Lib_PC3CSSFileEditor $oCSSFileEditor, Lib_PC3WPQueryFacade $oWPQueryFacade
            new Admin_PC3SectionPostTypeMetaLayer(
                get_class( $sectionManagerPage ),
                $this->container->getParameter('section__slug'),
                $this->container->getParameter('section__meta_key'),
                $this->container->getParameter('page__manage') . '__sections_page',
                $this->container->getParameter('page__manage') . '__sections',
                $this->container->getParameter('page__manage') . '__editor',
                $this->container->getParameter('page__manage') . '__submit',
                $this->container->getCSSFileEditor(),
                $this->container->getWPQueryFacade()
            );

            $pc3SettingsPage = new Admin_PC3SectionSettingsPage(
                $this->container->getParameter('page__settings'),
                '__debug',
                '__submit',
                $this->container->getParameter('debug'),
                array( $debugField )
            );

            echo '<pre>';
            $this->container->printParametersToScreen();
            echo '</pre>';
        }
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    0.4.0
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
