<?php
/**
 * Carousel Attributes.
 *
 * @package MASElementor\Modules\CarouselAttributes
 */

namespace MASElementor\Modules\CarouselAttributes\Traits;

use ELementor\Plugin;
use ELementor\Controls_Manager;

/**
 * The Swiper Options Trait.
 */
trait Swiper_Options_Trait {

	/**
	 * Get carousel settings
	 *
	 * @param array $widget widget.
	 * @param array $settings Settings of this widget.
	 * @return array
	 */
	public function get_swiper_carousel_options( $widget, array $settings ) {
		$active_breakpoint_instances = Plugin::$instance->breakpoints->get_active_breakpoints();
		// Devices need to be ordered from largest to smallest.
		$active_devices  = array_reverse( array_keys( $active_breakpoint_instances ) );
		$section_id      = $widget->get_id();
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
			$grid       = 'yes' === $settings['enable_grid'] && 'yes' !== $settings['loop'] && 'yes' !== $settings['center_slides'];
			$breakpoint = '1441';
			$swiper_settings['breakpoints'][ $breakpoint ]['slidesPerView'] = isset( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 3;

			if ( $grid ) {
				$swiper_settings['grid']['fill']                               = 'row';
				$swiper_settings['breakpoints'][ $breakpoint ]['grid']['rows'] = isset( $settings['carousel_rows'] ) ? $settings['carousel_rows'] : 1;
			}

			foreach ( $active_breakpoint_instances as $active_breakpoint_instance ) {
				$array_key = 'slides_per_view_' . $active_breakpoint_instance->get_name();
				$rows_key  = 'carousel_rows_' . $active_breakpoint_instance->get_name();
				if ( 'mobile' === $active_breakpoint_instance->get_name() ) {
					$breakpoint = '0';
				}
				if ( 'widescreen' === $active_breakpoint_instance->get_name() ) {
					$breakpoint = (string) $active_breakpoint_instance->get_default_value();
					if ( property_exists( $active_breakpoint_instance, 'value' ) ) {
						$breakpoint = (string) ( $active_breakpoint_instance->get_value() );
					}
					$swiper_settings['breakpoints'][ $breakpoint ]['slidesPerView'] = isset( $settings[ $array_key ] ) ? $settings[ $array_key ] : 1;
					if ( $grid ) {
						$swiper_settings['breakpoints'][ $breakpoint ]['grid']['rows'] = isset( $settings[ $rows_key ] ) ? $settings[ $rows_key ] : 1;
					}
					continue;
				}
				$swiper_settings['breakpoints'][ $breakpoint ]['slidesPerView'] = isset( $settings[ $array_key ] ) ? $settings[ $array_key ] : 1;
				if ( $grid ) {
					$swiper_settings['breakpoints'][ $breakpoint ]['grid']['rows'] = isset( $settings[ $rows_key ] ) ? $settings[ $rows_key ] : 1;
				}
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
			$swiper_settings['loop'] = 'true';
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
	 * Add carousel controls to the element.
	 *
	 * @param array $widget widget.
	 */
	public function register_swiper_controls( $widget ) {
		$widget->start_controls_section(
			'section_carousel_attributes',
			array(
				'label'     => __( 'Carousel', 'mas-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array( 'enable_carousel' => 'yes' ),
			)
		);

		$widget->add_control(
			'carousel_effect',
			array(
				'type'               => Controls_Manager::SELECT,
				'label'              => esc_html__( 'Effect', 'mas-addons-for-elementor' ),
				'default'            => 'slide',
				'options'            => array(
					''      => esc_html__( 'None', 'mas-addons-for-elementor' ),
					'slide' => esc_html__( 'Slide', 'mas-addons-for-elementor' ),
					'fade'  => esc_html__( 'Fade', 'mas-addons-for-elementor' ),
				),
				'condition'          => array(
					'enable_carousel' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$widget->add_control(
			'enable_grid',
			array(
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Grid', 'mas-addons-for-elementor' ),
				'default'   => 'no',
				'condition' => array(
					'enable_carousel' => 'yes',
					'carousel_effect' => 'slide',
				),
			)
		);

		$carousel_rows = array(
			'type'               => Controls_Manager::NUMBER,
			'label'              => esc_html__( 'Rows', 'mas-addons-for-elementor' ),
			'min'                => 1,
			'max'                => 10,
			'default'            => 1,
			'condition'          => array(
				'carousel_effect' => 'slide',
				'enable_carousel' => 'yes',
				'enable_grid'     => 'yes',
			),
			'frontend_available' => true,
		);

		// TODO: Once Core 3.4.0 is out, get the active devices using Breakpoints/Manager::get_active_devices_list().
		$active_breakpoint_instances = Plugin::$instance->breakpoints->get_active_breakpoints();
		// Devices need to be ordered from largest to smallest.
		$active_devices = array_reverse( array_keys( $active_breakpoint_instances ) );

		$slides_per_view = array(
			'type'               => Controls_Manager::NUMBER,
			'label'              => esc_html__( 'Slides Per View', 'mas-addons-for-elementor' ),
			'min'                => 1,
			'max'                => 10,
			'default'            => 1,
			'condition'          => array(
				'carousel_effect' => 'slide',
				'enable_carousel' => 'yes',
			),
			'frontend_available' => true,
		);

		$slides_to_scroll = array(
			'type'               => Controls_Manager::NUMBER,
			'label'              => esc_html__( 'Slides To Scroll', 'mas-addons-for-elementor' ),
			'min'                => 1,
			'max'                => 10,
			'default'            => 1,
			'condition'          => array(
				'carousel_effect' => 'slide',
				'enable_carousel' => 'yes',
			),
			'default'            => 1,
			'frontend_available' => true,
		);

		$space_between = array(
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
		);

		foreach ( $active_devices as $active_device ) {
			$space_between[ $active_device . '_default' ]    = 8;
			$slides_per_view[ $active_device . '_default' ]  = 1;
			$slides_to_scroll[ $active_device . '_default' ] = 1;
			$carousel_rows[ $active_device . '_default' ]    = 1;
		}

		$widget->add_responsive_control(
			'carousel_rows',
			$carousel_rows
		);

		$widget->add_responsive_control(
			'slides_per_view',
			$slides_per_view
		);

		$widget->add_responsive_control(
			'slides_to_scroll',
			$slides_to_scroll
		);

		$widget->add_control(
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
			)
		);

		$widget->add_responsive_control(
			'space_between',
			$space_between
		);

		$widget->add_control(
			'center_slides',
			array(
				'type'               => Controls_Manager::SWITCHER,
				'label'              => esc_html__( 'Center Slides', 'mas-addons-for-elementor' ),
				'default'            => 'no',
				'label_off'          => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
				'label_on'           => esc_html__( 'Show', 'mas-addons-for-elementor' ),
				'condition'          => array(
					'carousel_effect' => 'slide',
					'enable_carousel' => 'yes',
					'enable_grid!'    => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$widget->add_control(
			'mas_products_swiper_height',
			array(
				'label'                => esc_html__( 'Height', 'mas-addons-for-elementor' ),
				'type'                 => Controls_Manager::SELECT,
				'default'              => 'auto',
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
			)
		);

		$widget->add_control(
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
			)
		);

		$widget->add_responsive_control(
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
			)
		);

		$widget->add_control(
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
			)
		);

		$widget->add_control(
			'custom_prev_id',
			array(
				'label'     => esc_html__( 'Previous Arrow ID', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array(
					'enable_carousel'    => 'yes',
					'show_arrows!'       => 'yes',
					'show_custom_arrows' => 'yes',
				),
			)
		);

		$widget->add_control(
			'custom_next_id',
			array(
				'label'     => __( 'Next Arrow ID', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array(
					'enable_carousel'    => 'yes',
					'show_arrows!'       => 'yes',
					'show_custom_arrows' => 'yes',
				),
			)
		);

		$widget->add_control(
			'speed',
			array(
				'label'              => esc_html__( 'Transition Duration', 'mas-addons-for-elementor' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 500,
				'step'               => 100,
				'render_type'        => 'none',
				'condition'          => array(
					'enable_carousel' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$widget->add_control(
			'autoplay',
			array(
				'label'              => esc_html__( 'Autoplay', 'mas-addons-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'separator'          => 'before',
				'render_type'        => 'none',
				'condition'          => array(
					'enable_carousel' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$widget->add_control(
			'autoplay_speed',
			array(
				'label'              => esc_html__( 'Autoplay Speed', 'mas-addons-for-elementor' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 5000,
				'condition'          => array(
					'autoplay'        => 'yes',
					'enable_carousel' => 'yes',
				),
				'render_type'        => 'none',
				'frontend_available' => true,
			)
		);

		$widget->add_control(
			'loop',
			array(
				'label'              => esc_html__( 'Infinite Loop', 'mas-addons-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'condition'          => array(
					'enable_carousel' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$widget->add_control(
			'pause_on_hover',
			array(
				'label'              => esc_html__( 'Pause on Hover', 'mas-addons-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'condition'          => array(
					'autoplay'        => 'yes',
					'enable_carousel' => 'yes',
				),
				'render_type'        => 'none',
				'frontend_available' => true,
			)
		);

		$widget->add_control(
			'show_pagination',
			array(
				'type'               => Controls_Manager::SWITCHER,
				'label'              => esc_html__( 'Enable Pagination', 'mas-addons-for-elementor' ),
				'default'            => 'yes',
				'label_off'          => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
				'label_on'           => esc_html__( 'Show', 'mas-addons-for-elementor' ),
				'condition'          => array(
					'enable_carousel' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$widget->add_responsive_control(
			'hide_responsive_pagination',
			array(
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Hide Pagination Responsive', 'mas-addons-for-elementor' ),
				'default'      => 'no',
				'label_off'    => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
				'label_on'     => esc_html__( 'Show', 'mas-addons-for-elementor' ),
				'condition'    => array(
					'enable_carousel' => 'yes',
					'show_pagination' => 'yes',
				),
				'return_value' => 'hide',
				'prefix_class' => 'mas-swiper-carousel--pagination%s-',
			)
		);

		$widget->add_control(
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
			)
		);

		$widget->end_controls_section();
	}

	/**
	 * Add enable carousel controls to the element.
	 *
	 * @param array $widget widget.
	 */
	public function register_enable_carousel_control( $widget ) {
		$widget->add_control(
			'enable_carousel',
			array(
				'type'    => Controls_Manager::SWITCHER,
				'label'   => esc_html__( 'Enable Carousel', 'mas-addons-for-elementor' ),
				'default' => 'no',
			)
		);
	}
}
