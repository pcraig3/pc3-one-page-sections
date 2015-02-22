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

    private $container;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.7.0
	 * @var      string    $plugin_name     The name of the plugin.
	 * @var      string    $version    		The version of this plugin.
     * @var 	 string    $sections_page 	The id or slug of the page to use for displaying our sections
     * @var 	 Lib_PC3Container    $container 	    Depenency injection and variable knower-abouter.
	 */
	public function __construct( $plugin_name, $version, $sections_page, Lib_PC3Container $container ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->sections_page;
        $this->container = $container;

		$this->template_loader = new Lib_PC3TemplateLoader();

        add_shortcode( 'pc3_locate_section_template', array( $this, 'pc3_locate_section_template') );
        add_shortcode( 'pc3_get_parameter', array( $this, 'pc3_get_parameter') );
		add_shortcode( 'pc3_section_link', array( $this, 'pc3_section_link') );

		$_sPageID = PC3_AdminPageFramework::getOption('Admin_PC3SectionManagerPage', 'manage_sections__sections_page');

		if( $_sPageID )
			$this->sections_page = $_sPageID;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    0.8.0
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

		//@TODO: improve this, and check for empty values
		if( ! empty( $this->sections_page ) && is_page( $this->sections_page ) ) {

			wp_enqueue_style('pure', plugin_dir_url(__FILE__) . 'css/bower_components/pure/pure.css', array(), $this->version, 'all');
			wp_enqueue_style('pure-grids', plugin_dir_url(__FILE__) . 'css/bower_components/pure/grids.css', array( 'pure' ), $this->version, 'all');
			wp_enqueue_style('pure-grids-responsive', plugin_dir_url(__FILE__) . 'css/bower_components/pure/grids-responsive.css', array( 'pure', 'pure-grids' ), $this->version, 'all');

			wp_enqueue_style('pc3-custom', plugin_dir_url(__FILE__) . 'css/custom.css', array( 'pure', 'pure-grids', 'pure-grids-responsive' ), $this->version, 'all');

			//@TODO GET RID OF THIS
			remove_action( 'wp_footer', 'orbit_functioncall', 1000 );
		}
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    0.8.2
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

		//@TODO: improve this, and check for empty values
		if( ! empty( $this->sections_page ) &&  is_page( $this->sections_page ) ) {

			wp_enqueue_script( 'page-scroll-to-id', plugin_dir_url( __FILE__ ) . 'js/bower_components/page-scroll-to-id/jquery.malihu.PageScroll2id.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( 'pc3-scroll', plugin_dir_url( __FILE__ ) . 'js/scroll.js', array( 'jquery', 'page-scroll-to-id' ), $this->version, true );

			wp_enqueue_script( 'jquery-sticky', plugin_dir_url( __FILE__ ) . 'js/bower_components/jquery-sticky/jquery.sticky.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( 'pc3-sticky', plugin_dir_url( __FILE__ ) . 'js/sticky.js', array( 'jquery', 'jquery-sticky' ), $this->version, false );

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
			return $template === $this->container->getParameter('template__page');

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
	 * @since    0.8.0
	 *
	 * @param string $template Absolute path to template
	 * @return string Absolute path to template
	 */
	public function set_pc3_section_template( $template ) {

		//@TODO: improve this, and check for empty values
		if( ! empty( $this->sections_page ) && is_page( $this->sections_page ) && ! $this->_is_pc3_section_template( $template, 'page' ) )
			//$template = $this->_pc3_locate_template('page-pc3_section.php', false, true );
			$template = $this->template_loader->locate_template( $this->container->getParameter('template__page'), false, true );

		return $template;
	}

	/**
	 * @TODO: fix this method
	 *
	 * @since    0.4.0
	 *
	 * @return string
	 */
	public function pc3_locate_section_template() {

		return $this->template_loader->locate_template( $this->container->getParameter('template__post'), true, false );
	}

    /**
     * @since    0.8.2
     *
     * @param $atts
     * @return string
     */
    public function pc3_get_parameter( $atts ) {

        $atts = shortcode_atts(array(
            'parameter' => 'section__slug',
        ), $atts);

        $message = "Sorry, parameter invalid.";

        try {

            $message = $this->container->getParameter( $atts['parameter'] );

        } catch(Exception $e) {
            //throw $e;
            return $message;
        }

        return $message;
    }


	/**
	 * Method returns hashtag links to other sections on the same page.
	 * Intended to be used as part of 'pc3_section_link' shortcode
	 *
	 * Will return link formatted '#{pc3_section}__{post_name}' if atts['section'] contains
	 * the ID, post_title, or post_name of a valid section
	 *
	 * Defaults to returning HTML code for anchor tag, but can be set to return just the link
	 *
	 * Sets the link text to the Section Title if section is found and $content is empty
	 *
	 * title, class, and rel attributes can be passed in
	 *
	 * @since    0.8.0
	 *
	 * @param array $atts   array of values passed into our shortcode
	 * @param null $content link text for our anchor tag
	 *
	 * @return string       an anchor tag or just a hashtag 'href' string
	 */
	public function pc3_section_link( $atts, $content = null ) {

        $section__slug = $this->container->getParameter('section__slug');

		$atts = shortcode_atts( array(
			'section' => '',
			'class' => '',
			'title' => '',
			'rel' => '',
			'class_not_found' => $section__slug . '--not-found',
			'href' => '0',
		), $atts );

		$sSection = esc_sql( $atts['section'] );
		$sContent =  esc_html( $content );


		//if no section to look for, don't bother doing the query
		if( empty( $sSection ) )
			return '<a class="' . $section__slug . '--link ' . $atts['class_not_found'] . '" href="#">' . $sContent . '</a>';

		$aFoundSectionArray = Lib_PC3WPQueryFacade::getSectionByTitleOrID( $sSection );

		//if no post is returned, return an empty link
		if( empty( $aFoundSectionArray ) )
			return '<a class="' . $section__slug . '--link ' . $atts['class_not_found'] . '" href="#">' . $sContent . '</a>';

		//else, at this point we have a section.
		$aSection = array_shift( $aFoundSectionArray );

		//if no content, post_title will be used
		if( empty( $sContent ) )
			$sContent = esc_html( $aSection->post_title );

		$sHref = '#' . $section__slug . '__' . esc_attr( $aSection->post_name );
		$sClasses = $section__slug . '--link ' . esc_attr( $atts['class'] );
		$sTitle = esc_attr( $atts['title'] );
		$sRel = esc_attr( $atts['rel'] );

		if( $atts['href'] === "true" || intval($atts['href']) !== 0 )
			return $sHref;

		$sAnchor = '<a ';
		$sAnchor .= 'href="' . $sHref . '" ';
		$sAnchor .= 'class="' .$sClasses . '" ';
		$sAnchor .= $sTitle ? 'title="' . $sTitle . '" ' : '';
		$sAnchor .= $sRel ? 'rel="' . $sRel . '" ' : '';

		$sAnchor .= '>' . $sContent . '</a>';

		return $sAnchor;
	}

	/**
	 * @TODO: comments
     *
	 * @since    0.8.0
	 *
	 * @param $content
	 *
	 * @return mixed
	 */
	function pc3_remove_autop_for_posttype( $content )
	{
		# edit the post type here
        $this->container->getParameter('section__slug') === get_post_type() && remove_filter( 'the_content', 'wpautop' );
		return $content;
	}


    /**
     *
     * @TODO: Comments
     *
     * @since    0.8.0
     *
     * @param $query
     */
    function pc3_inject_pc3_sections_into_main_query( $query ) {

        $queried_object = $query->queried_object;

        if( ! is_null( $queried_object )  ) {

            if( $queried_object->ID === intval( $this->sections_page ) ||
                $queried_object->post_name === $this->sections_page ||
                $queried_object->post_title === $this->sections_page ) {

                $section__slug = $this->container->getParameter('section__slug');
                $query->$section__slug = Lib_PC3WPQueryFacade::getSectionsByOrderASC();
            }
        }

        //if sections page, put in paul;
        //if($query->queried_object_id === )
        //var_dump($this->sections_page);
        //var_dump($query->queried_object);
        //var_dump($query);

    }
}
