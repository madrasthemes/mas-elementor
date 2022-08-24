<?php
/**
 * The Mas Nav Tab Widget.
 *
 * @package MASElementor/Modules/MasNavTab/Widgets
 */

namespace MASElementor\Modules\MasNavTabs\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use MASElementor\Base\Base_Widget;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Repeater;
use MASElementor\Core\Controls_Manager as MAS_Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * MAS Nav Tabs Elementor Widget.
 */
class Mas_Nav_Tabs extends Base_Widget {

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-nav-tabs';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'MAS Nav Tabs', 'mas-elementor' );
	}

	/**
	 * Get the icon for the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-tabs';
	}

	/**
	 * Get the keywords associated with the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'nav', 'tabs', 'tabs', 'mas' );
	}

	/**
	 * Register controls for this widget.
	 */
	protected function register_controls() {
		parent::register_controls();

		$this->start_controls_section(
			'section_list',
			array(
				'label' => esc_html__( 'Nav Tabs List', 'mas-elementor' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'list',
			array(
				'label'       => esc_html__( 'List Item', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'List Item', 'mas-elementor' ),
				'default'     => esc_html__( 'List Item', 'mas-elementor' ),
			)
		);

		$repeater->add_control(
			'enable_link',
			array(
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Link?', 'mas-elementor' ),
				'default'   => 'no',
				'label_off' => esc_html__( 'Show', 'mas-elementor' ),
				'label_on'  => esc_html__( 'Hide', 'mas-elementor' ),
			)
		);

		$repeater->add_control(
			'list_url',
			array(
				'label'       => esc_html__( 'List Link', 'mas-elementor' ),
				'default'     => array(
					'url' => esc_url( '#', 'mas-elementor' ),
				),
				'placeholder' => esc_html__( 'https://your-link.com', 'mas-elementor' ),
				'type'        => Controls_Manager::URL,
				'condition'   => array(
					'enable_link' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'content_id',
			array(
				'label'       => esc_html__( 'Tab Content ID', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Tab Content Id', 'mas-elementor' ),
				'condition'   => array(
					'enable_link!' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'icon',
			array(
				'label'   => esc_html__( 'Icon', 'mas-elementor' ),
				'type'    => Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'bx bx-star ',
					'library' => 'solid',
				),
			)
		);

		$repeater->add_control(
			'icon_class',
			array(
				'label'       => esc_html__( 'Icon Class', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter Your Icon Class', 'mas-elementor' ),
			)
		);

		$repeater->add_control(
			'description_text',
			array(
				'label'       => esc_html__( 'Desciption', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'mas-elementor' ),
				'placeholder' => esc_html__( 'Enter your description', 'mas-elementor' ),
				'rows'        => 10,
				'separator'   => 'none',
				'show_label'  => true,
			)
		);

		$this->add_control(
			'nav_tabs',
			array(
				'label'       => esc_html__( 'Nav List', 'mas-elementor' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'title' => esc_html__( 'Featured Jobs', 'mas-elementor' ),
					),
					array(
						'title' => esc_html__( 'Recent Jobs', 'mas-elementor' ),
					),
				),
				'title_field' => '{{{ list }}}',
			)
		);

		$this->add_control(
			'view',
			array(
				'label'   => esc_html__( 'View', 'mas-elementor' ),
				'type'    => Controls_Manager::HIDDEN,
				'default' => 'traditional',
			)
		);

		$this->add_control(
			'type',
			array(
				'label'        => esc_html__( 'Position', 'mas-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'horizontal',
				'options'      => array(
					'horizontal' => esc_html__( 'Horizontal', 'mas-elementor' ),
					'vertical'   => esc_html__( 'Vertical', 'mas-elementor' ),
				),
				'prefix_class' => 'mas-elementor-nav-tab-layout-',
				'separator'    => 'before',
			)
		);

		$this->end_controls_section();

		// Section for List Controls in STYLE Tab.
		$this->start_controls_section(
			'list_section',
			array(
				'label' => esc_html__( 'UL', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'ul_wrap',
			array(
				'label'       => esc_html__( 'UL Wrap Class', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter Your Wrap Class', 'mas-elementor' ),
				'default'     => 'nav nav-tabs',
			)
		);

		// Padding Controls.
		$this->add_responsive_control(
			'mas_nav_tab_ul_padding',
			array(
				'label'      => esc_html__( 'UL Padding', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-elementor-nav-tab' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Margin Controls.
		$this->add_responsive_control(
			'mas_nav_tab_ul_margin',
			array(
				'label'      => esc_html__( 'UL Margin', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-elementor-nav-tab' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Border Controls.
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'mas_nav_tab_ul_border',
				'selector'  => '{{WRAPPER}} .mas-elementor-nav-tab',
				'separator' => 'before',
			)
		);

		// Border Radius Controls.
		$this->add_responsive_control(
			'mas_nav_tab_ul_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-elementor-nav-tab' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Box Shadow Controls.
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'mas_nav_tab_ul_box_shadow',
				'selector' => '{{WRAPPER}} .mas-elementor-nav-tab',
			)
		);

		$this->end_controls_section();

		// Section for List Item Controls in STYLE Tab.
		$this->start_controls_section(
			'list_item_section',
			array(
				'label' => esc_html__( 'LI', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'li_wrap',
			array(
				'label'       => esc_html__( 'LI Wrap Class', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter Your li Wrap Class', 'mas-elementor' ),
				'default'     => 'nav-item',
			)
		);

		// Padding Controls.
		$this->add_responsive_control(
			'mas_nav_tab_li_padding',
			array(
				'label'      => esc_html__( 'LI Padding', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-elementor-nav-tab-li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Margin Controls.
		$this->add_responsive_control(
			'mas_nav_tab_li_margin',
			array(
				'label'      => esc_html__( 'LI Margin', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-elementor-nav-tab-li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Border Controls.
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'mas_nav_tab_li_border',
				'selector'  => '{{WRAPPER}} .mas-elementor-nav-tab-li',
				'separator' => 'before',
			)
		);

		// Border Radius Controls.
		$this->add_responsive_control(
			'mas_nav_tab_li_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-elementor-nav-tab-li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Box Shadow Controls.
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'mas_nav_tab_li_box_shadow',
				'selector' => '{{WRAPPER}} .mas-elementor-nav-tab-li',
			)
		);

		$this->end_controls_section();

		// Section for Anchor or Button element Controls in STYLE Tab.
		$this->start_controls_section(
			'anchor_element_section',
			array(
				'label' => esc_html__( 'Anchor Element', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'anchor_wrap',
			array(
				'label'       => esc_html__( 'Anchor Class', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter Your Anchor Class', 'mas-elementor' ),
				'default'     => 'nav-link',
			)
		);

		// Padding Controls.
		$this->add_responsive_control(
			'mas_nav_link_padding',
			array(
				'label'      => esc_html__( 'Anchor Padding', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-nav-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Margin Controls.
		$this->add_responsive_control(
			'mas_nav_link_margin',
			array(
				'label'      => esc_html__( 'Anchor Margin', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-nav-link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Border Controls.
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'mas_nav_link_border',
				'selector'  => '{{WRAPPER}} .mas-nav-link',
				'separator' => 'before',
			)
		);

		// Border Radius Controls.
		$this->add_responsive_control(
			'mas_nav_link_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-nav-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Box Shadow Controls.
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'mas_nav_link_box_shadow',
				'selector' => '{{WRAPPER}} .mas-nav-link',
			)
		);

		$this->start_controls_tabs( 'tabs_style' );

		// Normal Tab Controls.
		$this->start_controls_tab(
			'tab_normal',
			array(
				'label' => esc_html__( 'Normal', 'mas-elementor' ),
			)
		);

		// Background Color Controls.
		$this->add_control(
			'normal_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-nav-tab .nav-link' => 'background-color: {{VALUE}};',
				),
			)
		);

		// Title Color Controls.
		$this->add_control(
			'normal_title_color',
			array(
				'label'     => esc_html__( 'Title Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-nav-tab .nav-link' => 'color: {{VALUE}};',
				),
			)
		);

		// Typography Controls.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .mas-elementor-nav-tab .nav-link',

			)
		);

		$this->end_controls_tab();

		// Active Tab Controls.
		$this->start_controls_tab(
			'tab_active',
			array(
				'label' => esc_html__( 'Active', 'mas-elementor' ),
			)
		);

		// Background Color Controls.
		$this->add_control(
			'active_bg_color',
			array(
				'label'     => esc_html__( 'Active Background Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-nav-tab .nav-link.active' => 'background-color: {{VALUE}};',
				),
			)
		);

		// Title Color Controls.
		$this->add_control(
			'active_title_color',
			array(
				'label'     => esc_html__( 'Active Title Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-nav-tab .nav-link.active' => 'color: {{VALUE}};',
				),
			)
		);

		// Typography Controls.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'active_title_typography',
				'selector' => '{{WRAPPER}} .mas-elementor-nav-tab .nav-link.active',

			)
		);

		// Box Shadow Controls.
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'active_anchor_box_shadow',
				'selector' => '{{WRAPPER}} .mas-elementor-nav-tab .nav-link.active',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	/**
	 * Renders the Nav Tabs widget.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$list_id  = uniqid( 'tabs-' );

		$this->add_render_attribute(
			'list',
			array(
				'class' => array( 'mas-elementor-nav-tab', $settings['ul_wrap'] ),
				'role'  => 'tablist',
				'id'    => $list_id,
			)
		);

		$this->add_render_attribute( 'description_text', 'class', 'mas__elementor__tab__description' );
		$this->add_inline_editing_attributes( 'description_text' );

		?>
		<ul <?php $this->print_render_attribute_string( 'list' ); ?>>
			<?php
			foreach ( $settings['nav_tabs'] as $index => $item ) :
				$count    = $index + 1;
				$active   = '';
				$selected = 'false';

				$this->add_render_attribute(
					'list_item' . $count,
					array(
						'class' => array( 'mas-elementor-nav-tab-li', $settings['li_wrap'] ),
						'role'  => 'presentation',
					)
				);

				if ( 1 === $count ) {
					$active   = 'active';
					$selected = 'true';
					$this->add_render_attribute( 'list_link_item' . $count, 'class' );
				}

				if ( isset( $item['list_url']['url'] ) ) {
					$this->add_link_attributes( 'list_link_item' . $count, $item['list_url'] );

				}

				$this->add_render_attribute(
					'list_link_item' . $count,
					array(
						'class' => array( 'mas-nav-link', $active, $settings['anchor_wrap'] ),
					)
				);

				if ( 1 === $count ) {
					$active   = 'active';
					$selected = 'true';
					$this->add_render_attribute( 'list_item' . $count, 'class' );
				}

				$this->add_render_attribute(
					'list_link' . $count,
					array(
						'class'          => array( 'mas-nav-link', $active, $settings['anchor_wrap'] ),
						'id'             => 'mas-' . $item['content_id'],
						'data-bs-toggle' => 'tab',
						'data-bs-target' => '#' . $item['content_id'],
						'type'           => 'button',
						'role'           => 'tab',
						'aria-controls'  => $item['content_id'],
						'aria-selected'  => $selected,
					)
				);

				$icon_class = $item['icon_class'];

				$icon = array( $icon_class, 'mas-nav__icon' );

				if ( $item['icon']['value'] ) {
					$icon[] = $item['icon']['value'];
				}

				$this->add_render_attribute(
					'list_icon' . $count,
					array(
						'class' => $icon,
					)
				);

				?>
				<li <?php $this->print_render_attribute_string( 'list_item' . $count ); ?>>
					<?php if ( isset( $item['list_url']['url'] ) ) : ?>
						<a  <?php $this->print_render_attribute_string( 'list_link_item' . $count ); ?>>
							<?php if ( ! empty( $item['icon_class'] ) ) : ?>
								<i <?php $this->print_render_attribute_string( 'list_icon' . $count ); ?>></i>
							<?php endif; ?>
							<?php echo esc_html( $item['list'] ); ?>
							<?php if ( ! empty( $item['description_text'] ) ) : ?>
								<p <?php $this->print_render_attribute_string( 'description_text' ); ?>><?php echo esc_html( $item['description_text'] ); ?></p>
							<?php endif; ?>
						</a>
						<?php
					else :
						?>
						<button <?php $this->print_render_attribute_string( 'list_link' . $count ); ?>>
							<i <?php $this->print_render_attribute_string( 'list_icon' . $count ); ?>></i>
							<?php echo esc_html( $item['list'] ); ?>
							<?php if ( ! empty( $item['description_text'] ) ) : ?>
								<p <?php $this->print_render_attribute_string( 'description_text' ); ?>><?php echo esc_html( $item['description_text'] ); ?></p>
							<?php endif; ?>
						</button>
					<?php endif; ?>
				</li>
				<?php
			endforeach;
			?>
		</ul>
		<?php
	}
}
