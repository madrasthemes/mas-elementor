<?php
/**
 * Carousel Attributes.
 *
 * @package MASElementor\Modules\CarouselAttributes
 */

namespace MASElementor\Modules\CarouselAttributes\Traits;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Utils;
use Elementor\Widget_Base;

/**
 * The button widget Trait.
 */
trait Pagination_Trait {

	/**
	 * Register button content controls.
	 *
	 * @param array $element Elements.
	 * @param array $args arguments for controls.
	 */
	public function register_pagination_style_controls( $element, $args = array( 'concat' => '+' ) ) {
		$element->start_controls_section(
			'swiper_section_navigation',
			array(
				'label'     => esc_html__( 'Swiper Pagination', 'mas-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'enable_carousel' => 'yes',
					'show_pagination' => 'yes',
				),
			)
		);

				$element->add_control(
					'swiper_heading_pagination',
					array(
						'label'     => esc_html__( 'Pagination', 'mas-elementor' ),
						'type'      => Controls_Manager::HEADING,
						'condition' => array(
							'enable_carousel' => 'yes',
							'show_pagination' => 'yes',
							'pagination'      => 'bullets',
						),
					)
				);

				$element->add_control(
					'swiper_dots_width',
					array(
						'label'      => esc_html__( 'Width', 'mas-elementor' ),
						'type'       => Controls_Manager::SLIDER,
						'size_units' => array( 'px', '%' ),
						'range'      => array(
							'%' => array(
								'max' => 100,
							),
						),
						'default'    => array(
							'unit' => 'px',
							'size' => 15,
						),
						'selectors'  => array(
							'{{WRAPPER}} ' . $args['concat'] . ' .swiper-pagination .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}!important;',
						),
						'condition'  => array(
							'enable_carousel' => 'yes',
							'show_pagination' => 'yes',
							'pagination'      => 'bullets',
						),

					)
				);

				$element->add_control(
					'swiper_dots_height',
					array(
						'label'      => esc_html__( 'Dots Height', 'mas-elementor' ),
						'type'       => Controls_Manager::SLIDER,
						'size_units' => array( 'px', '%' ),
						'range'      => array(
							'px' => array(
								'max' => 100,
							),
						),
						'default'    => array(
							'unit' => 'px',
							'size' => 15,
						),
						'selectors'  => array(
							'{{WRAPPER}} ' . $args['concat'] . ' .swiper-pagination .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}!important;',
						),
						'condition'  => array(
							'enable_carousel' => 'yes',
							'show_pagination' => 'yes',
							'pagination'      => 'bullets',
						),
					)
				);

				$element->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					array(
						'name'           => 'mas_swiper_box_shadow',
						'label'          => esc_html__( 'Box Shadow', 'mas-elementor' ),
						'selector'       => '{{WRAPPER}} ' . $args['concat'] . ' .swiper-pagination .swiper-pagination-bullet',
						'fields_options' => array(
							'box_shadow_position' => array(
								'default' => 'inset',
							),
							'box_shadow'          => array(
								'default' => array(
									'horizontal' => 0,
									'vertical'   => 0,
									'blur'       => 0,
									'spread'     => 4,
									'color'      => '#FFFFFF',
								),
							),
						),
						'condition'      => array(
							'enable_carousel' => 'yes',
							'show_pagination' => 'yes',
							'pagination'      => 'bullets',
						),
					)
				);

				$element->start_controls_tabs(
					'mas_swiper_pagination_style',
					array(
						'condition' => array(
							'enable_carousel' => 'yes',
							'show_pagination' => 'yes',
							'pagination'      => 'bullets',
						),
					)
				);

				$element->start_controls_tab(
					'mas_swiper_pagination_dots_normal',
					array(
						'label'     => esc_html__( 'Normal', 'mas-elementor' ),
						'condition' => array(
							'enable_carousel' => 'yes',
							'show_pagination' => 'yes',
							'pagination'      => 'bullets',
						),
					)
				);

				$element->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'           => 'mas_swiper_border',
						'selector'       => '{{WRAPPER}} ' . $args['concat'] . ' .swiper-pagination .swiper-pagination-bullet',
						'fields_options' => array(
							'border' => array(
								'default' => 'solid',
							),
							'width'  => array(
								'default' => array(
									'top'      => '2',
									'right'    => '2',
									'bottom'   => '2',
									'left'     => '2',
									'isLinked' => false,
								),
							),
							'color'  => array(
								'default' => '#B7BAC6',
							),
						),
						'condition'      => array(
							'enable_carousel' => 'yes',
							'show_pagination' => 'yes',
							'pagination'      => 'bullets',
						),
					)
				);

				$element->add_control(
					'mas_swiper_dots_background_color',
					array(
						'label'     => esc_html__( 'Background Color', 'mas-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '#FFFFFF',
						'selectors' => array(
							'{{WRAPPER}} ' . $args['concat'] . ' .swiper-pagination .swiper-pagination-bullet' => 'background-color: {{VALUE}}!important;',
						),
						'condition' => array(
							'enable_carousel' => 'yes',
							'show_pagination' => 'yes',
							'pagination'      => 'bullets',
						),
						'separator' => 'before',
					)
				);

				$element->add_control(
					'mas_swiper_dots_opacity',
					array(
						'label'     => esc_html__( 'Opacity', 'mas-elementor' ),
						'type'      => Controls_Manager::SLIDER,
						'default'   => array(
							'size' => 1,
						),
						'range'     => array(
							'px' => array(
								'max'  => 1,
								'min'  => 0.10,
								'step' => 0.01,
							),
						),
						'selectors' => array(
							'{{WRAPPER}} ' . $args['concat'] . ' .swiper-pagination .swiper-pagination-bullet' => 'opacity: {{SIZE}} !important;',
						),
						'condition' => array(
							'enable_carousel' => 'yes',
							'show_pagination' => 'yes',
							'pagination'      => 'bullets',
						),
					)
				);

				$element->end_controls_tab();

				$element->start_controls_tab(
					'mas_swiper_pagination_dots_active',
					array(
						'label'     => esc_html__( 'Active', 'mas-elementor' ),
						'condition' => array(
							'enable_carousel' => 'yes',
							'show_pagination' => 'yes',
							'pagination'      => 'bullets',
						),
					)
				);

				$element->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'           => 'mas_swiper_border_active',
						'selector'       => '{{WRAPPER}} ' . $args['concat'] . ' .swiper-pagination .swiper-pagination-bullet-active',
						'fields_options' => array(
							'border' => array(
								'default' => 'solid',
							),
							'width'  => array(
								'default' => array(
									'top'      => '2',
									'right'    => '2',
									'bottom'   => '2',
									'left'     => '2',
									'isLinked' => false,
								),
							),
							'color'  => array(
								'default' => '#B7BAC6',
							),
						),
						'condition'      => array(
							'enable_carousel' => 'yes',
							'show_pagination' => 'yes',
							'pagination'      => 'bullets',
						),
					)
				);

				$element->add_control(
					'mas_swiper_dots_active_background_color',
					array(
						'label'     => esc_html__( 'Acitve Background Color', 'mas-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '#B7BAC6',
						'selectors' => array(
							'{{WRAPPER}} ' . $args['concat'] . ' .swiper-pagination .swiper-pagination-bullet-active' => 'background-color: {{VALUE}}!important',
						),
						'condition' => array(
							'enable_carousel' => 'yes',
							'show_pagination' => 'yes',
							'pagination'      => 'bullets',
						),
						'separator' => 'before',
					)
				);

				$element->add_control(
					'mas_swiper_dots_opacity_active',
					array(
						'label'     => esc_html__( 'Active Opacity', 'mas-elementor' ),
						'type'      => Controls_Manager::SLIDER,
						'default'   => array(
							'size' => 1,
						),
						'range'     => array(
							'px' => array(
								'max'  => 1,
								'min'  => 0.10,
								'step' => 0.01,
							),
						),
						'selectors' => array(
							'{{WRAPPER}} ' . $args['concat'] . ' .swiper-pagination .swiper-pagination-bullet-active' => 'opacity: {{SIZE}} !important;',
						),
						'condition' => array(
							'enable_carousel' => 'yes',
							'show_pagination' => 'yes',
							'pagination'      => 'bullets',
						),
					)
				);

				$element->end_controls_tab();

				$element->end_controls_tabs();

				$element->add_control(
					'mas_swiper_border_radius',
					array(
						'label'      => __( 'Border Radius', 'mas-elementor' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => array( 'px', '%' ),
						'default'    => array(
							'top'      => '50',
							'right'    => '50',
							'bottom'   => '50',
							'left'     => '50',
							'unit'     => '%',
							'isLinked' => false,
						),
						'selectors'  => array(
							'{{WRAPPER}} ' . $args['concat'] . ' .swiper-pagination .swiper-pagination-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
						),
						'condition'  => array(
							'enable_carousel' => 'yes',
							'show_pagination' => 'yes',
							'pagination'      => 'bullets',
						),
					)
				);

				$element->add_control(
					'dots_pagination_spacing',
					array(
						'label'     => esc_html__( 'Dots Spacing', 'mas-elementor' ),
						'type'      => Controls_Manager::SLIDER,
						'default'   => array(
							'size' => 4,
						),
						'range'     => array(
							'px' => array(
								'min' => 0,
								'max' => 50,
							),
						),
						'selectors' => array(
							'{{WRAPPER}} ' . $args['concat'] . ' .swiper-pagination .swiper-pagination-bullet' => 'margin-right: {{SIZE}}px !important',
							'{{WRAPPER}} ' . $args['concat'] . ' .swiper-pagination .swiper-pagination-bullet:last-child' => 'margin-right: 0px !important',
						),
						'condition' => array(
							'enable_carousel' => 'yes',
							'show_pagination' => 'yes',
							'pagination'      => 'bullets',
						),
						'separator' => 'before',
					)
				);

				$element->add_control(
					'mas_swiper_pagination_position',
					array(
						'label'     => esc_html__( 'Position', 'mas-elementor' ),
						'type'      => Controls_Manager::SELECT,
						'default'   => 'absolute',
						'options'   => array(
							'relative' => esc_html__( 'Relative', 'mas-elementor' ),
							'absolute' => esc_html__( 'Absolute', 'mas-elementor' ),
						),
						'condition' => array(
							'enable_carousel' => 'yes',
							'show_pagination' => 'yes',
						),
					)
				);

				$element->add_control(
					'vertical_pagination_position',
					array(
						'label'     => esc_html__( 'Vertical Position', 'mas-elementor' ),
						'type'      => Controls_Manager::SLIDER,
						'default'   => array(
							'size' => 90,
						),
						'range'     => array(
							'%' => array(
								'min' => 0,
								'max' => 100,
							),
						),
						'selectors' => array(
							'{{WRAPPER}} ' . $args['concat'] . ' .swiper-pagination.swiper-pagination-bullets' => 'top: {{SIZE}}% !important;',
							'{{WRAPPER}} ' . $args['concat'] . ' .swiper-pagination-progressbar.swiper-pagination-horizontal' => 'top: {{SIZE}}% !important;',
							'{{WRAPPER}} ' . $args['concat'] . ' .swiper-pagination.swiper-pagination-fraction' => 'top: {{SIZE}}% !important;',
						),
						'condition' => array(
							'enable_carousel' => 'yes',
							'show_pagination' => 'yes',
							'mas_swiper_pagination_position' => 'absolute',
						),
					)
				);

				$element->add_control(
					'swiper_pagination_spacing_top',
					array(
						'label'      => esc_html__( 'Top Spacing', 'mas-elementor' ),
						'type'       => Controls_Manager::SLIDER,
						'default'    => array(
							'size' => 0,
						),
						'size_units' => array( 'px', '%', 'rem' ),
						'range'      => array(
							'%' => array(
								'min' => 0,
								'max' => 100,
							),
						),
						'selectors'  => array(
							'{{WRAPPER}} ' . $args['concat'] . ' .swiper-pagination.swiper-pagination-bullets' => 'margin-top: {{SIZE}}{{UNIT}} !important;',
							'{{WRAPPER}} ' . $args['concat'] . ' .swiper-pagination-progressbar.swiper-pagination-horizontal' => 'margin-top: {{SIZE}}{{UNIT}} !important;',
							'{{WRAPPER}} ' . $args['concat'] . ' .swiper-pagination.swiper-pagination-fraction' => 'margin-top: {{SIZE}}{{UNIT}} !important;',
						),
						'condition'  => array(
							'enable_carousel' => 'yes',
							'show_pagination' => 'yes',
							'mas_swiper_pagination_position' => 'relative',
						),
					)
				);

				$element->add_control(
					'pagination_spacing_bottom',
					array(
						'label'      => esc_html__( 'Bottom Spacing', 'mas-elementor' ),
						'type'       => Controls_Manager::SLIDER,
						'default'    => array(
							'size' => 0,
						),
						'size_units' => array( 'px', '%', 'rem' ),
						'range'      => array(
							'%' => array(
								'min' => 0,
								'max' => 100,
							),
						),
						'selectors'  => array(
							'{{WRAPPER}} ' . $args['concat'] . ' .swiper-pagination.swiper-pagination-bullets' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;',
							'{{WRAPPER}} ' . $args['concat'] . ' .swiper-pagination-progressbar.swiper-pagination-horizontal' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;',
							'{{WRAPPER}} ' . $args['concat'] . ' .swiper-pagination.swiper-pagination-fraction' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;',
						),
						'condition'  => array(
							'enable_carousel' => 'yes',
							'show_pagination' => 'yes',
							'mas_swiper_pagination_position' => 'relative',
						),
					)
				);

				$element->end_controls_section();
	}

}
