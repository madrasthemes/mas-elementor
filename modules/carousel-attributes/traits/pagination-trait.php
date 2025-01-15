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

		$render_type = 'container' === $element->get_name() ? 'none' : 'template';

		$start = is_rtl() ? 'right' : 'left';
		$end   = ! is_rtl() ? 'right' : 'left';

		$element->start_controls_section(
			'swiper_section_navigation',
			array(
				'label'     => esc_html__( 'Swiper Pagination', 'mas-addons-for-elementor' ),
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
						'label'     => esc_html__( 'Pagination', 'mas-addons-for-elementor' ),
						'type'      => Controls_Manager::HEADING,
						'condition' => array(
							'enable_carousel' => 'yes',
							'show_pagination' => 'yes',
							'pagination'      => 'bullets',
						),
					)
				);

				$element->add_responsive_control(
					'mas_swiper_pagination_alignment',
					array(
						'label'     => esc_html__( 'Alignment', 'mas-addons-for-elementor' ),
						'type'      => Controls_Manager::CHOOSE,
						'options'   => array(
							'start'  => array(
								'title' => esc_html__( 'Start', 'mas-addons-for-elementor' ),
								'icon'  => "eicon-text-align-$start",
							),
							'center' => array(
								'title' => esc_html__( 'Center', 'mas-addons-for-elementor' ),
								'icon'  => 'eicon-text-align-center',
							),
							'end'    => array(
								'title' => esc_html__( 'End', 'mas-addons-for-elementor' ),
								'icon'  => "eicon-text-align-$end",
							),
						),
						'selectors' => array(
							'{{WRAPPER}} ' . $args['concat'] . ' .swiper-pagination' => 'text-align: {{VALUE}};',
						),
						'condition' => array(
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
						'label'          => esc_html__( 'Box Shadow', 'mas-addons-for-elementor' ),
						'selector'       => '{{WRAPPER}} ' . $args['concat'] . ' .swiper-pagination .swiper-pagination-bullet',
						'fields_options' => array(
							'box_shadow_type'     => array( 'default' => 'yes' ),
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
						'label'     => esc_html__( 'Normal', 'mas-addons-for-elementor' ),
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
						'label'      => esc_html__( 'Width', 'mas-addons-for-elementor' ),
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
						'label'      => esc_html__( 'Dots Height', 'mas-addons-for-elementor' ),
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
					'mas_swiper_pag_margin',
					array(
						'label'      => __( 'Margin', 'mas-addons-for-elementor' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => array( 'px', '%', 'custom' ),
						'selectors'  => array(
							'{{WRAPPER}} ' . $args['concat'] . ' .swiper-pagination .swiper-pagination-bullet' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
						),
						'condition'  => array(
							'enable_carousel' => 'yes',
							'show_pagination' => 'yes',
							'pagination'      => 'bullets',
						),
					)
				);

				$element->add_control(
					'mas_swiper_border_radius',
					array(
						'label'      => __( 'Border Radius', 'mas-addons-for-elementor' ),
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
					'mas_swiper_dots_background_color',
					array(
						'label'     => esc_html__( 'Background Color', 'mas-addons-for-elementor' ),
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
						'label'     => esc_html__( 'Opacity', 'mas-addons-for-elementor' ),
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
						'label'     => esc_html__( 'Active', 'mas-addons-for-elementor' ),
						'condition' => array(
							'enable_carousel' => 'yes',
							'show_pagination' => 'yes',
							'pagination'      => 'bullets',
						),
					)
				);

				$element->add_control(
					'swiper_dots_width_active',
					array(
						'label'      => esc_html__( 'Width', 'mas-addons-for-elementor' ),
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
							'{{WRAPPER}} ' . $args['concat'] . ' .swiper-pagination .swiper-pagination-bullet-active' => 'width: {{SIZE}}{{UNIT}}!important;',
						),
						'condition'  => array(
							'enable_carousel' => 'yes',
							'show_pagination' => 'yes',
							'pagination'      => 'bullets',
						),

					)
				);

				$element->add_control(
					'swiper_dots_height_active',
					array(
						'label'      => esc_html__( 'Dots Height', 'mas-addons-for-elementor' ),
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
							'{{WRAPPER}} ' . $args['concat'] . ' .swiper-pagination .swiper-pagination-bullet-active' => 'height: {{SIZE}}{{UNIT}}!important;',
						),
						'condition'  => array(
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
					'mas_swiper_border_radius_active',
					array(
						'label'      => __( 'Border Radius', 'mas-addons-for-elementor' ),
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
							'{{WRAPPER}} ' . $args['concat'] . ' .swiper-pagination .swiper-pagination-bullet-active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
						),
						'condition'  => array(
							'enable_carousel' => 'yes',
							'show_pagination' => 'yes',
							'pagination'      => 'bullets',
						),
					)
				);

				$element->add_control(
					'mas_swiper_dots_active_background_color',
					array(
						'label'     => esc_html__( 'Active Background Color', 'mas-addons-for-elementor' ),
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
						'label'     => esc_html__( 'Active Opacity', 'mas-addons-for-elementor' ),
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
					'dots_pagination_spacing',
					array(
						'label'     => esc_html__( 'Dots Spacing', 'mas-addons-for-elementor' ),
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
							'{{WRAPPER}} ' . $args['concat'] . ' .swiper-pagination.mas-pagination-horizontal .swiper-pagination-bullet' => 'margin-right: {{SIZE}}px !important',
							'{{WRAPPER}} ' . $args['concat'] . ' .swiper-pagination.mas-pagination-horizontal .swiper-pagination-bullet:last-child' => 'margin-right: 0px !important',
							'{{WRAPPER}} ' . $args['concat'] . ' .swiper-pagination.swiper-pagination-vertical .swiper-pagination-bullet' => 'margin-bottom: {{SIZE}}px !important',
							'{{WRAPPER}} ' . $args['concat'] . ' .swiper-pagination.swiper-pagination-vertical .swiper-pagination-bullet:last-child' => 'margin-bottom: 0px !important',
						),
						'condition' => array(
							'enable_carousel' => 'yes',
							'show_pagination' => 'yes',
							'pagination'      => 'bullets',
						),
						'separator' => 'before',
					)
				);

				$element->start_controls_tabs( 'fraction_pagination_tabs' );

				$element->start_controls_tab(
					'fraction_pagination_tab',
					array(
						'label' => esc_html__( 'Normal', 'mas-addons-for-elementor' ),
					)
				);

				$element->add_control(
					'mas_swiper_fraction_color',
					array(
						'label'     => esc_html__( 'Fraction Color', 'mas-addons-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '#000000',
						'selectors' => array(
							'{{WRAPPER}} ' . $args['concat'] . ' .swiper-pagination-fraction' => 'color: {{VALUE}}!important',
						),
						'condition' => array(
							'enable_carousel' => 'yes',
							'show_pagination' => 'yes',
							'pagination'      => 'fraction',
						),
					)
				);

				$element->add_control(
					'fraction_pagination_font_size',
					array(
						'label'      => esc_html__( 'Font Size', 'mas-addons-for-elementor' ),
						'type'       => Controls_Manager::SLIDER,
						'size_units' => array( 'px', '%' ),
						'range'      => array(
							'px' => array(
								'min' => 0,
								'max' => 300,
							),
							'%'  => array(
								'min' => 0,
								'max' => 100,
							),
						),
						'selectors'  => array(
							'{{WRAPPER}} ' . $args['concat'] . ' .swiper-pagination-fraction' => 'font-size: {{SIZE}}{{UNIT}} !important;',
						),
						'condition'  => array(
							'enable_carousel' => 'yes',
							'show_pagination' => 'yes',
							'pagination'      => 'fraction',
						),
					)
				);

				$element->end_controls_tab();

				$element->start_controls_tab(
					'fraction_pagination_active_tab',
					array(
						'label' => esc_html__( 'Active', 'mas-addons-for-elementor' ),
					)
				);

				$element->add_control(
					'mas_swiper_fraction_active_color',
					array(
						'label'     => esc_html__( 'Fraction Color', 'mas-addons-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '#B7BAC6',
						'selectors' => array(
							'{{WRAPPER}} ' . $args['concat'] . ' .swiper-pagination-fraction .swiper-pagination-current' => 'color: {{VALUE}}!important',
						),
						'condition' => array(
							'enable_carousel' => 'yes',
							'show_pagination' => 'yes',
							'pagination'      => 'fraction',
						),
					)
				);

				$element->add_control(
					'fraction_pagination_active_font_size',
					array(
						'label'      => esc_html__( 'Font Size', 'mas-addons-for-elementor' ),
						'type'       => Controls_Manager::SLIDER,
						'size_units' => array( 'px', '%' ),
						'range'      => array(
							'px' => array(
								'min' => 0,
								'max' => 300,
							),
							'%'  => array(
								'min' => 0,
								'max' => 100,
							),
						),
						'selectors'  => array(
							'{{WRAPPER}} ' . $args['concat'] . ' .swiper-pagination-fraction .swiper-pagination-current' => 'font-size: {{SIZE}}{{UNIT}} !important;',
						),
						'condition'  => array(
							'enable_carousel' => 'yes',
							'show_pagination' => 'yes',
							'pagination'      => 'fraction',
						),
					)
				);

				$element->end_controls_tab();

				$element->end_controls_tabs();

				$element->add_control(
					'mas_swiper_progress_background_color',
					array(
						'label'     => esc_html__( 'Progress Background Color', 'mas-addons-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '#B7BAC6',
						'selectors' => array(
							'{{WRAPPER}} ' . $args['concat'] . ' .swiper-pagination-progressbar' => 'background-color: {{VALUE}}!important',
						),
						'condition' => array(
							'enable_carousel' => 'yes',
							'show_pagination' => 'yes',
							'pagination'      => 'progressbar',
						),
					)
				);

				$element->add_control(
					'mas_swiper_progress_fill_color',
					array(
						'label'     => esc_html__( 'Filled Color', 'mas-addons-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '#000000',
						'selectors' => array(
							'{{WRAPPER}} ' . $args['concat'] . ' .swiper-pagination-progressbar .swiper-pagination-progressbar-fill' => 'background-color: {{VALUE}}!important',
						),
						'condition' => array(
							'enable_carousel' => 'yes',
							'show_pagination' => 'yes',
							'pagination'      => 'progressbar',
						),
					)
				);

				$element->add_control(
					'progress_pagination_height',
					array(
						'label'      => esc_html__( 'Progress Bar Height', 'mas-addons-for-elementor' ),
						'type'       => Controls_Manager::SLIDER,
						'size_units' => array( 'px', '%' ),
						'range'      => array(
							'px' => array(
								'min' => 0,
								'max' => 300,
							),
							'%'  => array(
								'min' => 0,
								'max' => 100,
							),
						),
						'selectors'  => array(
							'{{WRAPPER}} ' . $args['concat'] . ' .swiper-pagination-progressbar.swiper-pagination-horizontal' => 'padding-top: {{SIZE}}{{UNIT}} !important;padding-bottom: {{SIZE}}{{UNIT}} !important;',
						),
						'condition'  => array(
							'enable_carousel' => 'yes',
							'show_pagination' => 'yes',
							'pagination'      => 'progressbar',
							'mas_swiper_pagination_position' => 'relative',
						),
					)
				);

				$element->add_control(
					'progress_pagination_width',
					array(
						'label'      => esc_html__( 'Progress Bar Width', 'mas-addons-for-elementor' ),
						'type'       => Controls_Manager::SLIDER,
						'size_units' => array( 'px', '%' ),
						'default'    => array(
							'size' => 100,
							'unit' => '%',
						),
						'range'      => array(
							'px' => array(
								'min' => 0,
								'max' => 2600,
							),
							'%'  => array(
								'min' => 0,
								'max' => 100,
							),
						),
						'selectors'  => array(
							'{{WRAPPER}} ' . $args['concat'] . ' .swiper-pagination-progressbar.swiper-pagination-horizontal' => 'width: {{SIZE}}{{UNIT}} !important;',
						),
						'condition'  => array(
							'enable_carousel' => 'yes',
							'show_pagination' => 'yes',
							'pagination'      => 'progressbar',
						),
					)
				);

				$element->add_responsive_control(
					'swiper_pagination_z_index',
					array(
						'label'     => esc_html__( 'Z-Index', 'mas-addons-for-elementor' ),
						'type'      => Controls_Manager::NUMBER,
						'selectors' => array(
							'{{WRAPPER}} ' . $args['concat'] . ' .swiper-pagination' => 'z-index: {{VALUE}};',
						),
					)
				);

				$element->add_responsive_control(
					'swiper_pagination_spacing_top',
					array(
						'label'      => esc_html__( 'Top Spacing', 'mas-addons-for-elementor' ),
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
							'{{WRAPPER}} ' . $args['concat'] . ' .swiper-pagination-horizontal' => 'bottom: 0px !important;',
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

				$element->add_responsive_control(
					'pagination_spacing_bottom',
					array(
						'label'      => esc_html__( 'Bottom Spacing', 'mas-addons-for-elementor' ),
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
							'{{WRAPPER}} ' . $args['concat'] . ' .swiper-pagination-horizontal' => 'bottom: 0px !important;',
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

				$element->add_control(
					'mas_swiper_pagination_position',
					array(
						'label'       => esc_html__( 'Position', 'mas-addons-for-elementor' ),
						'type'        => Controls_Manager::SELECT,
						'default'     => 'absolute',
						'options'     => array(
							'relative' => esc_html__( 'Relative', 'mas-addons-for-elementor' ),
							'absolute' => esc_html__( 'Absolute', 'mas-addons-for-elementor' ),
						),
						'condition'   => array(
							'enable_carousel' => 'yes',
							'show_pagination' => 'yes',
						),
						'render_type' => $render_type,
					)
				);

				$element->add_responsive_control(
					'vertical_pagination_position',
					array(
						'label'     => esc_html__( 'Vertical Position', 'mas-addons-for-elementor' ),
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

				$left  = esc_html__( 'Left', 'mas-addons-for-elementor' );
				$right = esc_html__( 'Right', 'mas-addons-for-elementor' );

				$start = is_rtl() ? $right : $left;
				$end   = ! is_rtl() ? $right : $left;

				$element->add_control(
					'pag_offset_orientation_h',
					array(
						'label'       => esc_html__( 'Horizontal Orientation', 'mas-addons-for-elementor' ),
						'type'        => Controls_Manager::CHOOSE,
						'toggle'      => false,
						'default'     => 'start',
						'options'     => array(
							'start' => array(
								'title' => $start,
								'icon'  => 'eicon-h-align-left',
							),
							'end'   => array(
								'title' => $end,
								'icon'  => 'eicon-h-align-right',
							),
						),
						'classes'     => 'elementor-control-start-end',
						'render_type' => 'ui',
						'condition'   => array(
							'enable_carousel' => 'yes',
							'show_pagination' => 'yes',
							'mas_swiper_pagination_position' => 'absolute',
						),
					)
				);

				$element->add_responsive_control(
					'pag_offset_x',
					array(
						'label'      => esc_html__( 'Offset', 'mas-addons-for-elementor' ),
						'type'       => Controls_Manager::SLIDER,
						'range'      => array(
							'px' => array(
								'min'  => -1000,
								'max'  => 1000,
								'step' => 1,
							),
							'%'  => array(
								'min' => -200,
								'max' => 200,
							),
							'vw' => array(
								'min' => -200,
								'max' => 200,
							),
							'vh' => array(
								'min' => -200,
								'max' => 200,
							),
						),
						'default'    => array(
							'size' => '0',
						),
						'size_units' => array( 'px', '%', 'vw', 'vh', 'custom' ),
						'selectors'  => array(
							'body:not(.rtl) {{WRAPPER}} ' . $args['concat'] . ' .swiper-pagination' => 'left: {{SIZE}}{{UNIT}} !important',
							'body.rtl {{WRAPPER}} ' . $args['concat'] . ' .swiper-pagination' => 'right: {{SIZE}}{{UNIT}} !important',
						),
						'condition'  => array(
							'pag_offset_orientation_h!' => 'end',
							'enable_carousel'           => 'yes',
							'show_pagination'           => 'yes',
							'mas_swiper_pagination_position' => 'absolute',
						),
					)
				);

				$element->add_responsive_control(
					'pag_offset_x_end',
					array(
						'label'      => esc_html__( 'Offset', 'mas-addons-for-elementor' ),
						'type'       => Controls_Manager::SLIDER,
						'range'      => array(
							'px' => array(
								'min'  => -1000,
								'max'  => 1000,
								'step' => 0.1,
							),
							'%'  => array(
								'min' => -200,
								'max' => 200,
							),
							'vw' => array(
								'min' => -200,
								'max' => 200,
							),
							'vh' => array(
								'min' => -200,
								'max' => 200,
							),
						),
						'default'    => array(
							'size' => '0',
						),
						'size_units' => array( 'px', '%', 'vw', 'vh', 'custom' ),
						'selectors'  => array(
							'body:not(.rtl) {{WRAPPER}} ' . $args['concat'] . ' .swiper-pagination' => 'right: {{SIZE}}{{UNIT}} !important',
							'body.rtl {{WRAPPER}} ' . $args['concat'] . ' .swiper-pagination' => 'left: {{SIZE}}{{UNIT}} !important',
						),
						'condition'  => array(
							'pag_offset_orientation_h' => 'end',
							'enable_carousel'          => 'yes',
							'show_pagination'          => 'yes',
							'mas_swiper_pagination_position' => 'absolute',
						),
					)
				);

				$element->end_controls_section();
	}

}
