<?php
/**
 * Testimonial.
 *
 * @package MASElementor\Modules\testimonial 
 */

namespace MASElementor\Modules\testimonial;

use MASElementor\Base\Module_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Group_Control_Box_Shadow;


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
		return 'testimonial';
	}

	/**
	 * Add Action.
	 *
	 * @return void
	 */
	public function add_actions() {
		add_action( 'elementor/element/testimonial/section_style_testimonial_content/before_section_end', array( $this, 'add_testimonial_style_controls' ), 15 );
		add_filter( 'elementor/testimonial/print_template', array( $this, 'print_template' ) );
	}

	/**
	 * Add wrap controls to the column element.
	 *
	 * @param Element_Column $element The Column element object.
	 */
	public function add_testimonial_style_controls( $element ) {
		//$element->start_controls_tabs( 'mas_elementor_icon_colors' );

		$element->start_injection(
			[
				'of' => 'content_shadow',
				'at' => 'before',
			]
		);
		$element->add_control(
			'mas_description_padding',
			[
				'label' => esc_html__( 'Padding', 'mas-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-testimonial-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$element->end_controls_tab();
		
		$element->end_injection();

		//$element->end_controls_tabs();
	}

	/**
	 * Before Render.
	 *
	 * @param array $widget The widget.
	 * @return void
	 */
	public function before_render( $widget ) {

		$settings = $widget->get_settings();

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
	 * Render testimonial widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 2.9.0
	 */
	protected function content_template() {
		?>
		<#
		var image = {
				id: settings.testimonial_image.id,
				url: settings.testimonial_image.url,
				size: settings.testimonial_image_size,
				dimension: settings.testimonial_image_custom_dimension,
				model: view.getEditModel()
			};
		var imageUrl = false, hasImage = '';

		if ( '' !== settings.testimonial_image.url ) {
			imageUrl = elementor.imagesManager.getImageUrl( image );
			hasImage = ' elementor-has-image';

			var imageHtml = '<img src="' + imageUrl + '" alt="testimonial" />';
			if ( settings.link.url ) {
				imageHtml = '<a href="' + settings.link.url + '">' + imageHtml + '</a>';
			}
		}

		var testimonial_image_position = settings.testimonial_image_position ? ' elementor-testimonial-image-position-' + settings.testimonial_image_position : '';
		#>
		<div class="elementor-testimonial-wrapper">
			<# if ( '' !== settings.testimonial_content ) {
				view.addRenderAttribute( 'testimonial_content', 'class', 'elementor-testimonial-content' );

				view.addInlineEditingAttributes( 'testimonial_content' );
				#>
				<div {{{ view.getRenderAttributeString( 'testimonial_content' ) }}}>{{{ settings.testimonial_content }}}</div>
			<# } #>
			<div class="elementor-testimonial-meta{{ hasImage }}{{ testimonial_image_position }}">
				<div class="elementor-testimonial-meta-inner">
					<# if ( imageUrl ) { #>
						<div class="elementor-testimonial-image">{{{ imageHtml }}}</div>
					<# } #>
					<div class="elementor-testimonial-details">
						<?php $this->render_testimonial_description(); ?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

}
