<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://omniglot.ai
 * @since             1.0.0
 * @package           Omniglot
 *
 * @wordpress-plugin
 * Plugin Name:       Omniglot Basic
 * Plugin URI:        https://omniglot.ai
 * Description:       Translate your website in just one click using the best artificial intelligence technology for translation and grammar check!.
 * Version:           2.0.0
 * Author:            Omniglot
 * Author URI:        https://omniglot.ai
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       omniglot
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
define( 'OMNIGLOT_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-omniglot-activator.php
 */
function omniglot_activate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-omniglot-activator.php';
	Omniglot_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-omniglot-deactivator.php
 */
function omniglot_deactivate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-omniglot-deactivator.php';
	Omniglot_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'omniglot_activate' );
register_deactivation_hook( __FILE__, 'omniglot_deactivate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-omniglot.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function omniglot_run() {

	$plugin = new Omniglot();
	$plugin->run();

}
omniglot_run();
/* add_filter( 'omniglot_license_key_verification', function(){ return array("status"=>"active");} );*/

	add_shortcode('show_flags',array('Omniglot_Public','cn_slug_filter_the_title'));