<?php
/**
 * The Posts Widget.
 *
 * @package MASElementor/Modules/Posts/Widgets
 */

namespace MASElementor\Modules\Posts\Widgets;

use Elementor\Controls_Manager;
use MASElementor\Modules\QueryControl\Module as Module_Query;
use MASElementor\Modules\QueryControl\Controls\Group_Control_Related;
use MASElementor\Modules\Posts\Skins;
use MASElementor\Core\Utils;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Controls_Stack;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Posts
 */
class Posts extends Posts_Base {

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-posts';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Posts', 'mas-addons-for-elementor' );
	}

	/**
	 * Get the keywords related to the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'posts', 'cpt', 'item', 'loop', 'query', 'cards', 'custom post type' );
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
	 * Called on import to override.
	 *
	 * @param array $element The element being imported.
	 */
	public function on_import( $element ) {
		if ( ! get_post_type_object( $element['settings']['posts_post_type'] ) ) {
			$element['settings']['posts_post_type'] = 'post';
		}

		return $element;
	}


	/**
	 * Register controls for this widget.
	 */
	protected function register_controls() {

		$this->register_layout_section_controls();
		parent::register_controls();
		$this->register_pagination_section_controls();
		$this->update_posts_base_controls();
	}

	/**
	 * Update controls for this widget.
	 */
	protected function update_posts_base_controls() {

		$this->update_control(
			'select_loop_template',
			array(
				'condition'  => array(),
				// 'conditions' => array(
				// 'relation' => 'or',
				// 'terms'    => array(
				// array(
				// 'name'     => 'enable_loop_selection',
				// 'operator' => '==',
				// 'value'    => 'yes',
				// ),
				// array(
				// 'name'     => 'enable_sticky_loop',
				// 'operator' => '==',
				// 'value'    => 'yes',
				// ),
				// ),
				// ),

				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'terms' => array(
								array(
									'name'     => 'enable_loop_selection',
									'operator' => '==',
									'value'    => 'yes',
								),
								array(
									'name'     => 'template_options',
									'operator' => '==',
									'value'    => 'id',
								),
							),
						),
						array(
							'terms' => array(
								array(
									'name'     => 'enable_sticky_loop',
									'operator' => '==',
									'value'    => 'yes',
								),
								array(
									'name'     => 'template_options',
									'operator' => '==',
									'value'    => 'id',
								),
							),
						),
					),
				),
			)
		);

		$this->update_control(
			'select_slug_loop_template',
			array(
				'condition'  => array(),
				// 'conditions' => array(
				// 'relation' => 'or',
				// 'terms'    => array(
				// array(
				// 'name'     => 'enable_loop_selection',
				// 'operator' => '==',
				// 'value'    => 'yes',
				// ),
				// array(
				// 'name'     => 'enable_sticky_loop',
				// 'operator' => '==',
				// 'value'    => 'yes',
				// ),
				// ),
				// ),

				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'terms' => array(
								array(
									'name'     => 'enable_slug_loop_selection',
									'operator' => '==',
									'value'    => 'yes',
								),
								array(
									'name'     => 'template_options',
									'operator' => '==',
									'value'    => 'slug',
								),
							),
						),
						array(
							'terms' => array(
								array(
									'name'     => 'enable_sticky_loop',
									'operator' => '==',
									'value'    => 'yes',
								),
								array(
									'name'     => 'template_options',
									'operator' => '==',
									'value'    => 'slug',
								),
							),
						),
					),
				),
			)
		);

		$this->start_injection(
			array(
				'at' => 'after',
				'of' => 'slug_select_template',
			)
		);

			$this->add_control(
				'enable_sticky_loop',
				array(
					'type'       => Controls_Manager::SWITCHER,
					'label'      => esc_html__( 'Enable Sticky Template', 'mas-addons-for-elementor' ),
					'default'    => 'no',
					'separator'  => 'none',
					// 'condition' => array(
					// 'select_template!'       => '',
					// 'enable_loop_selection!' => 'yes',
					// ),

					'conditions' => array(
						'relation' => 'or',
						'terms'    => array(
							array(
								'terms' => array(
									array(
										'name'     => 'select_template',
										'operator' => '!=',
										'value'    => '',
									),
									array(
										'name'     => 'enable_loop_selection',
										'operator' => '!=',
										'value'    => 'yes',
									),
									array(
										'name'     => 'template_options',
										'operator' => '==',
										'value'    => 'id',
									),
								),
							),
							array(
								'terms' => array(
									array(
										'name'     => 'slug_select_template',
										'operator' => '!=',
										'value'    => '',
									),
									array(
										'name'     => 'enable_slug_loop_selection',
										'operator' => '!=',
										'value'    => 'yes',
									),
									array(
										'name'     => 'template_options',
										'operator' => '==',
										'value'    => 'slug',
									),
								),
							),
						),
					),
				)
			);

		$this->end_injection();

	}

	/**
	 * Set the query variable
	 */
	public function query_posts() {
		$settings   = $this->get_settings_for_display();
		$rows       = ! empty( $settings['rows'] ) ? $settings['rows'] : 4;
		$columns    = ! empty( $settings['columns'] ) ? $settings['columns'] : 4;
		$query_args = array(
			'posts_per_page' => intval( $columns * $rows ),
			'paged'          => $this->get_current_page(),
		);

		if ( ! empty( $settings['swiper_posts_per_page'] ) && 'yes' === $settings['enable_carousel'] ) {
			$query_args['posts_per_page'] = intval( $settings['swiper_posts_per_page'] );
		}

		$elementor_query = Module_Query::instance();
		$this->query     = $elementor_query->get_query( $this, $this->get_name(), $query_args, array() );
	}

	/**
	 * Widget render.
	 */
	public function render() {
		$settings = $this->get_settings_for_display();
		$this->query_posts();

		$query = $this->get_query();

		if ( ! $query->found_posts ) {
			?><div class="elementor-nothing-found mas-elementor-posts-nothing-found"><?php echo esc_html( $settings['nothing_found_message'] ); ?> </div>
			<?php
			return;
		}

		$this->carousel_post( $settings, $query );
		$this->thumb_post( $settings, $query );
		$this->render_script( 'swiper-' . $this->get_id() );

	}

	/**
	 * Thumb Post.
	 *
	 * @param array  $settings Settings of this widget.
	 * @param object $query query of this widget.
	 */
	public function thumb_post( $settings, $query ) {
		if ( 'yes' === $settings['enable_thumbs'] && 'yes' === $settings['enable_carousel'] ) {
			?>
			<div class="mas-posts-thumbs-wrapper">
				<div class="mas-posts-thumbs-container">
				<?php
					$this->carousel_thumb_header( $settings );

				if ( $query->in_the_loop ) {

					// $this->current_permalink = get_permalink();
					print( mas_render_template( $settings['thumb_template'], false ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					wp_reset_postdata();

				} else {
					$count = 1;
					while ( $query->have_posts() ) {

						$query->the_post();
						$this->thumb_slide_loop_start( $settings );
						// $this->current_permalink = get_permalink();
						if ( ! empty( $settings['thumb_template'] ) ) {
							print( mas_render_template( $settings['thumb_template'], false ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						} else {
							if ( 'horizontal' === $settings['thumbs_direction'] ) {
								?>
								<span class="swiper-step-pagination-title"><?php the_title(); ?></span>
								<?php
							} else {
								?>
								<p class="swiper-step-pagination-title"><?php the_title(); ?></p>
								<div class="swiper-pagination-progress-body">
									<div class="js-swiper-pagination-progress-body-helper swiper-pagination-progress-body-helper"></div>
								</div>
								<?php
							}
						}

						$this->thumb_slide_loop_end( $settings );

						$count ++;
					}
					if ( 'yes' !== $settings['enable_carousel'] ) {
						// mas-post-container close.
						?>
							</div>
						<?php
					}
							wp_reset_postdata();
				}

					$this->carousel_thumb_footer( $settings );
				?>
				</div>
			</div>
			<?php
		}

	}

	/**
	 * Carousel Loop Header.
	 *
	 * @param array $settings Settings of this widget.
	 */
	public function carousel_thumb_header( array $settings = array() ) {
		if ( 'yes' === $settings['enable_thumbs'] && 'yes' === $settings['enable_carousel'] ) {
			$json        = wp_json_encode( $this->get_swiper_thumbs_options( $settings ) );
			$thumbs_json = wp_json_encode( array( 'thumbs_selector' => 'thumb-' . $this->get_id() ) );
			$classes     = array( 'mas-js-swiper-thumbs' );
			if ( 'light' === $settings['thumbs_pag_color'] ) {
				$classes[] = 'horizontal' === $settings['thumbs_direction'] ? 'swiper-step-pagination-light swiper-step-pagination' : 'swiper-pagination-progress-light';
			} elseif ( 'dark' === $settings['thumbs_pag_color'] ) {
				$classes[] = 'horizontal' === $settings['thumbs_direction'] ? 'swiper-step-pagination' : 'swiper-pagination-progress';
			}

			$this->add_render_attribute( 'thumb_swiper', 'data-thumbs-options', $thumbs_json );
			$this->add_render_attribute( 'thumb_swiper', 'data-swiper-options', $json );
			$this->add_render_attribute( 'thumb_swiper', 'class', $classes );

			?>
			<div <?php $this->print_render_attribute_string( 'thumb_swiper' ); ?>>
				<div class="swiper-wrapper">
			<?php
			if ( 'yes' === $settings['enable_thumbs'] && 'yes' === $settings['enable_carousel'] ) {
				$this->add_render_attribute( 'thumb_bg_image', 'class', 'swiper-slide' );
				if ( 'vertical' === $settings['thumbs_direction'] ) {
					$this->add_render_attribute( 'thumb_bg_image', 'class', 'swiper-pagination-progress swiper-pagination-progress-ight' );
				}
			}
		}

	}

	/**
	 * Carousel Loop Footer.
	 *
	 * @param array $settings Settings of this widget.
	 */
	public function carousel_thumb_footer( array $settings = array() ) {
		if ( 'yes' === $settings['enable_thumbs'] && 'yes' === $settings['enable_carousel'] ) {
			?>
			</div></div>
			<?php
		}
	}

	/**
	 * Swiper loop start.
	 *
	 * @param array $settings Settings of this widget.
	 */
	public function thumb_slide_loop_start( array $settings = array() ) {
		if ( 'yes' === $settings['enable_thumbs'] && 'yes' === $settings['enable_carousel'] ) {
			?>
			<div <?php $this->print_render_attribute_string( 'thumb_bg_image' ); ?>>
			<?php
		}
	}

	/**
	 * Swiper loop start.
	 *
	 * @param array $settings Settings of this widget.
	 */
	public function thumb_slide_loop_end( array $settings = array() ) {
		if ( 'yes' === $settings['enable_thumbs'] && 'yes' === $settings['enable_carousel'] ) {

			?>
			</div>
			<?php
		}
	}

	/**
	 * Carousel Post.
	 *
	 * @param array  $settings Settings of this widget.
	 * @param object $query query of this widget.
	 */
	public function carousel_post( $settings, $query ) {
		$post_wrapper = 'mas-posts-container mas-posts mas-grid';
		$this->carousel_loop_header( $settings );

		// It's the global `wp_query` it self. and the loop was started from the theme.
		if ( $query->in_the_loop ) {
			// $this->current_permalink = get_permalink();

			if ( 'slug' === $settings['template_options'] ) {
				if ( ! empty( $settings['slug_select_template'] ) ) {
					$default_template = get_page_by_path( $settings['slug_select_template'], OBJECT, 'elementor_library' );
					if ( ! empty( $default_template->ID ) ) {
						print( mas_render_template( $default_template->ID, false ) ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}
				}
			} else {
				print( mas_render_template( $settings['select_template'], false ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			wp_reset_postdata();

		} else {
			$count = 1;
			if ( 'yes' !== $settings['enable_carousel'] ) {
				// mas-post-container open.
				?>
				<div class="<?php echo esc_attr( $post_wrapper ); ?>">
				<?php
			}
			while ( $query->have_posts() ) {

				$query->the_post();
				$this->carousel_slide_loop_start( $settings );
				if ( 'slug' === $settings['template_options'] ) {
					$template    = get_page_by_path( $settings['slug_select_template'], OBJECT, 'elementor_library' );
					$template    = $template->ID;
					$post_format = get_post_format();
					if ( ! empty( $post_format ) ) {
						$post_format_setting = $post_format . '_slug_select_template';
						if ( ! empty( $settings[ $post_format_setting ] ) ) {
							$template = get_page_by_path( $settings[ $post_format_setting ], OBJECT, 'elementor_library' );
							$template = $template->ID;
						}
					}
				} else {
					$template    = $settings['select_template'];
					$post_format = get_post_format();
					if ( ! empty( $post_format ) ) {
						$post_format_setting = $post_format . '_select_template';
						if ( ! empty( $settings[ $post_format_setting ] ) ) {
							$template = $settings[ $post_format_setting ];
						}
					}
				}

				// $this->current_permalink = get_permalink();
				if ( ! empty( $template ) ) {
					if ( ! empty( $settings['select_loop'] ) && in_array( (string) $count, $settings['select_loop'], true ) ) {
						if ( 'slug' === $settings['template_options'] ) {
							if ( ! empty( $settings['select_slug_loop_template'] ) ) {
								$default_template = get_page_by_path( $settings['select_slug_loop_template'], OBJECT, 'elementor_library' );
								if ( ! empty( $default_template->ID ) ) {
									print( mas_render_template( $default_template->ID, false ) ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								}
							}
						} else {
							print( mas_render_template( $settings['select_loop_template'], false ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
					} elseif ( 'yes' === $settings['enable_sticky_loop'] && is_sticky() ) {
						if ( 'slug' === $settings['template_options'] ) {
							if ( ! empty( $settings['select_slug_loop_template'] ) ) {
								$default_template = get_page_by_path( $settings['select_slug_loop_template'], OBJECT, 'elementor_library' );
								if ( ! empty( $default_template->ID ) ) {
									print( mas_render_template( $default_template->ID, false ) ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								}
							}
						} else {
							print( mas_render_template( $settings['select_loop_template'], false ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
					} else {
						print( mas_render_template( $template, false ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}
				} else {
					mas_elementor_get_template( 'widgets/posts/post-classic.php', array( 'widget' => $this ) );
				}

				$this->carousel_slide_loop_end( $settings );

				$count ++;
			}
			if ( 'yes' !== $settings['enable_carousel'] ) {
				if ( 'in' === $settings['pagination_position_in_out'] ) {
					$this->render_loop_footer();
				}
				// mas-post-container close.
				?>
				</div>
				<?php
			}
					wp_reset_postdata();
		}

		$this->carousel_loop_footer( $settings );

		if ( 'yes' !== $settings['enable_carousel'] ) {
			if ( 'out' === $settings['pagination_position_in_out'] ) {
				$this->render_loop_footer();
			}
		}
	}
}
