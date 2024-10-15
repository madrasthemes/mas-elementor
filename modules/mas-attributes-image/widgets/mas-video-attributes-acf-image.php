<?php
/**
 * The MAS Nav Tab Widget.
 *
 * @package MASElementor/Modules/MasEpisodes/Widgets
 */

namespace MASElementor\Modules\MasAttributesImage\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * MAS Episodes Elementor Widget.
 */
class Mas_Video_Attributes_ACF_Image extends Mas_Attributes_ACF_Image_Base {

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-video-attributes-acf-image';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'MAS Video Attributes Image', 'mas-addons-for-elementor' );
	}

	/**
	 * Get the icon for the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-meta-data';
	}

	/**
	 * Get the keywords associated with the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'attributes', 'mas' );
	}

	/**
	 * Get the categories for the widget.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( 'mas-elements' );
	}

	/**
	 * Taxonomy Options.
	 */
	public function get_taxonomy_options() {
		$args       = array(
			'object_type' => array( 'video' ),
		);
		$taxonomies = get_taxonomies( $args, 'objects' );
		$options    = array();
		foreach ( $taxonomies as $taxonomy ) {
			if ( 'video_visibility' !== $taxonomy->name ) {
				$options[ $taxonomy->name ] = $taxonomy->label;
			}
		}
		return $options;
	}
}
