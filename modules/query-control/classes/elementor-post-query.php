<?php
/**
 * The Elementor Post Query.
 *
 * @package MASElementor/Modules/QueryControl/Classes
 */

namespace MASElementor\Modules\QueryControl\Classes;

use Elementor\Widget_Base;
use MASElementor\Modules\QueryControl\Module;
use MASElementor\Core\Utils;

/**
 * Class Elementor_Post_Query
 * Wrapper for WP_Query.
 * Used by the various widgets for generating the query, according to the controls added using Group_Control_Query.
 * Each class instance is associated with the specific widget that is passed in the class constructor.
 */
class Elementor_Post_Query {

	/**
	 * Widget
	 *
	 * @var Widget_Base
	 */
	protected $widget;

	/**
	 * Query Arguments
	 *
	 * @var Widget_Base
	 */
	protected $query_args;

	/**
	 * Prefix
	 *
	 * @var Widget_Base
	 */
	protected $prefix;

	/**
	 * Widget Settings
	 *
	 * @var Widget_Base
	 */
	protected $widget_settings;

	/**
	 * Elementor_Post_Query constructor.
	 *
	 * @param Widget_Base $widget the widget.
	 * @param string      $group_query_name group query name.
	 * @param array       $query_args query arguments.
	 */
	public function __construct( $widget, $group_query_name, $query_args = array() ) {
		$this->widget     = $widget;
		$this->prefix     = $group_query_name . '_';
		$this->query_args = $query_args;

		$settings = $this->widget->get_settings();
		$defaults = $this->get_query_defaults();

		$this->widget_settings = wp_parse_args( $settings, $defaults );
	}

	/**
	 * 1) build query args
	 * 2) invoke callback to fine-tune query-args
	 * 3) generate WP_Query object
	 * 4) if no results & fallback is set, generate a new WP_Query with fallback args
	 * 5) return WP_Query
	 *
	 * @return \WP_Query
	 */
	public function get_query() {
		$this->get_query_args();

		$offset_control = $this->get_widget_settings( 'offset' );

		$query_id = $this->get_widget_settings( 'query_id' );
		if ( ! empty( $query_id ) ) {
			add_action( 'pre_get_posts', array( $this, 'pre_get_posts_query_filter' ) );
		}

		$post_type = $this->get_widget_settings( 'post_type' );

		if ( 'by_id' !== $post_type && 0 < $offset_control ) {
			add_action( 'pre_get_posts', array( $this, 'fix_query_offset' ), 1 );
			add_filter( 'found_posts', array( $this, 'fix_query_found_posts' ), 1, 2 );
		}

		$query = new \WP_Query( $this->query_args );

		remove_action( 'pre_get_posts', array( $this, 'pre_get_posts_query_filter' ) );
		remove_action( 'pre_get_posts', array( $this, 'fix_query_offset' ), 1 );
		remove_filter( 'found_posts', array( $this, 'fix_query_found_posts' ), 1 );

		Module::add_to_avoid_list( wp_list_pluck( $query->posts, 'ID' ) );

		do_action( 'elementor/query/query_results', $query, $this->widget );

		return $query;
	}

	/**
	 * Get Default Query.
	 *
	 * @return array
	 */
	protected function get_query_defaults() {
		$defaults = array(
			$this->prefix . 'post_type'      => 'post',
			$this->prefix . 'posts_ids'      => array(),
			$this->prefix . 'orderby'        => 'date',
			$this->prefix . 'order'          => 'desc',
			$this->prefix . 'offset'         => 0,
			$this->prefix . 'posts_per_page' => 3,
		);

		return $defaults;
	}

	/**
	 * Get Query Arguments.
	 *
	 * @return array
	 */
	public function get_query_args() {
		$post_type = $this->get_widget_settings( 'post_type' );

		if ( 'current_query' === $post_type ) {
			$current_query_vars = $GLOBALS['wp_query']->query_vars;

			/**
			 * Current query variables.
			 *
			 * Filters the query variables for the current query.
			 *
			 * @since 1.0.0
			 *
			 * @param array $current_query_vars Current query variables.
			 */
			$current_query_vars = apply_filters_deprecated( 'mas_elementor/query_control/get_query_args/current_query', array( $current_query_vars ), '2.5.0', 'elementor/query/get_query_args/current_query' );
			$current_query_vars = apply_filters( 'elementor/query/get_query_args/current_query', $current_query_vars );
			$this->query_args   = $current_query_vars;
			return $current_query_vars;
		}

		$this->set_common_args();
		$this->set_order_args();
		$this->set_pagination_args();
		$this->set_post_include_args();

		if ( 'by_id' !== $post_type ) {

			$this->set_post_exclude_args();
			$this->set_avoid_duplicates();
			$this->set_terms_args();
			$this->set_author_args();
			$this->set_date_args();
		}

		$this->query_args = apply_filters( 'elementor/query/query_args', $this->query_args, $this->widget );

		return $this->query_args;
	}

	/**
	 * Set Pagination.
	 *
	 * @return void
	 */
	protected function set_pagination_args() {
		$this->set_query_arg( 'posts_per_page', $this->get_widget_settings( 'posts_per_page' ) );
		$sticky_post = $this->get_widget_settings( 'ignore_sticky_posts' ) ? true : false;
		$this->set_query_arg( 'ignore_sticky_posts', $sticky_post );
	}

	/**
	 * Set Arguments.
	 *
	 * @return void
	 */
	protected function set_common_args() {
		$this->query_args['post_status'] = 'publish'; // Hide drafts/private posts for admins.

		$post_type = $this->get_widget_settings( 'post_type' );
		if ( 'by_id' === $post_type ) {
			$post_types                    = Utils::get_public_post_types();
			$this->query_args['post_type'] = array_keys( $post_types );
		} else {
			$this->query_args['post_type'] = $post_type;
		}
	}

	/**
	 * Set Including Posts arguments.
	 *
	 * @return void
	 */
	protected function set_post_include_args() {

		if ( 'by_id' === $this->get_widget_settings( 'post_type' ) ) {

			$this->set_query_arg( 'post__in', $this->get_widget_settings( 'posts_ids' ) );

			if ( empty( $this->query_args['post__in'] ) ) {
				// If no selection - return an empty query.
				$this->query_args['post__in'] = array( 0 );
			}
		}
	}

	/**
	 * Set Excluding Posts arguments.
	 *
	 * @return void
	 */
	protected function set_post_exclude_args() {

		$exclude = $this->get_widget_settings( 'exclude' );

		if ( empty( $exclude ) ) {
			return;
		}

		$post__not_in = array();

		if ( $this->maybe_in_array( 'current_post', $exclude ) ) {
			if ( is_singular() ) {
				$post__not_in[] = get_queried_object_id();
			}
		}

		$exclude_ids = $this->get_widget_settings( 'exclude_ids' );
		if ( $this->maybe_in_array( 'manual_selection', $exclude ) && ! empty( $exclude_ids ) ) {
			$post__not_in = array_merge( $post__not_in, $exclude_ids );
		}

		$this->set_query_arg( 'post__not_in', $post__not_in );
	}

	/**
	 * Set Avoid Duplicates.
	 *
	 * @return void
	 */
	protected function set_avoid_duplicates() {
		if ( 'yes' === $this->get_widget_settings( 'avoid_duplicates' ) ) {
			$post__not_in = isset( $this->query_args['post__not_in'] ) ? $this->query_args['post__not_in'] : array();
			$post__not_in = array_merge( $post__not_in, Module::$displayed_ids );
			$this->set_query_arg( 'post__not_in', $post__not_in );
		}
	}

	/**
	 * Set Terms arguments.
	 *
	 * @return void
	 */
	protected function set_terms_args() {

		$post_type = $this->get_widget_settings( 'post_type' );

		if ( 'by_id' === $post_type ) {
			return;
		}
		$this->build_terms_query_include( 'include_term_ids' );
		$this->build_terms_query_exclude( 'exclude_term_ids' );
	}

	/**
	 * Set Include terms in query.
	 *
	 * @param string $control_id the control id.
	 *
	 * @return void
	 */
	protected function build_terms_query_include( $control_id ) {
		$this->build_terms_query( 'include', $control_id );
	}

	/**
	 * Set Exclude terms in query.
	 *
	 * @param string $control_id the control id.
	 *
	 * @return void
	 */
	protected function build_terms_query_exclude( $control_id ) {
		$this->build_terms_query( 'exclude', $control_id, true );
	}

	/**
	 * Set terms in query.
	 *
	 * @param string $tab_id the tab id.
	 * @param string $control_id the control id.
	 * @param string $exclude the exclude.
	 *
	 * @return void
	 */
	protected function build_terms_query( $tab_id, $control_id, $exclude = false ) {
		$tab_id         = $this->get_widget_settings( $tab_id );
		$settings_terms = $this->get_widget_settings( $control_id );

		if ( empty( $tab_id ) || empty( $settings_terms ) || ! $this->maybe_in_array( 'terms', $tab_id ) ) {
			return;
		}

		$terms    = array();
		$taxonomy = '';
		$new_tax  = false;

		// Switch to term_id in order to get all term children (sub-categories).
		foreach ( $settings_terms as $id ) {

			if ( is_numeric( $id ) ) {
				$field     = 'term_taxonomy_id';
				$term_data = get_term_by( $field, $id );
			} else {
				$field      = 'slug';
				$post_type  = $this->get_widget_settings( 'post_type' );
				$taxonomies = get_object_taxonomies( $post_type );
				foreach ( $taxonomies as $tax ) {
					$term_data = get_term_by( $field, $id, $tax );
					if ( false !== $term_data ) {
						break;
					}
				}
			}

			if ( false !== $term_data ) {
				if ( ! empty( $taxonomy ) && $taxonomy !== $term_data->taxonomy ) {
					$new_tax = true;
				}

				$taxonomy             = $term_data->taxonomy;
				$terms[ $taxonomy ][] = $term_data->term_id;
			}
		}

		$this->insert_tax_query( $terms, $exclude, $new_tax );
	}

	/**
	 * Insert Tax Query.
	 *
	 * @param array $terms the terms.
	 * @param bool  $exclude the exclude.
	 * @param bool  $new_tax the new tax.
	 *
	 * @return void
	 */
	protected function insert_tax_query( $terms, $exclude, $new_tax = false ) {

		$tax_query = array();
		foreach ( $terms as $taxonomy => $ids ) {
			$query = array(
				'taxonomy' => $taxonomy,
				'field'    => 'term_taxonomy_id',
				'terms'    => $ids,
			);

			if ( $exclude ) {
				$query['operator'] = 'NOT IN';
			}

			$tax_query[] = $query;
		}

		if ( empty( $tax_query ) ) {
			return;
		}

		if ( empty( $this->query_args['tax_query'] ) ) {
			$this->query_args['tax_query'] = $tax_query; //phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query

			if ( $new_tax ) {
				$this->query_args['tax_query']['relation'] = 'OR';
			}
		} else {
			$this->query_args['tax_query']['relation'] = 'AND';
			$this->query_args['tax_query'][]           = $tax_query;
		}
	}

	/**
	 * Set Author arguments.
	 *
	 * @return void
	 */
	protected function set_author_args() {

		$include_authors = $this->get_widget_settings( 'include_authors' );
		if ( ! empty( $include_authors ) && $this->maybe_in_array( 'authors', $this->get_widget_settings( 'include' ) ) ) {
			$this->set_query_arg( 'author__in', $include_authors );
		}

		$exclude_authors = $this->get_widget_settings( 'exclude_authors' );
		if ( ! empty( $exclude_authors ) && $this->maybe_in_array( 'authors', $this->get_widget_settings( 'exclude' ) ) ) {
			// exclude only if not explicitly included.
			if ( empty( $this->query_args['author__in'] ) ) {
				$this->set_query_arg( 'author__not_in', $exclude_authors );
			}
		}
	}

	/**
	 * Set Order arguments.
	 *
	 * @return void
	 */
	protected function set_order_args() {
		$order = $this->get_widget_settings( 'order' );
		if ( ! empty( $order ) ) {
			$this->set_query_arg( 'orderby', $this->get_widget_settings( 'orderby' ) );
			$this->set_query_arg( 'order', $this->get_widget_settings( 'order' ) );
		}
	}

	/**
	 * Set Date arguments.
	 *
	 * @return void
	 */
	protected function set_date_args() {

		$select_date = $this->get_widget_settings( 'select_date' );
		if ( ! empty( $select_date ) ) {
			$date_query = array();
			switch ( $select_date ) {
				case 'today':
					$date_query['after'] = '-1 day';
					break;
				case 'week':
					$date_query['after'] = '-1 week';
					break;
				case 'month':
					$date_query['after'] = '-1 month';
					break;
				case 'quarter':
					$date_query['after'] = '-3 month';
					break;
				case 'year':
					$date_query['after'] = '-1 year';
					break;
				case 'exact':
					$after_date = $this->get_widget_settings( 'date_after' );
					if ( ! empty( $after_date ) ) {
						$date_query['after'] = $after_date;
					}
					$before_date = $this->get_widget_settings( 'date_before' );
					if ( ! empty( $before_date ) ) {
						$date_query['before'] = $before_date;
					}
					$date_query['inclusive'] = true;
					break;
			}

			$this->set_query_arg( 'date_query', $date_query );
		}
	}

	/**
	 * Get widget settings
	 *
	 * @param string $control_name control name.
	 *
	 * @return mixed|null
	 */
	protected function get_widget_settings( $control_name ) {
		$control_name = $this->prefix . $control_name;
		return isset( $this->widget_settings[ $control_name ] ) ? $this->widget_settings[ $control_name ] : null;
	}

	/**
	 * Set Query Arguments
	 *
	 * @param string $key key.
	 * @param mixed  $value value.
	 */
	protected function set_query_arg( $key, $value ) {
		if ( ! isset( $this->query_args[ $key ] ) ) {
			$this->query_args[ $key ] = $value;
		}
	}

	/**
	 * Check Array
	 *
	 * @param string $value value.
	 * @param mixed  $maybe_array check array.
	 *
	 * @return bool
	 */
	protected function maybe_in_array( $value, $maybe_array ) {
		return is_array( $maybe_array ) ? in_array( $value, $maybe_array, true ) : $value === $maybe_array;
	}

	/**
	 * Pre Get posts
	 *
	 * @param \WP_Query $wp_query wp_query.
	 *
	 * @return void
	 */
	public function pre_get_posts_query_filter( $wp_query ) {
		if ( $this->widget ) {
			$query_id    = $this->get_widget_settings( 'query_id' );
			$widget_name = $this->widget->get_name();
			/**
			 * MAS Elementor posts widget Query args.
			 *
			 * It allows developers to alter individual posts widget queries.
			 *
			 * The dynamic portions of the hook name, `$widget_name` & `$query_id`, refers to the Widget name and Query ID respectively.
			 *
			 * @since 2.1.0
			 *
			 * @param \WP_Query     $wp_query wp_query
			 * @param Widget_Base   $this->current_widget current widget
			 */
			do_action_deprecated( "mas_elementor/{$widget_name}/query/{$query_id}", array( $wp_query, $this->widget ), '2.5.0', "elementor/query/{$query_id}" );
			do_action( "elementor/query/{$query_id}", $wp_query, $this->widget );
		}
	}

	/**
	 * Fix Query Offset.
	 *
	 * @param \WP_Query $query query.
	 *
	 * @return void
	 */
	public function fix_query_offset( &$query ) {
		$offset = $this->get_widget_settings( 'offset' );

		if ( $offset && $query->is_paged ) {
			$query->query_vars['offset'] = $offset + ( ( $query->query_vars['paged'] - 1 ) * $query->query_vars['posts_per_page'] );
		} else {
			$query->query_vars['offset'] = $offset;
		}
	}

	/**
	 * Fix Query Found Posts.
	 *
	 * @param int       $found_posts fount posts.
	 * @param \WP_Query $query querys.
	 *
	 * @return int
	 */
	public function fix_query_found_posts( $found_posts, $query ) {
		$offset = $this->get_widget_settings( 'offset' );

		if ( $offset ) {
			$found_posts -= $offset;
		}

		return $found_posts;
	}
}
