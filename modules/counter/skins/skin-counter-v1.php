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
		return esc_html__( 'Pie Chart - MAS', 'mas-elementor' );
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
			'mas_counter_value',
			array(
				'label'   => esc_html__( 'Enter Value', 'mas-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 75,
				'max'     => 100,
				'dynamic' => array(
					'active' => true,
				),
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
				'label'        => esc_html__( 'Hide Values', 'mas-elementor' ),
				'default'      => 'false',
				'label_off'    => esc_html__( 'Enable', 'mas-elementor' ),
				'label_on'     => esc_html__( 'Disable', 'mas-elementor' ),
				'return_value' => 'true',

			)
		);

		$this->add_control(
			'prefix_suffix',
			array(
				'label'   => esc_html__( 'Text Position', 'mas-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'prefix',
				'options' => array(
					'prefix' => esc_html__( 'Prefix', 'mas-elementor' ),
					'suffix' => esc_html__( 'Suffix', 'mas-elementor' ),
				),
			)
		);

		$this->add_control(
			'description',
			array(
				'label'       => esc_html__( 'Description', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => esc_html__( "One of the world's top research companies", 'mas-elementor' ),
				'placeholder' => esc_html__( 'Enter description', 'mas-elementor' ),
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
				'label' => esc_html__( 'Title', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'counter_title_color',
			array(
				'label'     => esc_html__( 'Title Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_SECONDARY,
				),
				'default'   => '#111111',
				'selectors' => array(
					'{{WRAPPER}} .mas-counter' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography_counter_title',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				),
				'selector' => '{{WRAPPER}} .mas-counter',
				'exclude'  => array( 'line_height' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_description',
			array(
				'label' => esc_html__( 'Description', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'desc_color',
			array(
				'label'     => esc_html__( 'Description Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_SECONDARY,
				),
				'default'   => '#111111',
				'selectors' => array(
					'selector' => '{{WRAPPER}} .mas-counter div',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography_desc',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				),
				'selector' => '{{WRAPPER}} .mas-counter div',
				'exclude'  => array( 'line_height' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_circle',
			array(
				'label' => esc_html__( 'Circle Bar', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'mas_circle_size',
			array(
				'label'   => esc_html__( 'Circle Size', 'mas-elementor' ),
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
				'label'     => esc_html__( 'Circle Bar Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_SECONDARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .pieChart circle.pieChart__bar' => 'stroke: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'circle_back_color',
			array(
				'label'     => esc_html__( 'Circle Transparent Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_SECONDARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .pieChart__back' => 'stroke: {{VALUE}};',
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
			'prefix_suffix',
			'description',

			'counter_title_color',
			'typography_counter_title_typography',
			'typography_counter_title_font_weight',
			'typography_counter_title_font_size',

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
			'data-circles-text-class'           => 'u-stats-progress__info mas-counter',
			'data-circles-is-hide-value'        => (string) $skin_settings['hide_value'],
			'data-circles-value'                => (string) $skin_settings['mas_counter_value'],
			'data-circles-max-value'            => '100',
			'data-circles-fg-color'             => (string) $skin_settings['circle_bar_color'],
			'data-circles-bg-color'             => (string) $skin_settings['circle_back_color'],
			'data-circles-fg-stroke-linecap'    => 'round',
			'data-circles-fg-stroke-miterlimit' => '10',
			'data-circles-radius'               => ! empty( $skin_settings['mas_circle_size']['size'] ) ? (string) $skin_settings['mas_circle_size']['size'] : '10',
			'data-circles-stroke-width'         => '8',
			'data-circles-additional-text'      => $number_text,
			'data-circles-additional-text-type' => (string) $skin_settings['prefix_suffix'],
			'data-circles-duration'             => '2000',
			'data-circles-scroll-animate'       => 'true',
			'data-circles-color'                => (string) $skin_settings['counter_title_color'],
			'data-circles-font-size'            => (string) $skin_settings['typography_counter_title_font_size']['size'],
			// 'data-circles-font-weight'          => (string) $skin_settings['typography_counter_title_font_weight'],description
			'data-circles-secondary-text'       => $skin_settings['description'],
			'data-circles-secondary-color'      => (string) $skin_settings['desc_color'],
			'data-circles-secondary-font-size'  => (string) $skin_settings['typography_desc_font_size']['size'],
			// 'data-circles-secondary-font-weight' => (string) $skin_settings['typography_desc_font_weight'],
			'data-circles-divider-space'        => '10',
		);

		$parent->add_render_attribute( 'mas-counter-js-attributes', $counter_attributes );
		?><div class="u-stats-progress position-relative d-flex align-items-center justify-content-center">
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
