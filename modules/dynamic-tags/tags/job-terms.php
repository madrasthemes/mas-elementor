<?php
/**
 * Job Terms.
 *
 * @package MASElementor\Modules\DynamicTags\tags\job-terms.php
 */

namespace MASElementor\Modules\DynamicTags\Tags;

use Elementor\Controls_Manager;
use MASElementor\Modules\DynamicTags\Tags\Base\Tag;
use MASElementor\Modules\DynamicTags\Module;
use MASElementor\Core\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Job terms class job-term tag.
 */
class Job_Terms extends \Elementor\Core\DynamicTags\Tag {
	/**
	 * Get the job-term tag name.
	 */
	public function get_name() {
		return 'job-terms';
	}
	/**
	 * Get the job-term title name.
	 */
	public function get_title() {
		return esc_html__( 'Job Terms', 'mas-addons-for-elementor' );
	}
	/**
	 * Get the job-term group name.
	 */
	public function get_group() {
		return Module::JOB_GROUP;
	}
	/**
	 * Get the job-term categories name.
	 */
	public function get_categories() {
		return array( Module::TEXT_CATEGORY );
	}
	/**
	 * Register controls.
	 */
	protected function register_controls() {
		$taxonomy_filter_args = array(
			'object_type' => array( 0 => 'job_listing' ),
		);

		/**
		 * Dynamic tags taxonomy args.
		 *
		 * Filters the taxonomy arguments used to retrieve the registered taxonomies
		 * displayed in the taxonomy dynamic tag.
		 *
		 * @since 2.0.0
		 *
		 * @param array $taxonomy_filter_args An array of `key => value` arguments to
		 *                                    match against the taxonomy objects inside
		 *                                    the `get_taxonomies()` function.
		 */

		$taxonomies = Utils::get_taxonomies( $taxonomy_filter_args, 'objects' );

		$options = array();

		foreach ( $taxonomies as $taxonomy => $object ) {
			$options[ $taxonomy ] = $object->label;
		}

		$this->add_control(
			'taxonomy',
			array(
				'label'   => esc_html__( 'Taxonomy', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $options,
				'default' => 'post_tag',
			)
		);

		$this->add_control(
			'separator',
			array(
				'label'   => esc_html__( 'Separator', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => ', ',
			)
		);

	}
	/**
	 * Render for post-terms tag.
	 */
	public function render() {
		$settings = $this->get_settings();

		$terms = get_the_terms( get_the_ID(), $settings['taxonomy'] );

		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			return '';
		}

		$term_names = array();

		foreach ( $terms as $term ) {
			$term_names[] = '<span>' . $term->name . '</span>';
		}

		$value = implode( $settings['separator'], $term_names );

		echo wp_kses_post( $value );
	}
}
