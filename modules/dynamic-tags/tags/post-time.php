<?php
/**
 * Post time.
 *
 * @package MASElementor\Modules\DynamicTags\tags\post-time.php
 */

namespace MASElementor\Modules\DynamicTags\Tags;

use Elementor\Controls_Manager;
use MASElementor\Modules\DynamicTags\Tags\Base\Tag;
use MASElementor\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Post time class.
 */
class Post_Time extends \Elementor\Core\DynamicTags\Tag {
	/**
	 * Get name.
	 */
	public function get_name() {
		return 'post-time';
	}
	/**
	 * Get the title.
	 */
	public function get_title() {
		return esc_html__( 'Post Time', 'mas-addons-for-elementor' );
	}
	/**
	 * Get the group.
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
	 * Register control post-time.
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
					'g:i a'   => gmdate( 'g:i a' ),
					'g:i A'   => gmdate( 'g:i A' ),
					'H:i'     => gmdate( 'H:i' ),
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
	 * Render for post time tag.
	 */
	public function render() {
		$time_type = $this->get_settings( 'type' );
		$format    = $this->get_settings( 'format' );

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

		if ( 'post_date_gmt' === $time_type ) {
			$value = get_the_time( $date_format );
		} else {
			$value = get_the_modified_time( $date_format );
		}

		echo wp_kses_post( $value );
	}
}
