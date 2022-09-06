<?php
/**
 * Register custom taxanomy.
 *
 * @package MASElementor/Classes/class-wp-job-manager.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class JH_WPJM_Job_Manager
 */
class JH_WPJM_Job_Manager {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_taxonomies' ) );
	}

	/**
	 * To check wp-job manager plugin activation.
	 */
	public function is_wpjm_activated() {
		return class_exists( 'WP_Job_Manager' ) ? true : false;
	}

	/**
	 * Register custom taxonamies to job-istings
	 */
	public function register_taxonomies() {
		if ( ! post_type_exists( 'job_listing' ) ) {
			return;
		}

		$admin_capability = 'manage_job_listings';

		/**
		 * Taxonomies
		 */
		$taxonomies_args = apply_filters(
			'jobhunt_job_listing_taxonomies_list',
			array(
				'job_listing_salary'        => array(
					'singular' => esc_html__( 'Job Salary', 'mas-elementor' ),
					'plural'   => esc_html__( 'Job Salaries', 'mas-elementor' ),
					'slug'     => esc_html_x( 'job-salary', 'Job salary permalink - resave permalinks after changing this', 'mas-elementor' ),
					'enable'   => get_option( 'job_manager_enable_salary', true ),
				),
				'job_listing_career_level'  => array(
					'singular' => esc_html__( 'Job Career Level', 'mas-elementor' ),
					'plural'   => esc_html__( 'Job Career level', 'mas-elementor' ),
					'slug'     => esc_html_x( 'job-career-level', 'Job career level permalink - resave permalinks after changing this', 'mas-elementor' ),
					'enable'   => get_option( 'job_manager_enable_career_level', true ),
				),
				'job_listing_experience'    => array(
					'singular' => esc_html__( 'Job Experience', 'mas-elementor' ),
					'plural'   => esc_html__( 'Job Experience', 'mas-elementor' ),
					'slug'     => esc_html_x( 'job-experience', 'Job experience permalink - resave permalinks after changing this', 'mas-elementor' ),
					'enable'   => get_option( 'job_manager_enable_experience', true ),
				),
				'job_listing_gender'        => array(
					'singular' => esc_html__( 'Job Gender', 'mas-elementor' ),
					'plural'   => esc_html__( 'Job Gender', 'mas-elementor' ),
					'slug'     => esc_html_x( 'job-gender', 'Job gender permalink - resave permalinks after changing this', 'mas-elementor' ),
					'enable'   => get_option( 'job_manager_enable_gender', true ),
				),
				'job_listing_industry'      => array(
					'singular' => esc_html__( 'Job Industry', 'mas-elementor' ),
					'plural'   => esc_html__( 'Job Industries', 'mas-elementor' ),
					'slug'     => esc_html_x( 'job-industry', 'Job industry permalink - resave permalinks after changing this', 'mas-elementor' ),
					'enable'   => get_option( 'job_manager_enable_industry', true ),
				),
				'job_listing_qualification' => array(
					'singular' => esc_html__( 'Job Qualification', 'mas-elementor' ),
					'plural'   => esc_html__( 'Job Qualification', 'mas-elementor' ),
					'slug'     => esc_html_x( 'job-qualification', 'Job qualification permalink - resave permalinks after changing this', 'mas-elementor' ),
					'enable'   => get_option( 'job_manager_enable_qualification', true ),
				),
			)
		);

		foreach ( $taxonomies_args as $taxonomy_name => $taxonomy_args ) {

				$singular = $taxonomy_args['singular'];
				$plural   = $taxonomy_args['plural'];
				$slug     = $taxonomy_args['slug'];

				$args = apply_filters(
					'register_taxonomy______taxonomy_name__args',
					array(
						'hierarchical'          => true,
						'update_count_callback' => '_update_post_term_count',
						'label'                 => $plural,
						'labels'                => array(
							'name'              => $plural,
							'singular_name'     => $singular,
							'menu_name'         => ucwords( $plural ),
							/* translators: %s: search term */
							'search_items'      => sprintf( esc_html__( 'Search %s', 'mas-elementor' ), $plural ),
							/* translators: %s: search term */
							'all_items'         => sprintf( esc_html__( 'All %s', 'mas-elementor' ), $plural ),
							/* translators: %s: search term */
							'parent_item'       => sprintf( esc_html__( 'Parent %s', 'mas-elementor' ), $singular ),
							/* translators: %s: search term */
							'parent_item_colon' => sprintf( esc_html__( 'Parent %s:', 'mas-elementor' ), $singular ),
							/* translators: %s: search term */
							'edit_item'         => sprintf( esc_html__( 'Edit %s', 'mas-elementor' ), $singular ),
							/* translators: %s: search term */
							'update_item'       => sprintf( esc_html__( 'Update %s', 'mas-elementor' ), $singular ),
							/* translators: %s: search term */
							'add_new_item'      => sprintf( esc_html__( 'Add New %s', 'mas-elementor' ), $singular ),
							/* translators: %s: search term */
							'new_item_name'     => sprintf( esc_html__( 'New %s Name', 'mas-elementor' ), $singular ),
						),
						'show_ui'               => true,
						'show_in_rest'          => true,
						'show_tagcloud'         => false,
						'public'                => true,
						'capabilities'          => array(
							'manage_terms' => $admin_capability,
							'edit_terms'   => $admin_capability,
							'delete_terms' => $admin_capability,
							'assign_terms' => $admin_capability,
						),
						'rewrite'               => array(
							'slug'         => $slug,
							'with_front'   => false,
							'hierarchical' => true,
						),
					)
				);

				register_taxonomy( $taxonomy_name, 'job_listing', $args );
		}
	}
}

global $jh_wpjm_job_manager;
$jh_wpjm_job_manager = new JH_WPJM_Job_Manager();
