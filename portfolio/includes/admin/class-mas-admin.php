<?php
/**
 * MAS Admin
 *
 * @class    mas_Admin
 * @package  Portfolio/Admin
 * @version  1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * MAS_Admin class.
 */
class MAS_Admin {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'includes' ) );
		add_action( 'admin_init', array( $this, 'buffer' ), 1 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

	}

	/**
	 * Output buffering allows admin screens to make redirects later on.
	 */
	public function buffer() {
		ob_start();
	}

	/**
	 * Include any classes we need within admin.
	 */
	public function includes() {
		include_once dirname( __FILE__ ) . '/mas-meta-box-functions.php';
		include_once dirname( __FILE__ ) . '/class-mas-admin-meta-boxes.php';
	}

	/**
	 * Enqueue style.
	 */
	public function admin_styles() {

		$screen    = get_current_screen();
		$screen_id = $screen ? $screen->id : '';

		if ( in_array( $screen_id, array( 'jetpack-portfolio' ), true ) ) {

			wp_register_style( 'mas_admin_styles', MAS_ELEMENTOR_URL . '/assets/css/portfolio/admin/admin.css', array(), MAS_ELEMENTOR_VERSION );
			wp_enqueue_style( 'mas_admin_styles' );
		}
		wp_register_style( 'mas_admin_styles', MAS_ELEMENTOR_URL . '/assets/css/portfolio/admin/admin.css', array(), MAS_ELEMENTOR_VERSION );
		wp_enqueue_style( 'mas_admin_styles' );
	}

	/**
	 * Enqueue scripts.
	 */
	public function admin_scripts() {
		global $wp_query, $post;

		$screen          = get_current_screen();
		$screen_id       = $screen ? $screen->id : '';
		$front_screen_id = sanitize_title( __( 'MAS', 'mas-addons-for-elementor' ) );
		$suffix          = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		if ( in_array( $screen_id, array( 'jetpack-portfolio' ), true ) ) {
			wp_enqueue_media();
			wp_register_script( 'mas-admin-portfolio-meta-boxes', MAS_ELEMENTOR_URL . '/assets/js/portfolio/admin/meta-boxes-portfolio.js', array( 'jquery', 'jquery-ui-sortable', 'media-models' ), MAS_ELEMENTOR_VERSION, true );
			wp_enqueue_script( 'mas-admin-portfolio-meta-boxes' );
		}
	}
}

return new MAS_Admin();
