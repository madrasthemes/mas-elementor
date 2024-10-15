<?php
/**
 * The Posts Widget.
 *
 * @package MASElementor/Modules/Forms/Widgets
 */

namespace MASElementor\Modules\Forms\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use MASElementor\Base\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Plugin;
use WP_Error;
use Elementor\Group_Control_Border;
use Elementor\Utils;

/**
 * MAS Elementor login widget.
 *
 * MAS Elementor widget that displays a login with the ability to control every
 * aspect of the login design.
 *
 * @since 1.0.0
 */
class Signin extends Base_Widget {

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
		return esc_html__( 'User Account Forms', 'mas-addons-for-elementor' );
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
		return array( 'login', 'user', 'form', 'signin', 'signup', 'register' );
	}

	/**
	 * Get the script dependencies for this widget.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array( 'forgot-password' );
	}

	/**
	 * Register controls for this widget.
	 */
	protected function register_controls() {

		$can_register = get_option( 'users_can_register' );

		$this->start_controls_section(
			'section_general',
			array(
				'label' => esc_html__( 'General', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'display',
			array(
				'label'       => esc_html__( 'Display', 'mas-addons-for-elementor' ),
				'description' => esc_html__( 'Choose which forms you want to display. Please note that the register form is available only if registration is enabled from Settings > General > Membership.', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'all'      => esc_html__( 'All', 'mas-addons-for-elementor' ),
					'login'    => esc_html__( 'Login', 'mas-addons-for-elementor' ),
					'register' => esc_html__( 'Register', 'mas-addons-for-elementor' ),
					'forgot'   => esc_html__( 'Password Reset', 'mas-addons-for-elementor' ),
				),
				'default'     => 'all',
			)
		);

		$this->end_controls_section();

		$this->add_form_option_controls();

		$this->add_login_form_controls();

		$this->add_register_form_controls();

		$this->add_password_reset_form_controls();

		$this->add_additional_options_controls();

		$this->start_controls_section(
			'section_heading_style',
			array(
				'label' => esc_html__( 'Form', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'form_title_heading',
			array(
				'label'     => __( 'Form Title', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => array( 'show_form_title' => 'yes' ),
			)
		);

		$this->add_control(
			'form_title_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .form-header__title' => 'color: {{VALUE}};',
				),
				'condition' => array( 'show_form_title' => 'yes' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'form_title_typography',
				'selector'  => '{{WRAPPER}} .form-header__title',
				'condition' => array( 'show_form_title' => 'yes' ),
			)
		);

		$this->add_control(
			'form_title_alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'mas-addons-for-elementor' ),
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
				'selectors' => array(
					'{{WRAPPER}} .form-header__title' => 'text-align: {{VALUE}}',
				),
				'default'   => 'left',
				'condition' => array( 'show_form_title' => 'yes' ),
			)
		);

		$this->add_control(
			'login_form_title_spacing',
			array(
				'label'      => esc_html__( 'Title Spacing', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'default'    => array(
					'size' => '0',
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .form-header__title' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array( 'show_form_title' => 'yes' ),
			)
		);

		$this->add_control(
			'title_css',
			array(
				'label'     => esc_html__( 'CSS Classes', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'title'     => esc_html__( 'Add your custom class for text without the dot. e.g: my-class', 'mas-addons-for-elementor' ),
				'condition' => array( 'show_form_title' => 'yes' ),
			)
		);

		$this->add_control(
			'form_description_heading',
			array(
				'label'     => __( 'Form Description', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array( 'show_form_description' => 'yes' ),
			)
		);

		$this->add_control(
			'form_description_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .form-header__desc' => 'color: {{VALUE}};',
				),
				'condition' => array( 'show_form_description' => 'yes' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'form_description_typography',
				'selector'  => '{{WRAPPER}} .form-header__desc',
				'condition' => array( 'show_form_description' => 'yes' ),
			)
		);

		$this->add_control(
			'description_alignment',
			array(
				'label'     => esc_html__( 'Description Alignment', 'mas-addons-for-elementor' ),
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
				'selectors' => array(
					'{{WRAPPER}} .form-header__desc' => 'text-align: {{VALUE}}',
				),
				'default'   => 'left',
				'condition' => array( 'show_form_description' => 'yes' ),
			)
		);

		$this->add_control(
			'login_form_desc_spacing',
			array(
				'label'      => esc_html__( 'Description Spacing', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'default'    => array(
					'size' => '15',
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .form-header__desc' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array( 'show_form_description' => 'yes' ),
			)
		);

		$this->add_control(
			'description_css',
			array(
				'label'     => esc_html__( 'CSS Classes', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'title'     => esc_html__( 'Add your custom class for text without the dot. e.g: my-class', 'mas-addons-for-elementor' ),
				'condition' => array(
					'show_form_description' => 'yes',
				),
			)
		);

		$this->add_control(
			'form_footer_alignment',
			array(
				'label'     => esc_html__( 'Form Footer Alignment', 'mas-addons-for-elementor' ),
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
				'separator' => 'before',
				'default'   => 'left',
				'selectors' => array(
					'{{WRAPPER}} .form__footer' => 'text-align: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();

		$this->add_login_style_controls();
	}
	/**
	 * Add form option controls.
	 */
	private function add_login_style_controls() {

		$this->start_controls_section(
			'section_login_style',
			array(
				'label' => esc_html__( 'Login Form', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'form_alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'mas-addons-for-elementor' ),
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
				'selectors' => array(
					'{{WRAPPER}} .mas-form-fields-wrapper .mas-form-fields > div' => 'display:flex;justify-content: {{VALUE}}',
					'{{WRAPPER}} .mas-form-fields-wrapper .mas-signin-btn' => 'display:flex;justify-content: {{VALUE}}',
					'{{WRAPPER}} .mas-form-fields-wrapper .mas-lost-password' => 'display:flex;justify-content: {{VALUE}}',
				),
				'default'   => 'left',
			)
		);

		$this->add_control(
			'login_form_heading',
			array(
				'label'     => esc_html__( 'Form', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'none',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'sigin_text_typography',
				'selector' => '{{WRAPPER}} .mas-form-fields-wrapper .elementor-field-type-text label',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'sigin_input_typography',
				'selector' => '{{WRAPPER}} .mas-form-fields-wrapper .elementor-field-type-text input',
			)
		);

		$this->add_control(
			'login_label_color',
			array(
				'label'     => esc_html__( 'Label Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-form-fields-wrapper .elementor-field-type-text label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'login_form_spacebetween',
			array(
				'label'      => esc_html__( 'Form Spacing', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'default'    => array(
					'size' => '12',
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mas-form-fields-wrapper .elementor-field-type-text label' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'login_label_width',
			array(
				'label'      => esc_html__( 'Label Width', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'default'    => array(
					'size' => '12',
					'unit' => '%',
				),
				'range'      => array(
					'%' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mas-form-fields-wrapper .elementor-field-type-text label' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'login_label_margin',
			array(
				'label'      => esc_html__( 'Form Label Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-form-fields-wrapper .elementor-field-type-text label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'login_form_width',
			array(
				'label'      => esc_html__( 'Form Width', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'default'    => array(
					'size' => '36',
					'unit' => '%',
				),
				'range'      => array(
					'%' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mas-form-fields-wrapper .elementor-field-type-text input' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'login_form_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'default'    => array(
					'size' => '15',
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mas-form-fields-wrapper .mas-form-fields > div' => 'margin-top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mas-form-fields-wrapper .elementor-field-type-text' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'login_form_input_heading',
			array(
				'label'     => esc_html__( 'Form Input', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'login_input_border',
				'selector'       => '{{WRAPPER}} .mas-form-fields-wrapper .elementor-field-type-text input[type="text"],{{WRAPPER}} .mas-form-fields-wrapper .elementor-field-type-text input[type="password"],.mas-form-fields-wrapper .elementor-field-type-text input[type="email"]',
				'separator'      => 'none',
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
							'isLinked' => false,
						),
					),
				),
				'exclude'        => array( 'color' ),
			)
		);

		$this->add_control(
			'form_input_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-form-fields-wrapper .elementor-field-type-text input[type="email"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .mas-form-fields-wrapper .elementor-field-type-text input[type="text"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .mas-form-fields-wrapper .elementor-field-type-text input[type="password"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'login_input_border_border!' => 'none',
				),
			)
		);

		$this->add_control(
			'form_input_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-form-fields-wrapper .elementor-field-type-text input[type="email"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .mas-form-fields-wrapper .elementor-field-type-text input[type="text"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .mas-form-fields-wrapper .elementor-field-type-text input[type="password"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'login_input_border_border!' => 'none',
				),
			)
		);

		$this->add_control(
			'input_icon_margin',
			array(
				'label'      => esc_html__( 'Input Icon Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => array(
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '-4',
					'left'     => '-36',
					'isLinked' => false,
				),
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-form-fields-wrapper .mas-input-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'form_input_controls' );

		$this->start_controls_tab(
			'form_input_controls_normal',
			array(
				'label' => esc_html__( 'Normal', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'login_input_color',
			array(
				'label'     => esc_html__( 'Input Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-form-fields-wrapper .elementor-field-type-text input[type="email"]' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mas-form-fields-wrapper .elementor-field-type-text input[type="text"]' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mas-form-fields-wrapper .elementor-field-type-text input[type="password"]' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'login_input_bg_color',
			array(
				'label'     => esc_html__( 'Input BG Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-form-fields-wrapper .elementor-field-type-text input[type="email"]' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .mas-form-fields-wrapper .elementor-field-type-text input[type="text"]' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .mas-form-fields-wrapper .elementor-field-type-text input[type="password"]' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'login_input_border_color',
			array(
				'label'     => esc_html__( 'Input Border Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-form-fields-wrapper .elementor-field-type-text input[type="email"]' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .mas-form-fields-wrapper .elementor-field-type-text input[type="text"]' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .mas-form-fields-wrapper .elementor-field-type-text input[type="password"]' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'login_input_border_border!' => 'none',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'form_input_controls_hover',
			array(
				'label' => esc_html__( 'Hover', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'login_input_hover_color',
			array(
				'label'     => esc_html__( 'Input Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-form-fields-wrapper .elementor-field-type-text input[type="email"]:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mas-form-fields-wrapper .elementor-field-type-text input[type="text"]:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mas-form-fields-wrapper .elementor-field-type-text input[type="password"]:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'login_input_bg_hover_color',
			array(
				'label'     => esc_html__( 'Input BG Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-form-fields-wrapper .elementor-field-type-text input[type="email"]:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .mas-form-fields-wrapper .elementor-field-type-text input[type="text"]:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .mas-form-fields-wrapper .elementor-field-type-text input[type="password"]:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'login_input_border_hover_color',
			array(
				'label'     => esc_html__( 'Input Border Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-form-fields-wrapper .elementor-field-type-text input[type="email"]:hover' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .mas-form-fields-wrapper .elementor-field-type-text input[type="text"]:hover' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .mas-form-fields-wrapper .elementor-field-type-text input[type="password"]:hover' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'login_input_border_border!' => 'none',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'login_button_heading',
			array(
				'label'     => esc_html__( 'Signin Button', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'sigin_button_typography',
				'selector' => '{{WRAPPER}} .mas-signin-btn button',
			)
		);

		$this->add_control(
			'login_button_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-signin-btn button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'login_button_bg_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#20D756',
				'selectors' => array(
					'{{WRAPPER}} .mas-signin-btn button' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'login_button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-signin-btn button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'top'      => '6',
					'right'    => '12',
					'bottom'   => '6',
					'left'     => '12',
					'unit'     => 'px',
					'isLinked' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'login_button_border',
				'selector'       => '{{WRAPPER}} .mas-signin-btn button',
				'separator'      => 'none',
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
							'isLinked' => false,
						),
					),
					'color'  => array(
						'default' => '#BEC2C2',
					),
				),
			)
		);

		$this->add_control(
			'button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-signin-btn button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'button_width_alignment',
			array(
				'label'     => esc_html__( 'Button Alignment', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'start'  => array(
						'title' => esc_html__( 'Left', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'end'    => array(
						'title' => esc_html__( 'Right', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .mas-signin-btn' => 'display:flex; justify-content: {{VALUE}} !important;',
				),

			)
		);

		$this->add_responsive_control(
			'signin_button_width',
			array(
				'label'      => esc_html__( 'Sign in Button Width', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'default'    => array(
					'size' => '',
					'unit' => '%',
				),
				'range'      => array(
					'%' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mas-signin-btn button[type="submit"]' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'signin_button_spacing',
			array(
				'label'      => esc_html__( 'Sign in Button Spacing', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'default'    => array(
					'size' => '15',
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mas-signin-btn' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'login_signup_text_heading',
			array(
				'label'     => esc_html__( 'Signup Text', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'signup_text_typography',
				'selector' => '{{WRAPPER}} .form__footer',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'        => 'signup_link_typography',
				'selector'    => '{{WRAPPER}} .form__footer a',
				'description' => esc_html__( 'For the sign up link', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'signup_text_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .form__footer' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'signup_link_color',
			array(
				'label'     => esc_html__( 'Link Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#20D756',
				'selectors' => array(
					'{{WRAPPER}} .form__footer a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'signup_link_spacing',
			array(
				'label'      => esc_html__( 'Sign up link Spacing', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'default'    => array(
					'size' => '12',
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .form__footer' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();
	}

	/**
	 * Add form option controls.
	 */
	private function add_form_option_controls() {
		$this->start_controls_section(
			'section_form_controls',
			array(
				'label' => esc_html__( 'Form Controls', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'show_form_title',
			array(
				'label'     => esc_html__( 'Title', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_off' => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
				'label_on'  => esc_html__( 'Show', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'title_tag',
			array(
				'label'     => esc_html__( 'Title HTML Tag', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				),
				'default'   => 'h1',
				'condition' => array(
					'show_form_title' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_form_description',
			array(
				'label'     => esc_html__( 'Description', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_off' => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
				'label_on'  => esc_html__( 'Show', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'show_labels',
			array(
				'label'     => esc_html__( 'Labels', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_off' => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
				'label_on'  => esc_html__( 'Show', 'mas-addons-for-elementor' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Form fields render attributes.
	 */
	private function form_fields_render_attributes() {
		$settings  = $this->get_settings();
		$unique_id = uniqid();

		$this->add_render_attribute(
			'button_text',
			'class',
			array(
				'elementor-login__button',
				'btn',
			)
		);

		$this->add_render_attribute(
			'register_button_text',
			'class',
			array(
				'mas-register__button',
				'btn',
			)
		);

		if ( ! empty( $settings['button_classes'] ) ) {
			$this->add_render_attribute( 'button_text', 'class', $settings['button_classes'] );
		}

		$this->add_render_attribute(
			array(
				'wrapper'                         => array(
					'class' => array(
						'mas-form-fields-wrapper',
					),
				),
				'field-group'                     => array(
					'class' => array(
						'elementor-field-type-text',
						'elementor-field-group',
						'elementor-column',
						'elementor-col-100',
						'form-group',
						'p-0',
					),
				),
				'submit-group'                    => array(
					'class' => array(
						'mas-signin-btn',
					),
				),

				'button'                          => array(
					'class' => array(
						'mas-button',
					),
					'name'  => 'login',
				),
				'user_label'                      => array(
					'for' => 'user-' . $unique_id,
				),
				'user_input'                      => array(
					'type'        => 'text',
					'name'        => 'username',
					'id'          => 'user-' . $unique_id,
					'placeholder' => $settings['user_placeholder'],
					'class'       => array(
						'form-control',
					),
					'value'       => ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : '', // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				),
				'password_label'                  => array(
					'for' => 'password-' . $unique_id,
				),
				'password_input'                  => array(
					'type'        => 'password',
					'name'        => 'password',
					'id'          => 'password-' . $unique_id,
					'placeholder' => $settings['password_placeholder'],
					'class'       => array(
						'form-control',

					),
				),
				'user_forget_password_label'      => array(
					'for' => 'recoverSrEmail-' . $unique_id,

				),

				'user_forget_password_input'      => array(
					'type'        => 'text',
					'name'        => 'user_login',
					'id'          => 'recoverSrEmail-' . $unique_id,
					'placeholder' => $settings['user_placeholder'],
					'class'       => array(
						'form-control',
					),
				),

				'user_register_label'             => array(
					'for' => 'reg_username-' . $unique_id,
				),

				'user_register_input'             => array(
					'type'        => 'text',
					'name'        => 'username',
					'id'          => 'reg_username-' . $unique_id,
					'placeholder' => ! empty( $settings['register_email_placeholder'] ) ? $settings['register_email_placeholder'] : 'name@address.com',
					'class'       => array(
						'form-control',
					),
				),

				'register_label_name'             => array(
					'for' => 'registerName-' . $unique_id,
				),

				'register_name_input'             => array(
					'type'        => 'text',
					'name'        => 'regname',
					'id'          => 'registerName-' . $unique_id,
					'placeholder' => ! empty( $settings['register_name_placeholder'] ) ? $settings['register_name_placeholder'] : 'enter your name',
					'class'       => array(
						'form-control',
					),
				),

				'register_password_label'         => array(
					'for' => 'signupSrPassword-' . $unique_id,
				),

				'register_password_input'         => array(
					'type'        => 'password',
					'name'        => 'password',
					'id'          => 'signupSrPassword-' . $unique_id,
					'placeholder' => ! empty( $settings['register_password_placeholder'] ) ? $settings['register_password_placeholder'] : 'name@address.com',
					'class'       => array(
						'form-control',
					),
				),
				'email_register_label'            => array(
					'for' => 'reg_email-' . $unique_id,
				),

				'email_register_input'            => array(
					'type'        => 'email',
					'name'        => 'email',
					'id'          => 'reg_email-' . $unique_id,
					'placeholder' => ! empty( $settings['register_email_placeholder'] ) ? $settings['register_email_placeholder'] : 'name@address.com',
					'class'       => array(
						'form-control',
					),
				),
				'register_confirm_password_label' => array(
					'for' => 'signupSrConfirmPassword-' . $unique_id,
				),
				'register_confirm_password_input' => array(
					'type'        => 'password',
					'name'        => 'confirmPassword',
					'id'          => 'signupSrConfirmPassword-' . $unique_id,
					'placeholder' => ! empty( $settings['register_confirm_password_placeholder'] ) ? $settings['register_confirm_password_placeholder'] : 'Confirm Password',
					'class'       => array(
						'form-control',
					),
				),

				// TODO: add unique ID.
				'label_user'                      => array(
					'for'   => 'user',
					'class' => 'elementor-field-label',
				),

				'label_password'                  => array(
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
	 * Add login form controls.
	 */
	private function add_login_form_controls() {

		$can_register = get_option( 'users_can_register' );

		$this->start_controls_section(
			'section_login',
			array(
				'label'     => esc_html__( 'Login Form', 'mas-addons-for-elementor' ),
				'condition' => array(
					'display' => array( 'all', 'login' ),
				),
			)
		);

		$this->add_control(
			'login_title',
			array(
				'label'       => esc_html__( 'Form Title', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => esc_html__( 'Sign in', 'mas-addons-for-elementor' ),
				'placeholder' => esc_html__( 'Title', 'mas-addons-for-elementor' ),
				'condition'   => array(
					'show_form_title' => 'yes',
				),
			)
		);

		$this->add_control(
			'login_description',
			array(
				'label'       => esc_html__( 'Form Description', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => esc_html__( 'Simplify your workflow in minutes.', 'mas-addons-for-elementor' ),
				'placeholder' => esc_html__( 'Description', 'mas-addons-for-elementor' ),
				'condition'   => array(
					'show_form_description' => 'yes',
				),
			)
		);

		$this->add_control(
			'user_label',
			array(
				'label'       => esc_html__( 'Username Label', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'separator'   => 'before',
				'default'     => esc_html__( 'Email Address', 'mas-addons-for-elementor' ),
				'condition'   => array(
					'show_labels' => 'yes',
				),
			)
		);

		$this->add_control(
			'user_placeholder',
			array(
				'label'       => esc_html__( 'Username Placeholder', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => esc_html__( 'name@address.com', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'password_label',
			array(
				'label'       => esc_html__( 'Password Label', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'separator'   => 'before',
				'default'     => esc_html__( 'Password', 'mas-addons-for-elementor' ),
				'condition'   => array(
					'show_labels' => 'yes',
				),
			)
		);

		$this->add_control(
			'password_placeholder',
			array(
				'label'       => esc_html__( 'Password Placeholder', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => esc_html__( 'Enter your password', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'show_lost_password',
			array(
				'label'     => esc_html__( 'Password Reset Link', 'mas-addons-for-elementor' ),
				'separator' => 'before',
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_off' => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
				'label_on'  => esc_html__( 'Show', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'password_reset_text',
			array(
				'label'       => esc_html__( 'Password Reset Text', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => esc_html__( 'Lost your password?', 'mas-addons-for-elementor' ),
				'label_off'   => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
				'label_on'    => esc_html__( 'Show', 'mas-addons-for-elementor' ),
				'condition'   => array( 'show_lost_password' => 'yes' ),
			)
		);

		$this->add_control(
			'password_reset_link',
			array(
				'label'     => esc_html__( 'Password Reset Page', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::URL,
				'default'   => array( 'url' => '#' ),
				'dynamic'   => array( 'active' => true ),
				'condition' => array(
					'display!'           => 'all',
					'show_lost_password' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_remember_me',
			array(
				'label'     => esc_html__( 'Remember Me', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'separator' => 'before',
				'label_off' => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
				'label_on'  => esc_html__( 'Show', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'       => esc_html__( 'Login Button Text', 'mas-addons-for-elementor' ),
				'label_block' => true,
				'separator'   => 'before',
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Sign in', 'mas-addons-for-elementor' ),
			)
		);

		if ( $can_register ) :

			$this->add_control(
				'show_register',
				array(
					'label'     => esc_html__( 'Registration Link', 'mas-addons-for-elementor' ),
					'type'      => Controls_Manager::SWITCHER,
					'default'   => 'yes',
					'separator' => 'before',
					'label_off' => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
					'label_on'  => esc_html__( 'Show', 'mas-addons-for-elementor' ),
				)
			);

			$this->add_control(
				'register_link_intro',
				array(
					'label'       => esc_html__( 'Registration Page Intro', 'mas-addons-for-elementor' ),
					'label_block' => true,
					'type'        => Controls_Manager::TEXT,
					'default'     => esc_html__( 'Don\'t have an account yet?', 'mas-addons-for-elementor' ),
					'condition'   => array( 'show_register' => 'yes' ),
				)
			);

			$this->add_control(
				'register_link_text',
				array(
					'label'       => esc_html__( 'Registration Link Text', 'mas-addons-for-elementor' ),
					'label_block' => true,
					'type'        => Controls_Manager::TEXT,
					'default'     => esc_html__( 'Sign up', 'mas-addons-for-elementor' ),
					'condition'   => array( 'show_register' => 'yes' ),
				)
			);

			$this->add_control(
				'register_link',
				array(
					'label'     => esc_html__( 'Registration Page Link', 'mas-addons-for-elementor' ),
					'type'      => Controls_Manager::URL,
					'default'   => array( 'url' => '#' ),
					'dynamic'   => array( 'active' => true ),
					'condition' => array(
						'display!'      => 'all',
						'show_register' => 'yes',
					),
				)
			);

		endif;

		$this->end_controls_section();
	}

	/**
	 * Add register form controls.
	 */
	private function add_register_form_controls() {
		$can_register = get_option( 'users_can_register' );

		if ( $can_register ) :

			$this->start_controls_section(
				'section_register',
				array(
					'label'     => esc_html__( 'Register Form', 'mas-addons-for-elementor' ),
					'condition' => array( 'display' => array( 'all', 'register' ) ),
				)
			);

			$this->add_control(
				'register_title',
				array(
					'label'       => esc_html__( 'Form Title', 'mas-addons-for-elementor' ),
					'type'        => Controls_Manager::TEXT,
					'label_block' => true,
					'default'     => esc_html__( 'Sign up', 'mas-addons-for-elementor' ),
					'placeholder' => esc_html__( 'Title', 'mas-addons-for-elementor' ),
					'condition'   => array( 'show_form_title' => 'yes' ),
				)
			);

			$this->add_control(
				'register_description',
				array(
					'label'       => esc_html__( 'Form Description', 'mas-addons-for-elementor' ),
					'type'        => Controls_Manager::TEXTAREA,
					'default'     => esc_html__( 'Simplify your workflow in minutes.', 'mas-addons-for-elementor' ),
					'placeholder' => esc_html__( 'Description', 'mas-addons-for-elementor' ),
					'condition'   => array( 'show_form_description' => 'yes' ),
					'separator'   => 'after',
				)
			);

			$this->add_control(
				'register_name_label',
				array(
					'label'       => esc_html__( 'Register Name', 'mas-addons-for-elementor' ),
					'label_block' => true,
					'type'        => Controls_Manager::TEXT,
					'default'     => esc_html__( 'Enter Your Name', 'mas-addons-for-elementor' ),
					'condition'   => array( 'show_labels' => 'yes' ),
				)
			);

			$this->add_control(
				'register_name_placeholder',
				array(
					'label'       => esc_html__( 'Register Name Placeholder', 'mas-addons-for-elementor' ),
					'type'        => Controls_Manager::TEXT,
					'label_block' => true,
					'default'     => esc_html__( 'enter your name', 'mas-addons-for-elementor' ),
				)
			);

			$this->add_control(
				'register_email_label',
				array(
					'label'       => esc_html__( 'Email Label', 'mas-addons-for-elementor' ),
					'label_block' => true,
					'type'        => Controls_Manager::TEXT,
					'default'     => esc_html__( 'Email Address', 'mas-addons-for-elementor' ),
					'condition'   => array( 'show_labels' => 'yes' ),
				)
			);

			$this->add_control(
				'register_email_placeholder',
				array(
					'label'       => esc_html__( 'Email Placeholder', 'mas-addons-for-elementor' ),
					'label_block' => true,
					'type'        => Controls_Manager::TEXT,
					'default'     => esc_html__( 'name@address.com', 'mas-addons-for-elementor' ),
					'separator'   => 'after',
				)
			);

			$this->add_control(
				'register_password_label',
				array(
					'label'       => esc_html__( 'Pasword Label', 'mas-addons-for-elementor' ),
					'label_block' => true,
					'type'        => Controls_Manager::TEXT,
					'default'     => esc_html__( 'Password', 'mas-addons-for-elementor' ),
					'condition'   => array( 'show_labels' => 'yes' ),
				)
			);

			$this->add_control(
				'register_password_placeholder',
				array(
					'label'       => esc_html__( 'Password Placeholder', 'mas-addons-for-elementor' ),
					'label_block' => true,
					'type'        => Controls_Manager::TEXT,
					'default'     => esc_html__( 'Enter your password', 'mas-addons-for-elementor' ),
				)
			);

			$this->add_control(
				'register_confirm_password_label',
				array(
					'label'       => esc_html__( 'Confirm Password Label', 'mas-addons-for-elementor' ),
					'label_block' => true,
					'type'        => Controls_Manager::TEXT,
					'default'     => esc_html__( 'Confirm Password', 'mas-addons-for-elementor' ),
					'separator'   => 'none',
				)
			);

			$this->add_control(
				'register_confirm_password_placeholder',
				array(
					'label'       => esc_html__( 'Confirm Password Placeholder', 'mas-addons-for-elementor' ),
					'label_block' => true,
					'type'        => Controls_Manager::TEXT,
					'default'     => esc_html__( 'Confirm Password', 'mas-addons-for-elementor' ),
					'separator'   => 'after',
				)
			);

			$this->add_control(
				'register_button_text',
				array(
					'label'       => esc_html__( 'Register Button Text', 'mas-addons-for-elementor' ),
					'type'        => Controls_Manager::TEXT,
					'label_block' => true,
					'default'     => esc_html__( 'Sign up', 'mas-addons-for-elementor' ),
				)
			);

			$this->add_control(
				'show_login',
				array(
					'label'     => esc_html__( 'Login Link', 'mas-addons-for-elementor' ),
					'type'      => Controls_Manager::SWITCHER,
					'default'   => 'yes',
					'separator' => 'before',
					'label_off' => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
					'label_on'  => esc_html__( 'Show', 'mas-addons-for-elementor' ),
				)
			);

			$this->add_control(
				'login_link_intro',
				array(
					'label'       => esc_html__( 'Login Page Intro', 'mas-addons-for-elementor' ),
					'label_block' => true,
					'type'        => Controls_Manager::TEXT,
					'default'     => esc_html__( 'Already have an account?', 'mas-addons-for-elementor' ),
					'condition'   => array( 'show_login' => 'yes' ),
				)
			);

			$this->add_control(
				'login_link_text',
				array(
					'label'       => esc_html__( 'Login Link Text', 'mas-addons-for-elementor' ),
					'label_block' => true,
					'type'        => Controls_Manager::TEXT,
					'default'     => esc_html__( 'Login', 'mas-addons-for-elementor' ),
					'condition'   => array( 'show_login' => 'yes' ),
				)
			);

			$this->add_control(
				'login_link',
				array(
					'label'     => esc_html__( 'Login Page Link', 'mas-addons-for-elementor' ),
					'type'      => Controls_Manager::URL,
					'default'   => array( 'url' => '#' ),
					'dynamic'   => array( 'active' => true ),
					'condition' => array(
						'display!'   => 'all',
						'show_login' => 'yes',
					),
				)
			);

			$this->end_controls_section();

		endif;
	}

	/**
	 * Add password reset form controls.
	 */
	private function add_password_reset_form_controls() {

		$this->start_controls_section(
			'section_password_reset',
			array(
				'label'     => esc_html__( 'Password Reset Form', 'mas-addons-for-elementor' ),
				'condition' => array( 'display' => array( 'all', 'forgot' ) ),
			)
		);

		$this->add_control(
			'password_title',
			array(
				'label'       => esc_html__( 'Form Title', 'mas-addons-for-elementor' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Password Reset', 'mas-addons-for-elementor' ),
				'placeholder' => esc_html__( 'Title', 'mas-addons-for-elementor' ),
				'condition'   => array( 'show_form_title' => 'yes' ),
			)
		);

		$this->add_control(
			'password_description',
			array(
				'label'       => esc_html__( 'Form Description', 'mas-addons-for-elementor' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => esc_html__( 'Enter your email to reset your password.', 'mas-addons-for-elementor' ),
				'placeholder' => esc_html__( 'Description', 'mas-addons-for-elementor' ),
				'condition'   => array( 'show_form_description' => 'yes' ),
			)
		);

		$this->add_control(
			'reset_pasword_button_text',
			array(
				'label'       => esc_html__( 'Button Text', 'mas-addons-for-elementor' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Reset Password', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rp_show_login',
			array(
				'label'     => esc_html__( 'Login Link', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'separator' => 'before',
				'label_off' => esc_html__( 'Hide', 'mas-addons-for-elementor' ),
				'label_on'  => esc_html__( 'Show', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rp_login_link_intro',
			array(
				'label'       => esc_html__( 'Login Page Intro', 'mas-addons-for-elementor' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Remember your password?', 'mas-addons-for-elementor' ),
				'condition'   => array( 'rp_show_login' => 'yes' ),
			)
		);

		$this->add_control(
			'rp_login_link_text',
			array(
				'label'       => esc_html__( 'Login Link Text', 'mas-addons-for-elementor' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Log in', 'mas-addons-for-elementor' ),
				'condition'   => array( 'rp_show_login' => 'yes' ),
			)
		);

		$this->add_control(
			'rp_login_link',
			array(
				'label'     => esc_html__( 'Login Page Link', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::URL,
				'default'   => array( 'url' => '#' ),
				'dynamic'   => array( 'active' => true ),
				'condition' => array(
					'display!'      => 'all',
					'rp_show_login' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Add additional options controls.
	 */
	private function add_additional_options_controls() {
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
				'condition'   => array( 'redirect_after_login' => 'yes' ),
				'default'     => array( 'url' => '#' ),
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
				'condition'   => array( 'redirect_after_logout' => 'yes' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render.
	 */
	protected function render() {
		$settings         = $this->get_settings();
		$current_url      = remove_query_arg( 'fake_arg' );
		$logout_redirect  = $current_url;
		$show_title       = $settings['show_form_title'];
		$show_description = $settings['show_form_description'];
		$title_tag        = $settings['title_tag'];
		$display          = $settings['display'];
		$unique_id        = uniqid();
		$login_form_id    = 'login-form-' . $unique_id;
		$register_form_id = 'register-form-' . $unique_id;
		$lost_pwd_form_id = 'lost-password-' . $unique_id;

		/**
		 * Add Form IDs to settings data
		 */
		$settings['login_form_id']    = $login_form_id;
		$settings['register_form_id'] = $register_form_id;
		$settings['lost_pwd_form_id'] = $lost_pwd_form_id;

		if ( isset( $_POST['recoverPassword'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$login_tab_pane           = '';
			$register_tab_pane        = '';
			$forget_password_tab_pane = ' active';
		} elseif ( isset( $_POST['register'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$login_tab_pane           = '';
			$forget_password_tab_pane = '';
			$register_tab_pane        = ' active';
		} else {
			$login_tab_pane           = ' active';
			$forget_password_tab_pane = '';
			$register_tab_pane        = '';
		}

		if ( 'yes' === $settings['redirect_after_logout'] && ! empty( $settings['redirect_logout_url']['url'] ) ) {
			$logout_redirect = $settings['redirect_logout_url']['url'];
		}

		if ( is_user_logged_in() && ! Plugin::$instance->editor->is_edit_mode() ) {
			$current_user = wp_get_current_user();

			echo '<div class="elementor-login elementor-login__logged-in-message">' .
				sprintf( __( 'You are Logged in as %1$s (<a href="%2$s">Logout</a>)', 'mas-addons-for-elementor' ), $current_user->display_name, wp_logout_url( $logout_redirect ) ) . // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped, WordPress.WP.I18n.MissingTranslatorsComment
				'</div>';

			return;
		}

		$this->form_fields_render_attributes();

		if ( 'register' === $display ) {
			$this->render_register_form( $settings );
			return;
		} elseif ( 'login' === $display ) {
			$this->render_login_form( $settings );
			return;
		} elseif ( 'forgot' === $display ) {
			$this->render_password_reset_form( $settings );
			return;
		}

		?><div class="mas-tab-content tab-content">
			<div class="tab-pane<?php echo esc_attr( $login_tab_pane ); ?>" id="<?php echo esc_attr( $login_form_id ); ?>" aria-labelledby="login-tab">
				<?php $this->render_login_form( $settings ); ?>
			</div>

			<div class="tab-pane<?php echo esc_attr( $forget_password_tab_pane ); ?>" id="<?php echo esc_attr( $lost_pwd_form_id ); ?>" aria-labelledby="forget-password-tab">
				<?php $this->render_password_reset_form( $settings ); ?>
			</div>

			<?php if ( get_option( 'users_can_register' ) ) : ?>
			<div class="tab-pane<?php echo esc_attr( $register_tab_pane ); ?>" id="<?php echo esc_attr( $register_form_id ); ?>" aria-labelledby="register-tab">
				<?php $this->render_register_form( $settings ); ?>
			</div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Render form header.
	 *
	 * @param string $title title.
	 * @param string $desc desc.
	 * @param array  $settings settings.
	 */
	private function render_form_header( $title, $desc, $settings ) {
		$show_title = ( 'yes' === $settings['show_form_title'] );
		$show_desc  = ( 'yes' === $settings['show_form_description'] );
		$title_tag  = $settings['title_tag'];

		$this->add_render_attribute( 'form_title', 'class', 'form-header__title' );

		if ( ! empty( $settings['title_css'] ) ) {
			$this->add_render_attribute( 'form_title', 'class', $settings['title_css'] );
		}

		$this->add_render_attribute( 'form_desc', 'class', 'form-header__desc' );

		if ( ! empty( $settings['description_css'] ) ) {
			$this->add_render_attribute( 'form_desc', 'class', $settings['description_css'] );
		}

		?>
		<?php if ( $show_title && ! empty( $title ) ) : ?>
			<<?php Utils::print_validated_html_tag( $title_tag ); ?> <?php $this->print_render_attribute_string( 'form_title' ); ?>><?php echo esc_html( $title ); ?></<?php Utils::print_validated_html_tag( $title_tag ); ?>>
		<?php endif; ?>

		<?php if ( $show_desc && ! empty( $desc ) ) : ?>
			<p <?php $this->print_render_attribute_string( 'form_desc' ); ?>><?php echo esc_html( $desc ); ?></p>
			<?php
		endif;
	}

	/**
	 * Render label.
	 *
	 * @param string $render_key render_key.
	 * @param array  $settings settings.
	 * @param string $label_key label_key.
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
			<?php print( $settings[ $label_key ] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</label>
		<?php
	}

	/**
	 * Render lost password form link.
	 *
	 * @param array $settings settings.
	 */
	private function render_lost_password_form_link( $settings ) {
		$show      = ( 'yes' === $settings['show_lost_password'] );
		$form_id   = $settings['lost_pwd_form_id'];
		$link_text = $settings['password_reset_text'];

		if ( ! $show ) {
			return;
		}

		$this->add_render_attribute(
			'lost_password_form_link',
			array(
				'id'    => 'forgot-password-tab',
				'class' => array( 'elementor-lost-password', 'font-size-sm' ),
			)
		);

		if ( 'all' === $settings['display'] ) {
			$this->add_render_attribute( 'lost_password_form_link', 'href', '#' . $form_id );
			$this->add_render_attribute( 'lost_password_form_link', 'class', 'login-register-tab-switcher' );
		} else {
			$this->add_link_attributes( 'lost_password_form_link', $settings['password_reset_link'] );
		}

		printf( '<a %1$s>%2$s</a>', $this->get_render_attribute_string( 'lost_password_form_link' ), $link_text ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Render register form link.
	 *
	 * @param array $settings settings.
	 */
	private function render_register_form_link( $settings ) {
		$show       = ( get_option( 'users_can_register' ) && 'yes' === $settings['show_register'] );
		$form_id    = $settings['register_form_id'];
		$link_intro = ! empty( $settings['register_link_intro'] ) ? $settings['register_link_intro'] : 'Don\'t have an account yet?';
		$link_text  = ! empty( $settings['register_link_text'] ) ? $settings['register_link_text'] : 'Sign up';

		if ( ! $show ) {
			return;
		}

		$this->add_render_attribute(
			'register_form_link',
			array(
				'class' => array( 'd-inline-block', 'login' ),
			)
		);

		if ( 'all' === $settings['display'] ) {
			$this->add_render_attribute( 'register_form_link', 'id', 'register-tab' );
			$this->add_render_attribute( 'register_form_link', 'href', '#' . $form_id );
			$this->add_render_attribute( 'register_form_link', 'class', 'login-register-tab-switcher' );
		} else {
			$this->add_link_attributes( 'register_form_link', $settings['register_link'] );
		}

		$link_html = sprintf( '<a %1$s>%2$s</a>', $this->get_render_attribute_string( 'register_form_link' ), $link_text );

		?>
		<p class="form__footer">
			<?php printf( '%s %s.', $link_intro, $link_html ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</p>
		<?php
	}

	/**
	 * Render login form link.
	 *
	 * @param array $settings settings.
	 */
	private function render_login_form_link( $settings ) {
		$show       = ( 'yes' === $settings['show_login'] );
		$form_id    = $settings['login_form_id'];
		$link_intro = $settings['login_link_intro'];
		$link_text  = $settings['login_link_text'];

		if ( ! $show ) {
			return;
		}

		$this->add_render_attribute(
			'login_form_link',
			array(
				'class' => array( 'login' ),
			)
		);

		if ( 'all' === $settings['display'] ) {
			$this->add_render_attribute( 'login_form_link', 'id', 'login-tab' );
			$this->add_render_attribute( 'login_form_link', 'href', '#' . $form_id );
			$this->add_render_attribute( 'login_form_link', 'class', 'login-register-tab-switcher' );
		} else {
			$this->add_link_attributes( 'login_form_link', $settings['login_link'] );
		}

		$link_html = sprintf( '<a %1$s>%2$s</a>', $this->get_render_attribute_string( 'login_form_link' ), $link_text );

		?>
		<p class="form__footer">
			<?php printf( '%s %s.', $link_intro, $link_html ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</p>
		<?php
	}

	/**
	 * Render rp login form link.
	 *
	 * @param array $settings settings.
	 */
	private function render_rp_login_form_link( $settings ) {
		$show       = ( 'yes' === $settings['rp_show_login'] );
		$form_id    = $settings['login_form_id'];
		$link_intro = $settings['rp_login_link_intro'];
		$link_text  = $settings['rp_login_link_text'];

		if ( ! $show ) {
			return;
		}

		$this->add_render_attribute(
			'rp_login_form_link',
			array(
				'class' => array( 'login' ),
				'id'    => 'login-tab',
			)
		);

		if ( 'all' === $settings['display'] ) {
			$this->add_render_attribute( 'rp_login_form_link', 'href', '#' . $form_id );
			$this->add_render_attribute( 'rp_login_form_link', 'class', 'login-register-tab-switcher' );
		} else {
			$this->add_link_attributes( 'rp_login_form_link', $settings['rp_login_link'] );
		}

		$link_html = sprintf( '<a %1$s>%2$s</a>', $this->get_render_attribute_string( 'rp_login_form_link' ), $link_text );

		?>
		<p class="form__footer">
			<?php printf( '%s %s.', $link_intro, $link_html ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</p>
		<?php
	}

	/**
	 * Render login form.
	 *
	 * @param array $settings settings.
	 */
	private function render_login_form( $settings ) {

		$current_url = remove_query_arg( 'fake_arg' );

		if ( 'yes' === $settings['redirect_after_login'] && ! empty( $settings['redirect_url']['url'] ) ) {
			$redirect_url = $settings['redirect_url']['url'];
		} else {
			$redirect_url = $current_url;
		}

		$this->render_form_header( $settings['login_title'], $settings['login_description'], $settings );

		?>
		<form class="elementor-form mas-login-form login" method="post">
			<?php
			if ( isset( $_POST['login'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
					// show any error messages after form submission.
				?>
					<div class="mb-3"><?php $this->mas_elementor_show_error_messages(); ?></div>
					<?php
			}
			?>

			<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_url ); ?>">	
			<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
				<div class="mas-form-fields">
					<div <?php $this->print_render_attribute_string( 'field-group' ); ?>>
						<?php $this->render_label( 'user_label', $settings ); ?>
						<input <?php $this->print_render_attribute_string( 'user_input' ); ?>>
					</div>
					<div <?php $this->print_render_attribute_string( 'field-group' ); ?>>
						<?php $this->render_label( 'password_label', $settings ); ?>
						<input <?php $this->print_render_attribute_string( 'password_input' ); ?>>
						<span class="show-password-input mas-input-icon"><i class="eicon-preview-thin"></i></span>
					</div>
					<div style="display:flex;justify-content:space-between">
						<?php if ( 'yes' === $settings['show_remember_me'] ) : ?>
							<div class="elementor-field-type-checkbox elementor-field-group elementor-column elementor-col-100 elementor-remember-me">
								<label for="elementor-login-remember-me">
									<input type="checkbox" name="rememberme" value="forever">
									<?php echo esc_html__( 'Remember Me', 'mas-addons-for-elementor' ); ?>
								</label>
							</div>
						<?php endif; ?>
						<div class="mas-lost-password" style="width:100%;">
							<?php
								$this->render_lost_password_form_link( $settings );
							?>
						</div>
					</div>
				</div>
				<div <?php $this->print_render_attribute_string( 'submit-group' ); ?>>
					<?php if ( ! empty( $settings['button_text'] ) ) : ?>
						<input type="hidden" name="mas_login_nonce" value="<?php echo esc_attr( wp_create_nonce( 'mas-login-nonce' ) ); ?>"/>
						<input type="hidden" name="mas_login_check" value="1"/>

						<button type="submit" name="login" <?php $this->print_render_attribute_string( 'button_text' ); ?>><?php echo esc_html( $settings['button_text'] ); ?></button>
					<?php endif; ?>
				</div>
			</div>
			<?php $this->render_register_form_link( $settings ); ?>
		</form>
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
		$this->render_form_header( $settings['register_title'], $settings['register_description'], $settings );
		?>
		<form class="mas-register elementor-form register" method="post">
			<?php
			if ( isset( $_POST['register'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
				?>
					<div class="mb-3"><?php $this->mas_elementor_show_error_messages(); ?></div>
					<?php
			}
			?>
			<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
				<div class="mas-form-fields">
					<div <?php $this->print_render_attribute_string( 'field-group' ); ?>>
						<?php $this->render_label( 'register_label_name', $settings, 'register_name_label' ); ?>
						<input <?php $this->print_render_attribute_string( 'register_name_input' ); ?>>
					</div>
					<div <?php $this->print_render_attribute_string( 'field-group' ); ?>>
						<?php $this->render_label( 'email_register_label', $settings, 'register_email_label' ); ?>
						<input <?php $this->print_render_attribute_string( 'email_register_input' ); ?>>
					</div>
					<?php if ( apply_filters( 'mas_register_password_enabled', true ) ) : ?>
						<div <?php $this->print_render_attribute_string( 'field-group' ); ?>>
							<?php $this->render_label( 'register_password_label', $settings, 'register_password_label' ); ?>
							<input <?php $this->print_render_attribute_string( 'register_password_input' ); ?>>
							<span class="show-password-input mas-input-icon"><i class="eicon-preview-thin"></i></span>
						</div>
						<div <?php $this->print_render_attribute_string( 'field-group' ); ?>>
							<?php $this->render_label( 'register_confirm_password_label', $settings, 'register_confirm_password_label' ); ?>
							<input <?php $this->print_render_attribute_string( 'register_confirm_password_input' ); ?>>
							<span class="show-password-input mas-input-icon"><i class="eicon-preview-thin"></i></span>
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
	 * Render password reset form.
	 *
	 * @param array $settings settings.
	 */
	private function render_password_reset_form( $settings ) {

		$this->render_form_header( $settings['password_title'], $settings['password_description'], $settings );
		?>

		<form class="mas-forget-password elementor-form forget-password" name="lostpasswordform" method="post">
			<?php if ( isset( $_POST['recoverPassword'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing ?>
				<div class="mb-3">
					<?php $this->mas_elementor_show_error_messages(); ?>
					<?php $this->mas_elementor_show_success_messages(); ?>
				</div>
			<?php } ?>
			<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
				<div <?php $this->print_render_attribute_string( 'field-group' ); ?>>
					<?php $this->render_label( 'user_forget_password_label', $settings, 'user_label' ); ?>
					<input <?php $this->print_render_attribute_string( 'user_forget_password_input' ); ?>>
				</div>
				<div <?php $this->print_render_attribute_string( 'submit-group' ); ?>>
					<?php if ( ! empty( $settings['button_text'] ) ) : ?>
						<input type="hidden" name="mas_lost_password_nonce" value="<?php echo esc_attr( wp_create_nonce( 'mas-lost-password-nonce' ) ); ?>"/>
						<input type="hidden" name="mas_lost_password_check" value="1"/>
						<?php wp_nonce_field( 'ajax-lost-password-nonce', 'lost-password-security' ); ?>
						<button type="submit" name="recoverPassword" <?php $this->print_render_attribute_string( 'button_text' ); ?>><?php echo esc_html( $settings['reset_pasword_button_text'] ); ?></button>
					<?php endif; ?>
				</div>
			</div>
			<?php $this->render_rp_login_form_link( $settings ); ?>
		</form>
		<?php
	}

	/**
	 * Render plain content.
	 */
	public function render_plain_content() {}

	/**
	 * Landkit Form Success.
	 *
	 * @return WP_Error
	 */
	public function mas_elementor_form_success() {
		static $wp_error; // Will hold global variable safely.
		if ( ! isset( $wp_error ) ) {
			$wp_error = new WP_Error( null, null, null );
		}
		return $wp_error;
	}

	/**
	 * Landkit show success messages.
	 */
	public function mas_elementor_show_success_messages() {
		$codes = $this->mas_elementor_form_success()->get_error_codes();
		if ( $codes ) {
			echo '<div class="notification alert alert-success">';
				// Loop success codes and display success.
			foreach ( $codes as $code ) {
				$message = $this->mas_elementor_form_success()->get_error_message( $code );
				echo '<span>' . esc_html( $message ) . '</span><br/>';
			}
			echo '</div>';
		}
	}

	/**
	 * Landkit Form errors.
	 *
	 * @return WP_Error
	 */
	public function mas_elementor_form_errors() {
		static $wp_error; // Will hold global variable safely.
		if ( ! isset( $wp_error ) ) {
			$wp_error = new WP_Error( null, null, null );
		}
		return $wp_error;

	}

	/**
	 * Show Error messages.
	 */
	public function mas_elementor_show_error_messages() {
		$codes = $this->mas_elementor_form_errors()->get_error_codes();
		if ( $codes ) {
			echo '<div class="notification alert alert-danger">';
				// Loop error codes and display errors.
			foreach ( $codes as $code ) {
				$message = $this->mas_elementor_form_errors()->get_error_message( $code );
				echo '<span>' . esc_html( $message ) . '</span><br/>';

			}
			echo '</div>';
		}
	}
}
