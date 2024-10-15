<?php
/**
 * The Group Control Related.
 *
 * @package MASElementor/Modules/QueryControl/Controls
 */

namespace MASElementor\Modules\QueryControl\Controls;

use Elementor\Controls_Manager;
use MASElementor\Core\Utils;
use MASElementor\Modules\QueryControl\Module as Query_Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Group_Control_Related
 *
 * @deprecated since 2.5.0, use class Group_Control_Query
 */
class Group_Control_Related extends Group_Control_Query {

	/**
	 * Get Type.
	 *
	 * @return string
	 */
	public static function get_type() {
		return 'related-query';
	}

	/**
	 * Build the group-controls array
	 * Note: this method completely overrides any settings done in Group_Control_Posts
	 *
	 * @param string $name name.
	 *
	 * @return array
	 */
	protected function init_fields_by_name( $name ) {
		$fields = parent::init_fields_by_name( $name );

		$tabs_wrapper    = $name . '_query_args';
		$include_wrapper = $name . '_query_include';

		$fields['post_type']['options']['related']                = __( 'Related', 'mas-addons-for-elementor' );
		$fields['include_term_ids']['condition']['post_type!'][]  = 'related';
		$fields['related_taxonomies']['condition']['post_type'][] = 'related';
		$fields['include_authors']['condition']['post_type!'][]   = 'related';
		$fields['exclude_authors']['condition']['post_type!'][]   = 'related';
		$fields['avoid_duplicates']['condition']['post_type!'][]  = 'related';
		$fields['offset']['condition']['post_type!'][]            = 'related';

		$related_taxonomies = array(
			'label'        => __( 'Term', 'mas-addons-for-elementor' ),
			'type'         => Controls_Manager::SELECT2,
			'options'      => $this->get_supported_taxonomies(),
			'label_block'  => true,
			'multiple'     => true,
			'condition'    => array(
				'include'   => 'terms',
				'post_type' => array(
					'related',
				),
			),
			'tabs_wrapper' => $tabs_wrapper,
			'inner_tab'    => $include_wrapper,
		);

		$related_fallback = array(
			'label'       => __( 'Fallback', 'mas-addons-for-elementor' ),
			'type'        => Controls_Manager::SELECT,
			'options'     => array(
				'fallback_none'   => __( 'None', 'mas-addons-for-elementor' ),
				'fallback_by_id'  => __( 'Manual Selection', 'mas-addons-for-elementor' ),
				'fallback_recent' => __( 'Recent Posts', 'mas-addons-for-elementor' ),
			),
			'default'     => 'fallback_none',
			'description' => __( 'Displayed if no relevant results are found. Manual selection display order is random', 'mas-addons-for-elementor' ),
			'condition'   => array(
				'post_type' => 'related',
			),
			'separator'   => 'before',
		);

		$fallback_ids = array(
			'label'        => __( 'Search & Select', 'mas-addons-for-elementor' ),
			'type'         => Query_Module::QUERY_CONTROL_ID,
			'options'      => array(),
			'label_block'  => true,
			'multiple'     => true,
			'autocomplete' => array(
				'object' => Query_Module::QUERY_OBJECT_POST,
			),
			'condition'    => array(
				'post_type'        => 'related',
				'related_fallback' => 'fallback_by_id',
			),
			'export'       => false,
		);

		$fields = \Elementor\Utils::array_inject( $fields, 'include_term_ids', array( 'related_taxonomies' => $related_taxonomies ) );
		$fields = \Elementor\Utils::array_inject( $fields, 'offset', array( 'related_fallback' => $related_fallback ) );
		$fields = \Elementor\Utils::array_inject( $fields, 'related_fallback', array( 'fallback_ids' => $fallback_ids ) );

		return $fields;
	}

	/**
	 * Get the supported taxonomies.
	 *
	 * @return array
	 */
	protected function get_supported_taxonomies() {
		$supported_taxonomies = array();

		$public_types = Utils::get_public_post_types();

		foreach ( $public_types as $type => $title ) {
			$taxonomies = get_object_taxonomies( $type, 'objects' );
			foreach ( $taxonomies as $key => $tax ) {
				if ( ! array_key_exists( $key, $supported_taxonomies ) ) {
					$label = $tax->label;
					if ( in_array( $tax->label, $supported_taxonomies, true ) ) {
						$label = $tax->label . ' (' . $tax->name . ')';
					}
					$supported_taxonomies[ $key ] = $label;
				}
			}
		}

		return $supported_taxonomies;
	}

	/**
	 * Initialize Presets.
	 *
	 * @return void
	 */
	protected static function init_presets() {
		parent::init_presets();
		static::$presets['related'] = array(
			'related_fallback',
			'fallback_ids',
		);
	}
}
