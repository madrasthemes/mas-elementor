<?php
/**
 * Counter Skin.
 *
 * @package mas-elementor
 */

namespace MASElementor\Modules\Counter\Skins;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor;
use Elementor\Skin_Base;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use Elementor\Plugin;

// Group Controls.
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

// Group Values.
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;

/**
 * Skin for Counter Style 1.
 */
class Skin_Counter_V1 extends Skin_Base {

	/**
	 * Get the name of the widget.
	 *
	 * @return string.
	 */
	public function get_id() {
		return 'counter-style-1';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Pie Chart - MAS', 'mas-addons-for-elementor' );
	}

	/**
	 * Register Control Actions Here.
	 */
	protected function _register_controls_actions() { //phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
		add_filter( 'elementor/widget/print_template', array( $this, 'skin_print_template' ), 10, 2 );
		add_action( 'elementor/element/counter/section_counter/before_section_end', array( $this, 'register_counter_content_controls' ), 10 );
		add_action( 'elementor/element/counter/section_title/after_section_end', array( $this, 'style_counter_controls' ), 10 );
		add_action( 'elementor/element/counter/section_number/after_section_end', array( $this, 'style_number_controls' ), 10 );
	}

	/**
	 * Style Counter controls.
	 *
	 * @param array $widget The widget settings.
	 */
	public function style_number_controls( $widget ) {
		$this->parent = $widget;
		$widget->update_control(
			'section_number',
			array(
				'condition' => array(
					'_skin' => '',
				),
			)
		);

	}

	/**
	 * Style Counter controls.
	 *
	 * @param array $widget The widget settings.
	 */
	public function style_title_controls( $widget ) {
		$this->parent = $widget;
		$widget->update_control(
			'section_title',
			array(
				'condition' => array(
					'_skin' => '',
				),
			)
		);
	}

	/**
	 * Register Counter content controls.
	 *
	 * @param array $widget The widget settings.
	 */
	public function register_counter_content_controls( $widget ) {
		$this->parent = $widget;

		$update_control_ids = array( 'prefix', 'suffix', 'duration', 'thousand_separator', 'thousand_separator_char', 'ending_number', 'starting_number' );

		foreach ( $update_control_ids as $update_control_id ) {
			$widget->update_control(
				$update_control_id,
				array(
					'condition' => array(
						'_skin' => '',
					),
				)
			);
		}

		$this->add_control(
			'mas_counter_max_value',
			array(
				'label'       => esc_html__( 'Enter Maximum Number', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 100,
				'description' => esc_html__( 'Maximum number must be higher than the counter number', 'mas-addons-for-elementor' ),
			),
			array(
				'position' => array(
					'at' => 'after',
					'of' => 'starting_number',
				),
			)
		);

		$this->add_control(
			'mas_counter_value',
			array(
				'label'       => esc_html__( 'Enter Counter Number', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Counter number must be lower than the Maximum number', 'mas-addons-for-elementor' ),
				'default'     => 75,
			),
			array(
				'position' => array(
					'at' => 'after',
					'of' => 'starting_number',
				),
			)
		);

		$this->add_control(
			'hide_value',
			array(
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Hide Values', 'mas-addons-for-elementor' ),
				'default'      => 'false',
				'label_off'    => esc_html__( 'Enable', 'mas-addons-for-elementor' ),
				'label_on'     => esc_html__( 'Disable', 'mas-addons-for-elementor' ),
				'return_value' => 'true',

			)
		);

		$this->add_control(
			'mas_counter_duration',
			array(
				'label'       => esc_html__( 'Duration', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Duration in seconds to complete', 'mas-addons-for-elementor' ),
				'default'     => 2,
				'min'         => 1,
				'max'         => 20,
				'step'        => .5,
			)
		);

		$this->add_control(
			'mas_counter_stroke_width',
			array(
				'label'   => esc_html__( 'Stroke Width', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '8',
				'min'     => '1',
				'max'     => '30',
			)
		);

		$this->add_control(
			'prefix_suffix',
			array(
				'label'   => esc_html__( 'Text Position', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'prefix',
				'options' => array(
					'prefix' => esc_html__( 'Prefix', 'mas-addons-for-elementor' ),
					'suffix' => esc_html__( 'Suffix', 'mas-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'description',
			array(
				'label'       => esc_html__( 'Description', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => esc_html__( "One of the world's top research companies", 'mas-addons-for-elementor' ),
				'placeholder' => esc_html__( 'Enter description', 'mas-addons-for-elementor' ),
			),
			array(
				'position' => array(
					'at' => 'after',
					'of' => 'title',
				),
			)
		);
	}

	/**
	 * Style Counter controls.
	 *
	 * @param array $widget The widget settings.
	 */
	public function style_counter_controls( $widget ) {

		$this->style_title_controls( $widget );

		$this->start_controls_section(
			'section_counter_title',
			array(
				'label' => esc_html__( 'Text', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'counter_title_color',
			array(
				'label'     => esc_html__( 'Title Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_SECONDARY,
				),
				'default'   => '#377dff',
				'selectors' => array(
					'{{WRAPPER}} .mas-stats-progress__info' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'typography_counter_title',
				'global'         => array(
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				),
				'selector'       => '{{WRAPPER}} .mas-stats-progress__info',
				'exclude'        => array( 'line_height' ),
				'fields_options' => array(
					'typography'  => array( 'default' => 'yes' ),
					// Inner control name.
					'font_weight' => array(
						// Inner control settings.
						'default' => '600',
					),
					'font_size'   => array(
						'default' => array(
							'unit' => 'px',
							'size' => 24,
						),
					),
				),
			)
		);

		$this->add_control(
			'desc_color',
			array(
				'label'     => esc_html__( 'Description Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_SECONDARY,
				),
				'default'   => '#77838f',
				'selectors' => array(
					'selector' => '{{WRAPPER}} .mas-stats-progress__info div',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'typography_desc',
				'global'         => array(
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				),
				'selector'       => '{{WRAPPER}} .mas-stats-progress__info div',
				'exclude'        => array( 'line_height' ),
				'fields_options' => array(
					'typography'  => array( 'default' => 'yes' ),
					// Inner control name.
					'font_weight' => array(
						// Inner control settings.
						'default' => '400',
					),
					'font_size'   => array(
						'default' => array(
							'unit' => 'px',
							'size' => 13,
						),
					),
				),
			)
		);

		$this->add_control(
			'mas_counter_top_position',
			array(
				'label'     => esc_html__( 'Position', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'%' => array(
						'max' => 100,
						'min' => 0,
					),
				),
				'default'   => array(
					'unit' => '%',
					'size' => 50,
				),
				'selectors' => array(
					'{{WRAPPER}} .mas-stats-progress__info' => 'top: {{SIZE}}{{UNIT}}; transform: translate(0,-{{SIZE}}{{UNIT}});left: 0;right: 0;',
				),
			)
		);

		$this->add_control(
			'mas_counter_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-stats-progress__info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'top'      => '2',
					'right'    => '2',
					'bottom'   => '2',
					'left'     => '2',
					'unit'     => 'rem',
					'isLinked' => true,
				),
			)
		);

		$this->add_control(
			'mas_counter_space_divider',
			array(
				'label'   => esc_html__( 'Text Spacing', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::SLIDER,
				'range'   => array(
					'%' => array(
						'max'  => 100,
						'min'  => 0,
						'step' => 2,
					),
				),
				'default' => array(
					'unit' => '%',
					'size' => 10,
				),
			)
		);

		$this->add_responsive_control(
			'mas_counter_text_align',
			array(
				'label'     => esc_html__( 'Text Alignment', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .mas-stats-progress__info' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_circle',
			array(
				'label' => esc_html__( 'Circle Bar', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'mas_circle_size',
			array(
				'label'   => esc_html__( 'Circle Size', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::SLIDER,
				'range'   => array(
					'px' => array(
						'max' => 300,
						'min' => 10,
					),
				),
				'default' => array(
					'unit' => 'px',
					'size' => 100,
				),
			)
		);

		$this->add_control(
			'circle_bar_color',
			array(
				'label'     => esc_html__( 'Circle Bar Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_SECONDARY,
				),
				'default'   => '#377dff',
				'selectors' => array(
					'{{WRAPPER}} .pieChart circle.pieChart__bar' => 'stroke: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'circle_back_color',
			array(
				'label'     => esc_html__( 'Circle Transparent Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_SECONDARY,
				),
				'default'   => '#f8fafd',
				'selectors' => array(
					'{{WRAPPER}} .pieChart__back' => 'stroke: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'mas_counter_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-stats-progress' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'top'      => '50',
					'right'    => '50',
					'bottom'   => '50',
					'left'     => '50',
					'unit'     => '%',
					'isLinked' => true,
				),
			)
		);

		$this->add_control(
			'mas_circle_outer_size',
			array(
				'label'     => esc_html__( 'Outer Circle Size', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'rem' => array(
						'max'  => 50,
						'min'  => 0.5,
						'step' => 0.25,
					),
				),
				'default'   => array(
					'unit' => 'rem',
					'size' => 13.75,
				),
				'selectors' => array(
					'{{WRAPPER}} .mas-stats-progress'     => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mas-stats-progress svg' => 'margin-bottom: -0.4375rem; vertical-align: baseline;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'           => 'mas_counter_box_shadow',
				'label'          => esc_html__( 'Box Shadow', 'mas-addons-for-elementor' ),
				'selector'       => '{{WRAPPER}} .mas-stats-progress',
				'fields_options' => array(
					'box_shadow_type'     => array( 'default' => 'yes' ),
					'box_shadow_position' => array(
						'default' => ' ',
					),
					'box_shadow'          => array(
						'default' => array(
							'horizontal' => 0,
							'vertical'   => 6,
							'blur'       => 24,
							'spread'     => 0,
							'color'      => 'rgba(140 ,152 ,164 ,0.13)',
						),
					),
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget in the frontend.
	 */
	public function render() {
		$parent   = $this->parent;
		$settings = $parent->get_settings_for_display();

		$skin_control_ids = array(
			'hide_value',
			'mas_counter_value',
			'mas_counter_max_value',
			'prefix_suffix',
			'description',
			'mas_counter_duration',
			'mas_counter_stroke_width',

			'counter_title_color',
			'typography_counter_title_typography',
			'typography_counter_title_font_weight',
			'typography_counter_title_font_size',
			'mas_counter_space_divider',

			'desc_color',
			'typography_desc_font_size',

			'mas_circle_size',
			'circle_bar_color',
			'circle_back_color',
		);

		$skin_settings = array();

		foreach ( $skin_control_ids as $skin_control_id ) {
			$skin_settings[ $skin_control_id ] = $this->get_instance_value( $skin_control_id );
		}
		$number_text        = 'prefix' === $skin_settings['prefix_suffix'] ? $settings['title'] . ' ' : ' ' . $settings['title'];
		$counter_attributes = array(
			'class'                             => 'mas-js-pie',
			'style'                             => 'text-align:center',
			'data-circles-text-class'           => 'mas-stats-progress__info',
			'data-circles-is-hide-value'        => (string) $skin_settings['hide_value'],
			'data-circles-value'                => (string) $skin_settings['mas_counter_value'],
			'data-circles-max-value'            => (string) $skin_settings['mas_counter_max_value'],
			'data-circles-fg-color'             => (string) $skin_settings['circle_bar_color'],
			'data-circles-bg-color'             => (string) $skin_settings['circle_back_color'],
			'data-circles-fg-stroke-linecap'    => 'round',
			'data-circles-fg-stroke-miterlimit' => '100',
			'data-circles-radius'               => ! empty( $skin_settings['mas_circle_size']['size'] ) ? (string) $skin_settings['mas_circle_size']['size'] : '10',
			'data-circles-stroke-width'         => (string) $skin_settings['mas_counter_stroke_width'],
			'data-circles-additional-text'      => $number_text,
			'data-circles-additional-text-type' => (string) $skin_settings['prefix_suffix'],
			'data-circles-duration'             => (string) ( $skin_settings['mas_counter_duration'] * 1000 ),
			'data-circles-scroll-animate'       => 'true',
			'data-circles-color'                => (string) $skin_settings['counter_title_color'],
			'data-circles-font-size'            => ! empty( $skin_settings['typography_counter_title_font_size']['size'] ) ? (string) $skin_settings['typography_counter_title_font_size']['size'] : '24',
			'data-circles-secondary-text'       => $skin_settings['description'],
			'data-circles-secondary-color'      => (string) $skin_settings['desc_color'],
			'data-circles-secondary-font-size'  => ! empty( $skin_settings['typography_desc_font_size']['size'] ) ? (string) $skin_settings['typography_desc_font_size']['size'] : '13',
			'data-circles-divider-space'        => ! empty( $skin_settings['mas_counter_space_divider']['size'] ) ? (string) $skin_settings['mas_counter_space_divider']['size'] : '5',
		);

		$parent->add_render_attribute( 'mas-counter-js-attributes', $counter_attributes );
		?><div class="mas-stats-progress" style="position:relative;display:flex;align-items:center;justify-content:center">
			<div <?php $parent->print_render_attribute_string( 'mas-counter-js-attributes' ); ?> ></div>
		</div>
		<?php
		$this->render_script();
	}

	/**
	 * Print Template.
	 *
	 * @param string                $content The skin output string.
	 * @param Elementor\Widget_Base $counter The widget.
	 *
	 * @return string
	 */
	public function skin_print_template( $content, $counter ) {

		if ( 'counter' === $counter->get_name() ) {
			return '';
		}

		return $content;
	}

	/**
	 * Render script in the editor.
	 */
	public function render_script() {
		if ( Plugin::$instance->editor->is_edit_mode() ) :
			?>
			<script type="text/javascript">
				/**
				 * Editor Counter Load.
				 */
				( function( $, window ) {
					'use strict';					
					// initialization of chart pies
					if ( $.HSCore.components.hasOwnProperty('HSChartPie') ) {
						$.HSCore.components.HSChartPie.init('.mas-js-pie');
					}
				} )( jQuery, window );
			</script>
			<?php
		endif;
	}

}
