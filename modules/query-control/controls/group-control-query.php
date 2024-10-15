<?php
/**
 * The Group Control Query.
 *
 * @package MASElementor/Modules/QueryControl/Controls
 */

namespace MASElementor\Modules\QueryControl\Controls;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Base;
use MASElementor\Core\Utils;
use MASElementor\Modules\QueryControl\Module as Query_Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Group_Control_Query
 *
 * @deprecated since 2.5.0, Elementor_Post_Query
 */
class Group_Control_Query extends Group_Control_Base {

	/**
	 * Property presets.
	 *
	 * @var array
	 */
	protected static $presets;

	/**
	 * Property fields.
	 *
	 * @var array
	 */
	protected static $fields;

	/**
	 * Get the post type.
	 *
	 * @return string
	 */
	public static function get_type() {
		return 'query-group';
	}

	/**
	 * Initialize Arguments.
	 *
	 * @param string $args arguments.
	 *
	 * @return void
	 */
	protected function init_args( $args ) {
		parent::init_args( $args );
		$args           = $this->get_args();
		static::$fields = $this->init_fields_by_name( $args['name'] );
	}

	/**
	 * Initialize fields.
	 *
	 * @return array
	 */
	protected function init_fields() {
		$args = $this->get_args();

		return $this->init_fields_by_name( $args['name'] );
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
		$fields = array();

		$name .= '_';

		$fields['post_type'] = array(
			'label'   => esc_html__( 'Source', 'mas-addons-for-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'options' => array(
				'by_id'         => esc_html__( 'Manual Selection', 'mas-addons-for-elementor' ),
				'current_query' => esc_html__( 'Current Query', 'mas-addons-for-elementor' ),
			),
		);

		$fields['query_args'] = array(
			'type' => Controls_Manager::TABS,
		);

		$tabs_wrapper    = $name . 'query_args';
		$include_wrapper = $name . 'query_include';
		$exclude_wrapper = $name . 'query_exclude';

		$fields['query_include'] = array(
			'type'         => Controls_Manager::TAB,
			'label'        => esc_html__( 'Include', 'mas-addons-for-elementor' ),
			'tabs_wrapper' => $tabs_wrapper,
			'condition'    => array(
				'post_type!' => array(
					'current_query',
					'by_id',
				),
			),
		);

		$fields['posts_ids'] = array(
			'label'        => esc_html__( 'Search & Select', 'mas-addons-for-elementor' ),
			'type'         => Query_Module::QUERY_CONTROL_ID,
			'label_block'  => true,
			'multiple'     => true,
			'autocomplete' => array(
				'object' => Query_Module::QUERY_OBJECT_POST,
			),
			'condition'    => array(
				'post_type' => 'by_id',
			),
			'tabs_wrapper' => $tabs_wrapper,
			'inner_tab'    => $include_wrapper,
			'export'       => false,
		);

		$fields['include'] = array(
			'label'        => esc_html__( 'Include By', 'mas-addons-for-elementor' ),
			'type'         => Controls_Manager::SELECT2,
			'multiple'     => true,
			'options'      => array(
				'terms'   => esc_html__( 'Term', 'mas-addons-for-elementor' ),
				'authors' => esc_html__( 'Author', 'mas-addons-for-elementor' ),
			),
			'condition'    => array(
				'post_type!' => array(
					'by_id',
					'current_query',
				),
			),
			'label_block'  => true,
			'tabs_wrapper' => $tabs_wrapper,
			'inner_tab'    => $include_wrapper,
		);

		$fields['include_term_ids'] = array(
			'label'        => esc_html__( 'Term', 'mas-addons-for-elementor' ),
			'description'  => esc_html__( 'Terms are items in a taxonomy. The available taxonomies are: Categories, Tags, Formats and custom taxonomies.', 'mas-addons-for-elementor' ),
			'type'         => Query_Module::QUERY_CONTROL_ID,
			'options'      => array(),
			'label_block'  => true,
			'multiple'     => true,
			'autocomplete' => array(
				'object'  => Query_Module::QUERY_OBJECT_CPT_TAX,
				'display' => 'detailed',
			),
			'group_prefix' => $name,
			'condition'    => array(
				'include'    => 'terms',
				'post_type!' => array(
					'by_id',
					'current_query',
				),
			),
			'tabs_wrapper' => $tabs_wrapper,
			'inner_tab'    => $include_wrapper,
		);

		$fields['include_authors'] = array(
			'label'        => esc_html__( 'Author', 'mas-addons-for-elementor' ),
			'label_block'  => true,
			'type'         => Query_Module::QUERY_CONTROL_ID,
			'multiple'     => true,
			'default'      => array(),
			'options'      => array(),
			'autocomplete' => array(
				'object' => Query_Module::QUERY_OBJECT_AUTHOR,
			),
			'condition'    => array(
				'include'    => 'authors',
				'post_type!' => array(
					'by_id',
					'current_query',
				),
			),
			'tabs_wrapper' => $tabs_wrapper,
			'inner_tab'    => $include_wrapper,
			'export'       => false,
		);

		$fields['query_exclude'] = array(
			'type'         => Controls_Manager::TAB,
			'label'        => esc_html__( 'Exclude', 'mas-addons-for-elementor' ),
			'tabs_wrapper' => $tabs_wrapper,
			'condition'    => array(
				'post_type!' => array(
					'by_id',
					'current_query',
				),
			),
		);

		$fields['exclude'] = array(
			'label'        => esc_html__( 'Exclude By', 'mas-addons-for-elementor' ),
			'type'         => Controls_Manager::SELECT2,
			'multiple'     => true,
			'options'      => array(
				'current_post'     => esc_html__( 'Current Post', 'mas-addons-for-elementor' ),
				'manual_selection' => esc_html__( 'Manual Selection', 'mas-addons-for-elementor' ),
				'terms'            => esc_html__( 'Term', 'mas-addons-for-elementor' ),
				'authors'          => esc_html__( 'Author', 'mas-addons-for-elementor' ),
			),
			'condition'    => array(
				'post_type!' => array(
					'by_id',
					'current_query',
				),
			),
			'label_block'  => true,
			'tabs_wrapper' => $tabs_wrapper,
			'inner_tab'    => $exclude_wrapper,
		);

		$fields['exclude_ids'] = array(
			'label'        => esc_html__( 'Search & Select', 'mas-addons-for-elementor' ),
			'type'         => Query_Module::QUERY_CONTROL_ID,
			'options'      => array(),
			'label_block'  => true,
			'multiple'     => true,
			'autocomplete' => array(
				'object' => Query_Module::QUERY_OBJECT_POST,
			),
			'condition'    => array(
				'exclude'    => 'manual_selection',
				'post_type!' => array(
					'by_id',
					'current_query',
				),
			),
			'tabs_wrapper' => $tabs_wrapper,
			'inner_tab'    => $exclude_wrapper,
			'export'       => false,
		);

		$fields['exclude_term_ids'] = array(
			'label'        => esc_html__( 'Term', 'mas-addons-for-elementor' ),
			'type'         => Query_Module::QUERY_CONTROL_ID,
			'options'      => array(),
			'label_block'  => true,
			'multiple'     => true,
			'autocomplete' => array(
				'object'  => Query_Module::QUERY_OBJECT_CPT_TAX,
				'display' => 'detailed',
			),
			'group_prefix' => $name,
			'condition'    => array(
				'exclude'    => 'terms',
				'post_type!' => array(
					'by_id',
					'current_query',
				),
			),
			'tabs_wrapper' => $tabs_wrapper,
			'inner_tab'    => $exclude_wrapper,
			'export'       => false,
		);

		$fields['exclude_authors'] = array(
			'label'        => esc_html__( 'Author', 'mas-addons-for-elementor' ),
			'type'         => Query_Module::QUERY_CONTROL_ID,
			'options'      => array(),
			'label_block'  => true,
			'multiple'     => true,
			'autocomplete' => array(
				'object'  => Query_Module::QUERY_OBJECT_AUTHOR,
				'display' => 'detailed',
			),
			'condition'    => array(
				'exclude'    => 'authors',
				'post_type!' => array(
					'by_id',
					'current_query',
				),
			),
			'tabs_wrapper' => $tabs_wrapper,
			'inner_tab'    => $exclude_wrapper,
			'export'       => false,
		);

		$fields['avoid_duplicates'] = array(
			'label'        => esc_html__( 'Avoid Duplicates', 'mas-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => '',
			'description'  => esc_html__( 'Set to Yes to avoid duplicate posts from showing up. This only effects the frontend.', 'mas-addons-for-elementor' ),
			'tabs_wrapper' => $tabs_wrapper,
			'inner_tab'    => $exclude_wrapper,
			'condition'    => array(
				'post_type!' => array(
					'by_id',
					'current_query',
				),
			),
		);

		$fields['offset'] = array(
			'label'        => esc_html__( 'Offset', 'mas-addons-for-elementor' ),
			'type'         => Controls_Manager::NUMBER,
			'default'      => 0,
			'condition'    => array(
				'post_type!' => array(
					'by_id',
					'current_query',
				),
			),
			'description'  => esc_html__( 'Use this setting to skip over posts (e.g. \'2\' to skip over 2 posts).', 'mas-addons-for-elementor' ),
			'tabs_wrapper' => $tabs_wrapper,
			'inner_tab'    => $exclude_wrapper,
		);

		$fields['select_date'] = array(
			'label'     => esc_html__( 'Date', 'mas-addons-for-elementor' ),
			'type'      => Controls_Manager::SELECT,
			'post_type' => '',
			'options'   => array(
				'anytime' => esc_html__( 'All', 'mas-addons-for-elementor' ),
				'today'   => esc_html__( 'Past Day', 'mas-addons-for-elementor' ),
				'week'    => esc_html__( 'Past Week', 'mas-addons-for-elementor' ),
				'month'   => esc_html__( 'Past Month', 'mas-addons-for-elementor' ),
				'quarter' => esc_html__( 'Past Quarter', 'mas-addons-for-elementor' ),
				'year'    => esc_html__( 'Past Year', 'mas-addons-for-elementor' ),
				'exact'   => esc_html__( 'Custom', 'mas-addons-for-elementor' ),
			),
			'default'   => 'anytime',
			'multiple'  => false,
			'condition' => array(
				'post_type!' => array(
					'by_id',
					'current_query',
				),
			),
			'separator' => 'before',
		);

		$fields['date_before'] = array(
			'label'       => esc_html__( 'Before', 'mas-addons-for-elementor' ),
			'type'        => Controls_Manager::DATE_TIME,
			'post_type'   => '',
			'label_block' => false,
			'multiple'    => false,
			'placeholder' => esc_html__( 'Choose', 'mas-addons-for-elementor' ),
			'condition'   => array(
				'select_date' => 'exact',
				'post_type!'  => array(
					'by_id',
					'current_query',
				),
			),
			'description' => esc_html__( 'Setting a ‘Before’ date will show all the posts published until the chosen date (inclusive).', 'mas-addons-for-elementor' ),
		);

		$fields['date_after'] = array(
			'label'       => esc_html__( 'After', 'mas-addons-for-elementor' ),
			'type'        => Controls_Manager::DATE_TIME,
			'post_type'   => '',
			'label_block' => false,
			'multiple'    => false,
			'placeholder' => esc_html__( 'Choose', 'mas-addons-for-elementor' ),
			'condition'   => array(
				'select_date' => 'exact',
				'post_type!'  => array(
					'by_id',
					'current_query',
				),
			),
			'description' => esc_html__( 'Setting an ‘After’ date will show all the posts published since the chosen date (inclusive).', 'mas-addons-for-elementor' ),
		);

		$fields['orderby'] = array(
			'label'     => esc_html__( 'Order By', 'mas-addons-for-elementor' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'post_date',
			'options'   => array(
				'post_date'  => esc_html__( 'Date', 'mas-addons-for-elementor' ),
				'post_title' => esc_html__( 'Title', 'mas-addons-for-elementor' ),
				'ID'         => esc_html__( 'ID', 'mas-addons-for-elementor' ),
				'menu_order' => esc_html__( 'Menu Order', 'mas-addons-for-elementor' ),
				'rand'       => esc_html__( 'Random', 'mas-addons-for-elementor' ),
			),
			'condition' => array(
				'post_type!' => 'current_query',
			),
		);

		$fields['order'] = array(
			'label'     => esc_html__( 'Order', 'mas-addons-for-elementor' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'desc',
			'options'   => array(
				'asc'  => esc_html__( 'ASC', 'mas-addons-for-elementor' ),
				'desc' => esc_html__( 'DESC', 'mas-addons-for-elementor' ),
			),
			'condition' => array(
				'post_type!' => 'current_query',
			),
		);

		$fields['posts_per_page'] = array(
			'label'     => esc_html__( 'Posts Per Page', 'mas-addons-for-elementor' ),
			'type'      => Controls_Manager::NUMBER,
			'default'   => 3,
			'condition' => array(
				'post_type!' => 'current_query',
			),
		);

		$fields['ignore_sticky_posts'] = array(
			'label'       => esc_html__( 'Ignore Sticky Posts', 'mas-addons-for-elementor' ),
			'type'        => Controls_Manager::SWITCHER,
			'default'     => 'yes',
			'condition'   => array(
				'post_type' => 'post',
			),
			'description' => esc_html__( 'Sticky-posts ordering is visible on frontend only', 'mas-addons-for-elementor' ),
		);

		$fields['query_id'] = array(
			'label'       => esc_html__( 'Query ID', 'mas-addons-for-elementor' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => '',
			'description' => esc_html__( 'Give your Query a custom unique id to allow server side filtering', 'mas-addons-for-elementor' ),
			'separator'   => 'before',
		);

		static::init_presets();

		return $fields;
	}

	/**
	 * Presets: filter controls subsets to be be used by the specific Group_Control_Query instance.
	 *
	 * Possible values:
	 * 'full' : (default) all presets
	 * 'include' : the 'include' tab - by id, by taxonomy, by author
	 * 'exclude': the 'exclude' tab - by id, by taxonomy, by author
	 * 'advanced_exclude': extend the 'exclude' preset with 'avoid-duplicates' & 'offset'
	 * 'date': date query controls
	 * 'pagination': posts per-page
	 * 'order': sort & ordering controls
	 * 'query_id': allow saving a specific query for future usage.
	 *
	 * Usage:
	 * full: build a Group_Controls_Query with all possible controls,
	 * when 'full' is passed, the Group_Controls_Query will ignore all other preset values.
	 * $this->add_group_control(
	 * Group_Control_Query::get_type(),
	 * [
	 * ...
	 * 'presets' => [ 'full' ],
	 *  ...
	 *  ] );
	 *
	 * Subset: build a Group_Controls_Query with subset of the controls,
	 * in the following example, the Query controls will set only the 'include' & 'date' query args.
	 * $this->add_group_control(
	 * Group_Control_Query::get_type(),
	 * [
	 * ...
	 * 'presets' => [ 'include', 'date' ],
	 *  ...
	 *  ] );
	 *
	 * @return void
	 */
	protected static function init_presets() {

		$tabs = array(
			'query_args',
			'query_include',
			'query_exclude',
		);

		static::$presets['include'] = array_merge(
			$tabs,
			array(
				'include',
				'include_ids',
				'include_term_ids',
				'include_authors',
			)
		);

		static::$presets['exclude'] = array_merge(
			$tabs,
			array(
				'exclude',
				'exclude_ids',
				'exclude_term_ids',
				'exclude_authors',
			)
		);

		static::$presets['advanced_exclude'] = array_merge(
			static::$presets['exclude'],
			array(
				'avoid_duplicates',
				'offset',
			)
		);

		static::$presets['date'] = array(
			'select_date',
			'date_before',
			'date_after',
		);

		static::$presets['pagination'] = array(
			'posts_per_page',
			'ignore_sticky_posts',
		);

		static::$presets['order'] = array(
			'orderby',
			'order',
		);

		static::$presets['query_id'] = array(
			'query_id',
		);
	}

	/**
	 * Set Offset.
	 *
	 * @param array $presets the presets.
	 * @param array $fields fields.
	 *
	 * @return array
	 */
	private function filter_by_presets( $presets, $fields ) {

		if ( in_array( 'full', $presets, true ) ) {
			return $fields;
		}

		$control_ids = array();
		foreach ( static::$presets as $key => $preset ) {
			$control_ids = array_merge( $control_ids, $preset );
		}

		foreach ( $presets as $preset ) {
			if ( array_key_exists( $preset, static::$presets ) ) {
				$control_ids = array_diff( $control_ids, static::$presets[ $preset ] );
			}
		}

		foreach ( $control_ids as $remove ) {
			unset( $fields[ $remove ] );
		}

		return $fields;

	}

	/**
	 * Prepare fields.
	 *
	 * @param array $fields the fields.
	 * @return array
	 */
	protected function prepare_fields( $fields ) {

		$args = $this->get_args();

		if ( ! empty( $args['presets'] ) ) {
			$fields = $this->filter_by_presets( $args['presets'], $fields );
		}

		$post_type_args = array();
		if ( ! empty( $args['post_type'] ) ) {
			$post_type_args['post_type'] = $args['post_type'];
		}

		$post_types = Utils::get_public_post_types( $post_type_args );

		$fields['post_type']['options']     = array_merge( $post_types, $fields['post_type']['options'] );
		$fields['post_type']['default']     = key( $post_types );
		$fields['posts_ids']['object_type'] = array_keys( $post_types );

		// skip parent, go directly to grandparent.
		return Group_Control_Base::prepare_fields( $fields );
	}

	/**
	 * Get Child Default Arguments.
	 *
	 * @return array
	 */
	protected function get_child_default_args() {
		$args            = parent::get_child_default_args();
		$args['presets'] = array( 'full' );

		return $args;
	}

	/**
	 * Get Default Arguments.
	 *
	 * @return array
	 */
	protected function get_default_options() {
		return array(
			'popover' => false,
		);
	}
}
