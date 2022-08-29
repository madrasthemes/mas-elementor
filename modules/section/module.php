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
		add_filter( 'elementor/section/print_template', array( $this, 'print_template' ) );

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
	 * @return string
	 */
	public function print_template() {
		ob_start();
			$this->content_template();
		return ob_get_clean();
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
			<?php if ( ! Plugin::$instance->experiments->is_feature_active( 'e_dom_optimization' ) ) { ?>
				<div class="elementor-row"></div>
			<?php } ?>
		</div>
		<?php
	}
}
