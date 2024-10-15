<?php
/**
 * Icon.
 *
 * @package MASElementor\Modules\icon
 */

namespace MASElementor\Modules\icon;

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
		return 'icon';
	}

	/**
	 * Add Action.
	 *
	 * @return void
	 */
	public function add_actions() {
		add_action( 'elementor/element/icon/section_style_icon/before_section_end', array( $this, 'add_icon_style_controls' ), 15 );
		add_filter( 'elementor/icon/print_template', array( $this, 'print_template' ) );
	}

	/**
	 * Add wrap controls to the column element.
	 *
	 * @param Element_Column $element The Column element object.
	 */
	public function add_icon_style_controls( $element ) {
		$element->start_controls_tabs( 'mas_elementor_icon_colors' );

		$element->start_controls_tab(
			'mas_icon_colors_normal',
			array(
				'label'     => esc_html__( 'Normal', 'mas-addons-for-elementor' ),
				'condition' => array(
					'view' => 'framed',
				),
			)
		);
		$element->add_control(
			'mas_accordion_icon_border_color',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Border Color', 'mas-addons-for-elementor' ),
				'selectors' => array(
					'{{WRAPPER}} .elementor-icon' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'view' => 'framed',
				),
			)
		);
		$element->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'box_shadow',
				'selector' => '{{WRAPPER}} .elementor-icon',
			)
		);

		$element->end_controls_tab();

		$element->start_controls_tab(
			'mas_icon_colors_hover',
			array(
				'label'     => esc_html__( 'Hover', 'mas-addons-for-elementor' ),
				'condition' => array(
					'view' => 'framed',
				),
			)
		);
		$element->add_control(
			'mas_accordion_icon_border_hover_color',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Border Color', 'mas-addons-for-elementor' ),
				'selectors' => array(
					'{{WRAPPER}} .elementor-icon:hover' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'view' => 'framed',
				),
			)
		);

		$element->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'box_shadow_hover',
				'selector' => '{{WRAPPER}} .elementor-icon:hover',
			)
		);

		$element->end_controls_tab();

		$element->end_controls_tabs();
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
	 * Render icon widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 2.9.0
	 */
	protected function content_template() {
		?>
		<# var link = settings.link.url ? 'href="' + settings.link.url + '"' : '',
				iconHTML = elementor.helpers.renderIcon( view, settings.selected_icon, { 'aria-hidden': true }, 'i' , 'object' ),
				migrated = elementor.helpers.isIconMigrated( settings, 'selected_icon' ),
				iconTag = link ? 'a' : 'div';
		#>
		<div class="elementor-icon-wrapper">
			<{{{ iconTag }}} class="elementor-icon elementor-animation-{{ settings.hover_animation }}" {{{ link }}}>
				<# if ( iconHTML && iconHTML.rendered && ( ! settings.icon || migrated ) ) { #>
					{{{ iconHTML.value }}}
				<# } else { #>
					<i class="{{ settings.icon }}" aria-hidden="true"></i>
				<# } #>
			</{{{ iconTag }}}>
		</div>
		<?php
	}

}
