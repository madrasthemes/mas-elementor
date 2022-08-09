<?php
/**
 * Post Module.
 *
 * @package MASElementor/Modules/Posts
 */

namespace MASElementor\Modules\Posts;

use MASElementor\Base\Module_Base;
use MASElementor\Modules\Posts\Widgets\Posts_Base;
use MASElementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The Posts module class
 */
class Module extends Module_Base {

	/**
	 * Get the name of the module.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-posts';
	}

	/**
	 * Return the widgets in this module.
	 *
	 * @return array
	 */
	public function get_widgets() {
		return array(
			'Posts',
		);
	}

	/**
	 * Fix WP 5.5 pagination issue.
	 *
	 * Return true to mark that it's handled and avoid WP to set it as 404.
	 *
	 * @see https://github.com/elementor/elementor/issues/12126
	 * @see https://core.trac.wordpress.org/ticket/50976
	 *
	 * Based on the logic at \WP::handle_404.
	 *
	 * @param string $handled - Default false.
	 * @param array  $wp_query query.
	 *
	 * @return bool
	 */
	public function allow_posts_widget_pagination( $handled, $wp_query ) {
		// Check it's not already handled and it's a single paged query.
		if ( $handled || empty( $wp_query->query_vars['page'] ) || ! is_singular() || empty( $wp_query->post ) ) {
			return $handled;
		}

		$document = Plugin::elementor()->documents->get( $wp_query->post->ID );

		return $this->is_valid_pagination( $document->get_elements_data(), $wp_query->query_vars['page'] );
	}

	/**
	 * Checks a set of elements if there is a posts/archive widget that may be paginated to a specific page number.
	 *
	 * @param array  $elements elements.
	 * @param string $current_page current page.
	 *
	 * @return bool
	 */
	public function is_valid_pagination( array $elements, $current_page ) {
		$is_valid = false;

		// Get all widgets that may add pagination.
		$widgets       = Plugin::elementor()->widgets_manager->get_widget_types();
		$posts_widgets = array();
		foreach ( $widgets as $widget ) {
			if ( $widget instanceof Posts_Base ) {
				$posts_widgets[] = $widget->get_name();
			}
		}

		Plugin::elementor()->db->iterate_data(
			$elements,
			function( $element ) use ( &$is_valid, $posts_widgets, $current_page ) {
				if ( isset( $element['widgetType'] ) && in_array( $element['widgetType'], $posts_widgets, true ) ) {
					// Has pagination.
					if ( ! empty( $element['settings']['pagination_type'] ) ) {
						// No max pages limits.
						if ( empty( $element['settings']['pagination_page_limit'] ) ) {
							$is_valid = true;
						} elseif ( (int) $current_page <= (int) $element['settings']['pagination_page_limit'] ) {
							// Has page limit but current page is less than or equal to max page limit.
							$is_valid = true;
						}
					}
				}
			}
		);

		return $is_valid;
	}

	/**
	 * Instantiate the class.
	 */
	public function __construct() {
		parent::__construct();

		add_filter( 'mas_elementor/utils/get_public_post_types', array( $this, 'post_widget_post_types' ), 10, 1 );
		add_filter( 'pre_handle_404', array( $this, 'allow_posts_widget_pagination' ), 10, 2 );
		add_action( 'elementor/editor/after_save', array( $this, 'clear_elementor_cache' ), 15 );
	}

	/**
	 * Post widget post types to adding thrd party plugins.
	 *
	 * @param array $post_types post type.
	 */
	public function post_widget_post_types( $post_types ) {
		$post_types['job_listing'] = 'Job Listings';
		$post_types['jersdka;']    = 'MAS Portfolio';
		return $post_types;
	}

	/**
	 * Clear elementor cache.
	 */
	public function clear_elementor_cache() {
		// Make sure that Elementor loaded and the hook fired.
		if ( did_action( 'elementor/loaded' ) ) {
			// Automatically purge and regenerate the Elementor CSS cache.
			\Elementor\Plugin::instance()->files_manager->clear_cache();
		}
	}
}
