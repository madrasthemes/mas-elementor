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
		return array( 'count-script', 'countdown-script' );
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
					'{{WRAPPER}} .elementor-countdown-digits' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'digits_typography',
				'selector' => '{{WRAPPER}} .elementor-countdown-digits',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
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
					'{{WRAPPER}} .elementor-countdown-label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typography',
				'selector' => '{{WRAPPER}} .elementor-countdown-label',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
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
					'{{WRAPPER}} .elementor-countdown-expire--message' => 'text-align: {{VALUE}};',
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
					'{{WRAPPER}} .elementor-countdown-expire--message' => 'color: {{VALUE}};',
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
				'selector' => '{{WRAPPER}} .elementor-countdown-expire--message',
			)
		);

		$this->add_responsive_control(
			'message_padding',
			array(
				'label'      => __( 'Padding', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-countdown-expire--message' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
			$string .= $this->render_countdown_item( $instance, 'label_days', 'js-cd-days' );
		}
		if ( $instance['show_hours'] ) {
			$string .= $this->render_countdown_item( $instance, 'label_hours', 'js-cd-hours' );
		}
		if ( $instance['show_minutes'] ) {
			$string .= $this->render_countdown_item( $instance, 'label_minutes', 'js-cd-minutes' );
		}
		if ( $instance['show_seconds'] ) {
			$string .= $this->render_countdown_item( $instance, 'label_seconds', 'js-cd-seconds' );
		}
		$a = '<div class="js-countdown row">' . $string . '</div>';
		return $a;
	}
	/**
	 * Init labels for this widget.
	 *
	 * @var $_default_countdown_labels label for the widget.
	 */
	public $_default_countdown_labels;
	/**
	 * Init labels for this widget.
	 */
	public function init_default_countdown_labels() {
		$this->_default_countdown_labels = array(
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
	public function get_default_countdown_labels() {
		if ( ! $this->_default_countdown_labels ) {
			$this->init_default_countdown_labels();
		}

		return $this->_default_countdown_labels;
	}
	/**
	 * Render for this widget.
	 *
	 * @param string $instance instance.
	 * @param string $label label for the widget.
	 * @param string $part_class part class.
	 */
	public function render_countdown_item( $instance, $label, $part_class ) {
		$string = '<div class="col-6 col-sm-3">
						<div class="elementor-countdown-item">
							<span class="elementor-countdown-digits ' . $part_class . '">0</span>';

		if ( $instance['show_labels'] ) {
			$default_labels = $this->get_default_countdown_labels();
			$label          = ( $instance['custom_labels'] ) ? $instance[ $label ] : $default_labels[ $label ];
			$string        .= ' <span class="elementor-countdown-label">' . $label . '</span>';
		}

		$string .= '</div></div>';

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
				var el = document.querySelector('.mas-elementor-countdown-wrapper');
				var dated = el.dataset.date;
				var countDownDate = new Date( dated ).getTime();
				// const oneYearFromNow = new Date()

				document.querySelectorAll('.js-countdown').forEach(item => {
					const days = item.querySelector('.js-cd-days'),
					hours = item.querySelector('.js-cd-hours'),
					minutes = item.querySelector('.js-cd-minutes'),
					seconds = item.querySelector('.js-cd-seconds')

					countdown(countDownDate,
					ts => {
						days.innerHTML = ts.days
						hours.innerHTML = ts.hours
						minutes.innerHTML = ts.minutes
						seconds.innerHTML = ts.seconds
					},
					countdown.DAYS | countdown.HOURS | countdown.MINUTES | countdown.SECONDS
					)
				})

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
