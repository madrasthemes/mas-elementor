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
	private static $_instance;

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
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Something went wrong.', 'mas-elementor' ), '1.0.0' );
	}

	/**
	 * Disable unserializing of the class
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Something went wrong.', 'mas-elementor' ), '1.0.0' );
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
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
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
	public function enqueue_styles() {}

	/**
	 * Enqueue frontend scripts used by the plugin.
	 */
	public function enqueue_frontend_scripts() {
		wp_enqueue_script(
			'mas-bootstrap-bundle',
			MAS_ELEMENTOR_ASSETS_URL . 'js/bootstrap.bundle.min.js',
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
		return esc_html__( 'MAS Elementor', 'mas-elementor' );
	}
}

if ( ! defined( 'MAS_ELEMENTOR_TESTS' ) ) {
	// In tests we run the instance manually.
	Plugin::instance();
}
