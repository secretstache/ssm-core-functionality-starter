<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.secretstache.com/
 * @since             1.0.0
 * @package           SSM_Core_Functionality
 *
 * @wordpress-plugin
 * Plugin Name:       SSM Core Functionality
 * Plugin URI:        https://www.secretstache.com/
 * Description:       SSM Core Functionality
 * Version:           1.0.0
 * Author:            Secret Stache Media
 * Author URI:        https://www.secretstache.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ssm-core-functionality
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define plugin constants
 */

define( 'PLUGIN_NAME_VERSION', '1.0.0' );
define( 'SSMC_VERSION', '0.2.3' );
define( 'SSMC_URL', trailingslashit ( plugin_dir_url( __FILE__ ) ) );
define( 'SSMC_DIR', plugin_dir_path( __FILE__ ) );
define( 'SSMC_ADMIN_URL', trailingslashit ( plugin_dir_url( __FILE__ ) . 'admin/' ) );
define( 'SSMC_PUBLIC_URL', trailingslashit ( plugin_dir_url( __FILE__ ) . 'public/' ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ssm-core-functionality-activator.php
 */
function activate_ssm_core_functionality() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ssm-core-functionality-activator.php';
	SSM_Core_Functionality_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ssm-core-functionality-deactivator.php
 */
function deactivate_ssm_core_functionality() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ssm-core-functionality-deactivator.php';
	SSM_Core_Functionality_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ssm_core_functionality' );
register_deactivation_hook( __FILE__, 'deactivate_ssm_core_functionality' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ssm-core-functionality.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ssm_core_functionality() {

	$plugin = new SSM_Core_Functionality();
	$plugin->run();

}
run_ssm_core_functionality();