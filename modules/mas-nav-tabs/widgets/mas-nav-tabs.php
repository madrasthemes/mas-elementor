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
use MASElementor\Modules\NavTabs\Skins;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Nav Tabs Silicon Elementor Widget.
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

		$this->add_control(
			'view',
			array(
				'label'          => esc_html__( 'Layout', 'mas-elementor' ),
				'type'           => Controls_Manager::CHOOSE,
				'default'        => 'traditional',
				'options'        => array(
					'traditional' => array(
						'title' => esc_html__( 'Default', 'mas-elementor' ),
						'icon'  => 'eicon-editor-list-ul',
					),
					'inline'      => array(
						'title' => esc_html__( 'Inline', 'mas-elementor' ),
						'icon'  => 'eicon-ellipsis-h',
					),
				),
				'render_type'    => 'template',
				'classes'        => 'elementor-control-start-end',
				'style_transfer' => true,
				'prefix_class'   => 'elementor-icon-list--layout-',
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

		$this->add_control(
			'nav_tabs',
			array(
				'label'       => esc_html__( 'Nav List', 'mas-elementor' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'title' => esc_html__( 'Project management', 'mas-elementor' ),
					),
					array(
						'title' => esc_html__( 'Remote work', 'mas-elementor' ),
					),
					array(
						'title' => esc_html__( 'Product release', 'mas-elementor' ),
					),
					array(
						'title' => esc_html__( 'Campaign planning', 'mas-elementor' ),
					),
				),
				'title_field' => '{{{ list }}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_list_style',
			array(
				'label' => esc_html__( 'List', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'tabs_style' );

		$this->start_controls_tab(
			'tab_normal',
			array(
				'label' => esc_html__( 'Normal', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'tab_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .si-nav__tab .nav-link' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'list_title_typography',
				'selector' => '{{WRAPPER}} .si-nav__tab .nav-item',

			)
		);

		$this->add_control(
			'tab_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .si-nav__tab .nav-link' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tab_icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .si-nav_tab .nav-link .si-nav_icon' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'list_icon_typography',
				'selector' => '{{WRAPPER}} .si-nav_tab .si-nav_icon',

			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_hover',
			array(
				'label' => esc_html__( 'Hover', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'tab_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .si-nav__tab .nav-link:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tab_background_hover_color',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .si-nav__tab .nav-link:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tab_icon_color_hover',
			array(
				'label'     => esc_html__( 'Icon Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .si-nav_tab .nav-link:hover .si-nav_icon' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_active',
			array(
				'label' => esc_html__( 'Active', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'tab_active_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .si-nav__tab .nav-link.active' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tab_background_active_color',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#6366f1',
				'selectors' => array(
					'{{WRAPPER}}  .si-nav__tab .nav-link.active' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tab_icon_color_active',
			array(
				'label'     => esc_html__( 'Icon Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .si-nav_tab .nav-link.active .si-nav_icon' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_style',
			array(
				'label' => esc_html__( 'Content', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'ul_wrap',
			array(
				'label'       => esc_html__( 'List Wrap Class', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'flex-nowrap justify-content-lg-center overflow-auto pb-2 mb-3 mb-lg-4', 'mas-elementor' ),
				'placeholder' => esc_html__( 'Enter Your Wrap Class', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'li_wrap',
			array(
				'label'       => esc_html__( 'List item Wrap Class', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter Your Wrap Class', 'mas-elementor' ),
			)
		);

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
				'class' => array( 'mas-nav__tab', 'nav', 'nav-tabs', $settings['ul_wrap'] ),
				'role'  => 'tablist',
				'id'    => $list_id,
			)
		);

		if ( 'inline' === $settings['view'] ) {
			$this->add_render_attribute( 'icon_list', 'class', 'elementor-inline-items' );
			$this->add_render_attribute( 'list_item', 'class', 'elementor-inline-item' );
		}

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
						'class' => array( 'nav-item', $settings['li_wrap'] ),
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
						'class' => array( 'nav-link', $active ),
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
						'class'          => array( 'nav-link text-nowrap', $active ),
						'id'             => 'si-' . $item['content_id'],
						'data-bs-toggle' => 'tab',
						'data-bs-target' => '#' . $item['content_id'],
						'type'           => 'button',
						'role'           => 'tab',
						'aria-controls'  => $item['content_id'],
						'aria-selected'  => $selected,
					)
				);

				$icon_class = $item['icon_class'];

				$icon = array( $icon_class, 'si-nav__icon' );

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
						<a  <?php $this->print_render_attribute_string( 'list_link_item' . $count ); ?>><i <?php $this->print_render_attribute_string( 'list_icon' . $count ); ?>></i><?php echo esc_html( $item['list'] ); ?></a>
						<?php
					else :
						?>
						<button <?php $this->print_render_attribute_string( 'list_link' . $count ); ?>><i <?php $this->print_render_attribute_string( 'list_icon' . $count ); ?>></i><?php echo esc_html( $item['list'] ); ?>
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
