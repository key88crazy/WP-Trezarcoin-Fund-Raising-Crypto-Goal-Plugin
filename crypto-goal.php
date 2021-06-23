<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              www.authorurl.com
 * @since             1.0.0
 * @package           Crypto_Goal
 *
 * @wordpress-plugin
 * Plugin Name:       Crypto Goal
 * Plugin URI:        www.pluginurl.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Author Name
 * Author URI:        www.authorurl.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       crypto-goal
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
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-crypto-goal-activator.php
 */
function activate_crypto_goal() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-crypto-goal-activator.php';
	Crypto_Goal_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-crypto-goal-deactivator.php
 */
function deactivate_crypto_goal() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-crypto-goal-deactivator.php';
	Crypto_Goal_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_crypto_goal' );
register_deactivation_hook( __FILE__, 'deactivate_crypto_goal' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-crypto-goal.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_crypto_goal() {

	$plugin = new Crypto_Goal();
	$plugin->run();

}
run_crypto_goal();
