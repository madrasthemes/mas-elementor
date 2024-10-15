<?php
/**
 * The Posts Widget.
 *
 * @package MASElementor/Modules/Forms/Widgets
 */

namespace MASElementor\Modules\Forms\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use MASElementor\Base\Base_Widget;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Class Login
 */
class Login extends Base_Widget {

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-login';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Login', 'mas-addons-for-elementor' );
	}

	/**
	 * Get the icon for the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-lock-user';
	}

	/**
	 * Get the keywords related to the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'login', 'user', 'form' );
	}

	/**
	 * Register controls for this widget.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_fields_content',
			array(
				'label' => esc_html__( 'Form Fields', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'show_labels',
			array(
				'label'     => esc_html__( 'Label', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_off' => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
				'label_on'  => esc_html__( 'Show', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'input_size',
			array(
				'label'   => esc_html__( 'Input Size', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'xs' => esc_html__( 'Extra Small', 'mas-addons-for-elementor' ),
					'sm' => esc_html__( 'Small', 'mas-addons-for-elementor' ),
					'md' => esc_html__( 'Medium', 'mas-addons-for-elementor' ),
					'lg' => esc_html__( 'Large', 'mas-addons-for-elementor' ),
					'xl' => esc_html__( 'Extra Large', 'mas-addons-for-elementor' ),
				),
				'default' => 'sm',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button_content',
			array(
				'label' => esc_html__( 'Button', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'   => esc_html__( 'Text', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
				'default' => esc_html__( 'Log In', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'button_size',
			array(
				'label'   => esc_html__( 'Size', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'xs' => esc_html__( 'Extra Small', 'mas-addons-for-elementor' ),
					'sm' => esc_html__( 'Small', 'mas-addons-for-elementor' ),
					'md' => esc_html__( 'Medium', 'mas-addons-for-elementor' ),
					'lg' => esc_html__( 'Large', 'mas-addons-for-elementor' ),
					'xl' => esc_html__( 'Extra Large', 'mas-addons-for-elementor' ),
				),
				'default' => 'sm',
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'        => esc_html__( 'Alignment', 'mas-addons-for-elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'start'   => array(
						'title' => esc_html__( 'Left', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => esc_html__( 'Center', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'end'     => array(
						'title' => esc_html__( 'Right', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
					'stretch' => array(
						'title' => esc_html__( 'Justified', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'prefix_class' => 'elementor%s-button-align-',
				'default'      => '',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_login_content',
			array(
				'label' => esc_html__( 'Additional Options', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'redirect_after_login',
			array(
				'label'     => esc_html__( 'Redirect After Login', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'label_off' => esc_html__( 'Off', 'mas-addons-for-elementor' ),
				'label_on'  => esc_html__( 'On', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'redirect_url',
			array(
				'type'        => Controls_Manager::URL,
				'show_label'  => false,
				'options'     => false,
				'separator'   => false,
				'placeholder' => esc_html__( 'https://your-link.com', 'mas-addons-for-elementor' ),
				'description' => esc_html__( 'Note: Because of security reasons, you can ONLY use your current domain here.', 'mas-addons-for-elementor' ),
				'dynamic'     => array(
					'active' => true,
				),
				'condition'   => array(
					'redirect_after_login' => 'yes',
				),
			)
		);

		$this->add_control(
			'redirect_after_logout',
			array(
				'label'     => esc_html__( 'Redirect After Logout', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'label_off' => esc_html__( 'Off', 'mas-addons-for-elementor' ),
				'label_on'  => esc_html__( 'On', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'redirect_logout_url',
			array(
				'type'        => Controls_Manager::URL,
				'show_label'  => false,
				'options'     => false,
				'separator'   => false,
				'placeholder' => esc_html__( 'https://your-link.com', 'mas-addons-for-elementor' ),
				'description' => esc_html__( 'Note: Because of security reasons, you can ONLY use your current domain here.', 'mas-addons-for-elementor' ),
				'dynamic'     => array(
					'active' => true,
				),
				'condition'   => array(
					'redirect_after_logout' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_lost_password',
			array(
				'label'     => esc_html__( 'Lost your password?', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_off' => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
				'label_on'  => esc_html__( 'Show', 'mas-addons-for-elementor' ),
			)
		);

		if ( get_option( 'users_can_register' ) ) {
			$this->add_control(
				'show_register',
				array(
					'label'     => esc_html__( 'Register', 'mas-addons-for-elementor' ),
					'type'      => Controls_Manager::SWITCHER,
					'default'   => 'yes',
					'label_off' => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
					'label_on'  => esc_html__( 'Show', 'mas-addons-for-elementor' ),
				)
			);
		}

		$this->add_control(
			'show_remember_me',
			array(
				'label'     => esc_html__( 'Remember Me', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_off' => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
				'label_on'  => esc_html__( 'Show', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'show_logged_in_message',
			array(
				'label'     => esc_html__( 'Logged in Message', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_off' => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
				'label_on'  => esc_html__( 'Show', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'custom_labels',
			array(
				'label' => esc_html__( 'Custom Label', 'mas-addons-for-elementor' ),
				'type'  => Controls_Manager::SWITCHER,
			)
		);

		$this->add_control(
			'user_label',
			array(
				'label'      => esc_html__( 'Username Label', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::TEXT,
				'dynamic'    => array(
					'active' => true,
				),
				'default'    => esc_html__( 'Username or Email Address', 'mas-addons-for-elementor' ),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'show_labels',
							'operator' => '===',
							'value'    => 'yes',
						),
						array(
							'name'     => 'custom_labels',
							'operator' => '===',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$this->add_control(
			'user_placeholder',
			array(
				'label'     => esc_html__( 'Username Placeholder', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Username or Email Address', 'mas-addons-for-elementor' ),
				'condition' => array(
					'custom_labels' => 'yes',
				),
				'dynamic'   => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'password_label',
			array(
				'label'      => esc_html__( 'Password Label', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::TEXT,
				'dynamic'    => array(
					'active' => true,
				),
				'default'    => esc_html__( 'Password', 'mas-addons-for-elementor' ),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'show_labels',
							'operator' => '===',
							'value'    => 'yes',
						),
						array(
							'name'     => 'custom_labels',
							'operator' => '===',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$this->add_control(
			'password_placeholder',
			array(
				'label'     => esc_html__( 'Password Placeholder', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Password', 'mas-addons-for-elementor' ),
				'condition' => array(
					'custom_labels' => 'yes',
				),
				'dynamic'   => array(
					'active' => true,
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			array(
				'label' => esc_html__( 'Form', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'row_gap',
			array(
				'label'     => esc_html__( 'Rows Gap', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => '10',
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-field-group' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-form-fields-wrapper' => 'margin-bottom: -{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'links_color',
			array(
				'label'     => esc_html__( 'Links Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-field-group > a' => 'color: {{VALUE}};',
				),
				'global'    => array(
					'default' => Global_Colors::COLOR_TEXT,
				),
			)
		);

		$this->add_control(
			'links_hover_color',
			array(
				'label'     => esc_html__( 'Links Hover Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-field-group > a:hover' => 'color: {{VALUE}};',
				),
				'global'    => array(
					'default' => Global_Colors::COLOR_ACCENT,
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_labels',
			array(
				'label'     => esc_html__( 'Label', 'mas-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_labels!' => '',
				),
			)
		);

		$this->add_control(
			'label_spacing',
			array(
				'label'     => esc_html__( 'Spacing', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => '0',
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
				),
				'selectors' => array(
					'body {{WRAPPER}} .elementor-field-group > label' => 'padding-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-form-fields-wrapper label' => 'color: {{VALUE}};',
				),
				'global'    => array(
					'default' => Global_Colors::COLOR_TEXT,
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typography',
				'selector' => '{{WRAPPER}} .elementor-form-fields-wrapper label',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_field_style',
			array(
				'label' => esc_html__( 'Fields', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'field_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-field-group .elementor-field' => 'color: {{VALUE}};',
				),
				'global'    => array(
					'default' => Global_Colors::COLOR_TEXT,
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'field_typography',
				'selector' => '{{WRAPPER}} .elementor-field-group .elementor-field, {{WRAPPER}} .elementor-field-subgroup label',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				),
			)
		);

		$this->add_control(
			'field_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .elementor-field-group .elementor-field:not(.elementor-select-wrapper)' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .elementor-field-group .elementor-select-wrapper select' => 'background-color: {{VALUE}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'field_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-field-group .elementor-field:not(.elementor-select-wrapper)' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .elementor-field-group .elementor-select-wrapper select' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .elementor-field-group .elementor-select-wrapper::before' => 'color: {{VALUE}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'field_border_width',
			array(
				'label'       => esc_html__( 'Border Width', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'placeholder' => '1',
				'size_units'  => array( 'px' ),
				'selectors'   => array(
					'{{WRAPPER}} .elementor-field-group .elementor-field:not(.elementor-select-wrapper)' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .elementor-field-group .elementor-select-wrapper select' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'field_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-field-group .elementor-field:not(.elementor-select-wrapper)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .elementor-field-group .elementor-select-wrapper select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button_style',
			array(
				'label' => esc_html__( 'Button', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label' => esc_html__( 'Normal', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'button_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .elementor-button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				),
				'selector' => '{{WRAPPER}} .elementor-button',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'button_background',
				'types'          => array( 'classic', 'gradient' ),
				'exclude'        => array( 'image' ),
				'selector'       => '{{WRAPPER}} .elementor-button',
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
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'button_border',
				'selector'  => '{{WRAPPER}} .elementor-button',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'button_text_padding',
			array(
				'label'      => esc_html__( 'Text Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			array(
				'label' => esc_html__( 'Hover', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'button_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'button_background_hover',
				'types'          => array( 'classic', 'gradient' ),
				'exclude'        => array( 'image' ),
				'selector'       => '{{WRAPPER}} .elementor-button:hover',
				'fields_options' => array(
					'background' => array(
						'default' => 'classic',
					),
				),
			)
		);

		$this->add_control(
			'button_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-button:hover' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'button_border_border!' => '',
				),
			)
		);

		$this->add_control(
			'button_hover_animation',
			array(
				'label' => esc_html__( 'Animation', 'mas-addons-for-elementor' ),
				'type'  => Controls_Manager::HOVER_ANIMATION,
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_message',
			array(
				'label' => esc_html__( 'Logged in Message', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'message_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-container .elementor-login__logged-in-message' => 'color: {{VALUE}};',
				),
				'global'    => array(
					'default' => Global_Colors::COLOR_TEXT,
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'message_typography',
				'selector' => '{{WRAPPER}} .elementor-widget-container .elementor-login__logged-in-message',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				),
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Form fields render attributes.
	 */
	private function form_fields_render_attributes() {
		$settings = $this->get_settings_for_display();

		if ( ! empty( $settings['button_size'] ) ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-size-' . $settings['button_size'] );
		}

		if ( $settings['button_hover_animation'] ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-animation-' . $settings['button_hover_animation'] );
		}

		$this->add_render_attribute(
			array(
				'wrapper'        => array(
					'class' => array(
						'elementor-form-fields-wrapper',
					),
				),
				'field-group'    => array(
					'class' => array(
						'elementor-field-type-text',
						'elementor-field-group',
						'elementor-column',
						'elementor-col-100',
					),
				),
				'submit-group'   => array(
					'class' => array(
						'elementor-field-group',
						'elementor-column',
						'elementor-field-type-submit',
						'elementor-col-100',
					),
				),

				'button'         => array(
					'class' => array(
						'elementor-button',
					),
					'name'  => 'wp-submit',
				),
				'user_label'     => array(
					'for' => 'user',
				),
				'user_input'     => array(
					'type'        => 'text',
					'name'        => 'log',
					'id'          => 'user',
					'placeholder' => $settings['user_placeholder'],
					'class'       => array(
						'elementor-field',
						'elementor-field-textual',
						'elementor-size-' . $settings['input_size'],
					),
				),
				'password_label' => array(
					'for' => 'password',
				),
				'password_input' => array(
					'type'        => 'password',
					'name'        => 'pwd',
					'id'          => 'password',
					'placeholder' => $settings['password_placeholder'],
					'class'       => array(
						'elementor-field',
						'elementor-field-textual',
						'elementor-size-' . $settings['input_size'],
					),
				),
				// TODO: add unique ID.
				'label_user'     => array(
					'for'   => 'user',
					'class' => 'elementor-field-label',
				),

				'label_password' => array(
					'for'   => 'password',
					'class' => 'elementor-field-label',
				),
			)
		);

		if ( ! $settings['show_labels'] ) {
			$this->add_render_attribute( 'label', 'class', 'elementor-screen-only' );
		}

		$this->add_render_attribute( 'field-group', 'class', 'elementor-field-required' )
			->add_render_attribute( 'input', 'required', true )
			->add_render_attribute( 'input', 'aria-required', 'true' );

	}

	/**
	 * Render.
	 */
	protected function render() {
		$settings        = $this->get_settings_for_display();
		$current_url     = remove_query_arg( 'fake_arg' );
		$logout_redirect = $current_url;

		if ( 'yes' === $settings['redirect_after_login'] && ! empty( $settings['redirect_url']['url'] ) ) {
			$redirect_url = $settings['redirect_url']['url'];
		} else {
			$redirect_url = $current_url;
		}

		if ( 'yes' === $settings['redirect_after_logout'] && ! empty( $settings['redirect_logout_url']['url'] ) ) {
			$logout_redirect = $settings['redirect_logout_url']['url'];
		}

		if ( is_user_logged_in() && ! Plugin::$instance->editor->is_edit_mode() ) {
			if ( 'yes' === $settings['show_logged_in_message'] ) {
				$current_user = wp_get_current_user();

				// PHPCS - `sprintf` is safe.
				echo '<div class="elementor-login elementor-login__logged-in-message">' .
					sprintf(
						/* translators: 1: User display name, 2: Link opening tag, 3: Link closing tag. */
						esc_html__( 'You are Logged in as %1$s (%2$sLogout%3$s)', 'mas-addons-for-elementor' ),
						wp_kses_post( $current_user->display_name ),
						sprintf( '<a href="%s" target="_blank">', esc_url( wp_logout_url( $logout_redirect ) ) ),
						'</a>'
					) .
					'</div>';
			}

			return;
		}

		$this->form_fields_render_attributes();
		?>
		<form class="elementor-login elementor-form" method="post" action="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>">
			<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_url ); ?>">
			<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
				<div <?php $this->print_render_attribute_string( 'field-group' ); ?>>
					<?php

					if ( $settings['show_labels'] ) {
						echo '<label ';
						$this->print_render_attribute_string( 'user_label' );
						echo '>';

						$this->print_unescaped_setting( 'user_label' );
						echo '</label>';
					}
					?>
					<input size="1" <?php $this->print_render_attribute_string( 'user_input' ); ?>>
				</div>
				<div <?php $this->print_render_attribute_string( 'field-group' ); ?>>
					<?php
					if ( $settings['show_labels'] ) :
						echo '<label ';
						$this->print_render_attribute_string( 'password_label' );
						echo '>';

						$this->print_unescaped_setting( 'password_label' );
						echo '</label>';
					endif;
					?>
					<input size="1" <?php $this->print_render_attribute_string( 'password_input' ); ?>>
				</div>

				<?php if ( 'yes' === $settings['show_remember_me'] ) : ?>
					<div class="elementor-field-type-checkbox elementor-field-group elementor-column elementor-col-100 elementor-remember-me">
						<label for="elementor-login-remember-me">
							<input type="checkbox" id="elementor-login-remember-me" name="rememberme" value="forever">
							<?php echo esc_html__( 'Remember Me', 'mas-addons-for-elementor' ); ?>
						</label>
					</div>
				<?php endif; ?>

				<div <?php $this->print_render_attribute_string( 'submit-group' ); ?>>
					<button type="submit" <?php $this->print_render_attribute_string( 'button' ); ?>>
							<?php if ( ! empty( $settings['button_text'] ) ) : ?>
								<span class="elementor-button-text"><?php $this->print_unescaped_setting( 'button_text' ); ?></span>
							<?php endif; ?>
					</button>
				</div>

				<?php
				$show_lost_password = 'yes' === $settings['show_lost_password'];
				$show_register      = get_option( 'users_can_register' ) && 'yes' === $settings['show_register'];

				if ( $show_lost_password || $show_register ) :
					?>
					<div class="elementor-field-group elementor-column elementor-col-100">
						<?php if ( $show_lost_password ) : ?>
							<?php // PHPCS - `wp_lostpassword_url` is safe. ?>
							<a class="elementor-lost-password" href="<?php echo wp_lostpassword_url( $redirect_url ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
								<?php echo esc_html__( 'Lost your password?', 'mas-addons-for-elementor' ); ?>
							</a>
						<?php endif; ?>

						<?php if ( $show_register ) : ?>
							<?php if ( $show_lost_password ) : ?>
								<span class="elementor-login-separator"> | </span>
							<?php endif; ?>
							<?php // PHPCS - `wp_registration_url` is safe. ?>
							<a class="elementor-register" href="<?php echo wp_registration_url(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
								<?php echo esc_html__( 'Register', 'mas-addons-for-elementor' ); ?>
							</a>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		</form>
		<?php
	}

	/**
	 * Render Label.
	 *
	 * @param string $render_key render key.
	 * @param array  $settings settings.
	 * @param string $label_key label key.
	 */
	private function render_label( $render_key, $settings, $label_key = '' ) {

		$show_label = ( 'yes' === $settings['show_labels'] );

		if ( ! $show_label ) {
			$this->add_render_attribute( $render_key, 'class', 'sr-only' );
		}

		if ( empty( $label_key ) ) {
			$label_key = $render_key;
		}

		?>
		<label <?php $this->print_render_attribute_string( $render_key ); ?>>
			<?php echo wp_kses_post( ( $settings[ $label_key ] ) ); ?>
		</label>
		<?php
	}

	/**
	 * Render register form.
	 *
	 * @param array $settings settings.
	 */
	private function render_register_form( $settings ) {

		$can_register = get_option( 'users_can_register' );

		if ( ! $can_register ) {
			return;
		}

		?>
		<form class="mas-register elementor-form register" method="post">
			<?php
			if ( isset( $_POST['register'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
				?>
					<div class="mb-3"><?php mas_show_error_messages(); ?></div>
					<?php
			}
			?>

			<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
				<div class="mb-5">
					<div <?php $this->print_render_attribute_string( 'field-group' ); ?>>
						<?php $this->render_label( 'email_register_label', $settings, 'register_email_label' ); ?>
						<input <?php $this->print_render_attribute_string( 'email_register_input' ); ?>>
					</div>

					<?php if ( apply_filters( 'mas_register_password_enabled', true ) ) : ?>

						<div <?php $this->print_render_attribute_string( 'field-group' ); ?>>
							<?php $this->render_label( 'register_password_label', $settings, 'register_password_label' ); ?>
							<input <?php $this->print_render_attribute_string( 'register_password_input' ); ?>>
						</div>

						<div <?php $this->print_render_attribute_string( 'field-group' ); ?>>
							<input <?php $this->print_render_attribute_string( 'register_confirm_password_input' ); ?>>
						</div>

					<?php else : ?>
						<p><?php echo esc_html__( 'A password will be sent to your email address.', 'mas-addons-for-elementor' ); ?></p>
					<?php endif; ?>
				</div>


				<div <?php $this->print_render_attribute_string( 'submit-group' ); ?>>
					<?php if ( ! empty( $settings['button_text'] ) ) : ?>
						<input type="hidden" name="mas_register_nonce" value="<?php echo esc_attr( wp_create_nonce( 'mas-register-nonce' ) ); ?>"/>
						<input type="hidden" name="mas_register_check" value="1"/>
						<button type="submit" <?php $this->print_render_attribute_string( 'button_text' ); ?> name="register"><?php echo esc_html( $settings['register_button_text'] ); ?></button>
					<?php endif; ?>
				</div>
			</div>
			<?php $this->render_login_form_link( $settings ); ?>
		</form>
		<?php
	}


	/**
	 * Render Login Form output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 2.9.0
	 */
	protected function content_template() {
		?>
		<div class="elementor-login elementor-form">
			<div class="elementor-form-fields-wrapper">
				<#
					fieldGroupClasses = 'elementor-field-group elementor-column elementor-col-100 elementor-field-type-text';
				#>
				<div class="{{ fieldGroupClasses }}">
					<# if ( settings.show_labels ) { #>
						<label class="elementor-field-label" for="user" >{{{ settings.user_label }}}</label>
						<# } #>
							<input size="1" type="text" id="user" placeholder="{{ settings.user_placeholder }}" class="elementor-field elementor-field-textual elementor-size-{{ settings.input_size }}" />
				</div>
				<div class="{{ fieldGroupClasses }}">
					<# if ( settings.show_labels ) { #>
						<label class="elementor-field-label" for="password" >{{{ settings.password_label }}}</label>
						<# } #>
							<input size="1" type="password" id="password" placeholder="{{ settings.password_placeholder }}" class="elementor-field elementor-field-textual elementor-size-{{ settings.input_size }}" />
				</div>

				<# if ( settings.show_remember_me ) { #>
					<div class="elementor-field-type-checkbox elementor-field-group elementor-column elementor-col-100 elementor-remember-me">
						<label for="elementor-login-remember-me">
							<input type="checkbox" id="elementor-login-remember-me" name="rememberme" value="forever">
							<?php // PHPCS - `esc_html__` is safe. ?>
							<?php echo esc_html__( 'Remember Me', 'mas-addons-for-elementor' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</label>
					</div>
				<# } #>

				<div class="elementor-field-group elementor-column elementor-field-type-submit elementor-col-100">
					<button type="submit" class="elementor-button elementor-size-{{ settings.button_size }}">
						<# if ( settings.button_text ) { #>
							<span class="elementor-button-text">{{ settings.button_text }}</span>
						<# } #>
					</button>
				</div>

				<# if ( settings.show_lost_password || settings.show_register ) { #>
					<div class="elementor-field-group elementor-column elementor-col-100">
						<# if ( settings.show_lost_password ) { #>
						<?php // PHPCS - `wp_lostpassword_url` is safe. ?>
						<a class="elementor-lost-password" href="<?php echo wp_lostpassword_url(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
								<?php echo esc_html__( 'Lost your password?', 'mas-addons-for-elementor' ); ?>
							</a>
						<# } #>

						<?php if ( get_option( 'users_can_register' ) ) { ?>
							<# if ( settings.show_register ) { #>
								<# if ( settings.show_lost_password ) { #>
									<span class="elementor-login-separator"> | </span>
								<# } #>
							<?php // PHPCS - `wp_registration_url` is safe. ?>
							<a class="elementor-register" href="<?php echo wp_registration_url(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
									<?php echo esc_html__( 'Register', 'mas-addons-for-elementor' ); ?>
								</a>
							<# } #>
						<?php } ?>
					</div>
				<# } #>
			</div>
		</div>
		<?php
	}

	/**
	 * Render plain content.
	 */
	public function render_plain_content() {}

	/**
	 * Get group name.
	 */
	public function get_group_name() {
		return 'forms';
	}
}
