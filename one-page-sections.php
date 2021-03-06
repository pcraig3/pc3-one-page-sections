<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * Dashboard. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://pcraig3.ca
 * @package           One_Page_Sections
 *
 * @wordpress-plugin
 * Plugin Name:       One Page Sections
 * Plugin URI:        https://github.com/pcraig3/pc3-one-page-sections
 * Description:       Quick and Dirty Plugin Builds One (Scrolling) Page with Sections
 * Version:           0.9.3
 * Author:            Paul Craig
 * Author URI:        http://pcraig3.ca
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       one-page-sections
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/pcraig3/pc3-one-page-sections
 * GitHub Branch:     master
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Plugin directory path.
 *
 * @since 0.4.0
 * @type string ONE_PAGE_SECTIONS_DIR_PATH
 */
if ( ! defined( 'ONE_PAGE_SECTIONS_DIR_PATH' ) ) {

	define( 'ONE_PAGE_SECTIONS_DIR_PATH', plugin_dir_path( __FILE__ ) );
}

/**
 * Plugin directory basename (ie, 'pc3-one-page-sections'
 *
 * @since 0.4.0
 * @type string ONE_PAGE_SECTIONS_DIR_PATH
 */
if ( ! defined( 'ONE_PAGE_SECTIONS_BASENAME' ) ) {

    define( 'ONE_PAGE_SECTIONS_BASENAME', plugin_basename( __DIR__ ) );
}


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-one-page-sections-activator.php
 */
function activate_one_page_sections() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-one-page-sections-activator.php';
	One_Page_Sections_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-one-page-sections-deactivator.php
 */
function deactivate_one_page_sections() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-one-page-sections-deactivator.php';
	One_Page_Sections_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_one_page_sections' );
register_deactivation_hook( __FILE__, 'deactivate_one_page_sections' );

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-one-page-sections.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.9.0
 */
function run_one_page_sections() {

	$plugin = new One_Page_Sections();

	$plugin->run();
}
run_one_page_sections();

/* So I know the answer.  The answer is that we save the version when we save the CSS file and then
if they don't match we overwrite the CSS file.  Easy peasy. */
