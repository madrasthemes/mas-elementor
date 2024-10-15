<?php
/**
 * Countdown Widget.
 *
 * @package mas-elementor
 */

namespace MASElementor\Modules\Scrollspy\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use MASElementor\Base\Base_Widget;
use Elementor\Plugin;
use MASElementor\Plugin as MASPlugin;
use Elementor\Widget_Icon_List;
use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * CountDown Widget.
 */
class Scrollspy extends Widget_Icon_List {

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-scrollspy';
	}

	/**
	 * Get the name of the title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Scroll Spy', 'mas-addons-for-elementor' );
	}

	/**
	 * Get the name of the icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-bullet-list';
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
		return array( 'scrollspy', 'number', 'spy', 'tab', 'scroll' );
	}

	/**
	 * Get the script dependencies for this widget.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array( 'mas-scroll-script', 'scrollspy-init-script' );
	}

	/**
	 * Register Controls.
	 *
	 * @return void
	 */
	public function register_controls() {
		parent::register_controls();

		$this->start_controls_section(
			'section_scrollspy',
			array(
				'label' => __( 'ScrollSpy', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'scrollspy_id',
			array(
				'label' => esc_html__( 'Scrollspy ID', 'mas-addons-for-elementor' ),
				'type'  => Controls_Manager::TEXT,

			)
		);

		$this->add_control(
			'parent_id',
			array(
				'label' => esc_html__( 'Parent ID', 'mas-addons-for-elementor' ),
				'type'  => Controls_Manager::TEXT,

			)
		);

		$this->add_control(
			'target_id',
			array(
				'label' => esc_html__( 'Data Target ID', 'mas-addons-for-elementor' ),
				'type'  => Controls_Manager::TEXT,

			)
		);

		$this->add_control(
			'breakpoint',
			array(
				'label'   => esc_html__( 'Breakpoint', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'xs' => 'XS',
					'sm' => 'SM',
					'md' => 'MD',
					'lg' => 'LG',
				),
				'default' => 'md',

			)
		);

		$this->add_control(
			'start_point_id',
			array(
				'label' => esc_html__( 'Startpoint ID', 'mas-addons-for-elementor' ),
				'type'  => Controls_Manager::TEXT,

			)
		);

		$this->add_control(
			'end_point_id',
			array(
				'label' => esc_html__( 'Endpoint ID', 'mas-addons-for-elementor' ),
				'type'  => Controls_Manager::TEXT,

			)
		);

		$this->add_control(
			'offset',
			array(
				'label' => esc_html__( 'Offset', 'mas-addons-for-elementor' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),

			)
		);
		$this->end_controls_section();

		$this->remove_control( 'section_text_style' );

		$this->start_controls_section(
			'scroll_section_text_style',
			array(
				'label' => esc_html__( 'Text', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs(
			'scrollspy_tabs'
		);

		$this->start_controls_tab(
			'scrollspy_tab_normal',
			array(
				'label' => esc_html__( 'Normal', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'scroll_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} a' => 'color: {{VALUE}};',
				),
				'global'    => array(
					'default' => Global_Colors::COLOR_SECONDARY,
				),
			)
		);

		$this->add_control(
			'scroll_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} a' => 'border-color: {{VALUE}};',
				),
				'global'    => array(
					'default' => Global_Colors::COLOR_SECONDARY,
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'scrollspy_tab_active',
			array(
				'label' => esc_html__( 'Active', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'scroll_text_color_active',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .elementor-icon-list-item a.active' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'scroll_border_color_active',
			array(
				'label'     => esc_html__( 'Border Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .elementor-icon-list-item a.active' => 'border-color: {{VALUE}} !important;',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'scroll_text_color_hover',
			array(
				'label'     => esc_html__( 'Text Hover Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .elementor-icon-list-item:hover a' => 'color: {{VALUE}} !important;',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'scroll_text_indent',
			array(
				'label'     => esc_html__( 'Text Indent', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-icon-list-icon' => ! is_rtl() ? 'padding-right: {{SIZE}}{{UNIT}};' : 'padding-left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'scroll_icon_typography',
				'selector' => '{{WRAPPER}} .elementor-icon-list-item > .elementor-icon-list-text, {{WRAPPER}} .elementor-icon-list-item > a',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render.
	 *
	 * @return void
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();
		$args     = array(
			'parentSelector' => '#' . $settings['parent_id'],
			'targetSelector' => '#' . $settings['target_id'],
			'breakpoint'     => $settings['breakpoint'],
			'startPoint'     => '#' . $settings['start_point_id'],
			'endPoint'       => '#' . $settings['end_point_id'],
			'scrollspyId'    => '#' . $settings['scrollspy_id'],
		);

		if ( isset( $settings['offset']['size'] ) ) {
			$args['stickyOffsetTop'] = $settings['offset']['size'];
		}

		$this->add_render_attribute(
			'icon_list',
			array(
				'id'                           => 'navbarSettings',
				'class'                        => 'js-sticky-block js-scrollspy',
				'data-hs-sticky-block-options' => wp_json_encode( $args ),
			)
		);

		$fallback_defaults = array(
			'fa fa-check',
			'fa fa-times',
			'fa fa-dot-circle-o',
		);

		$this->add_render_attribute( 'icon_list', 'class', 'elementor-icon-list-items' );
		$this->add_render_attribute( 'list_item', 'class', 'elementor-icon-list-item' );

		if ( 'inline' === $settings['view'] ) {
			$this->add_render_attribute( 'icon_list', 'class', 'elementor-inline-items' );
			$this->add_render_attribute( 'list_item', 'class', 'elementor-inline-item' );
		}
		$this->add_render_attribute( 'scrollspy_wrap', 'id', $settings['parent_id'] );
		?>
		<div <?php $this->print_render_attribute_string( 'scrollspy_wrap' ); ?>>
			<ul <?php $this->print_render_attribute_string( 'icon_list' ); ?>>
				<?php
				foreach ( $settings['icon_list'] as $index => $item ) :
					$repeater_setting_key = $this->get_repeater_setting_key( 'text', 'icon_list', $index );

					$this->add_inline_editing_attributes( $repeater_setting_key );
					$migration_allowed = Icons_Manager::is_migration_allowed();
					?>
					<li <?php $this->print_render_attribute_string( 'list_item' ); ?>>
						<?php
						if ( ! empty( $item['link']['url'] ) ) {
							$link_key = 'link_' . $index;

							$this->add_link_attributes( $link_key, $item['link'] );
							?>
							<a <?php $this->print_render_attribute_string( $link_key ); ?>>

							<?php
						}

						// add old default.
						if ( ! isset( $item['icon'] ) && ! $migration_allowed ) {
							$item['icon'] = isset( $fallback_defaults[ $index ] ) ? $fallback_defaults[ $index ] : 'fa fa-check';
						}

						$migrated = isset( $item['__fa4_migrated']['selected_icon'] );
						$is_new   = ! isset( $item['icon'] ) && $migration_allowed;
						if ( ! empty( $item['icon'] ) || ( ! empty( $item['selected_icon']['value'] ) && $is_new ) ) :
							?>
							<span class="elementor-icon-list-icon">
								<?php
								if ( $is_new || $migrated ) {
									Icons_Manager::render_icon( $item['selected_icon'], array( 'aria-hidden' => 'true' ) );
								} else {
									?>
										<i class="<?php echo esc_attr( $item['icon'] ); ?>" aria-hidden="true"></i>
								<?php } ?>
							</span>
						<?php endif; ?>
						<?php $this->print_unescaped_setting( 'text', 'icon_list', $index ); ?>
						<?php if ( ! empty( $item['link']['url'] ) ) : ?>
							</a>
						<?php endif; ?>
					</li>
					<?php
				endforeach;
				?>
			</ul>
		</div>
		<?php
	}
}
