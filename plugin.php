<?php
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
	exit; // Exit if accessed directly
}

/**
 * Main class plugin
 */
class Plugin {

	/**
	 * @var Plugin
	 */
	private static $_instance;

	/**
	 * @var Modules_Manager
	 */
	public $modules_manager;

	/**
	 * @var UpgradeManager
	 */
	public $upgrade;

	/**
	 * @var Editor
	 */
	public $editor;

	/**
	 * @var Preview
	 */
	public $preview;

	/**
	 * @var Admin
	 */
	public $admin;

	/**
	 * @var App
	 */
	public $app;

	/**
	 * @var License\Admin
	 */
	public $license_admin;

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
		// Cloning instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Something went wrong.', 'mas-elementor' ), '1.0.0' );
	}

	/**
	 * Disable unserializing of the class
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Something went wrong.', 'mas-elementor' ), '1.0.0' );
	}

	/**
	 * @return \Elementor\Plugin
	 */

	public static function elementor() {
		return \Elementor\Plugin::$instance;
	}

	/**
	 * @return Plugin
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function autoload( $class ) {
		if ( 0 !== strpos( $class, __NAMESPACE__ ) ) {
			return;
		}

		$class_to_load = $class;

		if ( ! class_exists( $class_to_load ) ) {
			$filename = strtolower(
				preg_replace(
					[ '/^' . __NAMESPACE__ . '\\\/', '/([a-z])([A-Z])/', '/_/', '/\\\/' ],
					[ '', '$1-$2', '-', DIRECTORY_SEPARATOR ],
					$class_to_load
				)
			);
			$filename = MAS_ELEMENTOR_PATH . $filename . '.php';

			if ( is_readable( $filename ) ) {
				include( $filename );
			}
		}
	}

	public function enqueue_styles() {}

	public function enqueue_frontend_scripts() {}

	public function register_frontend_scripts() {}

	public function register_preview_scripts() {}

	public function get_responsive_stylesheet_templates( $templates ) {
		$templates_paths = glob( $this->get_responsive_templates_path() . '*.css' );

		foreach ( $templates_paths as $template_path ) {
			$file_name = 'custom-pro-' . basename( $template_path );

			$templates[ $file_name ] = $template_path;
		}

		return $templates;
	}

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
	 * @param \Elementor\Core\Base\Document $document
	 */
	public function on_document_save_version( $document ) {
		$document->update_meta( '_mas_elementor_version', MAS_ELEMENTOR_VERSION );
	}

	private function get_frontend_depends() {
		$frontend_depends = [
			'mas-elementor-webpack-runtime',
			'elementor-frontend-modules',
		];

		if ( ! $this->is_assets_loader_exist() ) {
			$frontend_depends[] = 'elementor-sticky';
		}

		return $frontend_depends;
	}

	private function get_responsive_templates_path() {
		return MAS_ELEMENTOR_ASSETS_PATH . 'css/templates/';
	}

	private function add_subscription_template_access_level_to_settings( $settings ) {
		// Core >= 3.2.0
		if ( isset( $settings['library_connect']['current_access_level'] ) ) {
			$settings['library_connect']['current_access_level'] = API::get_library_access_level();
		}

		return $settings;
	}

	private function setup_hooks() {
		add_action( 'elementor/init', [ $this, 'on_elementor_init' ] );

		add_action( 'elementor/frontend/before_register_scripts', [ $this, 'register_frontend_scripts' ] );
		add_action( 'elementor/preview/enqueue_scripts', [ $this, 'register_preview_scripts' ] );

		add_action( 'elementor/frontend/before_enqueue_scripts', [ $this, 'enqueue_frontend_scripts' ] );
		add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'enqueue_styles' ] );

		add_filter( 'elementor/core/responsive/get_stylesheet_templates', [ $this, 'get_responsive_stylesheet_templates' ] );
		add_action( 'elementor/document/save_version', [ $this, 'on_document_save_version' ] );

		add_filter( 'elementor/editor/localize_settings', function ( $settings ) {
			return $this->add_subscription_template_access_level_to_settings( $settings );
		}, 11 /** After Elementor Core (Library) */ );
	}

	private function is_optimized_css_mode() {
		$is_optimized_css_loading = self::elementor()->experiments->is_feature_active( 'e_optimized_css_loading' );

		return ! Utils::is_script_debug() && $is_optimized_css_loading && ! self::elementor()->preview->is_preview_mode();
	}

	private function get_assets() {
		$suffix = $this->get_assets_suffix();

		return [
			'scripts' => [],
		];
	}

	private function register_assets() {
		$assets = $this->get_assets();

		if ( $assets ) {
			self::elementor()->assets_loader->add_assets( $assets );
		}
	}

	private function is_assets_loader_exist() {
		return ! ! self::elementor()->assets_loader;
	}

	/**
	 * Plugin constructor.
	 */
	private function __construct() {
		spl_autoload_register( [ $this, 'autoload' ] );

		$this->setup_hooks();

		$this->editor = new Editor();

		$this->preview = new Preview();

		$this->app = new App();

		if ( is_admin() ) {
			$this->admin = new Admin();
		}
	}

	private function get_assets_suffix() {
		return defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	}

	final public static function get_title() {
		return esc_html__( 'MAS Elementor', 'mas-elementor' );
	}
}

if ( ! defined( 'MAS_ELEMENTOR_TESTS' ) ) {
	// In tests we run the instance manually.
	Plugin::instance();
}
