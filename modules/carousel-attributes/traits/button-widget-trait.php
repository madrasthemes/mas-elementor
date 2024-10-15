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
trait Button_Widget_Trait {
	/**
	 * Get button sizes.
	 *
	 * Retrieve an array of button sizes for the button widget.
	 *
	 * @return array An array containing button sizes.
	 */
	public static function get_button_sizes() {
		return array(
			'xs' => esc_html__( 'Extra Small', 'mas-addons-for-elementor' ),
			'sm' => esc_html__( 'Small', 'mas-addons-for-elementor' ),
			'md' => esc_html__( 'Medium', 'mas-addons-for-elementor' ),
			'lg' => esc_html__( 'Large', 'mas-addons-for-elementor' ),
			'xl' => esc_html__( 'Extra Large', 'mas-addons-for-elementor' ),
		);
	}

	/**
	 * Register button content controls.
	 *
	 * @param array $element Elements.
	 * @param array $args arguments for controls.
	 */
	public function register_button_content_controls( $element, $args = array( 'button_concat' => '~' ) ) {
		if ( 'container' === $element->get_name() ) {
			$args['render_type'] = 'none';
		}
		$default_args = array(
			'section_condition'      => array(),
			'button_text'            => esc_html__( 'Click here', 'mas-addons-for-elementor' ),
			'control_label_name'     => esc_html__( 'Text', 'mas-addons-for-elementor' ),
			'prefix_class'           => 'elementor%s-align-',
			'alignment_default'      => '',
			'exclude_inline_options' => array(),
			'button_concat'          => '~',
			'render_type'            => 'template',
		);

		$args = array_merge( $default_args, $args );

		$element->add_control(
			'swiper_position_static',
			array(
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Enable Position Static', 'mas-addons-for-elementor' ),
				'default'      => 'enable',
				'label_off'    => esc_html__( 'Enable', 'mas-addons-for-elementor' ),
				'label_on'     => esc_html__( 'Disable', 'mas-addons-for-elementor' ),
				'condition'    => $args['section_condition'],
				'return_value' => 'enable',
				'prefix_class' => 'swiper-position-static-',
				'render_type'  => $args['render_type'],
			)
		);

		$element->add_control(
			'text',
			array(
				'label'       => esc_html__( 'Previous Text', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => '',
				'placeholder' => $args['button_text'],
				'condition'   => $args['section_condition'],
				'render_type' => $args['render_type'],
			)
		);

		$element->add_control(
			'next_text',
			array(
				'label'       => esc_html__( 'Next Text', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => '',
				'placeholder' => $args['button_text'],
				'condition'   => $args['section_condition'],
				'render_type' => $args['render_type'],
			)
		);

		$element->add_control(
			'size',
			array(
				'label'       => esc_html__( 'Icon Size', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::SLIDER,
				'range'       => array(
					'px' => array(
						'max' => 50,
					),
				),
				'size_units'  => array( 'px', '%' ),
				'default'     => array(
					'unit' => 'px',
					'size' => 30,
				),
				'selectors'   => array(
					'{{WRAPPER}} ' . $args['button_concat'] . ' .mas-swiper-arrows .elementor-button-icon i' => 'font-size: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} ' . $args['button_concat'] . ' .mas-swiper-arrows .elementor-button-content-wrapper .elementor-button-icon i' => 'width: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} ' . $args['button_concat'] . ' .mas-swiper-arrows .elementor-button-content-wrapper .elementor-button-icon i' => 'height: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} ' . $args['button_concat'] . ' .mas-swiper-arrows .elementor-button-icon' => 'font-size: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} ' . $args['button_concat'] . ' .mas-swiper-arrows .elementor-button-content-wrapper .elementor-button-icon svg' => 'width: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} ' . $args['button_concat'] . ' .mas-swiper-arrows .elementor-button-content-wrapper .elementor-button-icon svg' => 'height: {{SIZE}}{{UNIT}} !important;',
				),
				'condition'   => $args['section_condition'],
				'render_type' => $args['render_type'],
			)
		);

		$element->add_control(
			'selected_icon',
			array(
				'label'                  => esc_html__( 'Previous Icon', 'mas-addons-for-elementor' ),
				'type'                   => Controls_Manager::ICONS,
				'fa4compatibility'       => 'icon',
				'skin'                   => 'inline',
				'default'                => array(
					'value'   => 'fas fa-arrow-left',
					'library' => 'fa-solid',
				),
				'label_block'            => false,
				'condition'              => $args['section_condition'],
				'exclude_inline_options' => $args['exclude_inline_options'],
				'render_type'            => $args['render_type'],
			)
		);

		$element->add_control(
			'icon_align',
			array(
				'label'       => esc_html__( 'Previous Icon Position', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'left',
				'options'     => array(
					'left'  => esc_html__( 'Before', 'mas-addons-for-elementor' ),
					'right' => esc_html__( 'After', 'mas-addons-for-elementor' ),
				),
				'condition'   => array_merge( $args['section_condition'], array( 'selected_icon[value]!' => '' ) ),
				'render_type' => $args['render_type'],
			)
		);

		$element->add_control(
			'selected_next_icon',
			array(
				'label'                  => esc_html__( 'Next Icon', 'mas-addons-for-elementor' ),
				'type'                   => Controls_Manager::ICONS,
				'fa4compatibility'       => 'icon',
				'skin'                   => 'inline',
				'default'                => array(
					'value'   => 'fas fa-arrow-right',
					'library' => 'fa-solid',
				),
				'label_block'            => false,
				'condition'              => $args['section_condition'],
				'exclude_inline_options' => $args['exclude_inline_options'],
				'render_type'            => $args['render_type'],
			)
		);

		$element->add_control(
			'next_icon_align',
			array(
				'label'       => esc_html__( 'Next Icon Position', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'right',
				'options'     => array(
					'left'  => esc_html__( 'Before', 'mas-addons-for-elementor' ),
					'right' => esc_html__( 'After', 'mas-addons-for-elementor' ),
				),
				'condition'   => array_merge( $args['section_condition'], array( 'selected_next_icon[value]!' => '' ) ),
				'render_type' => $args['render_type'],
			)
		);

		$element->add_control(
			'icon_indent',
			array(
				'label'       => esc_html__( 'Icon Spacing', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::SLIDER,
				'range'       => array(
					'px' => array(
						'max'  => 300,
						'min'  => -300,
						'step' => 0.1,
					),
				),
				'default'     => array(
					'unit' => 'px',
					'size' => 0,
				),
				'selectors'   => array(
					'{{WRAPPER}} ' . $args['button_concat'] . ' .mas-swiper-arrows .swiper-button-prev, .swiper-container-rtl .swiper-button-next' => 'background-image:none !important;',
					'{{WRAPPER}} ' . $args['button_concat'] . ' .mas-swiper-arrows .swiper-button-prev:after' => 'content:none !important;',
					'{{WRAPPER}} ' . $args['button_concat'] . ' .mas-swiper-arrows .swiper-button-next, .swiper-rtl .swiper-button-prev' => 'background-image:none !important;',
					'{{WRAPPER}} ' . $args['button_concat'] . ' .mas-swiper-arrows .swiper-button-next:after' => 'content:none !important;',
					'{{WRAPPER}} ' . $args['button_concat'] . ' .mas-swiper-arrows .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} ' . $args['button_concat'] . ' .mas-swiper-arrows .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} ' . $args['button_concat'] . ' .mas-swiper-arrows' => 'width: 100px !important;',
				),
				'condition'   => $args['section_condition'],
				'render_type' => $args['render_type'],
			)
		);

		$element->add_responsive_control(
			'swiper_arrows_spacing',
			array(
				'type'            => Controls_Manager::SLIDER,
				'label'           => esc_html__( 'Arrows Spacing', 'mas-addons-for-elementor' ),
				'size_units'      => array( '%', 'px' ),
				'range'           => array(
					'%'  => array(
						'min' => -100,
						'max' => 100,
						'step' => 0.1,
					),
					'px' => array(
						'min' => -1000,
						'max' => 1000,
						'step' => 0.1,
					),
				),
				'desktop_default' => array(
					'size' => 5,
					'unit' => '%',
				),
				'tablet_default'  => array(
					'size' => 10,
					'unit' => '%',
				),
				'mobile_default'  => array(
					'size' => 5,
					'unit' => '%',
				),
				'selectors'       => array(
					'{{WRAPPER}} ' . $args['button_concat'] . ' .mas-swiper-arrows .swiper-button-prev' => 'left:{{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} ' . $args['button_concat'] . ' .mas-swiper-arrows .swiper-button-next' => 'right:{{SIZE}}{{UNIT}} !important;',
				),
				'condition'       => array_merge( $args['section_condition'], array( 'enable_individual_arrow_spacing!' => 'yes' ) ),
				'render_type'     => $args['render_type'],
			)
		);

		$element->add_control(
			'enable_individual_arrow_spacing',
			array(
				'type'        => Controls_Manager::SWITCHER,
				'label'       => esc_html__( 'Individual Arrow Spacing', 'mas-addons-for-elementor' ),
				'label_off'   => esc_html__( 'Enable', 'mas-addons-for-elementor' ),
				'label_on'    => esc_html__( 'Disable', 'mas-addons-for-elementor' ),
				'condition'   => $args['section_condition'],
				'render_type' => $args['render_type'],
			)
		);

		$element->add_control(
			'button_wrapper_css',
			array(
				'label'       => esc_html__( 'Arrow Wrapper CSS', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => esc_html__( 'Additional CSS to be applied to .btn-wrapper element.', 'mas-addons-for-elementor' ),
				'condition'   => $args['section_condition'],
				'render_type' => $args['render_type'],
			)
		);

		$element->add_control(
			'button_css',
			array(
				'label'       => esc_html__( 'Arrow CSS', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => esc_html__( 'Additional CSS to be applied to .btn element.', 'mas-addons-for-elementor' ),
				'condition'   => $args['section_condition'],
				'render_type' => $args['render_type'],
			)
		);
	}

	/**
	 * Register Swiper Arrow Spacing controls.
	 *
	 * @param array $element Elementor.
	 * @param array $args arguments for controls.
	 */
	public function register_swiper_arrow_spacing_controls( $element, $args = array( 'button_concat' => '~' ) ) {

		if ( 'container' === $element->get_name() ) {
			$args['render_type'] = 'none';
		}

		$default_args = array(
			'section_condition' => array(),
			'button_concat'     => '~',
			'render_type'       => 'template',
		);

		$args = array_merge( $default_args, $args );

		$element->start_controls_section(
			'mas__swiper_arrow_spacing',
			array(
				'label'     => esc_html__( 'Swiper Arrows Spacing', 'mas-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'enable_carousel'                 => 'yes',
					'show_arrows'                     => 'yes',
					'enable_individual_arrow_spacing' => 'yes',
				),
			)
		);

		$element->add_responsive_control(
			'swiper_prev_arrows_start_spacing',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Previous Arrow Start Spacing', 'mas-addons-for-elementor' ),
				'size_units'  => array( 'px', 'em', '%', 'rem' ),
				'selectors'   => array(
					'{{WRAPPER}} ' . $args['button_concat'] . ' .mas-swiper-arrows .swiper-button-prev' => 'left:{{SIZE}}{{UNIT}} !important;',
				),
				'condition'   => $args['section_condition'],
				'render_type' => $args['render_type'],
				'range'       => array(
					'px' => array(
						'step' => 0.1,
						'min'  => -500,
						'max'  => 500,
					),
					'%'  => array(
						'step' => 0.1,
						'min'  => -100,
					),
				),
			)
		);

		$element->add_responsive_control(
			'swiper_prev_arrows_end_spacing',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Previous Arrow End Spacing', 'mas-addons-for-elementor' ),
				'size_units'  => array( 'px', 'em', '%', 'rem' ),
				'selectors'   => array(
					'{{WRAPPER}} ' . $args['button_concat'] . ' .mas-swiper-arrows .swiper-button-prev' => 'right:{{SIZE}}{{UNIT}} !important;',
				),
				'condition'   => $args['section_condition'],
				'render_type' => $args['render_type'],
				'range'       => array(
					'px' => array(
						'step' => 0.1,
						'min'  => -500,
						'max'  => 500,
					),
					'%'  => array(
						'step' => 0.1,
						'min'  => -100,
					),
				),
			)
		);

		$element->add_responsive_control(
			'swiper_prev_arrows_top_spacing',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Previous Arrow Top Spacing', 'mas-addons-for-elementor' ),
				'size_units'  => array( 'px', 'em', '%', 'rem' ),
				'selectors'   => array(
					'{{WRAPPER}} ' . $args['button_concat'] . ' .mas-swiper-arrows .swiper-button-prev' => 'top:{{SIZE}}{{UNIT}} !important;',
				),
				'condition'   => $args['section_condition'],
				'render_type' => $args['render_type'],
				'range'       => array(
					'px' => array(
						'step' => 0.1,
						'min'  => -500,
						'max'  => 500,
					),
					'%'  => array(
						'step' => 0.1,
						'min'  => -100,
					),
				),
			)
		);

		$element->add_responsive_control(
			'swiper_prev_arrows_bottom_spacing',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Previous Arrow Bottom Spacing', 'mas-addons-for-elementor' ),
				'size_units'  => array( 'px', 'em', '%', 'rem' ),
				'selectors'   => array(
					'{{WRAPPER}} ' . $args['button_concat'] . ' .mas-swiper-arrows .swiper-button-prev' => 'bottom:{{SIZE}}{{UNIT}} !important;',
				),
				'condition'   => $args['section_condition'],
				'render_type' => $args['render_type'],
				'range'       => array(
					'px' => array(
						'step' => 0.1,
						'min'  => -500,
						'max'  => 500,
					),
					'%'  => array(
						'step' => 0.1,
						'min'  => -100,
					),
				),
			)
		);

		$element->add_responsive_control(
			'swiper_next_arrows_start_spacing',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Next Arrow Start Spacing', 'mas-addons-for-elementor' ),
				'size_units'  => array( 'px', 'em', '%', 'rem' ),
				'selectors'   => array(
					'{{WRAPPER}} ' . $args['button_concat'] . ' .mas-swiper-arrows .swiper-button-next' => 'left:{{SIZE}}{{UNIT}} !important;',
				),
				'condition'   => $args['section_condition'],
				'separator'   => 'before',
				'render_type' => $args['render_type'],
				'range'       => array(
					'px' => array(
						'step' => 0.1,
						'min'  => -500,
						'max'  => 500,
					),
					'%'  => array(
						'step' => 0.1,
						'min'  => -100,
					),
				),
			)
		);

		$element->add_responsive_control(
			'swiper_next_arrows_end_spacing',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Next Arrow End Spacing', 'mas-addons-for-elementor' ),
				'size_units'  => array( 'px', 'em', '%', 'rem' ),
				'selectors'   => array(
					'{{WRAPPER}} ' . $args['button_concat'] . ' .mas-swiper-arrows .swiper-button-next' => 'right:{{SIZE}}{{UNIT}} !important;',
				),
				'condition'   => $args['section_condition'],
				'render_type' => $args['render_type'],
				'range'       => array(
					'px' => array(
						'step' => 0.1,
						'min'  => -500,
						'max'  => 500,
					),
					'%'  => array(
						'step' => 0.1,
						'min'  => -100,
					),
				),
			)
		);

		$element->add_responsive_control(
			'swiper_next_arrows_top_spacing',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Next Arrow Top Spacing', 'mas-addons-for-elementor' ),
				'size_units'  => array( 'px', 'em', '%', 'rem' ),
				'selectors'   => array(
					'{{WRAPPER}} ' . $args['button_concat'] . ' .mas-swiper-arrows .swiper-button-next' => 'top:{{SIZE}}{{UNIT}} !important;',
				),
				'condition'   => $args['section_condition'],
				'render_type' => $args['render_type'],
				'range'       => array(
					'px' => array(
						'step' => 0.1,
						'min'  => -500,
						'max'  => 500,
					),
					'%'  => array(
						'step' => 0.1,
						'min'  => -100,
					),
				),
			)
		);

		$element->add_responsive_control(
			'swiper_next_arrows_bottom_spacing',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_html__( 'Next Arrow Bottom Spacing', 'mas-addons-for-elementor' ),
				'size_units'  => array( 'px', 'em', '%', 'rem' ),
				'selectors'   => array(
					'{{WRAPPER}} ' . $args['button_concat'] . ' .mas-swiper-arrows .swiper-button-next' => 'bottom:{{SIZE}}{{UNIT}} !important;',
				),
				'condition'   => $args['section_condition'],
				'render_type' => $args['render_type'],
				'range'       => array(
					'px' => array(
						'step' => 0.1,
						'min'  => -500,
						'max'  => 500,
					),
					'%'  => array(
						'step' => 0.1,
						'min'  => -100,
					),
				),
			)
		);

		$element->end_controls_section();
	}

	/**
	 * Register Button Style controls.
	 *
	 * @param array $element Elementor.
	 * @param array $args arguments for controls.
	 */
	public function register_button_style_controls( $element, $args = array( 'button_concat' => '~' ) ) {
		if ( 'container' === $element->get_name() ) {
			$args['render_type'] = 'none';
		}
		$default_args = array(
			'section_condition' => array(),
			'button_concat'     => '~',
			'render_type'       => 'template',
		);

		$args = array_merge( $default_args, $args );

		$element->start_controls_section(
			'style_swiper_button',
			array(
				'label'       => esc_html__( 'Carousel Arrows', 'mas-addons-for-elementor' ),
				'tab'         => Controls_Manager::TAB_STYLE,
				'condition'   => array(
					'enable_carousel' => 'yes',
					'show_arrows'     => 'yes',
				),
				'render_type' => $args['render_type'],
			)
		);

		$element->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'        => 'swiper_arrow_typography',
				'global'      => array(
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				),
				'selector'    => '{{WRAPPER}} .mas-swiper-arrows .elementor-button-link',
				'condition'   => $args['section_condition'],
				'render_type' => $args['render_type'],
			)
		);

		$element->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'        => 'swiper_arrow_text_shadow',
				'selector'    => '{{WRAPPER}} ' . $args['button_concat'] . ' .mas-swiper-arrows .elementor-button-link',
				'condition'   => $args['section_condition'],
				'render_type' => $args['render_type'],
			)
		);

		$element->start_controls_tabs(
			'swiper_arrow_tabs_button_style',
			array(
				'condition' => $args['section_condition'],
			)
		);

		$element->start_controls_tab(
			'swiper_arrow_tab_button_normal',
			array(
				'label'     => esc_html__( 'Normal', 'mas-addons-for-elementor' ),
				'condition' => $args['section_condition'],
			)
		);

		$element->add_control(
			'swiper_arrow_button_text_color',
			array(
				'label'       => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '',
				'selectors'   => array(
					'{{WRAPPER}} ' . $args['button_concat'] . ' .mas-swiper-arrows .elementor-button-text' => 'fill: {{VALUE}}; color: {{VALUE}} !important;',
				),
				'condition'   => $args['section_condition'],
				'render_type' => $args['render_type'],
			)
		);

		$element->add_control(
			'swiper_arrow_button_icon_color',
			array(
				'label'       => esc_html__( 'Icon Color', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#BEC2C2',
				'selectors'   => array(
					'{{WRAPPER}} ' . $args['button_concat'] . ' .mas-swiper-arrows .elementor-button-link' => 'fill: {{VALUE}}; color: {{VALUE}} !important;',
				),
				'condition'   => $args['section_condition'],
				'render_type' => $args['render_type'],
			)
		);

		$element->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'swiper_arrow_background',
				'label'          => esc_html__( 'Background', 'mas-addons-for-elementor' ),
				'types'          => array( 'classic', 'gradient' ),
				'exclude'        => array( 'image' ),
				'selector'       => '{{WRAPPER}} ' . $args['button_concat'] . ' .mas-swiper-arrows .elementor-button-link',
				'fields_options' => array(
					'background' => array(
						'default' => 'classic',
					),
					'color'      => array(
						'default' => '#FFFFFF',
					),
				),
				'condition'      => $args['section_condition'],
				'render_type'    => $args['render_type'],
			)
		);

		$element->end_controls_tab();

		$element->start_controls_tab(
			'swiper_arrow_tab_button_hover',
			array(
				'label'     => esc_html__( 'Hover', 'mas-addons-for-elementor' ),
				'condition' => $args['section_condition'],
			)
		);

		$element->add_control(
			'swiper_arrow_hover_color',
			array(
				'label'       => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'{{WRAPPER}} .elementor-button-link:hover, {{WRAPPER}} .elementor-button-link:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .elementor-button-link:hover svg, {{WRAPPER}} .elementor-button-link:focus svg' => 'fill: {{VALUE}};',
				),
				'condition'   => $args['section_condition'],
				'render_type' => $args['render_type'],
			)
		);

		$element->add_control(
			'swiper_arrow_icon_hover_color',
			array(
				'label'       => esc_html__( 'Icon Color', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#FFFFFF',
				'selectors'   => array(
					'{{WRAPPER}} ' . $args['button_concat'] . ' .mas-swiper-arrows .elementor-button-link:hover, {{WRAPPER}} ' . $args['button_concat'] . ' .mas-swiper-arrows .elementor-button-link:focus' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} ' . $args['button_concat'] . ' .mas-swiper-arrows .elementor-button-link:hover svg, {{WRAPPER}} ' . $args['button_concat'] . ' .mas-swiper-arrows .elementor-button-link:focus svg' => 'fill: {{VALUE}} !important;',
				),
				'condition'   => $args['section_condition'],
				'render_type' => $args['render_type'],
			)
		);

		$element->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'swiper_arrow_button_background_hover',
				'label'          => esc_html__( 'Background', 'mas-addons-for-elementor' ),
				'types'          => array( 'classic', 'gradient' ),
				'exclude'        => array( 'image' ),
				'selector'       => '{{WRAPPER}} ' . $args['button_concat'] . ' .mas-swiper-arrows .elementor-button-link:hover, {{WRAPPER}} ' . $args['button_concat'] . ' .mas-swiper-arrows .elementor-button-link:focus',
				'fields_options' => array(
					'background' => array(
						'default' => 'classic',
					),
					'color'      => array(
						'default' => '#FB236A',
					),
				),
				'condition'      => $args['section_condition'],
				'render_type'    => $args['render_type'],
			)
		);

		$element->add_control(
			'swiper_arrow_button_hover_border_color',
			array(
				'label'       => esc_html__( 'Border Color', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'{{WRAPPER}} ' . $args['button_concat'] . ' .mas-swiper-arrows .elementor-button-link:hover, {{WRAPPER}} ' . $args['button_concat'] . ' .mas-swiper-arrows .elementor-button-link:focus' => 'border-color: {{VALUE}};',
				),
				'default'     => '#FB236A',
				'render_type' => $args['render_type'],
			)
		);

		$element->end_controls_tab();

		$element->end_controls_tabs();

		$element->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'swiper_arrow_border',
				'selector'       => '{{WRAPPER}} ' . $args['button_concat'] . ' .mas-swiper-arrows .elementor-button-link',
				'separator'      => 'before',
				'condition'      => $args['section_condition'],
				'fields_options' => array(
					'border' => array(
						'default' => 'dashed',
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
						'default' => '#BEC2C2',
					),
				),
				'render_type'    => $args['render_type'],
			)
		);

		$element->add_control(
			'swiper_arrow_border_radius',
			array(
				'label'       => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => array( 'px', '%', 'em' ),
				'selectors'   => array(
					'{{WRAPPER}} ' . $args['button_concat'] . ' .mas-swiper-arrows .elementor-button-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'   => $args['section_condition'],
				'default'     => array(
					'top'      => '50',
					'right'    => '50',
					'bottom'   => '50',
					'left'     => '50',
					'unit'     => '%',
					'isLinked' => false,
				),
				'render_type' => $args['render_type'],
			)
		);

		$element->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'        => 'swiper_arrow_button_box_shadow',
				'selector'    => '{{WRAPPER}} ' . $args['button_concat'] . ' .mas-swiper-arrows .elementor-button-link',
				'condition'   => $args['section_condition'],
				'render_type' => $args['render_type'],
			)
		);

		$element->add_responsive_control(
			'swiper_arrow_text_padding',
			array(
				'label'       => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => array( 'px', 'em', '%' ),
				'selectors'   => array(
					'{{WRAPPER}} ' . $args['button_concat'] . ' .mas-swiper-arrows .elementor-button-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'   => 'before',
				'condition'   => array_merge( $args['section_condition'], array( 'enable_individual_arrow_padding!' => 'yes' ) ),
				'default'     => array(
					'top'      => '10',
					'right'    => '20',
					'bottom'   => '10',
					'left'     => '20',
					'unit'     => 'px',
					'isLinked' => false,
				),
				'render_type' => $args['render_type'],
			)
		);

		$element->add_control(
			'enable_individual_arrow_padding',
			array(
				'type'        => Controls_Manager::SWITCHER,
				'label'       => esc_html__( 'Individual Arrow Spacing', 'mas-addons-for-elementor' ),
				'label_off'   => esc_html__( 'Enable', 'mas-addons-for-elementor' ),
				'label_on'    => esc_html__( 'Disable', 'mas-addons-for-elementor' ),
				'condition'   => $args['section_condition'],
				'render_type' => $args['render_type'],
			)
		);

		$element->add_responsive_control(
			'swiper_prev_arrow_text_padding',
			array(
				'label'       => esc_html__( 'Prev Button Padding', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => array( 'px', 'em', '%' ),
				'separator'   => 'before',
				'selectors'   => array(
					'{{WRAPPER}} ' . $args['button_concat'] . ' .mas-swiper-arrows .swiper-button-prev .elementor-button-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'   => array_merge( $args['section_condition'], array( 'enable_individual_arrow_padding' => 'yes' ) ),
				'render_type' => $args['render_type'],
			)
		);

		$element->add_responsive_control(
			'swiper_next_arrow_text_padding',
			array(
				'label'       => esc_html__( 'Next Button Padding', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => array( 'px', 'em', '%' ),
				'selectors'   => array(
					'{{WRAPPER}} ' . $args['button_concat'] . ' .mas-swiper-arrows .swiper-button-next .elementor-button-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'   => array_merge( $args['section_condition'], array( 'enable_individual_arrow_padding' => 'yes' ) ),
				'render_type' => $args['render_type'],
			)
		);

		$element->end_controls_section();
	}

	/**
	 * Render button widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @param Widget_Base $instance The widget instance.
	 * @param String      $prev_id previous id.
	 * @param String      $next_id next id.
	 */
	public function render_button( $instance = null, $prev_id = '', $next_id = '' ) {
		if ( empty( $instance ) ) {
			$instance = $this;
		}

		$settings = $instance->get_settings();
		$instance->add_render_attribute( 'prev_wrapper', 'class', array( 'btn-wrapper', 'elementor-button-wrapper', 'swiper-button-prev mas-elementor-swiper-arrow' ) );
		$instance->add_render_attribute( 'next_wrapper', 'class', array( 'btn-wrapper', 'elementor-button-wrapper', 'swiper-button-next mas-elementor-swiper-arrow' ) );
		if ( ! empty( $prev_id ) && ! empty( $next_id ) ) {
			$instance->add_render_attribute( 'prev_wrapper', 'id', $prev_id );
			$instance->add_render_attribute( 'next_wrapper', 'id', $next_id );
		}

		if ( ! empty( $settings['button_wrapper_css'] ) ) {
			$instance->add_render_attribute( 'prev_wrapper', 'class', $settings['button_wrapper_css'] );
		}
		if ( ! empty( $settings['button_wrapper_css'] ) ) {
			$instance->add_render_attribute( 'next_wrapper', 'class', $settings['button_wrapper_css'] );
		}

		if ( ! empty( $settings['link']['url'] ) ) {
			$instance->add_link_attributes( 'swiper-button', $settings['link'] );
			$instance->add_render_attribute( 'swiper-button', 'class', 'elementor-button-link' );
		}

		$instance->add_render_attribute( 'swiper-button', 'class', array( 'elementor-button-link' ) );
		$instance->add_render_attribute( 'swiper-button', 'role', 'button' );

		if ( ! empty( $settings['button_css'] ) ) {
			$instance->add_render_attribute( 'swiper-button', 'class', $settings['button_css'] );
		}

		if ( ! empty( $settings['button_css_id'] ) ) {
			$instance->add_render_attribute( 'swiper-button', 'id', $settings['button_css_id'] );
		}

		?>
		<div <?php $instance->print_render_attribute_string( 'prev_wrapper' ); ?>>
			<a <?php $instance->print_render_attribute_string( 'swiper-button' ); ?>>
				<?php $this->render_prev_text( $instance ); ?>
			</a>
		</div>
		<div <?php $instance->print_render_attribute_string( 'next_wrapper' ); ?>>
			<a <?php $instance->print_render_attribute_string( 'swiper-button' ); ?>>
				<?php $this->render_next_text( $instance ); ?>
			</a>
		</div>
		<?php
	}

	/**
	 * Render button text.
	 *
	 * Render button widget text.
	 *
	 * @param \Elementor\Widget_Base $instance The widget instance.
	 */
	protected function render_prev_text( $instance ) {
		$settings = $instance->get_settings();

		$migrated = isset( $settings['__fa4_migrated']['selected_icon'] );
		$is_new   = empty( $settings['icon'] ) && Icons_Manager::is_migration_allowed();

		if ( ! $is_new && empty( $settings['icon_align'] ) ) {
			// @todo: remove when deprecated
			// added as bc in 2.6
			// old default
			$settings['icon_align'] = $instance->get_settings( 'icon_align' );
		}

		$instance->add_render_attribute(
			array(
				'content-wrapper' => array(
					'class' => 'elementor-button-content-wrapper',
				),
				'icon-align'      => array(
					'class' => array(
						'elementor-button-icon',
						'elementor-align-icon-' . $settings['icon_align'],
					),
				),
				'text'            => array(
					'class' => 'elementor-button-text',
				),
			)
		);

		if ( 'left' === $settings['icon_align'] ) {
			$instance->add_render_attribute(
				'icon-align',
				array(
					'style' => 'order:1 !important;',
				)
			);
			$instance->add_render_attribute(
				'content-wrapper',
				array(
					'style' => 'flex-direction:row;',
				)
			);
		} else {
			$instance->add_render_attribute(
				'icon-align',
				array(
					'style' => 'order:15 !important;',
				)
			);
			$instance->add_render_attribute(
				'content-wrapper',
				array(
					'style' => 'flex-direction:row-reverse;',
				)
			);
		}

		// TODO: replace the protected with public
		// $instance->add_inline_editing_attributes( 'text', 'none' ); .
		?>
		<span <?php $instance->print_render_attribute_string( 'content-wrapper' ); ?>>
			<?php if ( ! empty( $settings['icon'] ) || ! empty( $settings['selected_icon']['value'] ) ) : ?>
				<span <?php $instance->print_render_attribute_string( 'icon-align' ); ?>>
				<?php
				if ( $is_new || $migrated ) :
					Icons_Manager::render_icon( $settings['selected_icon'], array( 'aria-hidden' => 'true' ) );
				else :
					?>
					<i class="<?php echo esc_attr( $settings['icon'] ); ?>" aria-hidden="true"></i>
				<?php endif; ?>
			</span>
			<?php endif; ?>
			<span <?php $instance->print_render_attribute_string( 'text' ); ?>><?php Utils::print_unescaped_internal_string( $settings['text'] ); // Todo: Make $instance->print_unescaped_setting public. ?></span>
		</span>
		<?php
	}

	/**
	 * Render button text.
	 *
	 * Render button widget text.
	 *
	 * @param \Elementor\Widget_Base $instance The widget instance.
	 */
	protected function render_next_text( $instance ) {
		$settings = $instance->get_settings();

		$migrated = isset( $settings['__fa4_migrated']['selected_next_icon'] );
		$is_new   = empty( $settings['next_icon'] ) && Icons_Manager::is_migration_allowed();

		if ( ! $is_new && empty( $settings['next_icon_align'] ) ) {
			// @todo: remove when deprecated
			// added as bc in 2.6
			// old default
			$settings['next_icon_align'] = $instance->get_settings( 'next_icon_align' );
		}

		$instance->add_render_attribute(
			array(
				'next-content-wrapper' => array(
					'class' => 'elementor-button-content-wrapper',
				),
				'next-icon-align'      => array(
					'class' => array(
						'elementor-button-icon',
						'elementor-align-icon-' . $settings['next_icon_align'],
					),
				),
				'next-text'            => array(
					'class' => 'elementor-button-text',
				),
			)
		);

		if ( 'left' === $settings['next_icon_align'] ) {
			$instance->add_render_attribute(
				'next-icon-align',
				array(
					'style' => 'order:1;',
				)
			);
			$instance->add_render_attribute(
				'next-content-wrapper',
				array(
					'style' => 'flex-direction:row;',
				)
			);
		} else {
			$instance->add_render_attribute(
				'next-icon-align',
				array(
					'style' => 'order:15;',
				)
			);
			$instance->add_render_attribute(
				'next-content-wrapper',
				array(
					'style' => 'flex-direction:row-reverse;',
				)
			);
		}

		// TODO: replace the protected with public
		// $instance->add_inline_editing_attributes( 'text', 'none' ); .
		?>
		<span <?php $instance->print_render_attribute_string( 'next-content-wrapper' ); ?>>
			<?php if ( ! empty( $settings['next_icon'] ) || ! empty( $settings['selected_next_icon']['value'] ) ) : ?>
				<span <?php $instance->print_render_attribute_string( 'next-icon-align' ); ?>>
				<?php
				if ( $is_new || $migrated ) :
					Icons_Manager::render_icon( $settings['selected_next_icon'], array( 'aria-hidden' => 'true' ) );
				else :
					?>
					<i class="<?php echo esc_attr( $settings['next_icon'] ); ?>" aria-hidden="true"></i>
				<?php endif; ?>
			</span>
			<?php endif; ?>
			<span <?php $instance->print_render_attribute_string( 'next-text' ); ?>><?php Utils::print_unescaped_internal_string( $settings['next_text'] ); // Todo: Make $instance->print_unescaped_setting public. ?></span>
		</span>
		<?php
	}

	/**
	 * On import widget.
	 *
	 * @param array $element The element being imported.
	 */
	public function on_import( $element ) {
		return array( Icons_Manager::on_import_migration( $element, 'icon', 'selected_icon' ), Icons_Manager::on_import_migration( $element, 'next_icon', 'selected_next_icon' ) );
	}
}
