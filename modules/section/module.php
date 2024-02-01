<?php
/**
 * Section.
 *
 * @package MASElementor\Modules\Section
 */

namespace MASElementor\Modules\Section;

use MASElementor\Base\Module_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Module
 */
class Module extends Module_Base {

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();

		$this->add_actions();
	}

	/**
	 * Get Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'override-section';
	}

	/**
	 * Add Action.
	 *
	 * @return void
	 */
	public function add_actions() {
		add_action( 'elementor/frontend/section/before_render', array( $this, 'before_render' ), 5 );
		add_action( 'elementor/element/section/section_advanced/before_section_end', array( $this, 'add_section_controls' ), 10, 2 );
		add_filter( 'elementor/section/print_template', array( $this, 'print_template' ), 10, 2 );

	}

	/**
	 * Add Section Controls.
	 *
	 * @param array $element The widget.
	 * @param array $args The widget.
	 * @return void
	 */
	public function add_section_controls( $element, $args ) {
		$element->add_control(
			'mas_container_class',
			array(
				'label'       => esc_html__( 'Container CSS Classes', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Applied to elementor-container element. You can use additional bootstrap utility classes here.', 'mas-elementor' ),
			)
		);

		$element->add_responsive_control(
			'mas_section_height',
			array(
				'label'      => esc_html__( 'Height', 'mas-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'custom' ),
				'range'      => array(
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
					'px' => array(
						'min' => 0,
						'max' => 10000,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}}.elementor-section'   => 'height: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.elementor-container' => 'height: {{SIZE}}{{UNIT}}',
				),
			)
		);
	}

	/**
	 * Before Render.
	 *
	 * @param array $element The widget.
	 *
	 * @return void
	 */
	public function before_render( $element ) {

		$settings        = $element->get_settings();
		$container_class = $settings['gap'];

		if ( 'no' === $settings['gap'] ) {
			$container_class = $settings['gap'] . ' no-gutters';
		}

		if ( isset( $settings['mas_container_class'] ) && ! empty( $settings['mas_container_class'] ) ) {
			$container_class .= ' ' . $settings['mas_container_class'];
		}

		if ( ! empty( $container_class ) ) {
			$element->set_settings( 'gap', $container_class );
		}

	}

	/**
	 * Print Template.
	 *
	 * @param string $template template.
	 * @param array  $widget The section element object.
	 *
	 * @return string
	 */
	public function print_template( $template, $widget ) {
		if ( 'section' === $widget->get_name() ) {
			ob_start();
			$this->content_template();
			$template = ob_get_clean();
		}
		return $template;
	}

	/**
	 * Content Template.
	 *
	 * @return void
	 */
	public function content_template() {
		?>
		<#
		let swiper_wrapper = '';
		if ( settings.background_video_link ) {
			let videoAttributes = 'autoplay muted playsinline';

			if ( ! settings.background_play_once ) {
				videoAttributes += ' loop';
			}

			view.addRenderAttribute( 'background-video-container', 'class', 'elementor-background-video-container' );

			if ( ! settings.background_play_on_mobile ) {
				view.addRenderAttribute( 'background-video-container', 'class', 'elementor-hidden-phone' );
			}
		#>
			<div {{{ view.getRenderAttributeString( 'background-video-container' ) }}}>
				<div class="elementor-background-video-embed"></div>
				<video class="elementor-background-video-hosted elementor-html5-video" {{ videoAttributes }}></video>
			</div>
		<# }
			if ( 'no' == settings.gap ) {
				settings.gap = `${ settings.gap } no-gutters`;
			}

			if ( '' != settings.mas_container_class ) {
				settings.gap = `${ settings.gap } ${ settings.mas_container_class }`;
			}
			if ( 'yes' == settings.enable_carousel ) {
				swiper_wrapper = 'swiper-wrapper';
			}
		#>
		<div class="elementor-background-overlay"></div>
		<div class="elementor-shape elementor-shape-top"></div>
		<div class="elementor-shape elementor-shape-bottom"></div>
		<div class="elementor-container {{swiper_wrapper}} elementor-column-gap-{{ settings.gap }}">
		</div>
		<?php
	}
}
