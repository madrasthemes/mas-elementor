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
		return 'countdown';
	}

	/**
	 * Get the name of the title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Countdown', 'mas-elementor' );
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
				'label' => __( 'Countdown', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'countdown_type',
			array(
				'label'   => __( 'Type', 'mas-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'due_date'  => __( 'Due Date', 'mas-elementor' ),
					'evergreen' => __( 'Evergreen Timer', 'mas-elementor' ),
				),
				'default' => 'due_date',
			)
		);

		$this->add_control(
			'view',
			array(
				'label'   => esc_html__( 'Layout', 'mas-elementor' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'inline',
				'options' => array(
					'traditional' => array(
						'title' => esc_html__( 'Default', 'mas-elementor' ),
						'icon'  => 'eicon-editor-list-ul',
					),
					'inline'      => array(
						'title' => esc_html__( 'Inline', 'mas-elementor' ),
						'icon'  => 'eicon-ellipsis-h',
					),
				),
			)
		);

		$this->add_control(
			'due_date',
			array(
				'label'       => __( 'Due Date', 'mas-elementor' ),
				'type'        => Controls_Manager::DATE_TIME,
				'default'     => gmdate( 'Y-m-d H:i', strtotime( '+1 month' ) + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) ),
				/* translators: %s: Time zone. */
				'description' => sprintf( __( 'Date set according to your timezone: %s.', 'mas-elementor' ), Utils::get_timezone_string() ),
				'condition'   => array(
					'countdown_type' => 'due_date',
				),
			)
		);

		$this->add_control(
			'evergreen_counter_hours',
			array(
				'label'       => __( 'Hours', 'mas-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 47,
				'placeholder' => __( 'Hours', 'mas-elementor' ),
				'condition'   => array(
					'countdown_type' => 'evergreen',
				),
			)
		);

		$this->add_control(
			'evergreen_counter_minutes',
			array(
				'label'       => __( 'Minutes', 'mas-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 59,
				'placeholder' => __( 'Minutes', 'mas-elementor' ),
				'condition'   => array(
					'countdown_type' => 'evergreen',
				),
			)
		);

		$this->add_control(
			'single_view',
			array(
				'label'   => esc_html__( 'Text Layout', 'mas-elementor' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'wrap',
				'options' => array(
					'wrap'   => array(
						'title' => esc_html__( 'Default', 'mas-elementor' ),
						'icon'  => 'eicon-ellipsis-h',
					),
					'nowrap' => array(
						'title' => esc_html__( 'Inline', 'mas-elementor' ),
						'icon'  => 'eicon-editor-list-ul',
					),
				),
			)
		);

		$this->add_responsive_control(
			'countdown_text_align',
			array(
				'label'     => esc_html__( 'Text Alignment', 'mas-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'mas-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => esc_html__( 'Center', 'mas-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'   => array(
						'title' => esc_html__( 'Right', 'mas-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => esc_html__( 'Justified', 'mas-elementor' ),
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
			'show_days',
			array(
				'label'     => __( 'Days', 'mas-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Show', 'mas-elementor' ),
				'label_off' => __( 'Hide', 'mas-elementor' ),
				'default'   => 'yes',
			)
		);

		$this->add_control(
			'show_hours',
			array(
				'label'     => __( 'Hours', 'mas-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Show', 'mas-elementor' ),
				'label_off' => __( 'Hide', 'mas-elementor' ),
				'default'   => 'yes',
			)
		);

		$this->add_control(
			'show_minutes',
			array(
				'label'     => __( 'Minutes', 'mas-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Show', 'mas-elementor' ),
				'label_off' => __( 'Hide', 'mas-elementor' ),
				'default'   => 'yes',
			)
		);

		$this->add_control(
			'show_seconds',
			array(
				'label'     => __( 'Seconds', 'mas-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Show', 'mas-elementor' ),
				'label_off' => __( 'Hide', 'mas-elementor' ),
				'default'   => 'yes',
			)
		);

		$this->add_control(
			'show_labels',
			array(
				'label'     => __( 'Show Label', 'mas-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Show', 'mas-elementor' ),
				'label_off' => __( 'Hide', 'mas-elementor' ),
				'default'   => 'yes',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'custom_labels',
			array(
				'label'     => __( 'Custom Label', 'mas-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => array(
					'show_labels!' => '',
				),
			)
		);

		$this->add_control(
			'label_days',
			array(
				'label'       => __( 'Days', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Days', 'mas-elementor' ),
				'placeholder' => __( 'Days', 'mas-elementor' ),
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
				'label'       => __( 'Hours', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Hours', 'mas-elementor' ),
				'placeholder' => __( 'Hours', 'mas-elementor' ),
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
				'label'       => __( 'Minutes', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Minutes', 'mas-elementor' ),
				'placeholder' => __( 'Minutes', 'mas-elementor' ),
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
				'label'       => __( 'Seconds', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Seconds', 'mas-elementor' ),
				'placeholder' => __( 'Seconds', 'mas-elementor' ),
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
				'label'       => __( 'Actions After Expire', 'mas-elementor' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => array(
					'redirect' => __( 'Redirect', 'mas-elementor' ),
					'hide'     => __( 'Hide', 'mas-elementor' ),
					'message'  => __( 'Show Message', 'mas-elementor' ),
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
				'label'     => __( 'Message', 'mas-elementor' ),
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
				'label'     => __( 'Redirect URL', 'mas-elementor' ),
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
				'label' => __( 'Content', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'heading_digits',
			array(
				'label' => __( 'Digits', 'mas-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'digits_color',
			array(
				'label'     => __( 'Color', 'mas-elementor' ),
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
				'label'     => __( 'Label', 'mas-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => __( 'Color', 'mas-elementor' ),
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
				'label'     => __( 'Spacing', 'mas-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'mas_countdown_width',
			array(
				'label'          => esc_html__( 'Width', 'mas-elementor' ),
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
				'label'          => esc_html__( 'Label Spacing', 'mas-elementor' ),
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
				'label'      => esc_html__( 'Padding', 'mas-elementor' ),
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
					'unit'     => '0',
					'isLinked' => false,
				),
			)
		);

		$this->add_responsive_control(
			'mas_countdwon_item_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-elementor-countdown-wrapper .mas-elementor-countdown-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '32',
					'left'     => '0',
					'unit'     => '0',
					'isLinked' => true,
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_expire_message_style',
			array(
				'label'     => __( 'Message', 'mas-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'expire_actions' => 'message',
				),
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'     => __( 'Alignment', 'mas-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'mas-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'mas-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'mas-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-countdown-expire--message' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => __( 'Text Color', 'mas-elementor' ),
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
				'label'      => __( 'Padding', 'mas-elementor' ),
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
			'label_months'  => __( 'Months', 'mas-elementor' ),
			'label_weeks'   => __( 'Weeks', 'mas-elementor' ),
			'label_days'    => __( 'Days', 'mas-elementor' ),
			'label_hours'   => __( 'Hours', 'mas-elementor' ),
			'label_minutes' => __( 'Minutes', 'mas-elementor' ),
			'label_seconds' => __( 'Seconds', 'mas-elementor' ),
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
			
					setInterval(calculate, 1000);
			
					function calculate() {
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
						return;
						}
					}
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
