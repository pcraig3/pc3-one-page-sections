<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      0.1.0
 *
 * @package    One_Page_Sections
 * @subpackage One_Page_Sections/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    One_Page_Sections
 * @subpackage One_Page_Sections/public
 * @author     Paul Craig <paul@pcraig3.ca>
 */
class One_Page_Sections_Public {

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
	 * The template loading object, happily written for us by Gary Jones
	 *
	 * @see https://github.com/GaryJones/Gamajo-Template-Loader
	 *
	 * @since    0.4.0
	 * @access   private
	 * @var      object    $template_loader    Template loading object
	 */
	private $template_loader;

	private $sections_page;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.7.0
	 * @var      string    $plugin_name     The name of the plugin.
	 * @var      string    $version    		The version of this plugin.
	 * @var 	 string    $sections_page 	The id or slug of the page to use for displaying our sections
	 */
	public function __construct( $plugin_name, $version, $sections_page ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->sections_page;

		$this->template_loader = new PC3_TemplateLoader();

		add_shortcode( 'pc3_locate_template', array( $this, 'pc3_locate_template') );

		$_sPageID = PC3_AdminPageFramework::getOption('PC3_SectionManagerPage', 'manage_sections__sections_page');

		if( $_sPageID )
			$this->sections_page = $_sPageID;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    0.7.0
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
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/one-page-sections-public.css', array(), $this->version, 'all' );

		//@TODO: Terrible code
		if( is_page( $this->sections_page ) ) {

			wp_enqueue_style('pure', plugin_dir_url(__FILE__) . 'css/bower_components/pure/pure.css', array(), $this->version, 'all');
			wp_enqueue_style('pure-grids-responsive', plugin_dir_url(__FILE__) . 'css/bower_components/pure/grids-responsive.css', array(), $this->version, 'all');
			wp_enqueue_style('pure-grids-responsive-old-ie', plugin_dir_url(__FILE__) . 'css/bower_components/pure/grids-responsive-old-ie.css', array(), $this->version, 'all');

			wp_enqueue_style('pc3-marketing', plugin_dir_url(__FILE__) . 'css/marketing.css', array(), $this->version, 'all');
			wp_enqueue_style('pc3-marketing-old-ie', plugin_dir_url(__FILE__) . 'css/marketing-old-ie.css', array(), $this->version, 'all');

			//hardcoding here is fine: this is the name of the fontawesome plugin's handle
			//if (!wp_style_is('font-awesome-styles'))
			//	wp_enqueue_style('font-awesome-styles', plugin_dir_url(__FILE__) . 'css/bower_components/fontawesome/css/font-awesome.css', array(), $this->version, 'all');
		}
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    0.7.0
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/one-page-sections-public.js', array( 'jquery' ), $this->version, false );

		//@TODO: Terrible code
		if( is_page( $this->sections_page ) ) {

			wp_enqueue_script( 'page-scroll-to-id', plugin_dir_url( __FILE__ ) . 'js/bower_components/page-scroll-to-id/jquery.malihu.PageScroll2id.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( 'pc3-scroll', plugin_dir_url( __FILE__ ) . 'js/scroll.js', array( 'jquery', 'page-scroll-to-id' ), $this->version, false );

		}
	}

	/**
	 * Checks if provided template path points to the page we want it to.
	 *
	 * @TODO: All comments hereafter are junk to be sorted through later.
	 * ' template recognised by our humble little plugin.
	 * If no usc_jobs-archive template is present the plug-in will pick the most appropriate
	 * option, first from the theme/child-theme directory then the plugin.
	 *
	 * @see     https://github.com/stephenharris/Event-Organiser/blob/1.7.3/includes/event-organiser-templates.php#L153
	 * @author  Stephen Harris
	 *
	 * @since    0.4.0
	 *
	 * @param string    $templatePath absolute path to template or filename (with .php extension)
	 * @param string    $context What the template is for ('usc_jobs','archive-usc_jobs', etc).
	 * @return bool     return true if template is recognised as an 'event' template. False otherwise.
	 */
	private function _is_pc3_section_template($templatePath,$context=''){
		$template = basename($templatePath);

		switch($context):
			case 'page';
			//@var page-pc3_section.php
			return $template === 'page-pc3_section.php';
			//case 'archive':
			//	return $template === 'archive-usc_jobs.php';

		endswitch;
		return false;
	}
	/**
	 * Checks the provided template path is correct, considering that we want a specific page to
	 * display our one-page-sections.
	 *
	 * @TODO: Everything hereafter is junk and I'll get to it.
	 * Checks to see if appropriate templates are present in active template directory.
	 * Otherwise uses templates present in plugin's template directory.
	 * Hooked onto template_include
	 *
	 * **THIS MEANS THAT IF YOU WANT A CHANGE TO A TEMPLATE TO PROPAGATE, MAKE THE CHANGE TO THE TEMPLATE IN THE
	 * THEMES FOLDER, NOT THE TEMPLATE FILE IN THE FOLDER FOR THIS PLUGIN**
	 *
	 * @see     https://github.com/stephenharris/Event-Organiser/blob/1.7.3/includes/event-organiser-templates.php#L192
	 * @author  Stephen Harris
	 *
	 * @since    0.7.0
	 *
	 * @param string $template Absolute path to template
	 * @return string Absolute path to template
	 */
	public function set_pc3_section_template( $template ) {

		//@TODO: (ahem.) If WordPress can't find a 'usc_jobs' archive template use plug-in instead:
		//@var page-pc3_section.php
		if( is_page( $this->sections_page ) && ! $this->_is_pc3_section_template( $template, 'page' ) )
			//$template = $this->_pc3_locate_template('page-pc3_section.php', false, true );
			$template = $this->template_loader->locate_template( 'page-pc3_section.php', false, true );

		return $template;
	}

	/**
	 * @TODO: fix this method
	 *
	 * @since    0.4.0
	 *
	 * @return string
	 */
	public function pc3_locate_template() {

		//@var post-pc3_section.php
		return $this->template_loader->locate_template( 'post-pc3_section.php', true, false );
	}
}
