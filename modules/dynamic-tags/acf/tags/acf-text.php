<?php
/**
 * ACF Dynamic Text Tag .
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
 * ACF Text Class - Dynamic.
 */
class ACF_Text extends \Elementor\Core\DynamicTags\Tag {
	/**
	 * Get the name of dynamic  acf-image.
	 */
	public function get_name() {
		return 'mas-acf-text';
	}
	/**
	 * Get the title of dynamic acf-image.
	 */
	public function get_title() {
		return esc_html__( 'ACF', 'mas-addons-for-elementor' ) . ' ' . esc_html__( 'Field', 'mas-addons-for-elementor' );
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
		return array(
			Module::TEXT_CATEGORY,
			Module::POST_META_CATEGORY,
		);
	}
	/**
	 * Render.
	 */
	public function render() {
		$settings  = $this->get_settings_for_display();
		$author_id = is_author() ? get_the_ID() : get_the_author_meta( 'ID' );
		if ( 'user' === $settings['post_type_switch'] && ! empty( Module::get_user_tag_value_field( $this, $author_id ) ) ) {
			list( $field, $meta_key ) = Module::get_user_tag_value_field( $this, $author_id );
		} elseif ( Module::get_text_tag_value_field( $this ) ) {
			list( $field, $meta_key ) = Module::get_text_tag_value_field( $this );

		}
		if ( empty( $field ) && empty( $meta_key ) ) {
			echo wp_kses_post( 'Choose ACF Key' );
			return;
		}

		if ( ! empty( $field ) && ! empty( $field['type'] ) ) {
			$value = $field['value'];

			switch ( $field['type'] ) {
				case 'radio':
					if ( isset( $field['choices'][ $value ] ) ) {
						$value = $field['choices'][ $value ];
					}
					break;
				case 'select':
					// Use as array for `multiple=true` or `return_format=array`.
					$values = (array) $value;

					foreach ( $values as $key => $item ) {
						if ( isset( $field['choices'][ $item ] ) ) {
							$values[ $key ] = $field['choices'][ $item ];
						}
					}

					$value = implode( ', ', $values );

					break;
				case 'checkbox':
					$value  = (array) $value;
					$values = array();
					foreach ( $value as $item ) {
						if ( isset( $field['choices'][ $item ] ) ) {
							$values[] = $field['choices'][ $item ];
						} else {
							$values[] = $item;
						}
					}

					$value = implode( ', ', $values );

					break;
				case 'oembed':
					// Get from db without formatting.
					$value = $this->get_queried_object_meta( $meta_key );
					break;
				case 'google_map':
					$meta  = $this->get_queried_object_meta( $meta_key );
					$value = isset( $meta['address'] ) ? $meta['address'] : '';
					break;
			}
		} else {
			// Field settings has been deleted or not available.
			$value = get_field( $meta_key );
		}

		echo wp_kses_post( $value );
	}
	/**
	 * Get Panel settings.
	 */
	public function get_panel_template_setting_key() {
		return 'key';
	}
	/**
	 * Register Controls.
	 */
	protected function register_controls() {

		$this->add_control(
			'post_type_switch',
			array(
				'label'   => esc_html__( 'Options', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => array(
					'default' => esc_html__( 'Default', 'mas-addons-for-elementor' ),
					'user'    => esc_html__( 'User', 'mas-addons-for-elementor' ),
				),
			)
		);

		Module::add_key_control( $this );
	}
	/**
	 * Get Supported Fields.
	 */
	public function get_supported_fields() {
		return array(
			'text',
			'textarea',
			'number',
			'email',
			'password',
			'wysiwyg',
			'select',
			'checkbox',
			'radio',
			'true_false',

			// Pro.
			'oembed',
			'google_map',
			'date_picker',
			'time_picker',
			'date_time_picker',
			'color_picker',
		);
	}

	/**
	 * Get queried meta object.
	 *
	 * @param string $meta_key meta key.
	 */
	private function get_queried_object_meta( $meta_key ) {
		$value = '';
		if ( is_singular() ) {
			$value = get_post_meta( get_the_ID(), $meta_key, true );
		} elseif ( is_tax() || is_category() || is_tag() ) {
			$value = get_term_meta( get_queried_object_id(), $meta_key, true );
		}

		return $value;
	}
}
