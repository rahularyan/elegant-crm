<?php
/**
 * A simple CRM plugin.
 *
 * A Simple Lead Gen Form Plugin
 *
 * @author    Rahul Aryan <rah12@live.com>
 * @copyright 2018 wp.cafe & Rahul Aryan
 * @license   GPL-3.0+ https://www.gnu.org/licenses/gpl-3.0.txt
 * @link      https://wp.cafe
 * @package   ElegantCRM
 *
 * @wordpress-plugin
 * Plugin Name:       Elegant CRM
 * Plugin URI:        http://wp.cafe/plugins/elegant-crm/
 * Description:       A Simple Lead Gen Form Plugin.
 * Version:           1.0.0
 * Author:            Rahul Aryan
 * Author URI:        http://wp.cafe/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       elegant-crm
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
defined( 'ABSPATH' ) || exit;

/**
 * Auto load classes.
 *
 * @param string $class_name Class name to load.
 * @since 1.0.0
 */
function elegant_crm_auto_loader( $class_name ) {
	if ( false === strpos( $class_name, 'ElegantCRM\\' ) ) {
		return;
	}

	// Replace ElegantCRM\ and change to lowercase to fix WPCS warning.
	$file    = strtolower( str_replace( 'ElegantCRM\\', '', $class_name ) );
	$filename = plugin_dir_path( __FILE__ ) . 'inc/' . str_replace( '_', '-', str_replace( '\\', '/', $file ) ) . '.php';

	require_once $filename;
}
spl_autoload_register( 'elegant_crm_auto_loader' );

/**
 * The code that runs during plugin activation.
 */
function elegant_crm_activation() {
	//require_once plugin_dir_path( __FILE__ ) . 'inc/activator.php';
	//Activator::activate();
}
register_activation_hook( __FILE__, 'elegant_crm_activation' );

if ( ! function_exists( 'elegant_crm' ) ) {
	/**
	 * Main function of plugin.
	 */
	function elegant_crm() {
		return ElegantCRM\Plugin::instance();
	}
}

// Launch the rocket.
elegant_crm();
