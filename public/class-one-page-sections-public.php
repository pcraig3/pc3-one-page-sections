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
 * @author     Your Name <email@example.com>
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
	 * Initialize the class and set its properties.
	 *
	 * @since    0.1.0
	 * @var      string    $plugin_name       The name of the plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/one-page-sections-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/one-page-sections-public.js', array( 'jquery' ), $this->version, false );

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
	 * @since    0.3.0
	 *
	 * @param string    $templatePath absolute path to template or filename (with .php extension)
	 * @param string    $context What the template is for ('usc_jobs','archive-usc_jobs', etc).
	 * @return bool     return true if template is recognised as an 'event' template. False otherwise.
	 */
	private function _is_pc3_section_template($templatePath,$context=''){
		$template = basename($templatePath);

		switch($context):
			case 'page';
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
	 * @since    0.3.0
	 *
	 * @param string $template Absolute path to template
	 * @return string Absolute path to template
	 */
	public function set_pc3_section_template( $template ) {
		//If WordPress couldn't find a 'usc_jobs' archive template use plug-in instead:

		//@TODO: make this a real variable
		//@TODO: Name it better
		$pc3_template_dir = trailingslashit( dirname( __DIR__ ) ) . 'templates/';

		if( is_page( 'one-page-sections' ) && ! $this->_is_pc3_section_template( $template, 'page' ) )
			//@TODO: remove 4th parameter
			$template = $this->_usc_jobs_locate_template('page-pc3_section.php', false, true, $pc3_template_dir );

		return $template;
	}

	/**
	 * Retrieve the name of the highest priority template file that exists.
	 *
	 *
	 * @TODO: Everything hereafter is junk and I'll get to it.
	 *
	 * Searches the child theme first, then the parent theme before checking the plug-in templates folder.
	 * So parent themes can override the default plug-in templates, and child themes can over-ride both.
	 *
	 * Behaves almost identically to {@see locate_template()}
	 *
	 * @see     https://github.com/stephenharris/Event-Organiser/blob/1.7.3/includes/event-organiser-templates.php#L38
	 * @author  Stephen Harris
	 *
	 * @since 0.3.0
	 *
	 * @param string|array $template_names Template file(s) to search for, in order.
	 * @param bool $load If true the template file will be loaded if it is found.
	 * @param bool $require_once Whether to require_once or require. Default true. Has no effect if $load is false.
	 * @return string The template filename if one is located.
	 */
	private function _usc_jobs_locate_template($template_names, $load = false, $require_once = true, $local_template_dir = '') {
		$located = '';
		$local_template_dir = empty( $local_template_dir ) ? plugin_dir_url( __DIR__ ) . 'templates' : $local_template_dir;

		$template_dir = get_stylesheet_directory(); //child theme
		$parent_template_dir = get_template_directory(); //parent theme
		$stack = apply_filters( 'pc3_section_template_stack', array( $template_dir, $parent_template_dir,
		$local_template_dir ) );

		foreach ( (array) $template_names as $template_name ) {
			if ( !$template_name )
				continue;
			foreach ( $stack as $template_stack ){

				var_dump('name: ' . $template_name .' // stack: ' . $template_stack);
				var_dump( file_exists( trailingslashit( $template_stack ) . $template_name ) );
				echo '<br>';

				if ( file_exists( trailingslashit( $template_stack ) . $template_name ) ) {
					$located = trailingslashit( $template_stack ) . $template_name;
					break;
				}
			}
		}
		if ( $load && '' !== $located )
			load_template( $located, $require_once );

		return $located;
	}

}
