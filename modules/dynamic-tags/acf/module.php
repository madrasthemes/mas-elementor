<?php
/**
 * ACF Dynamic Image tag .
 *
 * @package MASElementor\Modules\DynamicTags
 */

namespace MASElementor\Modules\DynamicTags\ACF;

use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Base_Tag;
use Elementor\Modules\DynamicTags;
use MASElementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Module class for acf dynamic tags.
 */
class Module extends DynamicTags\Module {

	const ACF_GROUP = 'acf';

	/**
	 * Get control options.
	 *
	 * @param array $types type.
	 *
	 * @return array
	 */
	public static function get_control_options( $types ) {
		// ACF >= 5.0.0.
		if ( function_exists( 'acf_get_field_groups' ) ) {
			$acf_groups = acf_get_field_groups();
		} else {
			$acf_groups = apply_filters( 'acf/get_field_groups', array() );
		}

		$groups = array();

		$options_page_groups_ids = array();

		if ( function_exists( 'acf_options_page' ) ) {
			$pages = acf_options_page()->get_pages();
			foreach ( $pages as $slug => $page ) {
				$options_page_groups = acf_get_field_groups(
					array(
						'options_page' => $slug,
					)
				);

				foreach ( $options_page_groups as $options_page_group ) {
					$options_page_groups_ids[] = $options_page_group['ID'];
				}
			}
		}

		foreach ( $acf_groups as $acf_group ) {
			// ACF >= 5.0.0.
			if ( function_exists( 'acf_get_fields' ) ) {
				if ( isset( $acf_group['ID'] ) && ! empty( $acf_group['ID'] ) ) {
					$fields = acf_get_fields( $acf_group['ID'] );
				} else {
					$fields = acf_get_fields( $acf_group );
				}
			} else {
				$fields = apply_filters( 'acf/field_group/get_fields', array(), $acf_group['id'] );
			}

			$options = array();

			if ( ! is_array( $fields ) ) {
				continue;
			}

			$has_option_page_location = in_array( $acf_group['ID'], $options_page_groups_ids, true );
			$is_only_options_page     = $has_option_page_location && 1 === count( $acf_group['location'] );

			foreach ( $fields as $field ) {
				if ( ! in_array( $field['type'], $types, true ) ) {
					continue;
				}

				// Use group ID for unique keys.
				if ( $has_option_page_location ) {
					$key             = 'options:' . $field['name'];
					$options[ $key ] = esc_html__( 'Options', 'mas-addons-for-elementor' ) . ':' . $field['label'];
					if ( $is_only_options_page ) {
						continue;
					}
				}

				$key             = $field['key'] . ':' . $field['name'];
				$options[ $key ] = $field['label'];
			}

			if ( empty( $options ) ) {
				continue;
			}

			if ( 1 === count( $options ) ) {
				$options = array( -1 => ' -- ' ) + $options;
			}

			$groups[] = array(
				'label'   => $acf_group['title'],
				'options' => $options,
			);
		}

		return $groups;
	}
	/**
	 * Adding key controls.
	 *
	 * @param Base_Tag $tag tag.
	 */
	public static function add_key_control( Base_Tag $tag ) {
		$tag->add_control(
			'key',
			array(
				'label'  => esc_html__( 'Key', 'mas-addons-for-elementor' ),
				'type'   => Controls_Manager::SELECT,
				'groups' => self::get_control_options( $tag->get_supported_fields() ),
			)
		);
	}
	/**
	 * Get ACF tag class name.
	 */
	public function get_tag_classes_names() {
		return array(
			'ACF_Image',
			'ACF_Text',
			'ACF_File',
			'ACF_URL',
		);
	}

	/**
	 * Get ACF field.
	 *
	 * @param string $field_key field.
	 * @param string $meta_key meta key.
	 * @param string $post_id post_id.
	 * @return mixed
	 */
	private static function get_acf_field( $field_key, $meta_key, $post_id = '' ) {
		$post_id = empty( $post_id ) ? get_the_ID() : $post_id;
		if ( 'options' === $field_key ) {
			$field = get_field_object( $meta_key, $field_key );
		} else {
			$field = self::get_field_from_current_item( $field_key, $post_id );
		}
		return $field;
	}

	/**
	 * Get ACF field from current item.
	 *
	 * @param string $field_key field key.
	 * @param string $post_id post_id.
	 * @return mixed
	 */
	private static function get_field_from_current_item( $field_key, $post_id = '' ) {
		$post_id = empty( $post_id ) ? get_the_ID() : $post_id;
		$field   = get_field_object( $field_key, $post_id );

		return $field;
	}

	// For use by ACF tags.
	/**
	 * Get tag value field.
	 *
	 * @param Base_Tag $tag tag.
	 */
	public static function get_text_tag_value_field( Base_Tag $tag ) {
		$key = $tag->get_settings( 'key' );
		if ( ! empty( $key ) && ! (int) $key ) {
			list( $field_key, $meta_key ) = explode( ':', $key );
			return array( self::get_acf_field( $field_key, $meta_key ), $meta_key );
		}

		return array();
	}

	// For use by ACF tags.
	/**
	 * Get tag value field.
	 *
	 * @param Base_Tag $tag tag.
	 * @param string   $author_id author_id.
	 */
	public static function get_user_tag_value_field( Base_Tag $tag, $author_id ) {
		$key = $tag->get_settings( 'key' );

		if ( ! empty( $key ) && ! (int) $key ) {
			list( $field_key, $meta_key ) = explode( ':', $key );
			return array( self::get_acf_field( $field_key, $meta_key, 'user_' . $author_id ), $meta_key );
		}

		return array();
	}

	// For use by ACF tags.
	/**
	 * Get tag value field.
	 *
	 * @param Base_Tag $tag tag.
	 */
	public static function get_tag_value_field( Base_Tag $tag ) {
		$key = $tag->get_settings( 'key' );

		if ( ! empty( $key ) ) {
			list( $field_key, $meta_key ) = explode( ':', $key );

			if ( 'options' === $field_key ) {
				$field = get_field_object( $meta_key, $field_key );
			} else {
				$field = get_field_object( $field_key, get_queried_object() );
			}

			return array( $field, $meta_key );
		}

		return array();
	}
	/**
	 * Get groups.
	 */
	public function get_groups() {
		return array(
			self::ACF_GROUP => array(
				'title' => esc_html__( 'ACF', 'mas-addons-for-elementor' ),
			),
		);
	}
}
