<?php
/**
 * The Mas Nav Tab Widget.
 *
 * @package MASElementor/Modules/MasEpisodes/Widgets
 */

namespace MASElementor\Modules\MasEpisodes\Widgets;

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
use Elementor\Controls_Stack;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * MAS Episodes Elementor Widget.
 */
class Mas_Episodes extends Base_Widget {

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-episodes';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'MAS Episodes', 'mas-elementor' );
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
		return array( 'episode', 'mas' );
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
	 * Get style dependencies.
	 *
	 * Retrieve the list of style dependencies the element requires.
	 *
	 * @since 1.9.0
	 *
	 * @return array Element styles dependencies.
	 */
	public function get_style_depends() {
		return array( 'mas-episodes-stylesheet' );
	}

	/**
	 * Register Controls in Layout Section.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_layout',
			array(
				'label' => __( 'Layout', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$templates = function_exists( 'mas_template_options' ) ? mas_template_options() : array();
		$this->add_control(
			'select_template',
			array(
				'label'   => esc_html__( 'Mas Templates', 'mas-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $templates,
			)
		);

		$this->add_control(
			'mas_episodes_class',
			array(
				'type'         => Controls_Manager::HIDDEN,
				'default'      => 'mas-episodes',
				'prefix_class' => 'mas-episodes-grid mas-elementor-',
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'               => __( 'Columns', 'mas-elementor' ),
				'type'                => Controls_Manager::NUMBER,
				'prefix_class'        => 'mas-grid%s-',
				'min'                 => 1,
				'max'                 => 10,
				'default'             => 4,
				'required'            => true,
				'render_type'         => 'template',
				'device_args'         => array(
					Controls_Stack::RESPONSIVE_TABLET => array(
						'required' => false,
					),
					Controls_Stack::RESPONSIVE_MOBILE => array(
						'required' => false,
					),
				),
				'min_affected_device' => array(
					Controls_Stack::RESPONSIVE_DESKTOP => Controls_Stack::RESPONSIVE_TABLET,
					Controls_Stack::RESPONSIVE_TABLET  => Controls_Stack::RESPONSIVE_TABLET,
				),
			)
		);

		$this->add_control(
			'rows',
			array(
				'label'       => __( 'Rows', 'mas-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 1,
				'render_type' => 'template',
				'range'       => array(
					'px' => array(
						'max' => 20,
					),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_tab',
			array(
				'label' => __( 'Layout', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'me_tabs_style' );

		$this->start_controls_tab(
			'me_tab_normal',
			array(
				'label' => esc_html__( 'Normal', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'me_tab_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .nav-link' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'me_tab_text_typography',
				'selector' => '{{WRAPPER}} .nav-link',

			)
		);

		$this->add_control(
			'tab_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .me-tabs .mas-nav-link' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'me_tab_hover',
			array(
				'label' => esc_html__( 'Hover', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'me_hover_tab_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .nav-link:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tab_hover_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .me-tabs .mas-nav-link:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'me_tab_active',
			array(
				'label' => esc_html__( 'Active', 'mas-elementor' ),
			)
		);
		$this->add_control(
			'me_tab_active_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .nav-link.active' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tab_active_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-nav-link:has( .active )' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		// Title Padding Controls.
		$this->add_responsive_control(
			'me_nav_tab_li_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .me-tabs .mas-nav-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Title Margin Controls.
		$this->add_responsive_control(
			'me_nav_tab_li_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .me-tabs .mas-nav-link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'me_nav_tab_li_align',
			array(
				'label'     => esc_html__( 'Title Alignment', 'mas-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'mas-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'mas-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'mas-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .mas-nav-link' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Renders the Nav Tabs widget.
	 */
	protected function render() {
		$settings = $this->get_settings();
		$tv_shows = masvideos_get_tv_show( get_the_ID() );

		$rows    = ! empty( $settings['rows'] ) ? $settings['rows'] : 1;
		$columns = ! empty( $settings['columns'] ) ? $settings['columns'] : 4;

		?>
		<div class="mas-episodes-tabs mas-episodes-tabs-wrapper">
			<ul class="me-tabs nav nav-tabs" role="tablist">
				<?php
				$seasons = $tv_shows->get_seasons();
				foreach ( $seasons as $key => $season ) {
					$active = '';

					$count = $this->get_id() . (string) ( $key + 1 );

					if ( 1 === $key + 1 ) {
						$active = ' active';
					}

					$this->add_render_attribute(
						'mas_epi_li' . $count,
						array(
							'class'         => 'mas-nav-link nav-item',
							'id'            => 'me-tab-title-' . $count,
							'role'          => 'tab',
							'aria-controls' => 'me-tab-title-' . $count,
						)
					);

					if ( ! empty( $season['episodes'] ) ) :
						?>
						<li <?php $this->print_render_attribute_string( 'mas_epi_li' . $count ); ?>>
							<a class ="nav-link<?php echo esc_attr( $active ); ?>" href="#me-tab-<?php echo esc_attr( $count ); ?>" data-toggle="tab"><?php echo esc_html( $season['name'] ); ?></a>
						</li>
						<?php
					endif;
				}
				?>
			</ul>
			<div class="mas-episodes-content-wrapper tab-content">
				<?php
				foreach ( $seasons as $index => $season ) {
					$count    = $this->get_id() . (string) ( $index + 1 );
					$active   = '';
					$selected = 'false';

					if ( 1 === $index + 1 ) {
						$active   = ' active';
						$selected = 'true';
					}
					$this->add_render_attribute(
						'list_link_item' . $count,
						array(
							'class'           => 'mas-episodes-container mas-episodes mas-grid tab-pane' . $active,
							'id'              => 'me-tab-' . $count,
							'role'            => 'tabpanel',
							'aria-labelledby' => 'tab-title-' . $count,
							'aria-selected'   => $selected,
						)
					);
					?>
					<div <?php $this->print_render_attribute_string( 'list_link_item' . $count ); ?>>
						<?php
						foreach ( $season['episodes'] as $key => $episode_id ) {
							$episode = masvideos_get_episode( $episode_id );

							$post_object = get_post( $episode->get_id() );

							setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found
							print( mas_render_template( $settings['select_template'], false ) );//phpcs:ignore

						}
							wp_reset_postdata();
						?>
					</div>
					<?php
				}
				?>
			</div>		
		</div>
		<?php
	}
}
