<?php
/**
 * Post Excerpt.
 *
 * @package MASElementor\Modules\DynamicTags\tags\post-excerpt.php
 */

namespace MASElementor\Modules\DynamicTags\Tags;

use Elementor\Controls_Manager;
use MASElementor\Core\Utils;
use MASElementor\Modules\DynamicTags\Tags\Base\Tag;
use MASElementor\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Post excerpt class.
 */
class Post_Excerpt extends \Elementor\Core\DynamicTags\Tag {
	/**
	 * Get post excerpt.
	 */
	public function get_name() {
		return 'post-excerpt';
	}
	/**
	 * Get post excerpt title.
	 */
	public function get_title() {
		return esc_html__( 'Post Excerpt', 'mas-addons-for-elementor' );
	}
	/**
	 * Get group for excerpt.
	 */
	public function get_group() {
		return Module::POST_GROUP;
	}
	/**
	 * Register controls for post excerpt.
	 */
	protected function register_controls() {

		$this->add_control(
			'max_length',
			array(
				'label' => esc_html__( 'Excerpt Length', 'mas-addons-for-elementor' ),
				'type'  => Controls_Manager::NUMBER,
			)
		);
	}
	/**
	 * Get post categories.
	 */
	public function get_categories() {
		return array( Module::TEXT_CATEGORY );
	}
	/**
	 * Render the excerpt.
	 */
	public function render() {
		// Allow only a real `post_excerpt` and not the trimmed `post_content` from the `get_the_excerpt` filter.
		$post = get_post();

		if ( ! $post || empty( $post->post_excerpt ) ) {
			return;
		}

		$settings   = $this->get_settings_for_display();
		$max_length = (int) $settings['max_length'];
		$excerpt    = $post->post_excerpt;

		$excerpt = Utils::trim_words( $excerpt, $max_length );

		echo wp_kses_post( $excerpt );
	}
}
