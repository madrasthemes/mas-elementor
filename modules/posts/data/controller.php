<?php
/**
 * Controller.
 *
 * @package MASElementor\Modules\Posts\Data
 */

namespace MASElementor\Modules\Posts\Data;

use Elementor\Utils;
use MASElementor\Data\Base\Controller as Controller_Base;
use MASElementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Posts widget data controller class.
 */
class Controller extends Controller_Base {

	/**
	 * Get the name of the controller.
	 */
	public function get_name() {
		return 'mas-posts-widget';
	}

	/**
	 * Register the endpoints.
	 */
	public function register_endpoints() {
		// There is only get items end point.
	}

	/**
	 * Get the content.
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 * @return array
	 */
	public function get_items( $request ) {
		$post_id  = absint( $request->get_param( 'post_id' ) );
		$document = Plugin::elementor()->documents->get( $post_id );

		if ( ! $document ) {
			return new \WP_Error(
				'document_not_exist',
				esc_html__( 'Document doesn\'t exist', 'mas-addons-for-elementor' ),
				array( 'status' => 404 )
			);
		}

		if ( ! is_post_publicly_viewable( $post_id ) && ! current_user_can( 'read_post', $post_id ) ) {
			return new \WP_Error(
				'rest_forbidden',
				esc_html__( 'Sorry, you are not allowed to view this document.', 'mas-addons-for-elementor' ),
				array( 'status' => 403 )
			);
		}

		$element_data = $document->get_elements_data();
		$posts_widget = Utils::find_element_recursive( $element_data, $request->get_param( 'element_id' ) );

		if ( empty( $posts_widget ) ) {
			return new \WP_Error(
				'Element_not_exist',
				esc_html__( 'Posts widget doesn\'t exist', 'mas-addons-for-elementor' ),
				array( 'status' => 404 )
			);
		}

		set_query_var( 'paged', $request->get_param( 'page' ) );

		/**
		 * The Element instance.
		 *
		 * @var \MASElementor\Modules\Posts\Widgets\Posts $element_instance The Element instance.
		 */
		$element_instance = Plugin::elementor()->elements_manager->create_element_instance( $posts_widget );

		ob_start();
		$element_instance->render_content();
		$html = ob_get_clean();

		return array(
			'content' => $html,
		);
	}
	/**
	 * Get callback permission.
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 * @return bool.
	 */
	public function get_permission_callback( $request ) {
		return true;
	}
}
