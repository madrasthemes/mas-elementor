<?php
/**
 * Job Position Filled.
 *
 * @package MASElementor\Modules\DynamicTags\tags\job-position-filled.php
 */

namespace MASElementor\Modules\DynamicTags\Tags;

use MASElementor\Modules\DynamicTags\Tags\Base\Tag;
use MASElementor\Modules\DynamicTags\Module;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Job_Position_Filled class.
 */
class Job_Position_Filled extends \Elementor\Core\DynamicTags\Tag {
	/**
	 * Get name.
	 */
	public function get_name() {
		return 'mas-job-position-filled';
	}
	/**
	 * Get the title.
	 */
	public function get_title() {
		return esc_html__( 'Job Position Filled', 'mas-addons-for-elementor' );
	}
	/**
	 * Get the group.
	 */
	public function get_group() {
		return Module::JOB_GROUP;
	}
	/**
	 * Get the categories.
	 */
	public function get_categories() {
		return array( Module::TEXT_CATEGORY );
	}
	/**
	 * Render the post  title.
	 */
	public function render() {
		$post = get_post( get_the_ID() );
		if ( ! $post || 'job_listing' !== $post->post_type ) {
			return;
		}
		$filled = $post->_filled;
		if ( true === (bool) $filled ) {
			echo wp_kses_post( $this->get_settings( 'filled' ) );
		} else {
			echo wp_kses_post( $this->get_settings( 'non_filled' ) );
		}
	}

	/**
	 * Register Controls.
	 */
	protected function register_controls() {
		$this->add_control(
			'filled',
			array(
				'label'   => esc_html__( 'Position Filled Text', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'This position has been filled',
			)
		);

		$this->add_control(
			'non_filled',
			array(
				'label'   => esc_html__( 'Non Position Filled Text', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
			)
		);
	}

}
