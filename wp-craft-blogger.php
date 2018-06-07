<?php

/**
 * Plugin Name:       WP Craft Blogger
 * Plugin URI:        http://codebyshellbot.com/wordpress-plugins/wp-craft-blogger
 * Description:       Add crafting patterns to your blog with an easy-to-use interface and customizable template.
 * Version:           0.0.2
 * Author:            Shellbot
 * Author URI:        http://codebyshellbot.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-craft-blogger
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function activate_wpcb() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpcb-activator.php';
	WPCB_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function deactivate_wpcb() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpcb-deactivator.php';
	WPCB_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wpcb' );
register_deactivation_hook( __FILE__, 'deactivate_wpcb' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wpcb.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.0.1
 */
function run_wpcb() {

	$plugin = new WP_Craft_Blogger();
	$plugin->run();

}
run_wpcb();
