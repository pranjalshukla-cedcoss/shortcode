<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://makewebbetter.com/
 * @since             1.0.0
 * @package           Shortcode
 *
 * @wordpress-plugin
 * Plugin Name:       shortcode
 * Plugin URI:        https://makewebbetter.com/product/shortcode/
 * Description:       whole project with shortcode
 * Version:           1.0.0
 * Author:            makewebbetter
 * Author URI:        https://makewebbetter.com/
 * Text Domain:       shortcode
 * Domain Path:       /languages
 *
 * Requires at least: 4.6
 * Tested up to:      4.9.5
 *
 * License:           GNU General Public License v3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Define plugin constants.
 *
 * @since             1.0.0
 */
function define_shortcode_constants() {

	shortcode_constants( 'SHORTCODE_VERSION', '1.0.0' );
	shortcode_constants( 'SHORTCODE_DIR_PATH', plugin_dir_path( __FILE__ ) );
	shortcode_constants( 'SHORTCODE_DIR_URL', plugin_dir_url( __FILE__ ) );
	shortcode_constants( 'SHORTCODE_SERVER_URL', 'https://makewebbetter.com' );
	shortcode_constants( 'SHORTCODE_ITEM_REFERENCE', 'shortcode' );
}


/**
 * Callable function for defining plugin constants.
 *
 * @param   String $key    Key for contant.
 * @param   String $value   value for contant.
 * @since             1.0.0
 */
function shortcode_constants( $key, $value ) {

	if ( ! defined( $key ) ) {

		define( $key, $value );
	}
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-shortcode-activator.php
 */
function activate_shortcode() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-shortcode-activator.php';
	Shortcode_Activator::shortcode_activate();
	$mwb_s_active_plugin = get_option( 'mwb_all_plugins_active', false );
	if ( is_array( $mwb_s_active_plugin ) && ! empty( $mwb_s_active_plugin ) ) {
		$mwb_s_active_plugin['shortcode'] = array(
			'plugin_name' => __( 'shortcode', 'shortcode' ),
			'active' => '1',
		);
	} else {
		$mwb_s_active_plugin = array();
		$mwb_s_active_plugin['shortcode'] = array(
			'plugin_name' => __( 'shortcode', 'shortcode' ),
			'active' => '1',
		);
	}
	update_option( 'mwb_all_plugins_active', $mwb_s_active_plugin );
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-shortcode-deactivator.php
 */
function deactivate_shortcode() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-shortcode-deactivator.php';
	Shortcode_Deactivator::shortcode_deactivate();
	$mwb_s_deactive_plugin = get_option( 'mwb_all_plugins_active', false );
	if ( is_array( $mwb_s_deactive_plugin ) && ! empty( $mwb_s_deactive_plugin ) ) {
		foreach ( $mwb_s_deactive_plugin as $mwb_s_deactive_key => $mwb_s_deactive ) {
			if ( 'shortcode' === $mwb_s_deactive_key ) {
				$mwb_s_deactive_plugin[ $mwb_s_deactive_key ]['active'] = '0';
			}
		}
	}
	update_option( 'mwb_all_plugins_active', $mwb_s_deactive_plugin );
}

register_activation_hook( __FILE__, 'activate_shortcode' );
register_deactivation_hook( __FILE__, 'deactivate_shortcode' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-shortcode.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_shortcode() {
	define_shortcode_constants();

	$s_plugin_standard = new Shortcode();
	$s_plugin_standard->s_run();
	$GLOBALS['s_mwb_s_obj'] = $s_plugin_standard;

}
run_shortcode();

// Add rest api endpoint for plugin.
add_action( 'rest_api_init', 's_add_default_endpoint' );

/**
 * Callback function for endpoints.
 *
 * @since    1.0.0
 */
function s_add_default_endpoint() {
	register_rest_route(
		's-route',
		'/s-dummy-data/',
		array(
			'methods'  => 'POST',
			'callback' => 'mwb_s_default_callback',
			'permission_callback' => 'mwb_s_default_permission_check',
		)
	);
}

/**
 * API validation
 * @param 	Array 	$request 	All information related with the api request containing in this array.
 * @since    1.0.0
 */
function mwb_s_default_permission_check($request) {

	// Add rest api validation for each request.
	$result = true;
	return $result;
}

/**
 * Begins execution of api endpoint.
 *
 * @param   Array $request    All information related with the api request containing in this array.
 * @return  Array   $mwb_s_response   return rest response to server from where the endpoint hits.
 * @since    1.0.0
 */
function mwb_s_default_callback( $request ) {
	require_once SHORTCODE_DIR_PATH . 'includes/class-shortcode-api-process.php';
	$mwb_s_api_obj = new Shortcode_Api_Process();
	$mwb_s_resultsdata = $mwb_s_api_obj->mwb_s_default_process( $request );
	if ( is_array( $mwb_s_resultsdata ) && isset( $mwb_s_resultsdata['status'] ) && 200 == $mwb_s_resultsdata['status'] ) {
		unset( $mwb_s_resultsdata['status'] );
		$mwb_s_response = new WP_REST_Response( $mwb_s_resultsdata, 200 );
	} else {
		$mwb_s_response = new WP_Error( $mwb_s_resultsdata );
	}
	return $mwb_s_response;
}


// Add settings link on plugin page.
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'shortcode_settings_link' );

/**
 * Settings link.
 *
 * @since    1.0.0
 * @param   Array $links    Settings link array.
 */
function shortcode_settings_link( $links ) {

	$my_link = array(
		'<a href="' . admin_url( 'admin.php?page=shortcode_menu' ) . '">' . __( 'Settings', 'shortcode' ) . '</a>',
	);
	return array_merge( $my_link, $links );
}
