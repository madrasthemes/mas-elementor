<?php
/**
 * Load More Button.
 *
 * @package MASElementor\Modules\Posts\Traits
 */

namespace MASElementor\Modules\Posts\Traits;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Utils;
use Elementor\Widget_Base;

/**
 * The button widget Trait.
 */
trait Load_Button_Widget_Trait {

	/**
	 * Register button content controls.
	 *
	 * @param array $args Arguments.
	 */
	protected function load_more_register_button_content_controls( $args = array() ) {
		$default_args = array(
			'section_condition'      => array(),
			'button_text'            => esc_html__( 'Click here', 'mas-addons-for-elementor' ),
			'control_label_name'     => esc_html__( 'Text', 'mas-addons-for-elementor' ),
			'prefix_class'           => 'elementor%s-align-',
			'alignment_default'      => 'center',
			'exclude_inline_options' => array(),
			'button_css'             => '',
		);

		$args = wp_parse_args( $args, $default_args );

		$this->add_control(
			'lm_text',
			array(
				'label'       => $args['control_label_name'],
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => $args['button_text'],
				'placeholder' => $args['button_text'],
				'condition'   => $args['section_condition'],
			)
		);

		$this->add_control(
			'lm_link',
			array(
				'label'       => esc_html__( 'Link', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => array(
					'active' => true,
				),
				'placeholder' => esc_html__( 'https://your-link.com', 'mas-addons-for-elementor' ),
				'default'     => array(
					'url' => '#loadMore',
				),
				'condition'   => $args['section_condition'],
			)
		);

		$this->add_responsive_control(
			'lm_align',
			array(
				'label'     => esc_html__( 'Alignment', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'center',
				'options'   => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => esc_html__( 'Center', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'   => array(
						'title' => esc_html__( 'Right', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => esc_html__( 'Justified', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .mas-lm-align' => 'justify-content: {{VALUE}};',
				),
				'condition' => $args['section_condition'],
			)
		);

		$this->add_control(
			'lm_selected_icon',
			array(
				'label'                  => esc_html__( 'Icon', 'mas-addons-for-elementor' ),
				'type'                   => Controls_Manager::ICONS,
				'fa4compatibility'       => 'icon',
				'skin'                   => 'inline',
				'label_block'            => false,
				'condition'              => $args['section_condition'],
				'exclude_inline_options' => $args['exclude_inline_options'],
			)
		);

		$this->add_control(
			'lm_button_wrapper_css',
			array(
				'label'       => esc_html__( 'Button Wrapper CSS', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => esc_html__( 'Additional CSS to be applied to .btn-wrapper element.', 'mas-addons-for-elementor' ),
				'condition'   => $args['section_condition'],
			)
		);

		$this->add_control(
			'lm_button_css',
			array(
				'label'       => esc_html__( 'Button CSS', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => esc_html__( 'Additional CSS to be applied to .btn element.', 'mas-addons-for-elementor' ),
				'condition'   => $args['section_condition'],
			)
		);

	}

	/**
	 * Register Button Style controls.
	 *
	 * @param array $args Additional arguments to the control.
	 */
	protected function load_more_register_button_style_controls( $args = array() ) {
		$default_args = array(
			'section_condition' => array(),
		);

		$args = wp_parse_args( $args, $default_args );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'lm_typography',
				'global'         => array(
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				),
				'selector'       => '{{WRAPPER}} .btn__load-more',
				'condition'      => $args['section_condition'],
				'fields_options' => array(
					'typography'  => array( 'default' => 'yes' ),
					// Inner control name.
					'font_weight' => array(
						// Inner control settings.
						'default' => '500',
					),
					'font_family' => array(
						'default' => 'Quicksand',
					),
					'font_size'   => array(
						'default' => array(
							'unit' => 'px',
							'size' => 15,
						),
					),
					'line_height' => array(
						'default' => array(
							'unit' => 'px',
							'size' => 15,
						),
					),
				),
			)
		);

		$this->start_controls_tabs(
			'lm_tabs_button_style',
			array(
				'condition' => $args['section_condition'],
			)
		);

		$this->start_controls_tab(
			'lm_tab_button_normal',
			array(
				'label'     => esc_html__( 'Normal', 'mas-addons-for-elementor' ),
				'condition' => $args['section_condition'],
			)
		);

		$this->add_control(
			'lm_button_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fb236a',
				'selectors' => array(
					'{{WRAPPER}} .btn__load-more' => 'fill: {{VALUE}}; color: {{VALUE}};',
				),
				'condition' => $args['section_condition'],
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'lm_background',
				'label'          => esc_html__( 'Background', 'mas-addons-for-elementor' ),
				'types'          => array( 'classic', 'gradient' ),
				'exclude'        => array( 'image' ),
				'selector'       => '{{WRAPPER}} .btn__load-more',
				'fields_options' => array(
					'background' => array(
						'default' => 'classic',
					),
					'color'      => array(
						'default' => '#ffffff',
					),
				),
				'condition'      => $args['section_condition'],
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'lm_border',
				'selector'       => '{{WRAPPER}} .mas-elementor-button-link',
				'condition'      => $args['section_condition'],
				'fields_options' => array(
					'border' => array(
						'default' => 'solid',
					),
					'width'  => array(
						'default' => array(
							'top'      => '2',
							'right'    => '2',
							'bottom'   => '2',
							'left'     => '2',
							'isLinked' => true,
						),
					),
					'color'  => array(
						'default' => '#fb236a',
					),
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'lm_tab_button_hover',
			array(
				'label'     => esc_html__( 'Hover', 'mas-addons-for-elementor' ),
				'condition' => $args['section_condition'],
			)
		);

		$this->add_control(
			'lm_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .btn__load-more:hover, {{WRAPPER}} .btn__load-more:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .btn__load-more:hover svg, {{WRAPPER}} .btn__load-more:focus svg' => 'fill: {{VALUE}};',
				),
				'condition' => $args['section_condition'],
				'default'   => '#ffffff',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'lm_button_background_hover',
				'label'          => esc_html__( 'Background', 'mas-addons-for-elementor' ),
				'types'          => array( 'classic', 'gradient' ),
				'exclude'        => array( 'image' ),
				'selector'       => '{{WRAPPER}} .mas-elementor-button-link:hover, {{WRAPPER}} .mas-elementor-button-link:focus',
				'fields_options' => array(
					'background' => array(
						'default' => 'classic',
					),
					'color'      => array(
						'default' => '#fb236a',
					),
				),
				'condition'      => $args['section_condition'],
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'lm_border_hover',
				'selector'       => '{{WRAPPER}} .mas-elementor-button-link:hover',
				'condition'      => $args['section_condition'],
				'fields_options' => array(
					'border' => array(
						'default' => 'solid',
					),
					'width'  => array(
						'default' => array(
							'top'      => '2',
							'right'    => '2',
							'bottom'   => '2',
							'left'     => '2',
							'isLinked' => true,
						),
					),
					'color'  => array(
						'default' => '#fb236a',
					),
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'lm_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-elementor-button-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => $args['section_condition'],
				'separator'  => 'before',
				'default'    => array(
					'top'      => '8',
					'right'    => '8',
					'bottom'   => '8',
					'left'     => '8',
					'unit'     => 'px',
					'isLinked' => true,
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'lm_button_box_shadow',
				'selector'  => '{{WRAPPER}} .btn__load-more',
				'condition' => $args['section_condition'],
			)
		);

		$this->add_control(
			'lm_top_spacing',
			array(
				'label'      => __( 'Top Spacing', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'default'    => array(
					'size' => 30,
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mas-elementor-button-wrapper' => 'margin-top: {{SIZE}}{{UNIT}}',
				),
				'condition'  => $args['section_condition'],
			)
		);

		$this->add_responsive_control(
			'lm_text_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-elementor-button-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
				'condition'  => $args['section_condition'],
				'default'    => array(
					'top'      => '15',
					'right'    => '36',
					'bottom'   => '15',
					'left'     => '36',
					'unit'     => 'px',
					'isLinked' => false,
				),
			)
		);
	}

	/**
	 * Render button widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @param \Elementor\Widget_Base|null $instance The widget instance.
	 */
	protected function load_more_render_button( Widget_Base $instance = null ) {
		if ( empty( $instance ) ) {
			$instance = $this;
		}

		$settings = $instance->get_settings();

		$instance->add_render_attribute( 'wrapper', 'class', array( 'btn-wrapper', 'mas-elementor-button-wrapper', 'mas-lm-align' ) );

		if ( ! empty( $settings['lm_button_wrapper_css'] ) ) {
			$instance->add_render_attribute( 'wrapper', 'class', $settings['lm_button_wrapper_css'] );
		}

		if ( ! empty( $settings['lm_link']['url'] ) ) {
			$instance->add_link_attributes( 'button', $settings['lm_link'] );
			$instance->add_render_attribute( 'button', 'class', 'mas-elementor-button-link' );
			$instance->add_render_attribute( 'button', 'role', 'button' );
		}

		$instance->add_render_attribute( 'button', 'class', array( 'btn__load-more' ) );

		if ( ! empty( $settings['lm_button_css'] ) ) {
			$instance->add_render_attribute( 'button', 'class', $settings['lm_button_css'] );
		}

		?>
		<div <?php $instance->print_render_attribute_string( 'wrapper' ); ?>>
			<a <?php $instance->print_render_attribute_string( 'button' ); ?>>
				<?php $this->load_more_render_text( $instance ); ?>
			</a>
		</div>
		<?php
	}

	/**
	 * Render button text.
	 *
	 * Render button widget text.
	 *
	 * @param \Elementor\Widget_Base $instance The widget instance.
	 */
	protected function load_more_render_text( Widget_Base $instance ) {
		$settings = $instance->get_settings();

		$migrated = isset( $settings['__fa4_migrated']['selected_icon'] );
		$is_new   = empty( $settings['icon'] ) && Icons_Manager::is_migration_allowed();

		if ( ! $is_new && empty( $settings['icon_align'] ) ) {
			// @todo: remove when deprecated
			// added as bc in 2.6
			// old default
			$settings['icon_align'] = $instance->get_settings( 'icon_align' );
		}

		$instance->add_render_attribute(
			array(
				'content-wrapper' => array(
					'class' => 'mas-elementor-button-content-wrapper',
				),
				'icon-align'      => array(
					'class' => array(
						'elementor-button-icon',
						'elementor-align-icon-' . $settings['icon_align'],
					),
				),
				'text'            => array(
					'class' => 'mas-elementor-button-text',
				),
			)
		);

		// TODO: replace the protected with public
		// $instance->add_inline_editing_attributes( 'text', 'none' ); .
		?>
		<span <?php $instance->print_render_attribute_string( 'content-wrapper' ); ?>>
			<?php if ( ! empty( $settings['icon'] ) || ! empty( $settings['lm_selected_icon']['value'] ) ) : ?>
				<span <?php $instance->print_render_attribute_string( 'icon-align' ); ?>>
				<?php
				if ( $is_new || $migrated ) :
					Icons_Manager::render_icon( $settings['lm_selected_icon'], array( 'aria-hidden' => 'true' ) );
				else :
					?>
					<i class="<?php echo esc_attr( $settings['icon'] ); ?>" aria-hidden="true"></i>
				<?php endif; ?>
			</span>
			<?php endif; ?>
			<span <?php $instance->print_render_attribute_string( 'text' ); ?>><?php Utils::print_unescaped_internal_string( $settings['lm_text'] ); // Todo: Make $instance->print_unescaped_setting public. ?></span>
		</span>
		<?php
	}

	/**
	 * On import widget.
	 *
	 * @param array $element The element being imported.
	 */
	public function load_more_on_import( $element ) {
		return Icons_Manager::on_import_migration( $element, 'icon', 'lm_selected_icon' );
	}
}
