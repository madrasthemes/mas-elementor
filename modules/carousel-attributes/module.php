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

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The Carousel Attributes module class
 */
class Module extends Module_Base {

	/**
	 * Return the activation of the elementor-pro.
	 *
	 * @return string
	 */
	public static function is_active() {
		return ! class_exists( 'ElementorPro\Plugin' );
	}

	/**
	 * Return the script dependencies of the module.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array( 'swiper-script' );
	}

	/**
	 * Return the style dependencies of the module.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array( 'swiper-stylesheet' );
	}

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

		$element->add_control(
			'carousel_effect',
			array(
				'type'               => Controls_Manager::SELECT,
				'label'              => esc_html__( 'Effect', 'mas-elementor' ),
				'default'            => 'slide',
				'options'            => array(
					''      => esc_html__( 'None', 'mas-elementor' ),
					'slide' => esc_html__( 'Slide', 'mas-elementor' ),
					'fade'  => esc_html__( 'Fade', 'mas-elementor' ),
				),
				'condition'          => array(
					'enable_carousel' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$element->add_responsive_control(
			'slides_per_view',
			array(
				'type'               => Controls_Manager::NUMBER,
				'label'              => esc_html__( 'Slides Per View', 'mas-elementor' ),
				'min'                => 1,
				'max'                => 10,
				'default'            => 1,
				'condition'          => array(
					'carousel_effect' => 'slide',
					'enable_carousel' => 'yes',
				),
				'devices'            => array( 'desktop', 'tablet', 'mobile' ),
				'default'            => 1,
				'tablet_default'     => 1,
				'mobile_default'     => 1,
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'center_slides',
			array(
				'type'               => Controls_Manager::SWITCHER,
				'label'              => esc_html__( 'Center Slides', 'mas-elementor' ),
				'default'            => 'no',
				'label_off'          => esc_html__( 'Hide', 'mas-elementor' ),
				'label_on'           => esc_html__( 'Show', 'mas-elementor' ),
				'condition'          => array(
					'enable_carousel' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'speed',
			array(
				'label'              => esc_html__( 'Transition Duration', 'mas-elementor' ),
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

		$element->add_control(
			'autoplay',
			array(
				'label'              => esc_html__( 'Autoplay', 'mas-elementor' ),
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

		$element->add_control(
			'autoplay_speed',
			array(
				'label'              => esc_html__( 'Autoplay Speed', 'mas-elementor' ),
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

		$element->add_control(
			'loop',
			array(
				'label'              => esc_html__( 'Infinite Loop', 'mas-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'condition'          => array(
					'enable_carousel' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'pause_on_hover',
			array(
				'label'              => esc_html__( 'Pause on Hover', 'mas-elementor' ),
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

		$element->end_controls_section();
		$element->end_injection();
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
		$json     = wp_json_encode( $this->get_swiper_carousel_options( $settings ) );
		if ( 'yes' === $settings['enable_carousel'] ) {
			$element->add_render_attribute( '_wrapper', 'class', 'swiper' );
			$element->add_render_attribute( '_wrapper', 'data-swiper-options', $json );
		}

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
	 * @param array $settings The widget settings.
	 * @return array
	 */
	public function get_swiper_carousel_options( array $settings ) {

		$swiper_settings = array();

		if ( 'fade' === $settings['carousel_effect'] ) {
			$swiper_settings['effect']                  = 'fade';
			$swiper_settings['fadeEffect']['crossFade'] = true;
		}
		if ( 'slide' === $settings['carousel_effect'] ) {
			$swiper_settings['breakpoints']['1440']['slidesPerView'] = isset( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 3;
			$swiper_settings['breakpoints']['1024']['slidesPerView'] = isset( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 3;
			$swiper_settings['breakpoints']['500']['slidesPerView']  = isset( $settings['slides_per_view_tablet'] ) ? $settings['slides_per_view_tablet'] : 3;
			$swiper_settings['breakpoints']['0']['slidesPerView']    = isset( $settings['slides_per_view_mobile'] ) ? $settings['slides_per_view_mobile'] : 1;

		}

		if ( $settings['center_slides'] ) {
			$swiper_settings['centeredSlides'] = 'true';
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
	 * Add Actions.
	 */
	protected function add_actions() {
		add_action( 'elementor/element/after_section_end', array( $this, 'register_controls' ), 10, 2 );
		add_action( 'elementor/frontend/section/before_render', array( $this, 'before_render_section' ), 15 );
		add_action( 'elementor/frontend/column/before_render', array( $this, 'before_render_column' ), 5 );
		add_action( 'elementor/element/column/section_advanced/before_section_end', array( $this, 'add_column_wrapper_controls' ) );
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
	 * Instantiate the class.
	 */
	public function __construct() {
		parent::__construct();
		$this->add_actions();

		add_action( 'elementor/frontend/before_register_scripts', array( $this, 'register_frontend_scripts' ) );
		add_action( 'elementor/frontend/before_register_styles', array( $this, 'register_frontend_styles' ) );

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
			MAS_ELEMENTOR_MODULES_URL . 'carousel-attributes/assets/css/swiper-bundle.css',
			array(),
			MAS_ELEMENTOR_VERSION,
			'all'
		);
	}
}