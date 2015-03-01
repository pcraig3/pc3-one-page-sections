<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      0.1.0
 *
 * @package    One_Page_Sections
 * @subpackage One_Page_Sections/admin
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    One_Page_Sections
 * @subpackage One_Page_Sections/admin
 * @author     Your Name <email@example.com>
 */
class One_Page_Sections_Admin {

    /**
     * The ID of this plugin.
     *
     * @since    0.1.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    0.1.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * The service container.
     * Contains a lot of plugin-wide variables, as well as several service objects we need.
     *
     * @since    0.9.2
     * @access   private
     * @var      object    $container    service container contains lots of important stuff
     */
    private $container;

    /**
     * Initialize the class and set its properties.
     *
     * @since    0.1.0
     * @var      string    $plugin_name         The name of this plugin.
     * @var      string    $version             The version of this plugin.
     * @var 	 Lib_PC3Container    $container Dependency injection and variable knower-abouter.
     */
    public function __construct( $plugin_name, $version, Lib_PC3Container $container ) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

        $this->container = $container;

    }

    /**
     * Register the stylesheets for the Dashboard.
     *
     * @since    0.1.0
     */
    public function enqueue_styles() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in One_Page_Sections_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The One_Page_Sections_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/one-page-sections-admin.css', array(), $this->version, 'all' );

    }

    /**
     * Register the JavaScript for the dashboard.
     *
     * @since    0.1.0
     */
    public function enqueue_scripts() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in One_Page_Sections_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The One_Page_Sections_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/one-page-sections-admin.js', array( 'jquery' ), $this->version, false );

    }

    /**
     * Remove author, editor, comments metaboxes from admin edit screen for page selected as the sections page.
     * As none of this page's content should show up on the frontend, there is little reason to keep the editor around.
     *
     * @since    0.9.0
     */
    public function pc3_sections_page_remove_metaboxes() {

        //http://codex.wordpress.org/Function_Reference/remove_meta_box
        $metabox_ids = array(
            'authordiv',
            //'categorydiv',
            'commentstatusdiv',
            'commentsdiv',
            'formatdiv',
            'pageparentdiv',
            //'postcustom',
            'postexcerpt',
            //'postimagediv',
            'revisionsdiv',
            'slugdiv',
            'submitdiv',
            'tagsdiv-post_tag',
            //'{$tax-name}div',
            //'trackbacksdiv'
        );

        //http://codex.wordpress.org/Function_Reference/remove_post_type_support
        $post_supports = array(

            //'title',
            'editor',
            'author',
            'thumbnail',
            //'excerpt',
            'trackbacks',
            'custom-fields',
            'comments',
            'revisions',
            //'page-attributes',
            'post-formats'
        );

        if( $this->container->getFunctionsFacade()->isMatchesPostObject(
            $this->container->getParameter('page__sections'),
            $this->container->getFunctionsFacade()->getGlobalPostObject()
        ) ) {

            foreach ($post_supports as $post_support) {

                remove_post_type_support('page', $post_support);
            }

            foreach ($metabox_ids as $metabox_id) {

                remove_meta_box( $metabox_id, 'page', 'normal' );
            }

        }

    }

}
