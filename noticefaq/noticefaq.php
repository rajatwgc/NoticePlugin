<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://notice.studio
 * @since             1.0.0
 * @package           Noticefaq
 *
 * @wordpress-plugin
 * Plugin Name:       Notice FAQ
 * Plugin URI:        https://notice.studio
 * Description:       Display Faq, Document and Blog created in Notice.studio.
 * Version:           1.0.0
 * Author:            Raj WGC
 * Author URI:        https://wgc.net.in
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       noticefaq
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'NOTICEFAQ_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-noticefaq-activator.php
 */
function activate_noticefaq() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-noticefaq-activator.php';
	Noticefaq_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-noticefaq-deactivator.php
 */
function deactivate_noticefaq() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-noticefaq-deactivator.php';
	Noticefaq_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_noticefaq' );
register_deactivation_hook( __FILE__, 'deactivate_noticefaq' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-noticefaq.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_noticefaq() {

	$plugin = new Noticefaq();
	$plugin->run();

}
run_noticefaq();
