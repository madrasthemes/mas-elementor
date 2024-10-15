<?php
/**
 * Job Remote Position.
 *
 * @package MASElementor\Modules\DynamicTags\tags\job-remote-position.php
 */

namespace MASElementor\Modules\DynamicTags\Tags;

use MASElementor\Modules\DynamicTags\Tags\Base\Tag;
use MASElementor\Modules\DynamicTags\Module;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Job_Remote_Position class.
 */
class Job_Remote_Position extends \Elementor\Core\DynamicTags\Tag {
	/**
	 * Get name.
	 */
	public function get_name() {
		return 'mas-job-remote-position';
	}
	/**
	 * Get the title.
	 */
	public function get_title() {
		return esc_html__( 'Job Remote Position', 'mas-addons-for-elementor' );
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
		$remote_position = $post->_remote_position;
		if ( true === (bool) $remote_position ) {
			echo wp_kses_post( $this->get_settings( 'remote_position' ) );
		} else {
			echo wp_kses_post( $this->get_settings( 'non_remote_position' ) );
		}
	}

	/**
	 * Register Controls.
	 */
	protected function register_controls() {
		$this->add_control(
			'remote_position',
			array(
				'label'   => esc_html__( 'Remote Position Text', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'Remote',
			)
		);

		$this->add_control(
			'non_remote_position',
			array(
				'label'   => esc_html__( 'Non Remote Position Text', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
			)
		);
	}

}
