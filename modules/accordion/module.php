<?php
/**
 * Accordion.
 *
 * @package MASElementor\Modules\Accordion
 */

namespace MASElementor\Modules\Accordion;

use MASElementor\Base\Module_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Group_Control_Typography;

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
		return 'accordion';
	}

	/**
	 * Add Action.
	 *
	 * @return void
	 */
	public function add_actions() {
		add_action( 'elementor/widget/accordion/before_render_content', array( $this, 'before_render' ), 20 );
		add_action( 'elementor/element/accordion/section_toggle_style_icon/before_section_end', array( $this, 'add_icon_style_controls' ), 10 );
		add_action( 'elementor/element/accordion/section_title_style/before_section_end', array( $this, 'add_accordion_style_controls' ), 10 );
		add_action( 'elementor/element/accordion/section_toggle_style_title/before_section_end', array( $this, 'add_title_style_controls' ), 10 );
		add_action( 'elementor/element/accordion/section_toggle_style_content/before_section_end', array( $this, 'add_content_style_controls' ), 10 );
		add_filter( 'elementor/accordion/print_template', array( $this, 'print_template' ) );
	}

	/**
	 * Add wrap controls to the column element.
	 *
	 * @param Element_Column $element The Column element object.
	 */
	public function add_icon_style_controls( $element ) {

		$element->add_responsive_control(
			'mas_accordion_icon_size',
			array(
				'label'     => esc_html__( 'Icon Size', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 6,
						'max' => 300,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-accordion-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$element->add_control(
			'mas_accordion_icon_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-accordion .elementor-accordion-icon' => 'background-color: {{VALUE}};',
				),
			)
		);

		$element->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'mas_accordion_icon_border',
				'selector' => '{{WRAPPER}} .elementor-accordion .elementor-accordion-icon',
			)
		);

		$element->add_control(
			'mas_accordion_icon_border_radius',
			array(
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-accordion .elementor-accordion-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$element->add_responsive_control(
			'mas_accordion_icon_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-accordion-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$element->add_responsive_control(
			'mas_accordion_icon_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-accordion-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$element->add_control(
			'mas_accordion_icon_active_heading',
			array(
				'label'     => esc_html__( 'Tab Active', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$element->add_control(
			'mas_accordion_icon_active_background_color',
			array(
				'label'     => esc_html__( 'Background Active Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-accordion .elementor-tab-title.elementor-active .elementor-accordion-icon' => 'background-color: {{VALUE}};',
				),
			)
		);

		$element->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'mas_accordion_icon_active_border',
				'selector' => '{{WRAPPER}} .elementor-accordion .elementor-tab-title.elementor-active .elementor-accordion-icon',
			)
		);
	}

	/**
	 * Add wrap controls to the column element.
	 *
	 * @param Element_Column $element The Column element object.
	 */
	public function add_accordion_style_controls( $element ) {

		$element->add_responsive_control(
			'mas_accordion_space_between',
			array(
				'label'     => esc_html__( 'Space Between', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-accordion-item:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$element->add_control(
			'mas_accordion_border_bottom',
			array(
				'type'      => Controls_Manager::SLIDER,
				'label'     => esc_html__( 'Border Bottom', 'mas-addons-for-elementor' ),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 10,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-accordion .elementor-accordion-item:not(:last-child)' => 'border-bottom-width: {{SIZE}}{{UNIT}};',

				),
			)
		);

	}

	/**
	 * Add wrap controls to the column element.
	 *
	 * @param Element_Column $element The Column element object.
	 */
	public function add_title_style_controls( $element ) {

		$element->add_control(
			'content_background',
			array(
				'label'     => esc_html__( 'Background Active Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-accordion .elementor-tab-title.elementor-active' => 'background-color: {{VALUE}};',
				),
			)
		);

		$element->add_control(
			'tab_hover_title_color',
			array(
				'label'     => esc_html__( 'Title Hover Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-tab-title:hover .elementor-accordion-title' => 'color: {{VALUE}};',
				),
			)
		);

		$element->add_control(
			'mas_accordion_title_border_radius',
			array(
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-accordion .elementor-accordion-item .elementor-tab-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$element->add_control(
			'tab_title_active',
			array(
				'label'     => esc_html__( 'Tab Active', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$element->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'tab_active_title_typography',
				'selector' => '{{WRAPPER}} .elementor-accordion .elementor-tab-title.elementor-active .elementor-accordion-title',
			)
		);
	}

	/**
	 * Add wrap controls to the column element.
	 *
	 * @param Element_Column $element The Column element object.
	 */
	public function add_content_style_controls( $element ) {
		$element->add_control(
			'mas_accordion_content_border_radius',
			array(
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-accordion .elementor-tab-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$element->add_responsive_control(
			'mas_accordion_content_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-tab-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
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
	 * Render accordion widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 2.9.0
	 */
	protected function content_template() {
		?>
		<div class="elementor-accordion" role="tablist">
			<#
			if ( settings.tabs ) {
				var tabindex = view.getIDInt().toString().substr( 0, 3 ),
					iconHTML = elementor.helpers.renderIcon( view, settings.selected_icon, {}, 'i' , 'object' ),
					iconActiveHTML = elementor.helpers.renderIcon( view, settings.selected_active_icon, {}, 'i' , 'object' ),
					migrated = elementor.helpers.isIconMigrated( settings, 'selected_icon' );

				_.each( settings.tabs, function( item, index ) {
					var tabCount = index + 1,
						tabTitleKey = view.getRepeaterSettingKey( 'tab_title', 'tabs', index ),
						tabContentKey = view.getRepeaterSettingKey( 'tab_content', 'tabs', index );

					view.addRenderAttribute( tabTitleKey, {
						'id': 'elementor-tab-title-' + tabindex + tabCount,
						'class': [ 'elementor-tab-title' ],
						'tabindex': tabindex + tabCount,
						'data-tab': tabCount,
						'role': 'tab',
						'aria-controls': 'elementor-tab-content-' + tabindex + tabCount,
						'aria-expanded': 'false',
					} );

					view.addRenderAttribute( tabContentKey, {
						'id': 'elementor-tab-content-' + tabindex + tabCount,
						'class': [ 'elementor-tab-content', 'elementor-clearfix' ],
						'data-tab': tabCount,
						'role': 'tabpanel',
						'aria-labelledby': 'elementor-tab-title-' + tabindex + tabCount
					} );

					view.addInlineEditingAttributes( tabContentKey, 'advanced' );

					var titleHTMLTag = elementor.helpers.validateHTMLTag( settings.title_html_tag );
					#>
					<div class="elementor-accordion-item">
						<{{{ titleHTMLTag }}} {{{ view.getRenderAttributeString( tabTitleKey ) }}}>
							<# if ( settings.icon || settings.selected_icon ) { #>
							<span class="elementor-accordion-icon elementor-accordion-icon-{{ settings.icon_align }}" aria-hidden="true">
								<# if ( iconHTML && iconHTML.rendered && ( ! settings.icon || migrated ) ) { #>
									<span class="elementor-accordion-icon-closed">{{{ iconHTML.value }}}</span>
									<span class="elementor-accordion-icon-opened">{{{ iconActiveHTML.value }}}</span>
								<# } else { #>
									<i class="elementor-accordion-icon-closed {{ settings.icon }}"></i>
									<i class="elementor-accordion-icon-opened {{ settings.icon_active }}"></i>
								<# } #>
							</span>
							<# } #>
							<a class="elementor-accordion-title" href="">{{{ item.tab_title }}}</a>
						</{{{ titleHTMLTag }}}>
						<div {{{ view.getRenderAttributeString( tabContentKey ) }}}>{{{ item.tab_content }}}</div>
					</div>
					<#
				} );
			} #>
		</div>
		<?php
	}

}
