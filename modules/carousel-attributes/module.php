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
	// public static function is_active() {.
	// return ! class_exists( 'ElementorPro\Plugin' );
	// }.

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
			'show_arrows',
			array(
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Arrows', 'mas-elementor' ),
				'default'   => 'yes',
				'label_off' => esc_html__( 'Hide', 'mas-elementor' ),
				'label_on'  => esc_html__( 'Show', 'mas-elementor' ),
				'condition' => array(
					'enable_carousel' => 'yes',
				),
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

		$element->add_control(
			'show_pagination',
			array(
				'type'               => Controls_Manager::SWITCHER,
				'label'              => esc_html__( 'Enable Pagination', 'mas-elementor' ),
				'default'            => 'yes',
				'label_off'          => esc_html__( 'Hide', 'mas-elementor' ),
				'label_on'           => esc_html__( 'Show', 'mas-elementor' ),
				'condition'          => array(
					'enable_carousel' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$element->end_controls_section();
		$element->end_injection();

		$element->start_controls_section(
			'section_navigation',
			array(
				'label' => esc_html__( 'Navigation', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$element->add_control(
			'heading_arrows',
			array(
				'label'     => esc_html__( 'Arrows', 'mas-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'none',
				'condition' => array(
					'show_arrows' => 'yes',
				),
			)
		);

		$element->add_control(
			'arrows_size',
			array(
				'label'     => esc_html__( 'Size', 'mas-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 20,
				),
				'range'     => array(
					'px' => array(
						'min' => 10,
						'max' => 40,
					),
				),
				'selectors' => array(
					// '{{WRAPPER}} .sn-elementor-swiper-button' => 'width: {{SIZE}}{{UNIT}} !important;',
					// '{{WRAPPER}} .sn-elementor-swiper-button' => 'height: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} .sn-elementor-swiper-icon' => 'font-size: {{SIZE}}{{UNIT}} !important;',
				),
				'condition' => array(
					'show_arrows' => 'yes',
				),
			)
		);

		$element->add_control(
			'arrows_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .sn-elementor-swiper-icon' => 'color: {{VALUE}}',
					'{{WRAPPER}} .sn-elementor-swiper-button svg' => 'fill: {{VALUE}}',
				),
				'condition' => array(
					'show_arrows' => 'yes',
				),
			)
		);

		$element->add_control(
			'arrows_hover_color',
			array(
				'label'     => esc_html__( 'Background Hover Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .btn-prev:hover, {{WRAPPER}} .btn-prev:focus' => 'background-color: {{VALUE}} !important;',
					'{{WRAPPER}} .btn-prev:hover svg path, {{WRAPPER}} .btn-prev:focus svg path' => 'fill: {{VALUE}} !important;',
					'{{WRAPPER}} .btn-next:hover, {{WRAPPER}} .btn-next:focus' => 'background-color: {{VALUE}} !important;',
					'{{WRAPPER}} .btn-next:hover svg path, {{WRAPPER}} .btn-next:focus svg path' => 'fill: {{VALUE}} !important;',
				),
				'condition' => array(
					'show_arrows' => 'yes',
				),
			)
		);

		$element->add_control(
			'heading_pagination',
			array(
				'label' => esc_html__( 'Pagination', 'mas-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$element->add_control(
			'pagination_size',
			array(
				'label'     => esc_html__( 'Size', 'mas-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 20,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} + .swiper-pagination .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}!important;',
					'{{WRAPPER}} + .swiper-pagination .swiper-container-horizontal .swiper-pagination-progressbar' => 'height: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} + .swiper-pagination .swiper-pagination-fraction' => 'font-size: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$element->add_control(
			'dots_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'%' => array(
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} + .swiper-pagination .swiper-pagination-bullet' => 'border-radius: {{SIZE}}{{UNIT}} !important;',
				),

			)
		);

		$element->add_control(
			'dots_height',
			array(
				'label'     => esc_html__( 'Dots Height', 'mas-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 20,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} + .swiper-pagination .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}!important;',
				),
			)
		);

		$element->add_control(
			'pagination_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} + .swiper-pagination .swiper-pagination-bullet-active, {{WRAPPER}} + .swiper-pagination .swiper-pagination-progressbar-fill' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} + .swiper-pagination .swiper-pagination-fraction' => 'color: {{VALUE}}',
				),
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
			$element->add_render_attribute( '_wrapper', 'class', 'swiper' );
			$element->add_render_attribute( '_wrapper', 'data-swiper-options', $json );
			$element->add_render_attribute( 'section_carousel', 'class', 'mas-swiper-wrapper' );
			$element->add_render_attribute( 'section_carousel', 'style', 'position: relative;' );
			// $element->add_render_attribute( 'section_carousel', 'data-swiper-options', $json );
			?>
			<div <?php $element->print_render_attribute_string( 'section_carousel' ); ?>>
			<?php
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
			if ( ! empty( $settings['pag_id'] && 'yes' === $settings['show_pagination'] ) ) {
				$element->add_render_attribute( 'swiper-pagination', 'id', 'pagination-' . $section_id );
			}
			$element->add_render_attribute( 'swiper-pagination', 'class', 'swiper-pagination' );
			if ( 'yes' === $settings['show_pagination'] ) :
				?>
			<div <?php $element->print_render_attribute_string( 'swiper-pagination' ); ?>></div>
				<?php
			endif;
			if ( 'yes' === $settings['show_arrows'] ) :
				$prev_id = ! empty( $section_id ) ? 'prev-' . $section_id : '';
				$next_id = ! empty( $section_id ) ? 'next-' . $section_id : '';
				?>
				<!-- If we need navigation buttons -->
				<div id ="<?php echo esc_attr( $prev_id ); ?>" class="swiper-button-prev mas-elementor-swiper-arrow"></div>
				<div id ="<?php echo esc_attr( $next_id ); ?>" class="swiper-button-next mas-elementor-swiper-arrow"></div>
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
		$section_id      = $element->get_id();
		$swiper_settings = array();
		if ( 'yes' === $settings['show_pagination'] ) {
			$swiper_settings['pagination'] = array(
				'el'        => '#pagination-' . $section_id,
				'clickable' => true,

			);
		}

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

		$prev_id = ! empty( $section_id ) ? 'prev-' . $section_id : '';
		$next_id = ! empty( $section_id ) ? 'next-' . $section_id : '';
		if ( 'yes' === $settings['show_arrows'] ) {
			$swiper_settings['navigation'] = array(
				'prevEl' => '#' . $prev_id,
				'nextEl' => '#' . $next_id,

			);
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
		add_action( 'elementor/frontend/section/after_render', array( $this, 'after_render_section' ), 15 );
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
			MAS_ELEMENTOR_MODULES_URL . 'carousel-attributes/assets/css/swiper-bundle.min.css',
			array(),
			MAS_ELEMENTOR_VERSION,
		);
	}
}
