<?php
/**
 * Countdown Widget.
 *
 * @package mas-elementor
 */

namespace MASElementor\Modules\Countdown\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use MASElementor\Base\Base_Widget;
use Elementor\Plugin;
use MASElementor\Plugin as MASPlugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * CountDown Widget.
 */
class Countdown extends Base_Widget {

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-countdown';
	}

	/**
	 * Get the name of the title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Countdown', 'mas-addons-for-elementor' );
	}

	/**
	 * Get the script dependencies for this widget.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array( 'countdown-script' );
	}

	/**
	 * Get the name of the icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-countdown';
	}

	/**
	 * Get the categories for the widget.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( 'mas-elements' );
	}

	/**
	 * Get the keywords associated with the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'countdown', 'number', 'timer', 'time', 'date', 'evergreen' );
	}
	/**
	 * Register controls for this widget.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_countdown',
			array(
				'label' => __( 'Countdown', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'view',
			array(
				'label'   => esc_html__( 'Layout', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'inline',
				'options' => array(
					'traditional' => array(
						'title' => esc_html__( 'Default', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-editor-list-ul',
					),
					'inline'      => array(
						'title' => esc_html__( 'Inline', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-ellipsis-h',
					),
				),
			)
		);

		$this->add_control(
			'due_date',
			array(
				'label'       => __( 'Due Date', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::DATE_TIME,
				'default'     => gmdate( 'Y-m-d H:i', strtotime( '+1 month' ) + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) ),
				/* translators: %s: Time zone. */
				'description' => sprintf( __( 'Date set according to your timezone: %s.', 'mas-addons-for-elementor' ), Utils::get_timezone_string() ),
			)
		);

		$this->add_control(
			'single_view',
			array(
				'label'   => esc_html__( 'Text Layout', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'wrap',
				'options' => array(
					'wrap'   => array(
						'title' => esc_html__( 'Default', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-ellipsis-h',
					),
					'nowrap' => array(
						'title' => esc_html__( 'Inline', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-editor-list-ul',
					),
				),
			)
		);

		$this->add_responsive_control(
			'countdown_text_align',
			array(
				'label'     => esc_html__( 'Text Alignment', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
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
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-countdown-wrapper .mas-elementor-countdown-item' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'show_separator',
			array(
				'label'     => __( 'Show Separator', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Show', 'mas-addons-for-elementor' ),
				'label_off' => __( 'Hide', 'mas-addons-for-elementor' ),
				'default'   => 'no',
				'prefix_class' => 'mas-countdown-separator-',
			)
		);

		$this->add_control(
			'separator_color',
			array(
				'label'     => __( 'Separator Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}.mas-countdown-separator-yes .mas-js-countdown > .mas-elementor-countdown-item:not(:last-child):after' => 'color: {{VALUE}};',
				),
				'condition'   => array(
					'show_separator'   => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'separator_padding',
			array(
				'label'      => esc_html__( 'Separator Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}}.mas-countdown-separator-yes .mas-js-countdown > .mas-elementor-countdown-item:not(:last-child):after' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'top'      => '0',
					'right'    => '4',
					'bottom'   => '0',
					'left'     => '4',
					'unit'     => 'px',
					'isLinked' => false,
				),
				'condition'   => array(
					'show_separator'   => 'yes',
				),
			)
		);

		$this->add_control(
			'show_days',
			array(
				'label'     => __( 'Days', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Show', 'mas-addons-for-elementor' ),
				'label_off' => __( 'Hide', 'mas-addons-for-elementor' ),
				'default'   => 'yes',
			)
		);

		$this->add_control(
			'show_hours',
			array(
				'label'     => __( 'Hours', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Show', 'mas-addons-for-elementor' ),
				'label_off' => __( 'Hide', 'mas-addons-for-elementor' ),
				'default'   => 'yes',
			)
		);

		$this->add_control(
			'show_minutes',
			array(
				'label'     => __( 'Minutes', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Show', 'mas-addons-for-elementor' ),
				'label_off' => __( 'Hide', 'mas-addons-for-elementor' ),
				'default'   => 'yes',
			)
		);

		$this->add_control(
			'show_seconds',
			array(
				'label'     => __( 'Seconds', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Show', 'mas-addons-for-elementor' ),
				'label_off' => __( 'Hide', 'mas-addons-for-elementor' ),
				'default'   => 'yes',
			)
		);

		$this->add_control(
			'show_labels',
			array(
				'label'     => __( 'Show Label', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Show', 'mas-addons-for-elementor' ),
				'label_off' => __( 'Hide', 'mas-addons-for-elementor' ),
				'default'   => 'yes',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'custom_labels',
			array(
				'label'     => __( 'Custom Label', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => array(
					'show_labels!' => '',
				),
			)
		);

		$this->add_control(
			'label_days',
			array(
				'label'       => __( 'Days', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Days', 'mas-addons-for-elementor' ),
				'placeholder' => __( 'Days', 'mas-addons-for-elementor' ),
				'condition'   => array(
					'show_labels!'   => '',
					'custom_labels!' => '',
					'show_days'      => 'yes',
				),
			)
		);

		$this->add_control(
			'label_hours',
			array(
				'label'       => __( 'Hours', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Hours', 'mas-addons-for-elementor' ),
				'placeholder' => __( 'Hours', 'mas-addons-for-elementor' ),
				'condition'   => array(
					'show_labels!'   => '',
					'custom_labels!' => '',
					'show_hours'     => 'yes',
				),
			)
		);

		$this->add_control(
			'label_minutes',
			array(
				'label'       => __( 'Minutes', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Minutes', 'mas-addons-for-elementor' ),
				'placeholder' => __( 'Minutes', 'mas-addons-for-elementor' ),
				'condition'   => array(
					'show_labels!'   => '',
					'custom_labels!' => '',
					'show_minutes'   => 'yes',
				),
			)
		);

		$this->add_control(
			'label_seconds',
			array(
				'label'       => __( 'Seconds', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Seconds', 'mas-addons-for-elementor' ),
				'placeholder' => __( 'Seconds', 'mas-addons-for-elementor' ),
				'condition'   => array(
					'show_labels!'   => '',
					'custom_labels!' => '',
					'show_seconds'   => 'yes',
				),
			)
		);

		$this->add_control(
			'expire_actions',
			array(
				'label'       => __( 'Actions After Expire', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => array(
					'redirect' => __( 'Redirect', 'mas-addons-for-elementor' ),
					'hide'     => __( 'Hide', 'mas-addons-for-elementor' ),
					'message'  => __( 'Show Message', 'mas-addons-for-elementor' ),
				),
				'label_block' => true,
				'separator'   => 'before',
				'render_type' => 'none',
				'multiple'    => true,
			)
		);

		$this->add_control(
			'message_after_expire',
			array(
				'label'     => __( 'Message', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXTAREA,
				'separator' => 'before',
				'dynamic'   => array(
					'active' => true,
				),
				'condition' => array(
					'expire_actions' => 'message',
				),
			)
		);

		$this->add_control(
			'expire_redirect_url',
			array(
				'label'     => __( 'Redirect URL', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::URL,
				'separator' => 'before',
				'options'   => false,
				'dynamic'   => array(
					'active' => true,
				),
				'condition' => array(
					'expire_actions' => 'redirect',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_style',
			array(
				'label' => __( 'Content', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'heading_digits',
			array(
				'label' => __( 'Digits', 'mas-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'digits_color',
			array(
				'label'     => __( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-countdown-digits' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'digits_typography',
				'selector'       => '{{WRAPPER}} .mas-elementor-countdown-digits',
				'global'         => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				),
				'fields_options' => array(
					'typography'  => array( 'default' => 'yes' ),
					// Inner control name.
					'font_weight' => array(
						// Inner control settings.
						'default' => '700',
					),
					'font_family' => array(
						'default' => 'Inter,sans-serif',
					),
					'font_size'   => array(
						'default' => array(
							'unit' => 'px',
							'size' => 44,
						),
						'tablet'  => array(
							'unit' => 'px',
							'size' => 36.224,
						),
					),
					'line_height' => array(
						'default' => array(
							'unit' => 'px',
							'size' => 57.2,
						),
						'tablet'  => array(
							'unit' => 'px',
							'size' => 47.0912,
						),
					),
				),
			)
		);

		$this->add_control(
			'heading_label',
			array(
				'label'     => __( 'Label', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => __( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-countdown-label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'label_typography',
				'selector'       => '{{WRAPPER}} .mas-elementor-countdown-label',
				'global'         => array(
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				),
				'fields_options' => array(
					'typography'  => array( 'default' => 'yes' ),
					// Inner control name.
					'font_weight' => array(
						// Inner control settings.
						'default' => '700',
					),
					'font_family' => array(
						'default' => 'Inter,sans-serif',
					),
					'font_size'   => array(
						'default' => array(
							'unit' => 'px',
							'size' => 16,
						),
					),
					'line_height' => array(
						'default' => array(
							'unit' => 'px',
							'size' => 20.8,
						),
					),
				),
			)
		);

		$this->add_control(
			'heading_spacing',
			array(
				'label'     => __( 'Spacing', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'mas_countdown_width',
			array(
				'label'          => esc_html__( 'Width', 'mas-addons-for-elementor' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( '%', 'px' ),
				'range'          => array(
					'%' => array(
						'max' => 100,
					),
				),
				'default'        => array(
					'size' => 25,
					'unit' => '%',
				),
				'tablet_default' => array(
					'size' => 25,
					'unit' => '%',
				),
				'mobile_default' => array(
					'size' => 50,
					'unit' => '%',
				),
				'selectors'      => array(
					'{{WRAPPER}} .mas-elementor-countdown-wrapper .mas-countdown-inline .mas-elementor-countdown-item' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mas-elementor-countdown-wrapper .mas-countdown-block .mas-elementor-countdown-item' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'mas_countdown_space_between_label',
			array(
				'label'          => esc_html__( 'Label Spacing', 'mas-addons-for-elementor' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px' ),
				'range'          => array(
					'px' => array(
						'max' => 300,
					),
				),
				'default'        => array(
					'size' => 0,
					'unit' => 'px',
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .mas-elementor-countdown-wrapper .mas-elementor-countdown-item .mas-elementor-countdown-label.mas-countdown-item-nowrap' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mas-elementor-countdown-wrapper .mas-elementor-countdown-item .mas-elementor-countdown-label.mas-countdown-item-wrap' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'mas_countdwon_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-elementor-countdown-wrapper .mas-elementor-countdown-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'top'      => '0',
					'right'    => '12',
					'bottom'   => '0',
					'left'     => '12',
					'unit'     => 'px',
					'isLinked' => false,
				),
			)
		);

		$this->add_control(
			'disable_last_child_margin',
			array(
				'label'     => __( 'Disable Last Item Margin', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Disabled', 'mas-addons-for-elementor' ),
				'label_off' => __( 'Disable', 'mas-addons-for-elementor' ),
				'default'   => 'no',
			)
		);

		$this->add_responsive_control(
			'mas_countdwon_item_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-elementor-countdown-wrapper .mas-elementor-countdown-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .mas-elementor-countdown-wrapper.cd-mr-child-0 .mas-countdown-block  .mas-elementor-countdown-item:last-child' => 'margin-bottom: 0px;',
					'{{WRAPPER}} .mas-elementor-countdown-wrapper.cd-mr-child-0 .mas-countdown-inline  .mas-elementor-countdown-item:last-child' => 'margin-right: 0px;',
				),
				'default'    => array(
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '32',
					'left'     => '0',
					'unit'     => 'px',
					'isLinked' => true,
				),
			)
		);

		$this->add_control(
			'cd_bg_color',
			array(
				'label'     => __( 'Background Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-countdown-item' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'           => 'mas_countdown_border',
				'selector'       => '{{WRAPPER}} .mas-elementor-countdown-item',
			)
		);

		$this->add_control(
			'mas_countdown_border_radius',
			array(
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-elementor-countdown-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_expire_message_style',
			array(
				'label'     => __( 'Message', 'mas-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'expire_actions' => 'message',
				),
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'     => __( 'Alignment', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .mas-js-countdown' => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => __( 'Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-countdown-expire--message' => 'color: {{VALUE}};',
				),
				'global'    => array(
					'default' => Global_Colors::COLOR_TEXT,
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				),
				'selector' => '{{WRAPPER}} .mas-elementor-countdown-expire--message',
			)
		);

		$this->add_responsive_control(
			'message_padding',
			array(
				'label'      => __( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-elementor-countdown-expire--message' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}
	/**
	 * Render controls for this widget.
	 *
	 * @param array $instance instance of this renderer.
	 */
	public function get_strftime( $instance ) {
		$string = '';
		if ( $instance['show_days'] ) {
			$string .= $this->render_countdown_item( $instance, 'label_days', 'mas-js-cd-days' );
		}
		if ( $instance['show_hours'] ) {
			$string .= $this->render_countdown_item( $instance, 'label_hours', 'mas-js-cd-hours' );
		}
		if ( $instance['show_minutes'] ) {
			$string .= $this->render_countdown_item( $instance, 'label_minutes', 'mas-js-cd-minutes' );
		}
		if ( $instance['show_seconds'] ) {
			$string .= $this->render_countdown_item( $instance, 'label_seconds', 'mas-js-cd-seconds' );
		}

		$this->add_render_attribute( 'view', 'class', 'mas-js-countdown' );

		if ( 'inline' === $instance['view'] ) {

			$this->add_render_attribute( 'view', 'style', 'display: flex;flex-wrap:wrap' );
			$this->add_render_attribute( 'view', 'class', 'mas-countdown-inline' );
		} else {
			$this->add_render_attribute( 'view', 'class', 'mas-countdown-block' );
		}
		$a = '<div ' . $this->get_render_attribute_string( 'view' ) . ' >' . $string . '</div>';
		return $a;
	}
	/**
	 * Init labels for this widget.
	 *
	 * @var $default_countdown_labels label for the widget.
	 */
	public $default_countdown_labels;
	/**
	 * Init labels for this widget.
	 */
	public function initdefault_countdown_labels() {
		$this->default_countdown_labels = array(
			'label_months'  => __( 'Months', 'mas-addons-for-elementor' ),
			'label_weeks'   => __( 'Weeks', 'mas-addons-for-elementor' ),
			'label_days'    => __( 'Days', 'mas-addons-for-elementor' ),
			'label_hours'   => __( 'Hours', 'mas-addons-for-elementor' ),
			'label_minutes' => __( 'Minutes', 'mas-addons-for-elementor' ),
			'label_seconds' => __( 'Seconds', 'mas-addons-for-elementor' ),
		);
	}
	/**
	 * Get default label for this widget.
	 */
	public function getdefault_countdown_labels() {
		if ( ! $this->default_countdown_labels ) {
			$this->initdefault_countdown_labels();
		}

		return $this->default_countdown_labels;
	}
	/**
	 * Render for this widget.
	 *
	 * @param string $instance instance.
	 * @param string $label label for the widget.
	 * @param string $part_class part class.
	 */
	public function render_countdown_item( $instance, $label, $part_class ) {

		$digits_html = 'wrap' === $instance['single_view'] ? '<span class="mas-elementor-countdown-digits ' . $part_class . '" style="display:block">0</span>' : '<span class="mas-elementor-countdown-digits ' . $part_class . '">0</span>';
		$string      = '<div class="mas-elementor-countdown-item">' . $digits_html;

		if ( $instance['show_labels'] ) {
			$default_labels = $this->getdefault_countdown_labels();
			$label          = ( $instance['custom_labels'] ) ? $instance[ $label ] : $default_labels[ $label ];
			$label_html     = 'wrap' === $instance['single_view'] ? ' <span class="mas-elementor-countdown-label mas-countdown-item-wrap" style="display:block">' . $label . '</span>' : ' <span class="mas-elementor-countdown-label mas-countdown-item-nowrap">' . $label . '</span>';
			$string        .= $label_html;
		}

		$string .= '</div>';

		return $string;
	}
	/**
	 * Register evergreen controls for this widget.
	 *
	 * @param string $instance instance.
	 */
	public function get_evergreen_interval( $instance ) {
		$hours              = empty( $instance['evergreen_counter_hours'] ) ? 0 : ( $instance['evergreen_counter_hours'] * HOUR_IN_SECONDS );
		$minutes            = empty( $instance['evergreen_counter_minutes'] ) ? 0 : ( $instance['evergreen_counter_minutes'] * MINUTE_IN_SECONDS );
		$evergreen_interval = $hours + $minutes;

		return $evergreen_interval;
	}
	/**
	 * Get action for this widget.
	 *
	 * @param string $settings instance.
	 */
	public function get_actions( $settings ) {
		if ( empty( $settings['expire_actions'] ) || ! is_array( $settings['expire_actions'] ) ) {
			return false;
		}

		$actions = array();

		foreach ( $settings['expire_actions'] as $action ) {
			$action_to_run = array( 'type' => $action );
			if ( 'redirect' === $action ) {
				if ( empty( $settings['expire_redirect_url']['url'] ) ) {
					continue;
				}
				$action_to_run['redirect_url'] = $settings['expire_redirect_url']['url'];
			}
			$actions[] = $action_to_run;
		}

		return $actions;
	}

	/**
	 * Register frontend script.
	 */
	public function register_editor_scripts() {
		if ( Plugin::$instance->editor->is_edit_mode() ) :
			?>
			<script type="text/javascript">
			var countdown = (() => {
				let coundown = document.querySelectorAll('.mas-elementor-countdown-wrapper');

				if (coundown == null) return;

				for (let i = 0; i < coundown.length; i++) {

				let endDate = coundown[i].dataset.date,
					daysVal = coundown[i].querySelector('.mas-js-cd-days'),
					hoursVal = coundown[i].querySelector('.mas-js-cd-hours'),
					minutesVal = coundown[i].querySelector('.mas-js-cd-minutes'),
					secondsVal = coundown[i].querySelector('.mas-js-cd-seconds'),
					days, hours, minutes, seconds;

				endDate = new Date(endDate).getTime();

				if (isNaN(endDate)) return;

				var x = setInterval(function () {
					let startDate = new Date().getTime();
					let timeRemaining = parseInt((endDate - startDate) / 1000);

					if (timeRemaining >= 0) {
						days = parseInt(timeRemaining / 86400);
						timeRemaining = (timeRemaining % 86400);

						hours = parseInt(timeRemaining / 3600);
						timeRemaining = (timeRemaining % 3600);

						minutes = parseInt(timeRemaining / 60);
						timeRemaining = (timeRemaining % 60);

						seconds = parseInt(timeRemaining);

						if (daysVal != null) {
							daysVal.innerHTML = parseInt(days, 10);
						}
						if (hoursVal != null) {
							hoursVal.innerHTML = hours < 10 ? '0' + hours : hours;
						}
						if (minutesVal != null) {
							minutesVal.innerHTML = minutes < 10 ? '0' + minutes : minutes;
						}
						if (secondsVal != null) {
							secondsVal.innerHTML = seconds < 10 ? '0' + seconds : seconds;
						}

					} else {
					var html  = coundown[i].querySelector(".new-message").innerHTML;
					var htmla = coundown[i].querySelector(".new-message");
					var message = htmla.dataset.message;
					var link  = htmla.getAttribute('href');
					if ( message.includes('hide') ) {
						coundown[i].querySelector(".mas-js-countdown").innerHTML = '';
					}
					if ( message.includes('message') ) {
						coundown[i].querySelector(".mas-js-countdown").innerHTML = html;
					}
					}
				}, 1000);
				}
			})();
			</script>
			<?php
		endif;

	}
	/**
	 * Render.
	 *
	 * @return void
	 */
	protected function render() {
		mas_elementor_get_template( 'widgets/countdown/countdown.php', array( 'widget' => $this ) );
		$this->register_editor_scripts();
	}
}
