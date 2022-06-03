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
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Countdown', 'mas-elementor' );
	}

	/**
	 * Get the icon for the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-countdown';
	}

	/**
	 * Get the keywords associated with the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return [ 'countdown', 'number', 'timer', 'time', 'date', 'evergreen' ];
	}

	/**
	 * Register controls for this widget.
	 * 
	 * @return void
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_countdown',
			[
				'label' => esc_html__( 'Countdown', 'mas-elementor' ),
			]
		);

		$this->add_control(
			'countdown_type',
			[
				'label'     => esc_html__( 'Type', 'mas-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'due_date' => esc_html__( 'Due Date', 'mas-elementor' ),
					'evergreen' => esc_html__( 'Evergreen Timer', 'mas-elementor' ),
				],
				'default'   => 'due_date',
				'condition' => [
					'countdown_type' => 'evergreen',
				],
			]
		);

		$this->add_control(
			'due_date',
			[
				'label'       => esc_html__( 'Due Date', 'mas-elementor' ),
				'type'        => Controls_Manager::DATE_TIME,
				'default'     => gmdate( 'Y-m-d H:i', strtotime( '+1 month' ) + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) ),
				/* translators: %s: Time zone. */
				'description' => sprintf( esc_html__( 'Date set according to your timezone: %s.', 'mas-elementor' ), Utils::get_timezone_string() ),
				'condition'   => [
					'countdown_type' => 'due_date',
				],
			]
		);

		$this->add_control(
			'evergreen_counter_hours',
			[
				'label'       => esc_html__( 'Hours', 'mas-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 47,
				'placeholder' => esc_html__( 'Hours', 'mas-elementor' ),
				'condition'   => [
					'countdown_type' => 'evergreen',
				],
			]
		);

		$this->add_control(
			'evergreen_counter_minutes',
			[
				'label'       => esc_html__( 'Minutes', 'mas-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 59,
				'placeholder' => esc_html__( 'Minutes', 'mas-elementor' ),
				'condition'   => [
					'countdown_type' => 'evergreen',
				],
			]
		);

		$this->add_control(
			'show_days',
			[
				'label'     => esc_html__( 'Days', 'mas-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Show', 'mas-elementor' ),
				'label_off' => esc_html__( 'Hide', 'mas-elementor' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'show_hours',
			[
				'label'     => esc_html__( 'Hours', 'mas-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Show', 'mas-elementor' ),
				'label_off' => esc_html__( 'Hide', 'mas-elementor' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'show_minutes',
			[
				'label'     => esc_html__( 'Minutes', 'mas-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Show', 'mas-elementor' ),
				'label_off' => esc_html__( 'Hide', 'mas-elementor' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'show_seconds',
			[
				'label'     => esc_html__( 'Seconds', 'mas-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Show', 'mas-elementor' ),
				'label_off' => esc_html__( 'Hide', 'mas-elementor' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'show_labels',
			[
				'label'     => esc_html__( 'Show Label', 'mas-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Show', 'mas-elementor' ),
				'label_off' => esc_html__( 'Hide', 'mas-elementor' ),
				'default'   => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'custom_labels',
			[
				'label'     => esc_html__( 'Custom Label', 'mas-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => [
					'show_labels!' => '',
				],
			]
		);

		$this->add_control(
			'label_days',
			[
				'label'       => esc_html__( 'Days', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Days', 'mas-elementor' ),
				'placeholder' => esc_html__( 'Days', 'mas-elementor' ),
				'condition'   => [
					'show_labels!'   => '',
					'custom_labels!' => '',
					'show_days'      => 'yes',
				],
			]
		);

		$this->add_control(
			'label_hours',
			[
				'label'       => esc_html__( 'Hours', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Hours', 'mas-elementor' ),
				'placeholder' => esc_html__( 'Hours', 'mas-elementor' ),
				'condition'   => [
					'show_labels!'   => '',
					'custom_labels!' => '',
					'show_hours'     => 'yes',
				],
			]
		);

		$this->add_control(
			'label_minutes',
			[
				'label'       => esc_html__( 'Minutes', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Minutes', 'mas-elementor' ),
				'placeholder' => esc_html__( 'Minutes', 'mas-elementor' ),
				'condition'   => [
					'show_labels!'   => '',
					'custom_labels!' => '',
					'show_minutes'   => 'yes',
				],
			]
		);

		$this->add_control(
			'label_seconds',
			[
				'label'       => esc_html__( 'Seconds', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Seconds', 'mas-elementor' ),
				'placeholder' => esc_html__( 'Seconds', 'mas-elementor' ),
				'condition'   => [
					'show_labels!'   => '',
					'custom_labels!' => '',
					'show_seconds'   => 'yes',
				],
			]
		);

		$this->add_control(
			'expire_actions',
			[
				'label'       => __( 'Actions After Expire', 'mas-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'none'     => __( 'None', 'mas-elementor' ),
					'redirect' => __( 'Redirect', 'mas-elementor' ),
					'hide'     => __( 'Hide', 'mas-elementor' ),
					'message'  => __( 'Show Message', 'mas-elementor' ),
				],
				'default'     => 'none',
				'label_block' => true,
				'separator'   => 'before',
				'render_type' => 'none',
				'multiple'    => true,
			]
		);

		$this->add_control(
			'message_after_expire',
			[
				'label'     => __( 'Message', 'mas-elementor' ),
				'type'      => Controls_Manager::TEXTAREA,
				'separator' => 'before',
				'dynamic'   => [
					'active' => true,
				],
				'condition' => [
					'expire_actions' => 'message',
				],
			]
		);

		$this->add_control(
			'expire_redirect_url',
			[
				'label'     => __( 'Redirect URL', 'mas-elementor' ),
				'type'      => Controls_Manager::URL,
				'separator' => 'before',
				'options'   => false,
				'dynamic'   => [
					'active' => true,
				],
				'condition' => [
					'expire_actions' => 'redirect',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_style',
			[
				'label' => esc_html__( 'Content', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'heading_item',
			[
				'label' => esc_html__( 'Item', 'mas-elementor' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'heading_digits',
			[
				'label'     => esc_html__( 'Digits', 'mas-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'digits_color',
			[
				'label'     => esc_html__( 'Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .countdown-value' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'digits_typography',
				'selector' => '{{WRAPPER}} .countdown-value',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->add_control(
			'heading_label',
			[
				'label'     => esc_html__( 'Label', 'mas-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'label_color',
			[
				'label'     => esc_html__( 'Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .countdown-label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'label_typography',
				'selector' => '{{WRAPPER}} .countdown-label',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Get Strftime.
	 *
	 * @param  array $instance The instance information.
	 */
	public function get_strftime( $instance ) {
		$string = '';
		if ( $instance['show_days'] ) {
			$string .= $this->render_countdown_item( $instance, 'label_days', 'elementor-countdown-days' );
		}
		if ( $instance['show_hours'] ) {
			$string .= $this->render_countdown_item( $instance, 'label_hours', 'elementor-countdown-hours' );
		}
		if ( $instance['show_minutes'] ) {
			$string .= $this->render_countdown_item( $instance, 'label_minutes', 'elementor-countdown-minutes' );
		}
		if ( $instance['show_seconds'] ) {
			$string .= $this->render_countdown_item( $instance, 'label_seconds', 'elementor-countdown-seconds' );
		}

		return $string;
	}

	/**
	 * $_default_countdown_labels.
	 *
	 * @var mixed
	 */
	public $_default_countdown_labels;

	/**
	 * Init Default Countdown Labels .
	 */
	public function init_default_countdown_labels() {
		$this->_default_countdown_labels = [
			'label_months'  => esc_html__( 'Months', 'mas-elementor' ),
			'label_weeks'   => esc_html__( 'Weeks', 'mas-elementor' ),
			'label_days'    => esc_html__( 'Days', 'mas-elementor' ),
			'label_hours'   => esc_html__( 'Hours', 'mas-elementor' ),
			'label_minutes' => esc_html__( 'Minutes', 'mas-elementor' ),
			'label_seconds' => esc_html__( 'Seconds', 'mas-elementor' ),
		];
	}

	/**
	 * Get The Default Countdown Labels.
	 */
	public function get_default_countdown_labels() {

		if ( ! $this->_default_countdown_labels ) {
			$this->init_default_countdown_labels();
		}

		return $this->_default_countdown_labels;
	}

	/**
	 * Render the countdown items.
	 *
	 * @param  array $instance      The instance information.
	 * @param  mixed $label         The label.
	 * @param  mixed $part_class    The Part Class.
	 */
	public function render_countdown_item( $instance, $label, $part_class ) {
		$string = '<div class="elementor-countdown-item"><span class="elementor-countdown-digits ' . $part_class . '"></span>';

		if ( $instance['show_labels'] ) {
			$default_labels = $this->get_default_countdown_labels();
			$label = ( $instance['custom_labels'] ) ? $instance[ $label ] : $default_labels[ $label ];
			$string .= ' <span class="elementor-countdown-label">' . $label . '</span>';
		}

		$string .= '</div>';

		return $string;
	}

	/**
	 * Get Evergreen Interval.
	 *
	 * @param  array $instance The instance information.
	 */
	public function get_evergreen_interval( $instance ) {
		$hours              = empty( $instance['evergreen_counter_hours'] ) ? 0 : ( $instance['evergreen_counter_hours'] * HOUR_IN_SECONDS );
		$minutes            = empty( $instance['evergreen_counter_minutes'] ) ? 0 : ( $instance['evergreen_counter_minutes'] * MINUTE_IN_SECONDS );
		$evergreen_interval = $hours + $minutes;

		return $evergreen_interval;
	}

	/**
	 * Get Actions.
	 *
	 * @param  array $settings The Widget settings.
	 */
	public function get_actions( $settings ) {
		if ( empty( $settings['expire_actions'] ) || ! is_array( $settings['expire_actions'] ) ) {
			return false;
		}

		$actions = [];

		foreach ( $settings['expire_actions'] as $action ) {
			$action_to_run = [ 'type' => $action ];
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
	 * Render.
	 *
	 * @return void
	 */
	protected function render() {
		mas_elementor_get_template( 'widgets/countdown/countdown.php', array( 'widget' => $this ) );
	}

}
