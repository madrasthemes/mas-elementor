<?php
/**
 * Post Date.
 *
 * @package MASElementor\Modules\DynamicTags\tags\job-expiration-date.php
 */

namespace MASElementor\Modules\DynamicTags\Tags;

use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Tag;
use MASElementor\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Author Profile Picture class.
 */
class Job_Expiration extends Tag {

	/**
	 * Get tag name.
	 */
	public function get_name() {
		return 'job-expiration-date';
	}

	/**
	 * Get tag title.
	 */
	public function get_title() {
		return esc_html__( 'Job Expire', 'mas-elementor' );
	}

	/**
	 * Get tag group.
	 */
	public function get_group() {
		return Module::POST_GROUP;
	}

	/**
	 * Get categories.
	 */
	public function get_categories() {
		return array( Module::TEXT_CATEGORY );
	}

	/**
	 * Register Controls.
	 */
	protected function register_controls() {

		$this->add_control(
			'format',
			array(
				'label'   => esc_html__( 'Format', 'mas-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'F j, Y'  => gmdate( 'F j, Y' ),
					'Y-m-d'   => gmdate( 'Y-m-d' ),
					'm/d/Y'   => gmdate( 'm/d/Y' ),
					'd/m/Y'   => gmdate( 'd/m/Y' ),
					'human'   => esc_html__( 'Human Readable', 'mas-elementor' ),
					'custom'  => esc_html__( 'Custom', 'mas-elementor' ),
				),
				'default' => 'F j, Y',
			)
		);

		$this->add_control(
			'custom_format',
			array(
				'label'       => esc_html__( 'Custom Format', 'mas-elementor' ),
				'default'     => '',
				'description' => sprintf( '<a href="https://go.elementor.com/wordpress-date-time/" target="_blank">%s</a>', esc_html__( 'Documentation on date and time formatting', 'mas-elementor' ) ),
				'condition'   => array(
					'format' => 'custom',
				),
			)
		);
	}

	/**
	 * Render.
	 */
	public function render() {
		$format    = $this->get_settings( 'format' );

		$post = get_post( get_the_ID() );
		if ( ! $post || 'job_listing' !== $post->post_type ) {
			return;
		}
		$job_expires = $post->_job_expires;

		if ( 'human' === $format ) {
			/* translators: %s: Human readable date/time. */
			$value = sprintf( esc_html__( '%s ago', 'mas-elementor' ), human_time_diff( strtotime( $job_expires ) ) );
		} else {
			switch ( $format ) {
				case 'default':
					$date_format = '';
					break;
				case 'custom':
					$date_format = $this->get_settings( 'custom_format' );
					break;
				default:
					$date_format = $format;
					break;
			}

			$value = gmdate( $date_format, strtotime( $job_expires ) );
		}
		echo wp_kses_post( $value );
	}
}
