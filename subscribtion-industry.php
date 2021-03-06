<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://soft-industry.com
 * @since             1.0.0
 * @package           Subscribtion_Industry
 *
 * @wordpress-plugin
 * Plugin Name:       Subscription Industry
 * Plugin URI:        http://soft-industry.com/
 * Description:       Provide your site to get the subscribe widget and send newsletters.
 * Version:           1.0.0
 * Author:            Soft Industry Ltd.
 * Author URI:        http://soft-industry.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       subscribtion-industry
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-subscribtion-industry-activator.php
 */
function activate_subscribtion_industry() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-subscribtion-industry-activator.php';
	Subscribtion_Industry_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-subscribtion-industry-deactivator.php
 */
function deactivate_subscribtion_industry() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-subscribtion-industry-deactivator.php';
	Subscribtion_Industry_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_subscribtion_industry' );
register_deactivation_hook( __FILE__, 'deactivate_subscribtion_industry' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-subscribtion-industry.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_subscribtion_industry() {

	$plugin = new Subscribtion_Industry();
	$plugin->run();

}
run_subscribtion_industry();
