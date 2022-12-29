<?php
/**
 * Jobs Filter Module.
 *
 * @package mas-elementor
 */

namespace MASElementor\Modules\JobsFilter;

use MASElementor\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The module class.
 */
class Module extends Module_Base {

	/**
	 * Ajax filter options.
	 *
	 * @var array
	 */
	protected $mas_job_filter_options = array();

	/**
	 * Api integrated key.
	 *
	 * @var string
	 */
	protected $api_key;

	/**
	 * Return the widgets in this module.
	 *
	 * @return array
	 */
	public function get_widgets() {
		$classes = array();
		if ( class_exists( 'WP_Job_Manager' ) ) {
			$classes[] = 'Jobs_Filter';
		}

		return $classes;
	}

	/**
	 * Get the name of the module.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-jobs-filter';
	}

	/**
	 * Instantiate the class.
	 */
	public function __construct() {
		parent::__construct();
		$this->mas_job_filter_options = array(
			'ajax_url'                     => admin_url( 'admin-ajax.php' ),
			'enable_live_search'           => true,
			'enable_location_geocomplete'  => true,
			'location_geocomplete_options' => array(),
		);
		$this->api_key                = get_option( 'job_manager_google_maps_api_key' );

		add_action( 'elementor/frontend/before_register_scripts', array( $this, 'register_frontend_scripts' ) );
		add_action( 'wp_ajax_mas_live_search_jobs_suggest', array( __CLASS__, 'mas_live_search_jobs_suggest' ) );
		add_action( 'wp_ajax_nopriv_mas_live_search_jobs_suggest', array( __CLASS__, 'mas_live_search_jobs_suggest' ) );

	}

	/**
	 * Register frontend script.
	 */
	public function register_frontend_scripts() {
		wp_register_script(
			'mas-jobs-filter-scripts',
			MAS_ELEMENTOR_MODULES_URL . 'jobs-filter/assets/js/filter.js',
			array( 'jquery' ),
			MAS_ELEMENTOR_VERSION,
			true
		);
		wp_register_script(
			'mas-jobs-jquery-geocomplete',
			MAS_ELEMENTOR_MODULES_URL . 'jobs-filter/assets/js/geolocation.min.js',
			array( 'mas-jobs-google-maps' ),
			MAS_ELEMENTOR_VERSION,
			true
		);
		wp_register_script(
			'mas-jobs-google-maps',
			'https://maps.google.com/maps/api/js?key=' . $this->api_key . '&libraries=places',
			array(),
			MAS_ELEMENTOR_VERSION,
			true
		);
		wp_localize_script( 'mas-jobs-filter-scripts', 'mas_job_filter_options', $this->mas_job_filter_options );

	}

	/**
	 * Live search.
	 */
	public static function mas_live_search_jobs_suggest() {
		$suggestions = array();
		$jobs        = get_posts(
			array(
				's'              => ! empty( $_REQUEST['term'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['term'] ) ) : '', // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				'post_type'      => 'job_listing',
				'posts_per_page' => '8',
			)
		);

		global $post;

		$results = array();
		foreach ( $jobs as $job ) {
			setup_postdata( $job );
			$suggestion          = array();
			$suggestion['label'] = html_entity_decode( $job->post_title, ENT_QUOTES, 'UTF-8' );
			$suggestion['link']  = get_permalink( $job->ID );

			$suggestions[] = $suggestion;
		}
		$call_back = '';
		// JSON encode and echo.
		if ( isset( $_GET['callback'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$call_back = sanitize_text_field( wp_unslash( $_GET['callback'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}
		$response = $call_back . '(' . wp_json_encode( $suggestions ) . ')';
		echo wp_kses_post( $response );

		// Don't forget to exit!
		exit;
	}

}
