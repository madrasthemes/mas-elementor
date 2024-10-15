<?php
/**
 * ACF Dynamic Image tag .
 *
 * @package MASElementor\Modules\DynamicTags\ACF\Tags
 */

namespace MASElementor\Modules\DynamicTags\ACF\Tags;

use Elementor\Controls_Manager;
use MASElementor\Modules\DynamicTags\Tags\Base\Data_Tag;
use MASElementor\Modules\DynamicTags\ACF\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * ACF Image Class - Dynamic.
 */
class ACF_Image extends \Elementor\Core\DynamicTags\Data_Tag {
	/**
	 * Get the name of dynamic  acf-image.
	 */
	public function get_name() {
		return 'acf-image';
	}
	/**
	 * Get the title of dynamic acf-image.
	 */
	public function get_title() {
		return esc_html__( 'ACF', 'mas-addons-for-elementor' ) . ' ' . esc_html__( 'Image Field', 'mas-addons-for-elementor' );
	}
	/**
	 * Get the group of dynamic acf-image.
	 */
	public function get_group() {
		return Module::ACF_GROUP;
	}
	/**
	 * Get the categories of dynamic acf-image.
	 */
	public function get_categories() {
		return array( Module::IMAGE_CATEGORY );
	}
	/**
	 * Get the panel template dynamic acf-image.
	 */
	public function get_panel_template_setting_key() {
		return 'key';
	}
	/**
	 * Get the value of dynamic acf-image.
	 *
	 * @param array $options options.
	 */
	public function get_value( array $options = array() ) {
		$image_data = array(
			'id'  => null,
			'url' => '',
		);
		if ( empty( Module::get_tag_value_field( $this ) ) ) {
			return;
		}
		list( $field, $meta_key ) = Module::get_tag_value_field( $this );
		if ( $field && is_array( $field ) ) {
			$field['return_format'] = isset( $field['save_format'] ) ? $field['save_format'] : $field['return_format'];
			switch ( $field['return_format'] ) {
				case 'object':
				case 'array':
					$value = get_field( $field['key'] );
					break;
				case 'url':
					$value = array(
						'id'  => 0,
						'url' => get_field( $field['key'] ),
					);
					break;
				case 'id':
					$src   = wp_get_attachment_image_src( get_field( $field['key'] ), $field['preview_size'] );
					$value = array(
						'id'  => get_field( $field['key'] ),
						'url' => $src[0],
					);
					break;
			}
		}

		if ( ! isset( $value ) ) {
			// Field settings has been deleted or not available.
			$value = get_field( $meta_key );
		}

		if ( empty( $value ) && $this->get_settings( 'fallback' ) ) {
			$value = $this->get_settings( 'fallback' );
		}

		if ( ! empty( $value ) && is_array( $value ) ) {
			$image_data['id']  = $value['id'];
			$image_data['url'] = $value['url'];
		}

		return $image_data;
	}
	/**
	 * Register controls of dynamic acf-image.
	 */
	protected function register_controls() {
		Module::add_key_control( $this );

		$this->add_control(
			'fallback',
			array(
				'label' => esc_html__( 'Fallback', 'mas-addons-for-elementor' ),
				'type'  => Controls_Manager::MEDIA,
			)
		);
	}
	/**
	 * Get supported fields.
	 */
	public function get_supported_fields() {
		return array(
			'image',
		);
	}
}
