<?php
/**
 * Plugin Name: MAS Addons for Elementor
 * Description: More power to your Elementor powered website with beautifully designed blocks, templates, widgets, skins and extensions.
 * Plugin URI: https://mas-elementor.madrasthemes.com/
 * Author: MadrasThemes
 * Version: 0.0.1
 * Elementor tested up to: 3.5.0
 * Author URI: https://madrasthemes.com/
 *
 * Text Domain: mas-elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'MAS_ELEMENTOR_VERSION', '3.5.2' );

define( 'MAS_ELEMENTOR__FILE__', __FILE__ );
define( 'MAS_ELEMENTOR_PLUGIN_BASE', plugin_basename( MAS_ELEMENTOR__FILE__ ) );
define( 'MAS_ELEMENTOR_PATH', plugin_dir_path( MAS_ELEMENTOR__FILE__ ) );
define( 'MAS_ELEMENTOR_ASSETS_PATH', MAS_ELEMENTOR_PATH . 'assets/' );
define( 'MAS_ELEMENTOR_MODULES_PATH', MAS_ELEMENTOR_PATH . 'modules/' );
define( 'MAS_ELEMENTOR_URL', plugins_url( '/', MAS_ELEMENTOR__FILE__ ) );
define( 'MAS_ELEMENTOR_ASSETS_URL', MAS_ELEMENTOR_URL . 'assets/' );
define( 'MAS_ELEMENTOR_MODULES_URL', MAS_ELEMENTOR_URL . 'modules/' );

/**
 * Load gettext translate for our text domain.
 *
 * @since 1.0.0
 *
 * @return void
 */
function elementor_pro_load_plugin() {
	load_plugin_textdomain( 'mas-elementor' );

	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action( 'admin_notices', 'elementor_pro_fail_load' );

		return;
	}

	$elementor_version_required = '3.4.0';
	if ( ! version_compare( ELEMENTOR_VERSION, $elementor_version_required, '>=' ) ) {
		add_action( 'admin_notices', 'elementor_pro_fail_load_out_of_date' );

		return;
	}

	$elementor_version_recommendation = '3.4.0';
	if ( ! version_compare( ELEMENTOR_VERSION, $elementor_version_recommendation, '>=' ) ) {
		add_action( 'admin_notices', 'elementor_pro_admin_notice_upgrade_recommendation' );
	}

	require MAS_ELEMENTOR_PATH . 'plugin.php';
}

add_action( 'plugins_loaded', 'elementor_pro_load_plugin' );

function print_error( $message ) {
	if ( ! $message ) {
		return;
	}
	// PHPCS - $message should not be escaped
	echo '<div class="error">' . $message . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
/**
 * Show in WP Dashboard notice about the plugin is not activated.
 *
 * @since 1.0.0
 *
 * @return void
 */
function elementor_pro_fail_load() {
	$screen = get_current_screen();
	if ( isset( $screen->parent_file ) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id ) {
		return;
	}

	$plugin = 'elementor/elementor.php';

	if ( _is_elementor_installed() ) {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );

		$message = '<h3>' . esc_html__( 'Activate the Elementor Plugin', 'mas-elementor' ) . '</h3>';
		$message .= '<p>' . esc_html__( 'Before you can use all the features of MAS Addons for Elementor, you need to activate the Elementor plugin first.', 'mas-elementor' ) . '</p>';
		$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $activation_url, esc_html__( 'Activate Now', 'mas-elementor' ) ) . '</p>';
	} else {
		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		$install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );

		$message = '<h3>' . esc_html__( 'Install and Activate the Elementor Plugin', 'mas-elementor' ) . '</h3>';
		$message .= '<p>' . esc_html__( 'Before you can use all the features of MAS Addons for Elementor, you need to install and activate the Elementor plugin first.', 'mas-elementor' ) . '</p>';
		$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $install_url, esc_html__( 'Install Elementor', 'mas-elementor' ) ) . '</p>';
	}

	print_error( $message );
}

function elementor_pro_fail_load_out_of_date() {
	if ( ! current_user_can( 'update_plugins' ) ) {
		return;
	}

	$file_path = 'elementor/elementor.php';

	$upgrade_link = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file_path, 'upgrade-plugin_' . $file_path );
	$message = '<p>' . esc_html__( 'MAS Addons for Elementor is not working because you are using an old version of Elementor.', 'mas-elementor' ) . '</p>';
	$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $upgrade_link, esc_html__( 'Update Elementor Now', 'mas-elementor' ) ) . '</p>';

	print_error( $message );
}

function elementor_pro_admin_notice_upgrade_recommendation() {
	if ( ! current_user_can( 'update_plugins' ) ) {
		return;
	}

	$file_path = 'elementor/elementor.php';

	$upgrade_link = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file_path, 'upgrade-plugin_' . $file_path );
	$message = '<p>' . esc_html__( 'A new version of Elementor is available. For better performance and compatibility of MAS Addons for Elementor, we recommend updating to the latest version.', 'mas-elementor' ) . '</p>';
	$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $upgrade_link, esc_html__( 'Update Elementor Now', 'mas-elementor' ) ) . '</p>';

	print_error( $message );
}

if ( ! function_exists( '_is_elementor_installed' ) ) {

	function _is_elementor_installed() {
		$file_path = 'elementor/elementor.php';
		$installed_plugins = get_plugins();

		return isset( $installed_plugins[ $file_path ] );
	}
}
