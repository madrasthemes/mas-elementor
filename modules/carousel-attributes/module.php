<?php
/**
 * Carousel Attributes.
 *
 * @package MASElementor\Modules\CarouselAttributes
 */

namespace MASElementor\Modules\CarouselAttributes;

use Elementor\Controls_Stack;
use Elementor\Controls_Manager;
use Elementor\Element_Base;
use MASElementor\Base\Module_Base;
use Elementor\Plugin;
use MASElementor\Modules\CarouselAttributes\Traits\Button_Widget_Trait;
use MASElementor\Modules\CarouselAttributes\Traits\Pagination_Trait;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The Carousel Attributes module class
 */
class Module extends Module_Base {

	use Button_Widget_Trait;
	use Pagination_Trait;

	/**
	 * Return the name of the module.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-carousel-attributes';
	}

	/**
	 * Add carousel controls to the column element.
	 *
	 * @param Element_Base $element The Column element object.
	 */
	public function register_carousel_attributes_controls( Element_Base $element ) {
		$element_name = $element->get_name();

		$render_type = 'container' === $element_name ? 'none' : 'template';

		$element->start_injection(
			array(
				'at' => 'before',
				'of' => 'section_effects',
			)
		);
		$element->start_controls_section(
			'section_carousel_attributes',
			array(
				'label' => __( 'Carousel', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			),
			array(
				'position' => array(
					'at' => 'before',
					'of' => 'section_effects',
				),
			)
		);

		$element->add_control(
			'enable_carousel',
			array(
				'type'    => Controls_Manager::SWITCHER,
				'label'   => esc_html__( 'Enable Carousel', 'mas-addons-for-elementor' ),
				'default' => 'no',
			)
		);

		if ( 'container' === $element->get_name() ) {

			$element->update_control(
				'enable_carousel',
				array(
					'condition' => array(
						'enable_swiper_wrapper!' => 'yes',
						'enable_swiper_slide!'   => 'yes',
					),
					'render_type' => $render_type,
				)
			);

			$element->add_control(
				'enable_swiper_wrapper',
				array(
					'type'      => Controls_Manager::SWITCHER,
					'label'     => esc_html__( 'Enable Swiper wrapper', 'mas-addons-for-elementor' ),
					'default'   => 'no',
					'condition' => array(
						'enable_carousel!'     => 'yes',
						'enable_swiper_slide!' => 'yes',
					),
					'render_type' => $render_type,
				)
			);

			$element->add_control(
				'enable_swiper_slide',
				array(
					'type'      => Controls_Manager::SWITCHER,
					'label'     => esc_html__( 'Enable Swiper Slide', 'mas-addons-for-elementor' ),
					'default'   => 'no',
					'condition' => array(
						'enable_carousel!'       => 'yes',
						'enable_swiper_wrapper!' => 'yes',
					),
					'render_type' => $render_type,
				)
			);

			$element->add_control(
				'enable_hover',
				array(
					'type'        => Controls_Manager::SWITCHER,
					'label'       => esc_html__( 'Enable Hover/Click', 'mas-addons-for-elementor' ),
					'label_off'   => esc_html__( 'Enable', 'mas-addons-for-elementor' ),
					'label_on'    => esc_html__( 'Disable', 'mas-addons-for-elementor' ),
					'description' => esc_html__( 'Should have child element inside this container', 'mas-addons-for-elementor' ),
					'render_type' => $render_type,
				)
			);

			$element->add_control(
				'hover_click_effect',
				array(
					'type'      => Controls_Manager::SELECT,
					'label'     => esc_html__( 'Hover/Click', 'mas-addons-for-elementor' ),
					'default'     => 'hover',
					'options'   => array(
						'hover'      => esc_html__( 'Hover', 'mas-addons-for-elementor' ),
						'click' => esc_html__( 'Click', 'mas-addons-for-elementor' ),
					),
					'condition' => array(
						'enable_hover' => 'yes',
					),
					'render_type' => $render_type,
				)
			);

			$element->add_control(
				'data_hover',
				array(
					'label'     => esc_html__( 'Data Hover Id', 'mas-addons-for-elementor' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => 'content-hover',
					'condition' => array(
						'enable_hover' => 'yes',
					),
					'render_type' => $render_type,
				)
			);

			$element->add_control(
				'enable_thumbs',
				array(
					'type'      => Controls_Manager::SWITCHER,
					'label'     => esc_html__( 'Enable Thumbs', 'mas-addons-for-elementor' ),
					'default'   => 'no',
					'condition' => array(
						'enable_carousel'        => 'yes',
						'enable_swiper_slide!'   => 'yes',
						'enable_swiper_wrapper!' => 'yes',
					),
					'render_type' => $render_type,
				)
			);

			$element->add_control(
				'thumb_swiper_widget',
				array(
					'label'     => esc_html__( 'Thumbs ID', 'mas-addons-for-elementor' ),
					'type'      => Controls_Manager::TEXT,
					'condition' => array(
						'enable_carousel' => 'yes',
					),
					'render_type' => $render_type,
				)
			);
		}

		$element->add_control(
			'carousel_effect',
			array(
				'type'      => Controls_Manager::SELECT,
				'label'     => esc_html__( 'Effect', 'mas-addons-for-elementor' ),
				'default'   => 'slide',
				'options'   => array(
					''      => esc_html__( 'None', 'mas-addons-for-elementor' ),
					'slide' => esc_html__( 'Slide', 'mas-addons-for-elementor' ),
					'fade'  => esc_html__( 'Fade', 'mas-addons-for-elementor' ),
				),
				'condition' => array(
					'enable_carousel' => 'yes',
				),
				'render_type' => $render_type,
			)
		);

		// TODO: Once Core 3.4.0 is out, get the active devices using Breakpoints/Manager::get_active_devices_list().
		$active_breakpoint_instances = Plugin::$instance->breakpoints->get_active_breakpoints();
		// Devices need to be ordered from largest to smallest.
		$active_devices = array_reverse( array_keys( $active_breakpoint_instances ) );

		$slides_per_view  = array(
			'type'      => Controls_Manager::NUMBER,
			'label'     => esc_html__( 'Slides Per View', 'mas-addons-for-elementor' ),
			'min'       => 1,
			'max'       => 15,
			'default'   => 1,
			'condition' => array(
				'carousel_effect' => 'slide',
				'enable_carousel' => 'yes',
			),
			'render_type' => $render_type,
		);
		$slides_to_scroll = array(
			'type'      => Controls_Manager::NUMBER,
			'label'     => esc_html__( 'Slides To Scroll', 'mas-addons-for-elementor' ),
			'min'       => 1,
			'max'       => 15,
			'default'   => 1,
			'condition' => array(
				'carousel_effect' => 'slide',
				'enable_carousel' => 'yes',
			),
			'default'   => 1,
			'render_type' => $render_type,
		);
		$space_between    = array(
			'type'        => Controls_Manager::NUMBER,
			'label'       => esc_html__( 'Space Between', 'mas-addons-for-elementor' ),
			'description' => esc_html__( 'Set Space between each Slides', 'mas-addons-for-elementor' ),
			'min'         => 0,
			'max'         => 100,
			'default'     => 8,
			'condition'   => array(
				'carousel_effect'      => 'slide',
				'enable_carousel'      => 'yes',
				'enable_space_between' => 'yes',
			),
			'render_type' => $render_type,
		);
		foreach ( $active_devices as $active_device ) {
			$space_between[ $active_device . '_default' ]    = 8;
			$slides_per_view[ $active_device . '_default' ]  = 1;
			$slides_to_scroll[ $active_device . '_default' ] = 1;
		}

		$element->add_responsive_control(
			'slides_per_view',
			$slides_per_view
		);

		$element->add_responsive_control(
			'slides_to_scroll',
			$slides_to_scroll
		);

		$element->add_control(
			'enable_space_between',
			array(
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Space Between', 'mas-addons-for-elementor' ),
				'default'   => 'yes',
				'label_off' => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
				'label_on'  => esc_html__( 'Show', 'mas-addons-for-elementor' ),
				'condition' => array(
					'carousel_effect' => 'slide',
					'enable_carousel' => 'yes',
				),
				'render_type' => $render_type,
			)
		);

		$element->add_responsive_control(
			'space_between',
			$space_between
		);

		$element->add_control(
			'swiper_height_auto',
			array(
				'label'                => esc_html__( 'Height', 'mas-addons-for-elementor' ),
				'type'                 => Controls_Manager::SELECT,
				'default'              => 'default',
				'options'              => array(
					'default' => 'Default',
					'auto'    => 'Auto',
				),
				'selectors_dictionary' => array(
					'default' => '100%',
					'auto'    => 'auto',
				),
				'selectors'            => array(
					'{{WRAPPER}} .swiper-slide' => 'height: {{VALUE}};',
				),
				'condition'            => array(
					'enable_carousel' => 'yes',
				),
				'render_type' => $render_type,
			)
		);

		$element->add_control(
			'center_slides',
			array(
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Center Slides', 'mas-addons-for-elementor' ),
				'default'   => 'no',
				'label_off' => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
				'label_on'  => esc_html__( 'Show', 'mas-addons-for-elementor' ),
				'condition' => array(
					'carousel_effect' => 'slide',
					'enable_carousel' => 'yes',
				),
				'render_type' => $render_type,
			)
		);

		$element->add_control(
			'show_arrows',
			array(
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Arrows', 'mas-addons-for-elementor' ),
				'default'   => 'no',
				'label_off' => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
				'label_on'  => esc_html__( 'Show', 'mas-addons-for-elementor' ),
				'condition' => array(
					'enable_carousel'     => 'yes',
					'show_custom_arrows!' => 'yes',
				),
				'render_type' => $render_type,
			)
		);

		$element->add_responsive_control(
			'hide_responsive_arrows',
			array(
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Hide Arrow Responsive', 'mas-addons-for-elementor' ),
				'default'      => 'no',
				'label_off'    => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
				'label_on'     => esc_html__( 'Show', 'mas-addons-for-elementor' ),
				'condition'    => array(
					'enable_carousel'     => 'yes',
					'show_arrows'         => 'yes',
					'show_custom_arrows!' => 'yes',
				),
				'return_value' => 'hide',
				'prefix_class' => 'mas-swiper-carousel--arrows%s-',
				'render_type' => $render_type,
			)
		);

		$element->add_control(
			'show_custom_arrows',
			array(
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Custom Arrows', 'mas-addons-for-elementor' ),
				'default'   => 'no',
				'label_off' => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
				'label_on'  => esc_html__( 'Show', 'mas-addons-for-elementor' ),
				'condition' => array(
					'enable_carousel' => 'yes',
					'show_arrows!'    => 'yes',
				),
				'render_type' => $render_type,

			)
		);

		$element->add_control(
			'custom_prev_id',
			array(
				'label'     => esc_html__( 'Previous Arrow ID', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array(
					'enable_carousel'    => 'yes',
					'show_arrows!'       => 'yes',
					'show_custom_arrows' => 'yes',
				),
				'render_type' => $render_type,
			)
		);

		$element->add_control(
			'custom_next_id',
			array(
				'label'     => __( 'Next Arrow ID', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array(
					'enable_carousel'    => 'yes',
					'show_arrows!'       => 'yes',
					'show_custom_arrows' => 'yes',
				),
				'render_type' => $render_type,
			)
		);

		$element->add_control(
			'speed',
			array(
				'label'       => esc_html__( 'Transition Duration', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 500,
				'step'        => 100,
				'render_type' => 'none',
				'condition'   => array(
					'enable_carousel' => 'yes',
				),
			)
		);

		$element->add_control(
			'autoplay',
			array(
				'label'       => esc_html__( 'Autoplay', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'yes',
				'separator'   => 'before',
				'render_type' => 'none',
				'condition'   => array(
					'enable_carousel' => 'yes',
				),
			)
		);

		$element->add_control(
			'autoplay_speed',
			array(
				'label'       => esc_html__( 'Autoplay Speed', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 5000,
				'condition'   => array(
					'autoplay'        => 'yes',
					'enable_carousel' => 'yes',
				),
				'render_type' => 'none',
			)
		);

		$element->add_control(
			'loop',
			array(
				'label'     => esc_html__( 'Infinite Loop', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => array(
					'enable_carousel' => 'yes',
				),
				'render_type' => $render_type,
			)
		);

		$element->add_control(
			'pause_on_hover',
			array(
				'label'       => esc_html__( 'Pause on Hover', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'yes',
				'condition'   => array(
					'autoplay'        => 'yes',
					'enable_carousel' => 'yes',
				),
				'render_type' => 'none',
			)
		);

		$element->add_control(
			'show_pagination',
			array(
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Pagination', 'mas-addons-for-elementor' ),
				'default'   => 'yes',
				'label_off' => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
				'label_on'  => esc_html__( 'Show', 'mas-addons-for-elementor' ),
				'condition' => array(
					'enable_carousel' => 'yes',
				),
				'render_type' => $render_type,
			)
		);

		$element->add_control(
			'pagination',
			array(
				'label'     => esc_html__( 'Pagination', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'bullets',
				'options'   => array(
					'bullets'     => esc_html__( 'Dots', 'mas-addons-for-elementor' ),
					'fraction'    => esc_html__( 'Fraction', 'mas-addons-for-elementor' ),
					'progressbar' => esc_html__( 'Progress', 'mas-addons-for-elementor' ),
				),
				'condition' => array(
					'enable_carousel' => 'yes',
					'show_pagination' => 'yes',
				),
				'render_type' => $render_type,
			)
		);

		$element->add_control(
			'pagination_style',
			array(
				'label'     => esc_html__( 'Pagination Style', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'horizontal',
				'options'   => array(
					'horizontal' => esc_html__( 'Horizontal', 'mas-addons-for-elementor' ),
					'vertical'   => esc_html__( 'Vertical', 'mas-addons-for-elementor' ),
				),
				'condition' => array(
					'enable_carousel' => 'yes',
					'show_pagination' => 'yes',

				),
				'render_type' => $render_type,
			)
		);

		$element->end_controls_section();
		$element->end_injection();
		$this->register_pagination_style_controls( $element );
		$this->register_mas_swiper_wrapper_style_controls( $element );
		$this->register_mas_swiper_thumbs_style_controls( $element );

	}

	/**
	 * Register button content controls.
	 *
	 * @param array $element Elements.
	 */
	public function register_mas_swiper_wrapper_style_controls( $element ) {

		$render_type = 'container' === $element->get_name() ? 'none' : 'template';

		$element->start_controls_section(
			'section_mas_swiper_wrapper',
			array(
				'label'     => esc_html__( 'MAS Swiper Wrapper', 'mas-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'enable_carousel' => 'yes',
				),
			)
		);

		$element->add_control(
			'mas_swiper_wrapper_attributes',
			array(
				'label'       => esc_html__( 'MAS Swiper wrapper', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Add the styles to be added to mas-swiper-wrapper', 'mas-addons-for-elementor' ),
				'render_type' => $render_type,
			)
		);

		$element->add_control(
			'mas_swiper_css_classes',
			array(
				'label'       => esc_html__( 'MAS Swiper CSS Class', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Add the class to be added to mas-swiper-wrapper', 'mas-addons-for-elementor' ),
				'render_type' => $render_type,
			)
		);

		$element->end_controls_section();
	}

	/**
	 * Register button content controls.
	 *
	 * @param array $element Elements.
	 */
	public function register_mas_swiper_thumbs_style_controls( $element ) {

		$render_type = 'container' === $element->get_name() ? 'none' : 'template';

		$element->start_controls_section(
			'section_mas_swiper_thumbs',
			array(
				'label'     => esc_html__( 'Thumbs Style', 'mas-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'enable_carousel' => 'yes',
					'enable_thumbs'   => 'yes',
				),
			)
		);

		$element->add_control(
			'thumbs_opacity',
			array(
				'label'     => esc_html__( 'Thumbs Inactive Opacity', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					),
				),
				'default'   => array(
					'size' => 0.4,
				),
				'selectors' => array(
					'{{WRAPPER}}.swiper-thumbs .swiper-slide' => 'opacity: {{SIZE}};',
				),
				'render_type' => $render_type,
			)
		);
		$element->add_control(
			'thumbs_active_opacity',
			array(
				'label'     => esc_html__( 'Thumbs active Opacity', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					),
				),
				'default'   => array(
					'size' => 1,
				),
				'selectors' => array(
					'{{WRAPPER}}.swiper-thumbs .swiper-slide-thumb-active' => 'opacity: {{SIZE}};',
				),
				'render_type' => $render_type,
			)
		);

		$element->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array(
				'name'     => 'thumbs_active_background',
				'selector' => '{{WRAPPER}}.swiper-thumbs .swiper-slide-thumb-active > div',
				'render_type' => $render_type,
			)
		);

		$element->end_controls_section();
	}

	/**
	 * Register Controls.
	 *
	 * @param Controls_Stack $element The widget.
	 * @param string         $section_id string.
	 */
	public function register_controls( Controls_Stack $element, $section_id ) {
		if ( ! $element instanceof Element_Base ) {
			return;
		}
		// Remove Custom CSS Banner (From free version).
		if ( 'section_shape_divider' === $section_id ) {
			$this->register_carousel_attributes_controls( $element );
		}
	}


	/**
	 * Before Render.
	 *
	 * @param array $element The widget.
	 *
	 * @return void
	 */
	public function before_render_section( $element ) {

		$settings = $element->get_settings();
		$json     = wp_json_encode( $this->get_swiper_carousel_options( $settings, $element ) );
		$id       = $element->get_id();
		if ( 'yes' === $settings['enable_carousel'] ) {
			if ( ! empty( $settings['thumb_swiper_widget'] ) && 'yes' !== $settings['enable_thumbs'] ) {
				$element->add_render_attribute( '_wrapper', 'data-swiper-widget', 'thumb-' . $settings['thumb_swiper_widget'] );
			}
			$element->add_render_attribute( '_wrapper', 'class', 'swiper' );
			$element->add_render_attribute( '_wrapper', 'data-swiper-options', $json );
			$element->add_render_attribute( 'section_carousel', 'class', 'mas-swiper-wrapper elementor-element' );
			if ( ! empty( $settings['mas_swiper_css_classes'] ) ) {
				$element->add_render_attribute( 'section_carousel', 'class', $settings['mas_swiper_css_classes'] );
			}
			$element->add_render_attribute( 'section_carousel', 'style', 'position: relative;' );
			if ( ! empty( $settings['mas_swiper_wrapper_attributes'] ) ) {
				$element->add_render_attribute( 'section_carousel', 'style', $settings['mas_swiper_wrapper_attributes'] );
			}
			if ( 'yes' === $settings['enable_thumbs'] && ! empty( $settings['thumb_swiper_widget'] ) ) {
				$thumbs_json = wp_json_encode( array( 'thumbs_selector' => 'thumb-' . $settings['thumb_swiper_widget'] ) );
				$element->add_render_attribute( '_wrapper', 'data-thumbs-options', $thumbs_json );
				$element->add_render_attribute( '_wrapper', 'class', 'mas-js-swiper-thumbs' );
			}
			?>
			<div <?php $element->print_render_attribute_string( 'section_carousel' ); ?>>
			<?php
		}
		if ( isset( $settings['enable_swiper_wrapper'] ) && 'yes' === $settings['enable_swiper_wrapper'] ) {
			$element->add_render_attribute( '_wrapper', 'class', 'swiper-wrapper' );
		}
		if ( isset( $settings['enable_swiper_slide'] ) && 'yes' === $settings['enable_swiper_slide'] ) {
			$element->add_render_attribute( '_wrapper', 'class', 'swiper-slide' );
		}

		if ( isset( $settings['enable_hover'] ) && 'yes' === $settings['enable_hover'] ) {
			$element->add_render_attribute( '_wrapper', 'class', 'mas-hover-button' );
			$element->add_render_attribute( '_wrapper', 'data-hover', $settings['data_hover'] );
			if ( 'click' === $settings['hover_click_effect'] ) {
				$element->add_render_attribute( '_wrapper', 'data-click', true );
			}
		}

		if ( isset( $settings['gap'] ) ) {
			$container_class = $settings['gap'];

			if ( 'no' === $settings['gap'] ) {
				$container_class = $settings['gap'] . ' no-gutters';
			}

			if ( 'yes' === $settings['enable_carousel'] ) {
				$container_class .= ' swiper-wrapper';
			}

			if ( ! empty( $container_class ) ) {
				$element->set_settings( 'gap', $container_class );
			}
		}

	}

	/**
	 * After Render.
	 *
	 * @param array $element The widget.
	 *
	 * @return void
	 */
	public function after_render_section( $element ) {
		$settings   = $element->get_settings();
		$section_id = $element->get_id();
		if ( 'yes' === $settings['enable_carousel'] ) {
			if ( ! empty( $section_id ) && 'yes' === $settings['show_pagination'] ) {
				$element->add_render_attribute( 'swiper-pagination', 'id', 'pagination-' . $section_id );
			}
			$element->add_render_attribute( 'swiper-pagination', 'class', 'swiper-pagination' );
			if ( 'vertical' === $settings['pagination_style'] ) {
				$element->add_render_attribute( 'swiper-pagination', 'class', 'swiper-pagination-vertical' );
			} else {
				$element->add_render_attribute( 'swiper-pagination', 'class', 'mas-pagination-horizontal' );
			}
			$element->add_render_attribute( 'swiper-pagination', 'style', 'position: ' . $settings['mas_swiper_pagination_position'] . ';' );
			if ( 'yes' === $settings['show_pagination'] ) :
				?>
			<div <?php $element->print_render_attribute_string( 'swiper-pagination' ); ?>></div>
				<?php
			endif;
			if ( 'yes' === $settings['show_arrows'] ) :
				$prev_id = ! empty( $section_id ) ? 'prev-' . $section_id : '';
				$next_id = ! empty( $section_id ) ? 'next-' . $section_id : '';
				?>
				<div class="d-flex mas-swiper-arrows">
				<?php
				$this->render_button( $element, $prev_id, $next_id );
				?>
				</div>
				<!-- If we need navigation buttons -->
				<?php
			endif;
			?>
			</div>
			<?php
		}
	}

	/**
	 * Before Render.
	 *
	 * @param array $element The widget.
	 *
	 * @return void
	 */
	public function before_render_column( $element ) {

		$settings = $element->get_settings_for_display();
		if ( 'yes' === $settings['enable_slide'] ) {
			$element->add_render_attribute( '_wrapper', 'class', 'swiper-slide' );
		}

	}

	/**
	 * Get carousel settings
	 *
	 * @param array        $settings The widget settings.
	 * @param Element_Base $element The element settings.
	 * @return array
	 */
	public function get_swiper_carousel_options( array $settings, $element ) {
		$active_breakpoint_instances = Plugin::$instance->breakpoints->get_active_breakpoints();
		// Devices need to be ordered from largest to smallest.
		$active_devices = array_reverse( array_keys( $active_breakpoint_instances ) );

		$section_id      = $element->get_id();
		$swiper_settings = array();
		if ( 'yes' === $settings['show_pagination'] ) {
			$swiper_settings['pagination'] = array(
				'el' => '#pagination-' . $section_id,
			);
		}

		if ( 'bullets' === $settings['pagination'] ) {
			$swiper_settings['pagination']['type']      = 'bullets';
			$swiper_settings['pagination']['clickable'] = true;
		}
		if ( 'fraction' === $settings['pagination'] ) {
			$swiper_settings['pagination']['type'] = 'fraction';
		}
		if ( 'progressbar' === $settings['pagination'] ) {
			$swiper_settings['pagination']['type'] = 'progressbar';
		}

		if ( 'fade' === $settings['carousel_effect'] ) {
			$swiper_settings['effect']                  = 'fade';
			$swiper_settings['fadeEffect']['crossFade'] = true;
		}
		if ( 'slide' === $settings['carousel_effect'] ) {
			$breakpoint = '1441';
			$swiper_settings['breakpoints'][ $breakpoint ]['slidesPerView'] = isset( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 3;
			foreach ( $active_breakpoint_instances as $active_breakpoint_instance ) {
				$array_key = 'slides_per_view_' . $active_breakpoint_instance->get_name();
				if ( 'mobile' === $active_breakpoint_instance->get_name() ) {
					$breakpoint = '0';
				}
				if ( 'widescreen' === $active_breakpoint_instance->get_name() ) {
					$breakpoint = (string) $active_breakpoint_instance->get_default_value();
					if ( property_exists( $active_breakpoint_instance, 'value' ) ) {
						$breakpoint = (string) ( $active_breakpoint_instance->get_value() );
					}
					$swiper_settings['breakpoints'][ $breakpoint ]['slidesPerView'] = isset( $settings[ $array_key ] ) ? $settings[ $array_key ] : 1;
					continue;
				}
				$swiper_settings['breakpoints'][ $breakpoint ]['slidesPerView'] = isset( $settings[ $array_key ] ) ? $settings[ $array_key ] : 1;
				$breakpoint = (string) $active_breakpoint_instance->get_default_value() + 1;
				if ( property_exists( $active_breakpoint_instance, 'value' ) ) {
					$breakpoint = (string) ( $active_breakpoint_instance->get_value() + 1 );
				}
			}
		}

		if ( 'slide' === $settings['carousel_effect'] ) {
			$breakpoint = '1441';
			$swiper_settings['breakpoints'][ $breakpoint ]['slidesPerGroup'] = isset( $settings['slides_to_scroll'] ) ? $settings['slides_to_scroll'] : 3;
			foreach ( $active_breakpoint_instances as $active_breakpoint_instance ) {
				$array_key = 'slides_to_scroll_' . $active_breakpoint_instance->get_name();
				if ( 'mobile' === $active_breakpoint_instance->get_name() ) {
					$breakpoint = '0';
				}
				if ( 'widescreen' === $active_breakpoint_instance->get_name() ) {
					$breakpoint = (string) $active_breakpoint_instance->get_default_value();
					if ( property_exists( $active_breakpoint_instance, 'value' ) ) {
						$breakpoint = (string) ( $active_breakpoint_instance->get_value() );
					}
					$swiper_settings['breakpoints'][ $breakpoint ]['slidesPerGroup'] = isset( $settings[ $array_key ] ) ? $settings[ $array_key ] : 1;
					continue;
				}
				$swiper_settings['breakpoints'][ $breakpoint ]['slidesPerGroup'] = isset( $settings[ $array_key ] ) ? $settings[ $array_key ] : 1;
				$breakpoint = (string) $active_breakpoint_instance->get_default_value() + 1;
				if ( property_exists( $active_breakpoint_instance, 'value' ) ) {
					$breakpoint = (string) ( $active_breakpoint_instance->get_value() + 1 );
				}
			}
		}

		if ( 'yes' === $settings['enable_space_between'] ) {
			$breakpoint = '1441';
			$swiper_settings['breakpoints'][ $breakpoint ]['spaceBetween'] = isset( $settings['space_between'] ) ? (int) $settings['space_between'] : 8;
			foreach ( $active_breakpoint_instances as $active_breakpoint_instance ) {
				$array_key = 'space_between_' . $active_breakpoint_instance->get_name();
				if ( 'mobile' === $active_breakpoint_instance->get_name() ) {
					$breakpoint = '0';
				}
				if ( 'widescreen' === $active_breakpoint_instance->get_name() ) {
					$breakpoint = (string) $active_breakpoint_instance->get_default_value();
					if ( property_exists( $active_breakpoint_instance, 'value' ) ) {
						$breakpoint = (string) ( $active_breakpoint_instance->get_value() );
					}
					$swiper_settings['breakpoints'][ $breakpoint ]['spaceBetween'] = isset( $settings[ $array_key ] ) ? (int) $settings[ $array_key ] : 8;
					continue;
				}
				$swiper_settings['breakpoints'][ $breakpoint ]['spaceBetween'] = isset( $settings[ $array_key ] ) ? (int) $settings[ $array_key ] : 8;
				$breakpoint = (string) $active_breakpoint_instance->get_default_value() + 1;
				if ( property_exists( $active_breakpoint_instance, 'value' ) ) {
					$breakpoint = (string) ( $active_breakpoint_instance->get_value() + 1 );
				}
			}
		}

		$prev_id = ! empty( $section_id ) ? 'prev-' . $section_id : '';
		$next_id = ! empty( $section_id ) ? 'next-' . $section_id : '';
		if ( 'yes' === $settings['show_arrows'] && 'yes' !== $settings['show_custom_arrows'] ) {
			$swiper_settings['navigation'] = array(
				'prevEl' => '#' . $prev_id,
				'nextEl' => '#' . $next_id,

			);
		}
		if ( 'yes' === $settings['show_custom_arrows'] && 'yes' !== $settings['show_arrows'] ) {
			$swiper_settings['navigation'] = array(
				'prevEl' => '#' . $settings['custom_prev_id'],
				'nextEl' => '#' . $settings['custom_next_id'],

			);
		}

		if ( 'yes' === $settings['center_slides'] ) {
			$swiper_settings['centeredSlides']       = true;
			$swiper_settings['centeredSlidesBounds'] = true;
		}

		if ( $settings['loop'] ) {
			$swiper_settings['loop'] = true;
		}
		if ( $settings['autoplay'] && $settings['autoplay_speed'] ) {
			$swiper_settings['autoplay']['delay'] = $settings['autoplay_speed'];
		}
		if ( $settings['autoplay'] && $settings['pause_on_hover'] ) {
			$swiper_settings['autoplay']['pauseOnMouseEnter']    = true;
			$swiper_settings['autoplay']['disableOnInteraction'] = false;
		}
		if ( $settings['speed'] ) {
			$swiper_settings['speed'] = $settings['speed'];
		}

		return $swiper_settings;
	}

	/**
	 * Add Actions.
	 */
	protected function add_actions() {
		add_action( 'elementor/element/after_section_end', array( $this, 'register_controls' ), 10, 2 );
		add_action( 'elementor/frontend/section/before_render', array( $this, 'before_render_section' ), 10 );
		add_action( 'elementor/frontend/section/after_render', array( $this, 'after_render_section' ), 15 );
		add_action( 'elementor/frontend/column/before_render', array( $this, 'before_render_column' ), 5 );
		add_action( 'elementor/element/column/section_advanced/before_section_end', array( $this, 'add_column_wrapper_controls' ) );
		add_action( 'elementor/element/section/section_layout/after_section_end', array( $this, 'register_button_layout_controls' ) );
		add_action( 'elementor/element/section/swiper_section_navigation/after_section_end', array( $this, 'register_button_style_controls' ) );

		// Container Carousel Hooks.
		add_action( 'elementor/frontend/container/before_render', array( $this, 'before_render_section' ), 10 );
		add_action( 'elementor/frontend/container/after_render', array( $this, 'after_render_section' ), 15 );
		add_action( 'elementor/element/container/section_layout_container/after_section_end', array( $this, 'register_button_layout_controls' ) );
		add_action( 'elementor/element/container/swiper_section_navigation/after_section_end', array( $this, 'register_button_style_controls' ) );
		add_action( 'elementor/element/container/swiper_section_navigation/after_section_end', array( $this, 'register_swiper_arrow_spacing_controls' ) );
	}

	/**
	 * Add wrap controls to the column element.
	 *
	 * @param Element_Column $element The Column element object.
	 */
	public function add_column_wrapper_controls( $element ) {

		$element->add_control(
			'enable_slide',
			array(
				'type'    => Controls_Manager::SWITCHER,
				'label'   => esc_html__( 'Enable Slide', 'mas-addons-for-elementor' ),
				'default' => 'no',
			)
		);
	}

	/**
	 * Add wrap controls to the column element.
	 *
	 * @param Element_Column $element The Column element object.
	 */
	public function register_button_layout_controls( $element ) {

		$element->start_controls_section(
			'section_swiper_button',
			array(
				'label'     => esc_html__( 'Button', 'mas-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_LAYOUT,
				'condition' => array(
					'enable_carousel' => 'yes',
					'show_arrows'     => 'yes',
				),
			)
		);

			$this->register_button_content_controls( $element );

		$element->end_controls_section();
	}

	/**
	 * Instantiate the class.
	 */
	public function __construct() {
		parent::__construct();
		$this->add_actions();

		add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'register_frontend_scripts' ) );
		add_action( 'elementor/frontend/before_enqueue_styles', array( $this, 'register_frontend_styles' ) );
	}

	/**
	 * Register frontend script.
	 */
	public function register_frontend_scripts() {
		wp_enqueue_script(
			'swiper-script',
			MAS_ELEMENTOR_MODULES_URL . 'carousel-attributes/assets/js/swiper-bundle.min.js',
			array(),
			MAS_ELEMENTOR_VERSION,
			true
		);
		wp_enqueue_script(
			'swiper-init-script',
			MAS_ELEMENTOR_MODULES_URL . 'carousel-attributes/assets/js/carousel.js',
			array(),
			MAS_ELEMENTOR_VERSION,
			true
		);

	}

	/**
	 * Register frontend styles.
	 */
	public function register_frontend_styles() {
		wp_enqueue_style(
			'swiper-stylesheet',
			MAS_ELEMENTOR_MODULES_URL . 'carousel-attributes/assets/css/swiper-bundle.min.css',
			array(),
			MAS_ELEMENTOR_VERSION
		);
	}
}
