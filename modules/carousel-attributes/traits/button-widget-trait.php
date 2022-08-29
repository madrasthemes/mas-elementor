<?php
/**
 * Carousel Attributes.
 *
 * @package MASElementor\Modules\CarouselAttributes
 */

namespace MASElementor\Modules\CarouselAttributes\Traits;

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
trait Button_Widget_Trait {
	/**
	 * Get button sizes.
	 *
	 * Retrieve an array of button sizes for the button widget.
	 *
	 * @return array An array containing button sizes.
	 */
	public static function get_button_sizes() {
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
	 * @param array $element Elements.
	 */
	public function register_button_content_controls( $element ) {
		$default_args = array(
			'section_condition'      => array(),
			'button_text'            => esc_html__( 'Click here', 'mas-elementor' ),
			'control_label_name'     => esc_html__( 'Text', 'mas-elementor' ),
			'prefix_class'           => 'elementor%s-align-',
			'alignment_default'      => '',
			'exclude_inline_options' => array(),
			'button_css'             => 'btn-outline-primary',
		);

		$args = $default_args;

		$element->start_controls_section(
			'section_swiper_button',
			array(
				'label' => esc_html__( 'Button', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_LAYOUT,
				'condition' => array(
					'enable_carousel' => 'yes',
					'show_arrows' => 'yes',
				),
			)
		);

		$element->add_control(
			'button_type',
			array(
				'label'        => esc_html__( 'Type', 'mas-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'primary',
				'options'      => array(
					'primary' => esc_html__( 'Default', 'mas-elementor' ),
					'info'    => esc_html__( 'Info', 'mas-elementor' ),
					'success' => esc_html__( 'Success', 'mas-elementor' ),
					'warning' => esc_html__( 'Warning', 'mas-elementor' ),
					'danger'  => esc_html__( 'Danger', 'mas-elementor' ),
				),
				'prefix_class' => 'btn-',
				'condition'    => $args['section_condition'],
			)
		);

		$element->add_control(
			'text',
			array(
				'label'       => esc_html__( 'Next Text', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => $args['button_text'],
				'placeholder' => $args['button_text'],
				'condition'   => $args['section_condition'],
			)
		);

		$element->add_control(
			'next_text',
			array(
				'label'       => esc_html__( 'Previous Text', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => $args['button_text'],
				'placeholder' => $args['button_text'],
				'condition'   => $args['section_condition'],
			)
		);

		$element->add_control(
			'link',
			array(
				'label'       => esc_html__( 'Link', 'mas-elementor' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => array(
					'active' => true,
				),
				'placeholder' => esc_html__( 'https://your-link.com', 'mas-elementor' ),
				'default'     => array(
					'url' => '#',
				),
				'condition'   => $args['section_condition'],
			)
		);

		$element->add_control(
			'size',
			array(
				'label'     => esc_html__( 'Icon Size', 'mas-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ~ .mas-swiper-arrows .elementor-button-icon' => 'font-size: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} ~ .mas-swiper-arrows .elementor-button-content-wrapper .elementor-button-icon i' => 'width: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} ~ .mas-swiper-arrows .elementor-button-content-wrapper .elementor-button-icon i' => 'height: {{SIZE}}{{UNIT}} !important;',
				),
				'condition' => $args['section_condition'],
			)
		);

		$element->add_control(
			'selected_icon',
			array(
				'label'                  => esc_html__( 'Previous Icon', 'mas-elementor' ),
				'type'                   => Controls_Manager::ICONS,
				'fa4compatibility'       => 'icon',
				'skin'                   => 'inline',
				'label_block'            => false,
				'condition'              => $args['section_condition'],
				'exclude_inline_options' => $args['exclude_inline_options'],
			)
		);

		$element->add_control(
			'selected_next_icon',
			array(
				'label'                  => esc_html__( 'Next Icon', 'mas-elementor' ),
				'type'                   => Controls_Manager::ICONS,
				'fa4compatibility'       => 'icon',
				'skin'                   => 'inline',
				'label_block'            => false,
				'condition'              => $args['section_condition'],
				'exclude_inline_options' => $args['exclude_inline_options'],
			)
		);

		$element->add_control(
			'icon_align',
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

		$element->add_control(
			'next_icon_align',
			array(
				'label'     => esc_html__( 'Icon Position', 'mas-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'left',
				'options'   => array(
					'left'  => esc_html__( 'Before', 'mas-elementor' ),
					'right' => esc_html__( 'After', 'mas-elementor' ),
				),
				'condition' => array_merge( $args['section_condition'], array( 'selected_next_icon[value]!' => '' ) ),
			)
		);

		$element->add_control(
			'icon_indent',
			array(
				'label'     => esc_html__( 'Icon Spacing', 'mas-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ~ .mas-swiper-arrows .swiper-button-prev, .swiper-container-rtl .swiper-button-next' => 'background-image:none !important;',
					'{{WRAPPER}} ~ .mas-swiper-arrows .swiper-button-prev:after' => 'content:none !important;',
					'{{WRAPPER}} ~ .mas-swiper-arrows .swiper-button-next, .swiper-rtl .swiper-button-prev' => 'background-image:none !important;',
					'{{WRAPPER}} ~ .mas-swiper-arrows .swiper-button-next:after' => 'content:none !important;',
					'{{WRAPPER}} ~ .mas-swiper-arrows .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} ~ .mas-swiper-arrows .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} ~ .mas-swiper-arrows' => 'width: 100px !important;',
				),
				'condition' => $args['section_condition'],
			)
		);

		$element->add_control(
			'view',
			array(
				'label'     => esc_html__( 'View', 'mas-elementor' ),
				'type'      => Controls_Manager::HIDDEN,
				'default'   => 'traditional',
				'condition' => $args['section_condition'],
			)
		);

		$element->add_control(
			'button_wrapper_css',
			array(
				'label'       => esc_html__( 'Button Wrapper CSS', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => esc_html__( 'Additional CSS to be applied to .btn-wrapper element.', 'mas-elementor' ),
				'condition'   => $args['section_condition'],
			)
		);

		$element->add_control(
			'button_css',
			array(
				'label'       => esc_html__( 'Button CSS', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => esc_html__( 'Additional CSS to be applied to .btn element.', 'mas-elementor' ),
				'condition'   => $args['section_condition'],
			)
		);

		$element->add_control(
			'button_css_id',
			array(
				'label'       => esc_html__( 'Button ID', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => '',
				'title'       => esc_html__( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'mas-elementor' ),
				'description' => esc_html__( 'Please make sure the ID is unique and not used elsewhere on the page this form is displayed. This field allows `A-z 0-9` & underscore chars without spaces.', 'mas-elementor' ),
				'separator'   => 'before',
				'condition'   => $args['section_condition'],
			)
		);

		$element->end_controls_section();
	}

	/**
	 * Register Button Style controls.
	 *
	 * @param array $element Elementor.
	 */
	public function register_button_style_controls( $element ) {
		$default_args = array(
			'section_condition' => array(),
		);

		$args = $default_args;

		$element->start_controls_section(
			'style_swiper_button',
			array(
				'label' => esc_html__( 'Button', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'enable_carousel' => 'yes',
					'show_arrows' => 'yes',
				),
			)
		);

		$element->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'swiper_arrow_typography',
				'global'    => array(
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				),
				'selector'  => '{{WRAPPER}} .btn__load-more',
				'condition' => $args['section_condition'],
			)
		);

		$element->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'      => 'swiper_arrow_text_shadow',
				'selector'  => '{{WRAPPER}} .btn__load-more',
				'condition' => $args['section_condition'],
			)
		);

		$element->start_controls_tabs(
			'swiper_arrow_tabs_button_style',
			array(
				'condition' => $args['section_condition'],
			)
		);

		$element->start_controls_tab(
			'swiper_arrow_tab_button_normal',
			array(
				'label'     => esc_html__( 'Normal', 'mas-elementor' ),
				'condition' => $args['section_condition'],
			)
		);

		$element->add_control(
			'swiper_arrow_button_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} ~ .mas-swiper-arrows .elementor-button-text' => 'fill: {{VALUE}}; color: {{VALUE}} !important;',
				),
				'condition' => $args['section_condition'],
			)
		);

		$element->add_control(
			'swiper_arrow_button_icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} ~ .mas-swiper-arrows .elementor-button-link' => 'fill: {{VALUE}}; color: {{VALUE}} !important;',
				),
				'condition' => $args['section_condition'],
			)
		);

		$element->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'swiper_arrow_background',
				'label'          => esc_html__( 'Background', 'mas-elementor' ),
				'types'          => array( 'classic', 'gradient' ),
				'exclude'        => array( 'image' ),
				'selector'       => '{{WRAPPER}} ~ .mas-swiper-arrows .elementor-button-link',
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

		$element->end_controls_tab();

		$element->start_controls_tab(
			'swiper_arrow_tab_button_hover',
			array(
				'label'     => esc_html__( 'Hover', 'mas-elementor' ),
				'condition' => $args['section_condition'],
			)
		);

		$element->add_control(
			'swiper_arrow_hover_color',
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

		$element->add_control(
			'swiper_arrow_icon_hover_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ~ .mas-swiper-arrows .elementor-button-link:hover, {{WRAPPER}} ~ .mas-swiper-arrows .elementor-button-link:focus' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} ~ .mas-swiper-arrows .elementor-button-link:hover svg, {{WRAPPER}} ~ .mas-swiper-arrows .elementor-button-link:focus svg' => 'fill: {{VALUE}} !important;',
				),
				'condition' => $args['section_condition'],
			)
		);

		$element->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'swiper_arrow_button_background_hover',
				'label'          => esc_html__( 'Background', 'mas-elementor' ),
				'types'          => array( 'classic', 'gradient' ),
				'exclude'        => array( 'image' ),
				'selector'       => '{{WRAPPER}} ~ .mas-swiper-arrows .elementor-button-link:hover, {{WRAPPER}} ~ .mas-swiper-arrows .elementor-button-link:focus',
				'fields_options' => array(
					'background' => array(
						'default' => 'classic',
					),
				),
				'condition'      => $args['section_condition'],
			)
		);

		$element->add_control(
			'swiper_arrow_button_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'border_border!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} ~ .mas-swiper-arrows .elementor-button-link:hover, {{WRAPPER}} ~ .mas-swiper-arrows .elementor-button-link:focus' => 'border-color: {{VALUE}};',
				),
				'condition' => $args['section_condition'],
			)
		);

		$element->add_control(
			'swiper_arrow_hover_animation',
			array(
				'label'     => esc_html__( 'Hover Animation', 'mas-elementor' ),
				'type'      => Controls_Manager::HOVER_ANIMATION,
				'condition' => $args['section_condition'],
			)
		);

		$element->end_controls_tab();

		$element->end_controls_tabs();

		$element->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'swiper_arrow_border',
				'selector'  => '{{WRAPPER}} ~ .mas-swiper-arrows .elementor-button-link',
				'separator' => 'before',
				'condition' => $args['section_condition'],
			)
		);

		$element->add_control(
			'swiper_arrow_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ~ .mas-swiper-arrows .elementor-button-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => $args['section_condition'],
			)
		);

		$element->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'swiper_arrow_button_box_shadow',
				'selector'  => '{{WRAPPER}} .btn__load-more',
				'condition' => $args['section_condition'],
			)
		);

		$element->add_responsive_control(
			'swiper_arrow_text_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ~ .mas-swiper-arrows .elementor-button-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
				'condition'  => $args['section_condition'],
			)
		);

		$element->end_controls_section();
	}

	/**
	 * Render button widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @param Widget_Base $instance The widget instance.
	 * @param String      $prev_id previous id.
	 * @param String      $next_id next id.
	 */
	public function render_button( $instance = null, $prev_id = '', $next_id = '' ) {
		if ( empty( $instance ) ) {
			$instance = $this;
		}

		$settings = $instance->get_settings();
		$instance->add_render_attribute( 'prev_wrapper', 'class', array( 'btn-wrapper', 'elementor-button-wrapper', 'swiper-button-prev mas-elementor-swiper-arrow' ) );
		$instance->add_render_attribute( 'next_wrapper', 'class', array( 'btn-wrapper', 'elementor-button-wrapper', 'swiper-button-next mas-elementor-swiper-arrow' ) );
		if ( ! empty( $prev_id ) && ! empty( $next_id ) ) {
			$instance->add_render_attribute( 'prev_wrapper', 'id', $prev_id );
			$instance->add_render_attribute( 'next_wrapper', 'id', $next_id );
		}

		if ( ! empty( $settings['button_wrapper_css'] ) ) {
			$instance->add_render_attribute( 'prev_wrapper', 'class', $settings['button_wrapper_css'] );
		}
		if ( ! empty( $settings['button_wrapper_css'] ) ) {
			$instance->add_render_attribute( 'next_wrapper', 'class', $settings['button_wrapper_css'] );
		}

		if ( ! empty( $settings['link']['url'] ) ) {
			$instance->add_link_attributes( 'button', $settings['link'] );
			$instance->add_render_attribute( 'button', 'class', 'elementor-button-link' );
		}

		$instance->add_render_attribute( 'button', 'class', array( 'btn', 'btn__load-more' ) );
		$instance->add_render_attribute( 'button', 'role', 'button' );

		if ( ! empty( $settings['button_css'] ) ) {
			$instance->add_render_attribute( 'button', 'class', $settings['button_css'] );
		}

		if ( ! empty( $settings['button_css_id'] ) ) {
			$instance->add_render_attribute( 'button', 'id', $settings['button_css_id'] );
		}

		// if ( $settings['hover_animation'] ) {
		// $instance->add_render_attribute( 'button', 'class', 'elementor-animation-' . $settings['hover_animation'] );
		// }.
		?>
		<div <?php $instance->print_render_attribute_string( 'prev_wrapper' ); ?>>
			<a <?php $instance->print_render_attribute_string( 'button' ); ?>>
				<?php $this->render_prev_text( $instance ); ?>
			</a>
		</div>
		<div <?php $instance->print_render_attribute_string( 'next_wrapper' ); ?>>
			<a <?php $instance->print_render_attribute_string( 'button' ); ?>>
				<?php $this->render_next_text( $instance ); ?>
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
	protected function render_prev_text( $instance ) {
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

		if ( 'left' === $settings['icon_align'] ) {
			$instance->add_render_attribute(
				'icon-align',
				array(
					'style' => 'order:1;',
				)
			);
		} else {
			$instance->add_render_attribute(
				'icon-align',
				array(
					'style' => 'order:15;',
				)
			);
		}

		// TODO: replace the protected with public
		// $instance->add_inline_editing_attributes( 'text', 'none' ); .
		?>
		<span <?php $instance->print_render_attribute_string( 'content-wrapper' ); ?>>
			<?php if ( ! empty( $settings['icon'] ) || ! empty( $settings['selected_icon']['value'] ) ) : ?>
				<span <?php $instance->print_render_attribute_string( 'icon-align' ); ?>>
				<?php
				if ( $is_new || $migrated ) :
					Icons_Manager::render_icon( $settings['selected_icon'], array( 'aria-hidden' => 'true' ) );
				else :
					?>
					<i class="<?php echo esc_attr( $settings['icon'] ); ?>" aria-hidden="true"></i>
				<?php endif; ?>
			</span>
			<?php endif; ?>
			<span <?php $instance->print_render_attribute_string( 'text' ); ?>><?php Utils::print_unescaped_internal_string( $settings['text'] ); // Todo: Make $instance->print_unescaped_setting public. ?></span>
		</span>
		<?php
	}

	/**
	 * Render button text.
	 *
	 * Render button widget text.
	 *
	 * @param \Elementor\Widget_Base $instance The widget instance.
	 */
	protected function render_next_text( $instance ) {
		$settings = $instance->get_settings();

		$migrated = isset( $settings['__fa4_migrated']['selected_next_icon'] );
		$is_new   = empty( $settings['next_icon'] ) && Icons_Manager::is_migration_allowed();

		if ( ! $is_new && empty( $settings['next_icon_align'] ) ) {
			// @todo: remove when deprecated
			// added as bc in 2.6
			// old default
			$settings['next_icon_align'] = $instance->get_settings( 'next_icon_align' );
		}

		$instance->add_render_attribute(
			array(
				'next-content-wrapper' => array(
					'class' => 'elementor-button-content-wrapper',
				),
				'next-icon-align'      => array(
					'class' => array(
						'elementor-button-icon',
						'elementor-align-icon-' . $settings['next_icon_align'],
					),
				),
				'next-text'            => array(
					'class' => 'elementor-button-text',
				),
			)
		);

		if ( 'left' === $settings['next_icon_align'] ) {
			$instance->add_render_attribute(
				'next-icon-align',
				array(
					'style' => 'order:1;',
				)
			);
		} else {
			$instance->add_render_attribute(
				'next-icon-align',
				array(
					'style' => 'order:15;',
				)
			);
		}

		// TODO: replace the protected with public
		// $instance->add_inline_editing_attributes( 'text', 'none' ); .
		?>
		<span <?php $instance->print_render_attribute_string( 'next-content-wrapper' ); ?>>
			<?php if ( ! empty( $settings['next_icon'] ) || ! empty( $settings['selected_next_icon']['value'] ) ) : ?>
				<span <?php $instance->print_render_attribute_string( 'next-icon-align' ); ?>>
				<?php
				if ( $is_new || $migrated ) :
					Icons_Manager::render_icon( $settings['selected_next_icon'], array( 'aria-hidden' => 'true' ) );
				else :
					?>
					<i class="<?php echo esc_attr( $settings['next_icon'] ); ?>" aria-hidden="true"></i>
				<?php endif; ?>
			</span>
			<?php endif; ?>
			<span <?php $instance->print_render_attribute_string( 'next-text' ); ?>><?php Utils::print_unescaped_internal_string( $settings['next_text'] ); // Todo: Make $instance->print_unescaped_setting public. ?></span>
		</span>
		<?php
	}

	/**
	 * On import widget.
	 *
	 * @param array $element The element being imported.
	 */
	public function on_import( $element ) {
		return array( Icons_Manager::on_import_migration( $element, 'icon', 'selected_icon' ), Icons_Manager::on_import_migration( $element, 'next_icon', 'selected_next_icon' ) );
	}
}
