<?php
/**
 * Mas_Breadcrumb class.
 *
 * @package MAS Elementor
 */

defined( 'ABSPATH' ) || exit;

/**
 * Breadcrumb class.
 */
class Mas_Breadcrumb_Class {

	/**
	 * Breadcrumb trail.
	 *
	 * @var array
	 */
	protected $crumbs = array();

	/**
	 * Add a crumb so we don't get lost.
	 *
	 * @param string $name   Name.
	 * @param string $link   Link.
	 * @param string $before Append to the name like icon.
	 */
	public function add_crumb( $name, $link = '', $before = '' ) {
		$this->crumbs[] = array(
			$before . wp_strip_all_tags( $name ),
			$link,
		);
	}

	/**
	 * Reset crumbs.
	 */
	public function reset() {
		$this->crumbs = array();
	}

	/**
	 * Get the breadcrumb.
	 *
	 * @return array
	 */
	public function get_breadcrumb() {
		return apply_filters( 'mas_get_breadcrumb', $this->crumbs, $this );
	}

	/**
	 * Generate breadcrumb trail.
	 *
	 * @return array of breadcrumbs
	 */
	public function generate() {
		$conditionals = array(
			'is_home',
			'is_404',
			'is_attachment',
			'is_single',
			'is_page',
			'is_post_type_archive',
			'is_category',
			'is_tag',
			'is_author',
			'is_date',
			'is_tax',
		);

		if ( ( ! is_front_page() && ! ( is_post_type_archive() && intval( get_option( 'page_on_front' ) ) ) ) || is_paged() ) {
			foreach ( $conditionals as $conditional ) {
				if ( call_user_func( $conditional ) ) {
					call_user_func( array( $this, 'add_crumbs_' . substr( $conditional, 3 ) ) );
					break;
				}
			}

			$this->search_trail();
			$this->paged_trail();

			return $this->get_breadcrumb();
		}

		return array();
	}


	/**
	 * Is docs ..
	 */
	protected function prepend_docs_page() {
		$docs_home = wedocs_get_option( 'docs_home', 'wedocs_settings' );
		if ( $docs_home ) {
			$this->add_crumb( get_the_title( $docs_home ), get_permalink( $docs_home ) );
		}
	}

	/**
	 * Is home trail..
	 */
	protected function add_crumbs_home() {
		$this->add_crumb( single_post_title( '', false ) );
	}

	/**
	 * 404 trail.
	 */
	protected function add_crumbs_404() {
		$this->add_crumb( esc_html__( 'Error 404', 'mas-addons-for-elementor' ) );
	}

	/**
	 * Attachment trail.
	 */
	protected function add_crumbs_attachment() {
		global $post;

		$this->add_crumbs_single( $post->post_parent, get_permalink( $post->post_parent ) );
		$this->add_crumb( get_the_title(), get_permalink() );
	}

	/**
	 * Single post trail.
	 *
	 * @param int    $post_id   Post ID.
	 * @param string $permalink Post permalink.
	 */
	protected function add_crumbs_single( $post_id = 0, $permalink = '' ) {
		if ( ! $post_id ) {
			global $post;
		} else {
			$post = get_post( $post_id ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		}

		if ( ! $permalink ) {
			$permalink = get_permalink( $post );
		}

		if ( 'docs' === get_post_type( $post ) ) {

			$this->prepend_docs_page();

		} elseif ( 'post' !== get_post_type( $post ) ) {
			$post_type = get_post_type_object( get_post_type( $post ) );

			if ( ! empty( $post_type->has_archive ) ) {
				$this->add_crumb( $post_type->labels->singular_name, get_post_type_archive_link( get_post_type( $post ) ) );
			}
		} else {
			$cat = current( get_the_category( $post ) );
			if ( $cat ) {
				$this->term_ancestors( $cat->term_id, 'category' );
				$this->add_crumb( $cat->name, get_term_link( $cat ) );
			}
		}

		$this->add_crumb( get_the_title( $post ), $permalink );
	}

	/**
	 * Page trail.
	 */
	protected function add_crumbs_page() {
		global $post;

		if ( $post->post_parent ) {
			$parent_crumbs = array();
			$parent_id     = $post->post_parent;

			while ( $parent_id ) {
				$page            = get_post( $parent_id );
				$parent_id       = $page->post_parent;
				$parent_crumbs[] = array( get_the_title( $page->ID ), get_permalink( $page->ID ) );
			}

			$parent_crumbs = array_reverse( $parent_crumbs );

			foreach ( $parent_crumbs as $crumb ) {
				$this->add_crumb( $crumb[0], $crumb[1] );
			}
		}

		$this->add_crumb( get_the_title(), get_permalink() );
		$this->endpoint_trail();
	}

	/**
	 * Post type archive trail.
	 */
	protected function add_crumbs_post_type_archive() {
		$post_type = get_post_type_object( get_post_type() );

		if ( $post_type ) {
			$this->add_crumb( $post_type->labels->name, get_post_type_archive_link( get_post_type() ) );
		}
	}

	/**
	 * Category trail.
	 */
	protected function add_crumbs_category() {
		$this_category = get_category( $GLOBALS['wp_query']->get_queried_object() );

		if ( 0 !== intval( $this_category->parent ) ) {
			$this->term_ancestors( $this_category->term_id, 'category' );
		}

		$this->add_crumb( single_cat_title( '', false ), get_category_link( $this_category->term_id ) );
	}

	/**
	 * Tag trail.
	 */
	protected function add_crumbs_tag() {
		$queried_object = $GLOBALS['wp_query']->get_queried_object();

		/* translators: %s: tag name */
		$this->add_crumb( sprintf( esc_html__( 'Posts tagged &ldquo;%s&rdquo;', 'mas-addons-for-elementor' ), single_tag_title( '', false ) ), get_tag_link( $queried_object->term_id ) );
	}

	/**
	 * Add crumbs for date based archives.
	 */
	protected function add_crumbs_date() {
		if ( is_year() || is_month() || is_day() ) {
			$this->add_crumb( get_the_time( 'Y' ), get_year_link( get_the_time( 'Y' ) ) );
		}
		if ( is_month() || is_day() ) {
			$this->add_crumb( get_the_time( 'F' ), get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ) );
		}
		if ( is_day() ) {
			$this->add_crumb( get_the_time( 'd' ) );
		}
	}

	/**
	 * Add crumbs for taxonomies
	 */
	protected function add_crumbs_tax() {
		$this_term = $GLOBALS['wp_query']->get_queried_object();
		$taxonomy  = get_taxonomy( $this_term->taxonomy );

		$this->add_crumb( $taxonomy->labels->name );

		if ( 0 !== intval( $this_term->parent ) ) {
			$this->term_ancestors( $this_term->term_id, $this_term->taxonomy );
		}

		$this->add_crumb( single_term_title( '', false ), get_term_link( $this_term->term_id, $this_term->taxonomy ) );
	}

	/**
	 * Add a breadcrumb for author archives.
	 */
	protected function add_crumbs_author() {
		global $author;

		$userdata = get_userdata( $author );

		/* translators: %s: author name */
		$this->add_crumb( sprintf( esc_html__( 'Author: %s', 'mas-addons-for-elementor' ), $userdata->display_name ) );
	}

	/**
	 * Add crumbs for a term.
	 *
	 * @param int    $term_id  Term ID.
	 * @param string $taxonomy Taxonomy.
	 */
	protected function term_ancestors( $term_id, $taxonomy ) {
		$ancestors = get_ancestors( $term_id, $taxonomy );
		$ancestors = array_reverse( $ancestors );

		foreach ( $ancestors as $ancestor ) {
			$ancestor = get_term( $ancestor, $taxonomy );

			if ( ! is_wp_error( $ancestor ) && $ancestor ) {
				$this->add_crumb( $ancestor->name, get_term_link( $ancestor ) );
			}
		}
	}

	/**
	 * Endpoints.
	 */
	protected function endpoint_trail() {
		$endpoint       = '';
		$endpoint_title = '';

		if ( $endpoint_title ) {
			$this->add_crumb( $endpoint_title );
		}
	}

	/**
	 * Add a breadcrumb for search results.
	 */
	protected function search_trail() {
		if ( is_search() ) {
			/* translators: %s: search term */
			$this->add_crumb( sprintf( esc_html__( 'Search results for &ldquo;%s&rdquo;', 'mas-addons-for-elementor' ), get_search_query() ), remove_query_arg( 'paged' ) );
		}
	}

	/**
	 * Add a breadcrumb for pagination.
	 */
	protected function paged_trail() {
		if ( get_query_var( 'paged' ) ) {
			/* translators: %d: page number */
			$this->add_crumb( sprintf( esc_html__( 'Page %d', 'mas-addons-for-elementor' ), get_query_var( 'paged' ) ) );
		}
	}
}
