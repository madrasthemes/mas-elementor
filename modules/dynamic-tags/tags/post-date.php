<?php
/**
 * Post Date.
 *
 * @package MASElementor\Modules\DynamicTags\tags\post-date.php
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
class Post_Date extends Tag {

	/**
	 * Get tag name.
	 */
	public function get_name() {
		return 'post-date';
	}

	/**
	 * Get tag title.
	 */
	public function get_title() {
		return esc_html__( 'Post Date', 'mas-addons-for-elementor' );
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
			'type',
			array(
				'label'   => esc_html__( 'Type', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'post_date_gmt'     => esc_html__( 'Post Published', 'mas-addons-for-elementor' ),
					'post_modified_gmt' => esc_html__( 'Post Modified', 'mas-addons-for-elementor' ),
				),
				'default' => 'post_date_gmt',
			)
		);

		$this->add_control(
			'format',
			array(
				'label'   => esc_html__( 'Format', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'default' => esc_html__( 'Default', 'mas-addons-for-elementor' ),
					'F j, Y'  => gmdate( 'F j, Y' ),
					'Y-m-d'   => gmdate( 'Y-m-d' ),
					'm/d/Y'   => gmdate( 'm/d/Y' ),
					'd/m/Y'   => gmdate( 'd/m/Y' ),
					'human'   => esc_html__( 'Human Readable', 'mas-addons-for-elementor' ),
					'custom'  => esc_html__( 'Custom', 'mas-addons-for-elementor' ),
				),
				'default' => 'default',
			)
		);

		$this->add_control(
			'custom_format',
			array(
				'label'       => esc_html__( 'Custom Format', 'mas-addons-for-elementor' ),
				'default'     => '',
				'description' => sprintf( '<a href="https://go.elementor.com/wordpress-date-time/" target="_blank">%s</a>', esc_html__( 'Documentation on date and time formatting', 'mas-addons-for-elementor' ) ),
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
		$date_type = $this->get_settings( 'type' );
		$format    = $this->get_settings( 'format' );

		if ( 'human' === $format ) {
			/* translators: %s: Human readable date/time. */
			$value = sprintf( esc_html__( '%s ago', 'mas-addons-for-elementor' ), human_time_diff( strtotime( get_post()->{$date_type} ) ) );
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

			if ( 'post_date_gmt' === $date_type ) {
				$value = get_the_date( $date_format );
			} else {
				$value = get_the_modified_date( $date_format );
			}
		}
		echo wp_kses_post( $value );
	}
}
