<?php
/**
 * Post content Widget.
 *
 * @package mas-elementor
 */

namespace MASElementor\Modules\PostContent\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use MASElementor\Base\Base_Widget;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Post Content
 */
class Post_Content extends Base_Widget {

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		// `theme` prefix is to avoid conflicts with a dynamic-tag with same name.
		return 'mas-post-content';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Post Content', 'mas-elementor' );
	}

	/**
	 * Get the icon for the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-post-content';
	}

	/**
	 * Get the categories for the widget.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( 'mas-elementor' );
	}

	/**
	 * Set the key words for the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'content', 'post' );
	}

	/**
	 * Register controls for this widget.
	 *
	 * @return void
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_style',
			array(
				'label' => esc_html__( 'Style', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'     => esc_html__( 'Alignment', 'mas-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'mas-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => esc_html__( 'Center', 'mas-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'   => array(
						'title' => esc_html__( 'Right', 'mas-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => esc_html__( 'Justified', 'mas-elementor' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render.
	 *
	 * @return void
	 */
	protected function render() {
		// Post CSS should not be printed here because it overrides the already existing post CSS.
		$this->render_post_content( false, false );
	}

	/**
	 * Render post content.
	 *
	 * @param boolean $with_wrapper - Whether to wrap the content with a div.
	 * @param boolean $with_css - Decides whether to print inline CSS before the post content.
	 *
	 * @return void
	 */
	public function render_post_content( $with_wrapper = false, $with_css = true ) {
		static $did_posts = array();
		static $level     = 0;

		$post = get_post();

		if ( post_password_required( $post->ID ) ) {
			// PHPCS - `get_the_password_form`. is safe.
			echo get_the_password_form( $post->ID ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			return;
		}

		// Avoid recursion.
		if ( isset( $did_posts[ $post->ID ] ) ) {
			return;
		}

		$level++;
		$did_posts[ $post->ID ] = true;
		// End avoid recursion.

		$editor       = Plugin::instance()->editor;
		$is_edit_mode = $editor->is_edit_mode();

		if ( Plugin::instance()->preview->is_preview_mode( $post->ID ) ) {
			$content = Plugin::instance()->preview->builder_wrapper( '' ); // XSS ok.
		} else {
			$document = Plugin::$instance->documents->get_doc_for_frontend( $post->ID );
			// On view theme document show it's preview content.
			if ( $document ) {
				$preview_type = $document->get_settings( 'preview_type' );
				$preview_id   = $document->get_settings( 'preview_id' );

				if ( 0 === strpos( $preview_type, 'single' ) && ! empty( $preview_id ) ) {
					$post = get_post( $preview_id );

					if ( ! $post ) {
						$level--;

						return;
					}
				}
			}

			// Set edit mode as false, so don't render settings and etc. use the $is_edit_mode to indicate if we need the CSS inline.
			$editor->set_edit_mode( false );

			// Print manually (and don't use `the_content()`) because it's within another `the_content` filter, and the Elementor filter has been removed to avoid recursion.
			$content = Plugin::instance()->frontend->get_builder_content( $post->ID, $with_css );

			Plugin::instance()->frontend->remove_content_filter();

			if ( empty( $content ) ) {

				// Split to pages.
				setup_postdata( $post );

				/** This filter is documented in wp-includes/post-template.php */
				// PHPCS - `get_the_content` is safe.
				echo apply_filters( 'the_content', get_the_content() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

				wp_link_pages(
					array(
						'before'      => '<div class="page-links elementor-page-links"><span class="page-links-title elementor-page-links-title">' . esc_html__( 'Pages:', 'mas-elementor' ) . '</span>',
						'after'       => '</div>',
						'link_before' => '<span>',
						'link_after'  => '</span>',
						'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'mas-elementor' ) . ' </span>%',
						'separator'   => '<span class="screen-reader-text">, </span>',
					)
				);

				Plugin::instance()->frontend->add_content_filter();

				$level--;

				// Restore edit mode state.
				Plugin::instance()->editor->set_edit_mode( $is_edit_mode );

				return;
			} else {
				Plugin::instance()->frontend->remove_content_filters();
				$content = apply_filters( 'the_content', $content );
				Plugin::instance()->frontend->restore_content_filters();
			}
		} // End if().

		// Restore edit mode state.
		Plugin::instance()->editor->set_edit_mode( $is_edit_mode );

		if ( $with_wrapper ) {
			echo '<div class="elementor-post__content">' . balanceTags( $content, true ) . '</div>';  // XSS ok.
		} else {
			echo $content; // XSS ok.
		}

		$level--;

		if ( 0 === $level ) {
			$did_posts = array();
		}
	}

	/**
	 * Register plain content.
	 *
	 * @return void
	 */
	public function render_plain_content() {}
}
