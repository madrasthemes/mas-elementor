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

		$element->start_injection(
			array(
				'at' => 'before',
				'of' => 'section_effects',
			)
		);
		$element->start_controls_section(
			'section_carousel_attributes',
			array(
				'label' => __( 'Carousel', 'mas-elementor' ),
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
				'label'   => esc_html__( 'Enable Carousel', 'mas-elementor' ),
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
				)
			);

			$element->add_control(
				'enable_swiper_wrapper',
				array(
					'type'      => Controls_Manager::SWITCHER,
					'label'     => esc_html__( 'Enable Swiper wrapper', 'mas-elementor' ),
					'default'   => 'no',
					'condition' => array(
						'enable_carousel!'     => 'yes',
						'enable_swiper_slide!' => 'yes',
					),
				)
			);

			$element->add_control(
				'enable_swiper_slide',
				array(
					'type'      => Controls_Manager::SWITCHER,
					'label'     => esc_html__( 'Enable Swiper Slide', 'mas-elementor' ),
					'default'   => 'no',
					'condition' => array(
						'enable_carousel!'       => 'yes',
						'enable_swiper_wrapper!' => 'yes',
					),
				)
			);
		}

		$element->add_control(
			'carousel_effect',
			array(
				'type'      => Controls_Manager::SELECT,
				'label'     => esc_html__( 'Effect', 'mas-elementor' ),
				'default'   => 'slide',
				'options'   => array(
					''      => esc_html__( 'None', 'mas-elementor' ),
					'slide' => esc_html__( 'Slide', 'mas-elementor' ),
					'fade'  => esc_html__( 'Fade', 'mas-elementor' ),
				),
				'condition' => array(
					'enable_carousel' => 'yes',
				),
			)
		);

		// TODO: Once Core 3.4.0 is out, get the active devices using Breakpoints/Manager::get_active_devices_list().
		$active_breakpoint_instances = Plugin::$instance->breakpoints->get_active_breakpoints();
		// Devices need to be ordered from largest to smallest.
		$active_devices = array_reverse( array_keys( $active_breakpoint_instances ) );

		$slides_per_view  = array(
			'type'      => Controls_Manager::NUMBER,
			'label'     => esc_html__( 'Slides Per View', 'mas-elementor' ),
			'min'       => 1,
			'max'       => 10,
			'default'   => 1,
			'condition' => array(
				'carousel_effect' => 'slide',
				'enable_carousel' => 'yes',
			),
		);
		$slides_to_scroll = array(
			'type'      => Controls_Manager::NUMBER,
			'label'     => esc_html__( 'Slides To Scroll', 'mas-elementor' ),
			'min'       => 1,
			'max'       => 10,
			'default'   => 1,
			'condition' => array(
				'carousel_effect' => 'slide',
				'enable_carousel' => 'yes',
			),
			'default'   => 1,
		);
		$space_between    = array(
			'type'        => Controls_Manager::NUMBER,
			'label'       => esc_html__( 'Space Between', 'mas-elementor' ),
			'description' => esc_html__( 'Set Space between each Slides', 'mas-elementor' ),
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
				'label'     => esc_html__( 'Enable Space Between', 'mas-elementor' ),
				'default'   => 'yes',
				'label_off' => esc_html__( 'Hide', 'mas-elementor' ),
				'label_on'  => esc_html__( 'Show', 'mas-elementor' ),
				'condition' => array(
					'carousel_effect' => 'slide',
					'enable_carousel' => 'yes',
				),
			)
		);

		$element->add_responsive_control(
			'space_between',
			$space_between
		);

		$element->add_control(
			'swiper_height_auto',
			array(
				'label'                => esc_html__( 'Height', 'mas-elementor' ),
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
			)
		);

		$element->add_control(
			'center_slides',
			array(
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Center Slides', 'mas-elementor' ),
				'default'   => 'no',
				'label_off' => esc_html__( 'Hide', 'mas-elementor' ),
				'label_on'  => esc_html__( 'Show', 'mas-elementor' ),
				'condition' => array(
					'carousel_effect' => 'slide',
					'enable_carousel' => 'yes',
				),
			)
		);

		$element->add_control(
			'show_arrows',
			array(
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Arrows', 'mas-elementor' ),
				'default'   => 'no',
				'label_off' => esc_html__( 'Hide', 'mas-elementor' ),
				'label_on'  => esc_html__( 'Show', 'mas-elementor' ),
				'condition' => array(
					'enable_carousel'     => 'yes',
					'show_custom_arrows!' => 'yes',
				),
			)
		);

		$element->add_responsive_control(
			'hide_responsive_arrows',
			array(
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Hide Arrow Responsive', 'mas-elementor' ),
				'default'      => 'no',
				'label_off'    => esc_html__( 'Hide', 'mas-elementor' ),
				'label_on'     => esc_html__( 'Show', 'mas-elementor' ),
				'condition'    => array(
					'enable_carousel'     => 'yes',
					'show_arrows'         => 'yes',
					'show_custom_arrows!' => 'yes',
				),
				'return_value' => 'hide',
				'prefix_class' => 'mas-swiper-carousel--arrows%s-',
			)
		);

		$element->add_control(
			'show_custom_arrows',
			array(
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Custom Arrows', 'mas-elementor' ),
				'default'   => 'no',
				'label_off' => esc_html__( 'Hide', 'mas-elementor' ),
				'label_on'  => esc_html__( 'Show', 'mas-elementor' ),
				'condition' => array(
					'enable_carousel' => 'yes',
					'show_arrows!'    => 'yes',
				),

			)
		);

		$element->add_control(
			'custom_prev_id',
			array(
				'label'     => esc_html__( 'Previous Arrow ID', 'mas-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array(
					'enable_carousel'    => 'yes',
					'show_arrows!'       => 'yes',
					'show_custom_arrows' => 'yes',
				),
			)
		);

		$element->add_control(
			'custom_next_id',
			array(
				'label'     => __( 'Next Arrow ID', 'mas-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array(
					'enable_carousel'    => 'yes',
					'show_arrows!'       => 'yes',
					'show_custom_arrows' => 'yes',
				),
			)
		);

		$element->add_control(
			'speed',
			array(
				'label'       => esc_html__( 'Transition Duration', 'mas-elementor' ),
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
				'label'       => esc_html__( 'Autoplay', 'mas-elementor' ),
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
				'label'       => esc_html__( 'Autoplay Speed', 'mas-elementor' ),
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
				'label'     => esc_html__( 'Infinite Loop', 'mas-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => array(
					'enable_carousel' => 'yes',
				),
			)
		);

		$element->add_control(
			'pause_on_hover',
			array(
				'label'       => esc_html__( 'Pause on Hover', 'mas-elementor' ),
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
				'label'     => esc_html__( 'Enable Pagination', 'mas-elementor' ),
				'default'   => 'yes',
				'label_off' => esc_html__( 'Hide', 'mas-elementor' ),
				'label_on'  => esc_html__( 'Show', 'mas-elementor' ),
				'condition' => array(
					'enable_carousel' => 'yes',
				),
			)
		);

		$element->add_control(
			'pagination',
			array(
				'label'     => esc_html__( 'Pagination', 'mas-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'bullets',
				'options'   => array(
					'bullets'     => esc_html__( 'Dots', 'mas-elementor' ),
					'fraction'    => esc_html__( 'Fraction', 'mas-elementor' ),
					'progressbar' => esc_html__( 'Progress', 'mas-elementor' ),
				),
				'condition' => array(
					'enable_carousel' => 'yes',
					'show_pagination' => 'yes',
				),
			)
		);

		$element->add_control(
			'pagination_style',
			array(
				'label'     => esc_html__( 'Pagination Style', 'mas-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'horizontal',
				'options'   => array(
					'horizontal' => esc_html__( 'Horizontal', 'mas-elementor' ),
					'vertical'   => esc_html__( 'Vertical', 'mas-elementor' ),
				),
				'condition' => array(
					'enable_carousel' => 'yes',
					'show_pagination' => 'yes',

				),
			)
		);

		$element->end_controls_section();
		$element->end_injection();
		$this->register_pagination_style_controls( $element );

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
			$element->add_render_attribute( '_wrapper', 'class', 'swiper' );
			$element->add_render_attribute( '_wrapper', 'data-swiper-options', $json );
			$element->add_render_attribute( 'section_carousel', 'class', 'mas-swiper-wrapper elementor-element' );
			$element->add_render_attribute( 'section_carousel', 'style', 'position: relative;' );
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
			$swiper_settings['breakpoints'][ $breakpoint ]['spaceBetween'] = isset( $settings['space_between'] ) ? $settings['space_between'] : 8;
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
					$swiper_settings['breakpoints'][ $breakpoint ]['spaceBetween'] = isset( $settings[ $array_key ] ) ? $settings[ $array_key ] : 8;
					continue;
				}
				$swiper_settings['breakpoints'][ $breakpoint ]['spaceBetween'] = isset( $settings[ $array_key ] ) ? $settings[ $array_key ] : 8;
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
			$swiper_settings['centeredSlides'] = true;
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
				'label'   => esc_html__( 'Enable Slide', 'mas-elementor' ),
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
				'label'     => esc_html__( 'Button', 'mas-elementor' ),
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

		add_action( 'elementor/frontend/before_register_scripts', array( $this, 'register_frontend_scripts' ) );
		add_action( 'elementor/frontend/before_register_styles', array( $this, 'register_frontend_styles' ) );
	}

	/**
	 * Add wrap controls to the column element.
	 *
	 * @param string $content content.
	 * @param array  $instance object.
	 */
	public function testing( $content, $instance ) {

		if ( $instance->get_name() !== 'section' ) {
			return $content;
		}

		// Doing a simple find and replace on the template.
		// I know it's not ideal but I didn't want to duplicate the whole template .
		// and this seems like it shouldn't change often...
		// Note: in $tpl, I don't have access to the very top level section tag :(.
		$content = str_replace( 'class="elementor-container', 'class="elementor-container swiper-wrapper', $content );
		return $content;

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

	/**
	 * Render script in the editor.
	 */
	public function render_script() {
		if ( Plugin::$instance->editor->is_edit_mode() ) :
			?>
			<script type="text/javascript">
			var carousel = (() => {
				// forEach function
				let forEach = (array, callback, scope) => {
				for (let i = 0; i < array.length; i++) {
					callback.call(scope, i, array[i]); // passes back stuff we need
				}
				};

				// Carousel initialisation
				let carousels = document.querySelectorAll('.swiper');
				forEach(carousels, (index, value) => {
					let userOptions,
					pagerOptions;
				if(value.dataset.swiperOptions != undefined) userOptions = JSON.parse(value.dataset.swiperOptions);


				// Pager
				if(userOptions.pager) {
					pagerOptions = {
					pagination: {
						el: userOptions.pager,
						clickable: true,
						bulletActiveClass: 'active',
						bulletClass: 'page-item',
						renderBullet: function (index, className) {
						return '<li class="' + className + '"><a href="#" class="page-link btn-icon btn-sm">' + (index + 1) + '</a></li>';
						}
					}
					}
				}

				// Slider init
				let options = {...userOptions, ...pagerOptions};
				let swiper = new Swiper(value, options);

				// Tabs (linked content)
				if(userOptions.tabs) {

					swiper.on('activeIndexChange', (e) => {
					let targetTab = document.querySelector(e.slides[e.activeIndex].dataset.swiperTab),
						previousTab = document.querySelector(e.slides[e.previousIndex].dataset.swiperTab);

					previousTab.classList.remove('active');
					targetTab.classList.add('active');
					});
				}

				});

				})();
			</script>
			<?php
		endif;
	}
}
