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
	 * Get button sizes.
	 *
	 * Retrieve an array of button sizes for the button widget.
	 *
	 * @return array An array containing button sizes.
	 */
	public static function load_more_get_button_sizes() {
		return array(
			'xs' => esc_html__( 'Extra Small', 'mas-elementor' ),
			'sm' => esc_html__( 'Small', 'mas-elementor' ),
			'md' => esc_html__( 'Medium', 'mas-elementor' ),
			'lg' => esc_html__( 'Large', 'mas-elementor' ),
			'xl' => esc_html__( 'Extra Large', 'mas-elementor' ),
		);
	}

	/**
	 * Register button content controls.
	 *
	 * @param array $args Arguments.
	 */
	protected function load_more_register_button_content_controls( $args = array() ) {
		$default_args = array(
			'section_condition'      => array(),
			'button_text'            => esc_html__( 'Click here', 'mas-elementor' ),
			'control_label_name'     => esc_html__( 'Text', 'mas-elementor' ),
			'prefix_class'           => 'elementor%s-align-',
			'alignment_default'      => 'center',
			'exclude_inline_options' => array(),
			'button_css'             => 'button -md -dark text-white',
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
				'label'       => esc_html__( 'Link', 'mas-elementor' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => array(
					'active' => true,
				),
				'placeholder' => esc_html__( 'https://your-link.com', 'mas-elementor' ),
				'default'     => array(
					'url' => '#loadMore',
				),
				'condition'   => $args['section_condition'],
			)
		);

		$this->add_responsive_control(
			'lm_align',
			array(
				'label'        => esc_html__( 'Alignment', 'mas-elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'start'  => array(
						'title' => esc_html__( 'Left', 'mas-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'mas-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'end'    => array(
						'title' => esc_html__( 'Right', 'mas-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'prefix_class' => $args['prefix_class'],
				'default'      => $args['alignment_default'],
				'condition'    => $args['section_condition'],
			)
		);

		$this->add_control(
			'lm_size',
			array(
				'label'          => esc_html__( 'Size', 'mas-elementor' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => 'sm',
				'options'        => self::get_button_sizes(),
				'style_transfer' => true,
				'condition'      => $args['section_condition'],
			)
		);

		$this->add_control(
			'lm_selected_icon',
			array(
				'label'                  => esc_html__( 'Icon', 'mas-elementor' ),
				'type'                   => Controls_Manager::ICONS,
				'fa4compatibility'       => 'icon',
				'skin'                   => 'inline',
				'label_block'            => false,
				'condition'              => $args['section_condition'],
				'exclude_inline_options' => $args['exclude_inline_options'],
			)
		);

		$this->add_control(
			'lm_icon_align',
			array(
				'label'     => esc_html__( 'Icon Position', 'mas-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'left',
				'options'   => array(
					'left'  => esc_html__( 'Before', 'mas-elementor' ),
					'right' => esc_html__( 'After', 'mas-elementor' ),
				),
				'condition' => array_merge( $args['section_condition'], array( 'selected_icon[value]!' => '' ) ),
			)
		);

		$this->add_control(
			'lm_icon_indent',
			array(
				'label'     => esc_html__( 'Icon Spacing', 'mas-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .btn__load-more .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .btn__load-more .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
				'condition' => $args['section_condition'],
			)
		);

		$this->add_control(
			'lm_view',
			array(
				'label'     => esc_html__( 'View', 'mas-elementor' ),
				'type'      => Controls_Manager::HIDDEN,
				'default'   => 'traditional',
				'condition' => $args['section_condition'],
			)
		);

		$this->add_control(
			'lm_button_wrapper_css',
			array(
				'label'       => esc_html__( 'Button Wrapper CSS', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'row mt-40',
				'description' => esc_html__( 'Additional CSS to be applied to .btn-wrapper element.', 'mas-elementor' ),
				'condition'   => $args['section_condition'],
			)
		);

		$this->add_control(
			'lm_button_css',
			array(
				'label'       => esc_html__( 'Button CSS', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'button -md -dark text-white',
				'description' => esc_html__( 'Additional CSS to be applied to .btn element.', 'mas-elementor' ),
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
				'name'      => 'lm_typography',
				'global'    => array(
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				),
				'selector'  => '{{WRAPPER}} .btn__load-more',
				'condition' => $args['section_condition'],
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'      => 'lm_text_shadow',
				'selector'  => '{{WRAPPER}} .btn__load-more',
				'condition' => $args['section_condition'],
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
				'label'     => esc_html__( 'Normal', 'mas-elementor' ),
				'condition' => $args['section_condition'],
			)
		);

		$this->add_control(
			'lm_button_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .btn__load-more' => 'fill: {{VALUE}}; color: {{VALUE}};',
					'{{WRAPPER}} .btn-wrapper a'  => 'fill: {{VALUE}}; color: {{VALUE}} important;',
				),
				'condition' => $args['section_condition'],
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'lm_background',
				'label'          => esc_html__( 'Background', 'mas-elementor' ),
				'types'          => array( 'classic', 'gradient' ),
				'exclude'        => array( 'image' ),
				'selector'       => '{{WRAPPER}} .btn__load-more',
				'fields_options' => array(
					'background' => array(
						'default' => 'classic',
					),
					'color'      => array(
						'global' => array(
							'default' => Global_Colors::COLOR_ACCENT,
						),
					),
				),
				'condition'      => $args['section_condition'],
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'lm_tab_button_hover',
			array(
				'label'     => esc_html__( 'Hover', 'mas-elementor' ),
				'condition' => $args['section_condition'],
			)
		);

		$this->add_control(
			'lm_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .btn__load-more:hover, {{WRAPPER}} .btn__load-more:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .btn__load-more:hover svg, {{WRAPPER}} .btn__load-more:focus svg' => 'fill: {{VALUE}};',
				),
				'condition' => $args['section_condition'],
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'lm_button_background_hover',
				'label'          => esc_html__( 'Background', 'mas-elementor' ),
				'types'          => array( 'classic', 'gradient' ),
				'exclude'        => array( 'image' ),
				'selector'       => '{{WRAPPER}} .btn__load-more:hover, {{WRAPPER}} .btn__load-more:focus',
				'fields_options' => array(
					'background' => array(
						'default' => 'classic',
					),
				),
				'condition'      => $args['section_condition'],
			)
		);

		$this->add_control(
			'lm_button_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'border_border!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .btn__load-more:hover, {{WRAPPER}} .btn__load-more:focus' => 'border-color: {{VALUE}};',
				),
				'condition' => $args['section_condition'],
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'lm_border',
				'selector'  => '{{WRAPPER}} .btn__load-more',
				'separator' => 'before',
				'condition' => $args['section_condition'],
			)
		);

		$this->add_control(
			'lm_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .btn__load-more' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => $args['section_condition'],
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

		$this->add_responsive_control(
			'lm_text_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .btn__load-more' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
				'condition'  => $args['section_condition'],
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
		$instance->add_render_attribute( 'wrapper', 'class', array( 'btn-wrapper', 'elementor-button-wrapper' ) );

		if ( ! empty( $settings['lm_button_wrapper_css'] ) ) {
			$instance->add_render_attribute( 'wrapper', 'class', $settings['lm_button_wrapper_css'] );
		}

		if ( ! empty( $settings['lm_link']['url'] ) ) {
			$instance->add_link_attributes( 'button', $settings['lm_link'] );
			$instance->add_render_attribute( 'button', 'class', 'elementor-button-link' );
			$instance->add_render_attribute( 'button', 'role', 'button' );
		}

		$instance->add_render_attribute( 'button', 'class', array( 'btn', 'btn__load-more' ) );

		if ( ! empty( $settings['lm_button_css'] ) ) {
			$instance->add_render_attribute( 'button', 'class', $settings['lm_button_css'] );
		}

		if ( ! empty( $settings['lm_size'] ) ) {
			$instance->add_render_attribute( 'button', 'class', 'elementor-size-' . $settings['lm_size'] );
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
					'class' => 'elementor-button-content-wrapper',
				),
				'icon-align'      => array(
					'class' => array(
						'elementor-button-icon',
						'elementor-align-icon-' . $settings['icon_align'],
					),
				),
				'text'            => array(
					'class' => 'elementor-button-text',
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
