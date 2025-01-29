<?php
/**
 * The MAS Elementor Plugin.
 *
 * @package mas-elementor
 */

namespace MASElementor;

use MASElementor\Core\Admin\Admin;
use MASElementor\Core\App\App;
use MASElementor\Core\Connect;
use Elementor\Core\Responsive\Files\Frontend as FrontendFile;
use Elementor\Utils;
use MASElementor\Core\Editor\Editor;
use MASElementor\Core\Modules_Manager;
use MASElementor\Core\Preview\Preview;
use MASElementor\Core\Upgrade\Manager as UpgradeManager;
use MASElementor\License\API;
use WP_Job_Manager_Post_Types;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main class plugin
 */
class Plugin {

	/**
	 * The plugin instance.
	 *
	 * @var Plugin
	 */
	private static $instance;

	/**
	 * The Modules Manager.
	 *
	 * @var Modules_Manager
	 */
	public $modules_manager;

	/**
	 * The Upgrade Manager.
	 *
	 * @var UpgradeManager
	 */
	public $upgrade;

	/**
	 * The Editor.
	 *
	 * @var Editor
	 */
	public $editor;

	/**
	 * The Preview.
	 *
	 * @var Preview
	 */
	public $preview;

	/**
	 * The Admin.
	 *
	 * @var Admin
	 */
	public $admin;

	/**
	 * The App.
	 *
	 * @var App
	 */
	public $app;

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Something went wrong.', 'mas-addons-for-elementor' ), '1.0.0' );
	}

	/**
	 * Disable unserializing of the class
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Something went wrong.', 'mas-addons-for-elementor' ), '1.0.0' );
	}

	/**
	 * The Elementor instance.
	 *
	 * @return \Elementor\Plugin
	 */
	public static function elementor() {
		return \Elementor\Plugin::$instance;
	}

	/**
	 * The Plugin instance.
	 *
	 * @return Plugin
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Autoload class.
	 *
	 * @param mixed $class The class that needs to be autoloaded.
	 */
	public function autoload( $class ) {
		if ( 0 !== strpos( $class, __NAMESPACE__ ) ) {
			return;
		}

		$class_to_load = $class;

		if ( ! class_exists( $class_to_load ) ) {
			$filename = strtolower(
				preg_replace(
					array( '/^' . __NAMESPACE__ . '\\\/', '/([a-z])([A-Z])/', '/_/', '/\\\/' ),
					array( '', '$1-$2', '-', DIRECTORY_SEPARATOR ),
					$class_to_load
				)
			);
			$filename = MAS_ELEMENTOR_PATH . $filename . '.php';

			if ( is_readable( $filename ) ) {
				include $filename;
			}
		}
	}

	/**
	 * Enqueue styles used by the plugin.
	 */
	public function enqueue_styles() {
		$has_custom_file      = \Elementor\Plugin::instance()->breakpoints->has_custom_breakpoints();
		$direction_suffix     = is_rtl() ? '-rtl' : '';
		$responsive_file_name = 'mas-responsive' . $direction_suffix . '.css';

		$frontend_file_url = $this->get_frontend_file_url( $responsive_file_name, true );

		wp_enqueue_style(
			'mas-elementor-responsive',
			$frontend_file_url,
			array(),
			$has_custom_file ? null : MAS_ELEMENTOR_VERSION
		);

		wp_enqueue_style(
			'mas-elementor-main',
			MAS_ELEMENTOR_ASSETS_URL . 'css/main.css',
			array(),
			MAS_ELEMENTOR_VERSION
		);

		wp_enqueue_style(
			'mas-magnific-popup',
			MAS_ELEMENTOR_ASSETS_URL . 'css/popup/magnific-popup.css',
			array(),
			MAS_ELEMENTOR_VERSION
		);
	}

	/**
	 * Enqueue frontend scripts used by the plugin.
	 */
	public function enqueue_frontend_scripts() {
		if ( ! wp_script_is( 'bootstrap-js', 'enqueued' ) ) {
			wp_enqueue_script(
				'mas-bootstrap-bundle',
				MAS_ELEMENTOR_ASSETS_URL . 'js/bootstrap/bootstrap.bundle.min.js',
				array(),
				MAS_ELEMENTOR_VERSION,
				true
			);
		}

		wp_enqueue_script(
			'mas-magnigy-popup',
			MAS_ELEMENTOR_ASSETS_URL . 'js/popup/jquery.magnific-popup.min.js',
			array(),
			MAS_ELEMENTOR_VERSION,
			true
		);

		wp_enqueue_script(
			'mas-popup-init',
			MAS_ELEMENTOR_ASSETS_URL . 'js/popup/popup-init.js',
			array(),
			MAS_ELEMENTOR_VERSION,
			true
		);

		wp_enqueue_script(
			'mas-collapse-script',
			MAS_ELEMENTOR_ASSETS_URL . 'js/tabs/mas-button-toggle.js',
			array(),
			MAS_ELEMENTOR_VERSION,
			true
		);
	}

	/**
	 * Register frontend scripts used by the plugin.
	 */
	public function register_frontend_scripts() {}

	/**
	 * Register preview scripts used by the plugin.
	 */
	public function register_preview_scripts() {}

	/**
	 * Run on elementor init.
	 */
	public function on_elementor_init() {
		$this->modules_manager = new Modules_Manager();

		/**
		 * MAS Elementor init.
		 *
		 * Fires on MAS Elementor init, after Elementor has finished loading but
		 * before any headers are sent.
		 *
		 * @since 1.0.0
		 */
		do_action( 'mas_elementor/init' );
	}

	/**
	 * Add meta information to the documentation about MAS Elementor.
	 *
	 * @param \Elementor\Core\Base\Document $document The Elementor document instance.
	 */
	public function on_document_save_version( $document ) {
		$document->update_meta( '_mas_elementor_version', MAS_ELEMENTOR_VERSION );
	}

	/**
	 * Setup hooks.
	 */
	private function setup_hooks() {
		add_action( 'elementor/init', array( $this, 'on_elementor_init' ) );

		add_action( 'elementor/frontend/before_register_scripts', array( $this, 'register_frontend_scripts' ) );
		add_action( 'elementor/preview/enqueue_scripts', array( $this, 'register_preview_scripts' ) );

		add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'enqueue_frontend_scripts' ) );
		add_action( 'elementor/frontend/after_enqueue_styles', array( $this, 'enqueue_styles' ) );

		add_action( 'elementor/document/save_version', array( $this, 'on_document_save_version' ) );
		add_filter( 'wp_kses_allowed_html', array( $this, 'mas_add_style_tag' ), 10, 2 );

		add_filter( 'elementor/core/responsive/get_stylesheet_templates', array( $this, 'get_responsive_stylesheet_templates' ) );

		add_filter( 'register_post_type_job_listing', array( $this, 'mas_elementor_modify_register_post_type_job_listing' ) );
	}

	/**
	 * Get Jobs page id.
	 *
	 * @param string $page page.
	 *
	 * @return int|string
	 */
	public function mas_wpjm_get_page_id( $page ) {

		$option_name = '';
		switch ( $page ) {
			case 'jobs':
				$option_name = 'job_manager_jobs_page_id';
				break;
			case 'jobs-dashboard':
				$option_name = 'job_manager_job_dashboard_page_id';
				break;
			case 'post-a-job':
				$option_name = 'job_manager_submit_job_form_page_id';
				break;
		}

		$page_id = 0;

		if ( ! empty( $option_name ) ) {
			$page_id = get_option( $option_name );
		}

		$page_id = apply_filters( 'mas_elementor_wpjm_get_' . $page . '_page_id', $page_id );
		return $page_id ? absint( $page_id ) : -1;
	}

	/**
	 * Enabling Archive of Job listing.
	 *
	 * @param array $args arguments.
	 *
	 * @return array
	 */
	public function mas_elementor_modify_register_post_type_job_listing( $args ) {
		if ( class_exists( 'WP_Job_Manager_Post_Types' ) ) {
			$args['show_in_rest'] = true;

			$jobs_page_id = $this->mas_wpjm_get_page_id( 'jobs' );
			if ( $jobs_page_id && get_post( $jobs_page_id ) ) {
				$permalinks          = WP_Job_Manager_Post_Types::get_permalink_structure();
				$args['has_archive'] = urldecode( get_page_uri( $jobs_page_id ) );
				$args['rewrite']     = $permalinks['job_rewrite_slug'] ? array(
					'slug'       => $permalinks['job_rewrite_slug'],
					'with_front' => false,
					'feeds'      => true,
				) : false;
			}

			return $args;

		}
	}

	/**
	 * Responsive templates path.
	 */
	private function get_responsive_templates_path() {
		return MAS_ELEMENTOR_ASSETS_PATH . 'css/templates/';
	}

	/**
	 * Get frontend file.
	 *
	 * @param string $frontend_file_name frontend_file_name.
	 *
	 * @return string
	 */
	private function get_frontend_file( $frontend_file_name ) {
		$template_file_path = self::get_responsive_templates_path() . $frontend_file_name;

		return self::elementor()->frontend->get_frontend_file( $frontend_file_name, 'mas-custom-', $template_file_path );
	}

	/**
	 * Get frontend file url.
	 *
	 * @param string $frontend_file_name frontend_file_name.
	 * @param string $custom_file custom_file.
	 *
	 * @return string
	 */
	public function get_frontend_file_url( $frontend_file_name, $custom_file ) {
		if ( $custom_file ) {
			$frontend_file = $this->get_frontend_file( $frontend_file_name );

			$frontend_file_url = $frontend_file->get_url();
		} else {
			$frontend_file_url = MAS_ELEMENTOR_ASSETS_URL . 'css/templates/' . $frontend_file_name;
		}

		return $frontend_file_url;
	}

	/**
	 * Get responsive stylesheet templates.
	 *
	 * @param array $templates templates.
	 *
	 * @return array
	 */
	public function get_responsive_stylesheet_templates( $templates ) {
		// $templates_paths = glob( MAS_ELEMENTOR_ASSETS_PATH . 'css/main.css' );
		$templates_paths = glob( $this->get_responsive_templates_path() . '*.css' );

		foreach ( $templates_paths as $template_path ) {
			$file_name = 'mas-custom-' . basename( $template_path );

			$templates[ $file_name ] = $template_path;
		}

		return $templates;
	}

	/**
	 * Added Style tag in wp_kses_post.
	 *
	 * @param array  $allowed allowed tags and attributes.
	 * @param string $context context parameters type.
	 */
	public function mas_add_style_tag( $allowed, $context ) {

		if ( is_array( $context ) ) {
			return $allowed;
		}

		if ( 'post' === $context ) {
			$allowed['style'] = '';

			// Add or modify allowed HTML elements, attributes, or styles here.
			$allowed['form']   = array(
				'class'  => array(),
				'method' => array(),
			);
			$allowed['select'] = array(
				'name'       => array(),
				'class'      => array(),
				'aria-label' => array(),
			);
			$allowed['option'] = array(
				'value'    => array(),
				'selected' => array(),
			);
			$allowed['input']  = array(
				'type'  => array(),
				'name'  => array(),
				'value' => array(),
			);
			$allowed['hidden'] = array(
				'type'  => array(),
				'name'  => array(),
				'value' => array(),
			);

			// Add SVG args directly to $allowed array.
			$allowed['svg'] = array(
				'class'             => true,
				'aria-hidden'       => true,
				'aria-labelledby'   => true,
				'role'              => true,
				'xmlns'             => true,
				'width'             => true,
				'height'            => true,
				'viewbox'           => true,
				'style'             => true,
				'x'                 => true,
				'y'                 => true,
				'xmlns:xlink'       => true,
				'xml:space'         => true,
			);
			$allowed['g'] = array(
				'fill'              => true,
			);
			$allowed['title'] = array(
				'title'             => true,
			);
			$allowed['path'] = array(
				'class'             => true,
				'd'                 => true,
				'fill'              => true,
				'opacity'           => true,
			);
			$allowed['circle'] = array(
				'cx'             => true,
				'cy'                 => true,
				'fill'              => true,
				'r'           => true,
			);
			$allowed['style'] = array(
				'type'              => true,
			);

		}

		return apply_filters( 'mas_add_style_tag', $allowed );
	}

	/**
	 * Check if optimized CSS mode is enabled
	 *
	 * @return bool
	 */
	private function is_optimized_css_mode() {
		$is_optimized_css_loading = self::elementor()->experiments->is_feature_active( 'e_optimized_css_loading' );

		return ! Utils::is_script_debug() && $is_optimized_css_loading && ! self::elementor()->preview->is_preview_mode();
	}

	/**
	 * Get the assets
	 *
	 * @return array
	 */
	private function get_assets() {
		$suffix = $this->get_assets_suffix();

		return array(
			'scripts' => array(),
		);
	}

	/**
	 * Register the assets.
	 */
	private function register_assets() {
		$assets = $this->get_assets();

		if ( $assets ) {
			self::elementor()->assets_loader->add_assets( $assets );
		}
	}

	/**
	 * Does assets loader exists.
	 *
	 * @return bool|Assets_Loader
	 */
	private function is_assets_loader_exist() {
		return ! ! self::elementor()->assets_loader;
	}

	/**
	 * Plugin constructor.
	 */
	private function __construct() {
		spl_autoload_register( array( $this, 'autoload' ) );

		$this->setup_hooks();
		require_once MAS_ELEMENTOR_TEMPLATES_PATH . 'templates.php';
	}

	/**
	 * Get assets suffix.
	 *
	 * @return string
	 */
	private function get_assets_suffix() {
		return defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	}

	/**
	 * Get the title
	 *
	 * @return string
	 */
	final public static function get_title() {
		return esc_html__( 'MAS Elementor', 'mas-addons-for-elementor' );
	}
}

if ( ! defined( 'MAS_ELEMENTOR_TESTS' ) ) {
	// In tests we run the instance manually.
	Plugin::instance();
}
