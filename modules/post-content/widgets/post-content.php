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
		return esc_html__( 'Post Content', 'mas-addons-for-elementor' );
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
		return array( 'mas-addons-for-elementor' );
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
				'label' => esc_html__( 'Style', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'     => esc_html__( 'Alignment', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => esc_html__( 'Center', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'   => array(
						'title' => esc_html__( 'Right', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => esc_html__( 'Justified', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'enable_trim_content',
			array(
				'type'        => Controls_Manager::SWITCHER,
				'label'       => esc_html__( 'Enable Trim content', 'mas-addons-for-elementor' ),
				'default'     => 'yes',
				'description' => esc_html__( 'Only for mas-post looping', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'trim_words',
			array(
				'label'     => esc_html__( 'Content Length', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '10',
				'condition' => array(
					'enable_trim_content' => 'yes',
				),
			)
		);

		$this->add_control(
			'words_continuation',
			array(
				'label'     => esc_html__( 'Trimmed words', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'description' => esc_html__( 'Text to display in trimmed words', 'mas-addons-for-elementor' ),
				'default'   => '...',
				'condition' => array(
					'enable_trim_content' => 'yes',
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
		$settings         = $this->get_settings_for_display();
		static $did_posts = array();
		static $level     = 0;

		$post = get_post();

		if ( post_password_required( $post->ID ) ) {
			// PHPCS - `get_the_password_form`. is safe.
			echo get_the_password_form( $post->ID ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			return;
		}

		$re_occur          = apply_filters( 'mas_post_content_recursive_variable', false );
		$allowed_posttypes = apply_filters(
			'mas_post_content_allowed_posttypes',
			get_post_types(
				array(
					'public' => true,
				)
			)
		);
		$exclude_posttype  = apply_filters( 'mas_post_content_exclude_posttype', array( 'page' ) );

		if ( is_array( $allowed_posttypes ) ) {
			foreach ( $allowed_posttypes as $allowed_posttype ) {
				if ( in_array( $allowed_posttype, $exclude_posttype, true ) ) {
					continue;
				}
				if ( is_singular( $allowed_posttype ) ) {
					$re_occur = true;
				}
			}
		}

		// Avoid recursion.
		if ( isset( $did_posts[ $post->ID ] ) && $re_occur ) {
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

				if ( is_string( $preview_type ) && 0 === strpos( $preview_type, 'single' ) && ! empty( $preview_id ) ) {
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
				if ( 'yes' === $settings['enable_trim_content'] && ! empty( $settings['trim_words'] ) ) {
					echo apply_filters( 'the_content', wp_trim_words( get_the_content(), $settings['trim_words'], $settings['words_continuation'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				} else {
					echo apply_filters( 'the_content', get_the_content() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}

				wp_link_pages(
					array(
						'before'      => '<div class="page-links elementor-page-links"><span class="page-links-title elementor-page-links-title">' . esc_html__( 'Pages:', 'mas-addons-for-elementor' ) . '</span>',
						'after'       => '</div>',
						'link_before' => '<span>',
						'link_after'  => '</span>',
						'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'mas-addons-for-elementor' ) . ' </span>%',
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
		}

		// Restore edit mode state.
		Plugin::instance()->editor->set_edit_mode( $is_edit_mode );

		if ( $with_wrapper ) {
			echo '<div class="elementor-post__content">' . balanceTags( $content, true ) . '</div>'; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			echo $content;//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
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
