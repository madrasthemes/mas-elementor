<?php
/**
 * ACF Dynamic URL tag .
 *
 * @package MASElementor\Modules\DynamicTags\ACF\Tags
 */

namespace MASElementor\Modules\DynamicTags\ACF\Tags;

use MASElementor\Modules\DynamicTags\ACF\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * ACF URL Class - Dynamic.
 */
class ACF_URL extends \Elementor\Core\DynamicTags\Data_Tag {

	/**
	 * Get the name of dynamic  acf-url.
	 */
	public function get_name() {
		return 'acf-url';
	}

	/**
	 * Get the title of dynamic acf-url.
	 */
	public function get_title() {
		return esc_html__( 'ACF', 'mas-addons-for-elementor' ) . ' ' . esc_html__( 'URL Field', 'mas-addons-for-elementor' );
	}

	/**
	 * Get the group of dynamic acf-url.
	 */
	public function get_group() {
		return Module::ACF_GROUP;
	}

	/**
	 * Get the categories of dynamic acf-url.
	 */
	public function get_categories() {
		return array( Module::URL_CATEGORY );
	}

	/**
	 * Get the panel template dynamic acf-image.
	 */
	public function get_panel_template_setting_key() {
		return 'key';
	}

	/**
	 * Get the value of dynamic acf-url.
	 *
	 * @param array $options options.
	 */
	public function get_value( array $options = array() ) {
		list( $field, $meta_key ) = Module::get_tag_value_field( $this );

		if ( $field ) {
			$value = $field['value'];

			if ( is_array( $value ) && isset( $value[0] ) ) {
				$value = $value[0];
			}

			if ( $field ) {
				if ( ! isset( $field['return_format'] ) ) {
					$field['return_format'] = isset( $field['save_format'] ) ? $field['save_format'] : '';
				}

				switch ( $field['type'] ) {
					case 'email':
						if ( $value ) {
							$value = 'mailto:' . $value;
						}
						break;
					case 'image':
					case 'file':
						switch ( $field['return_format'] ) {
							case 'array':
							case 'object':
								$value = $value['url'];
								break;
							case 'id':
								if ( 'image' === $field['type'] ) {
									$src   = wp_get_attachment_image_src( $value, 'full' );
									$value = $src[0];
								} else {
									$value = wp_get_attachment_url( $value );
								}
								break;
						}
						break;
					case 'post_object':
					case 'relationship':
						$value = get_permalink( $value );
						break;
					case 'taxonomy':
						$value = get_term_link( $value, $field['taxonomy'] );
						break;
				} // End switch().
			}
		} else {
			// Field settings has been deleted or not available.
			$value = get_field( $meta_key );
		} // End if().

		if ( empty( $value ) && $this->get_settings( 'fallback' ) ) {
			$value = $this->get_settings( 'fallback' );
		}

		return wp_kses_post( $value );
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
			)
		);
	}

	/**
	 * Get supported fields.
	 */
	public function get_supported_fields() {
		return array(
			'text',
			'email',
			'image',
			'file',
			'page_link',
			'post_object',
			'relationship',
			'taxonomy',
			'url',
		);
	}
}
