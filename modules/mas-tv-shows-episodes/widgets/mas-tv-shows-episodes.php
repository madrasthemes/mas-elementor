<?php
/**
 * The MAS Nav Tab Widget.
 *
 * @package MASElementor/Modules/MasTvShowsEpisodes/Widgets
 */

namespace MASElementor\Modules\MasTvShowsEpisodes\Widgets;

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
class Mas_Tv_Shows_Episodes extends Base_Widget {

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
		return esc_html__( 'MAS Tv-Shows Episodes', 'mas-addons-for-elementor' );
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
		return array( 'tv-shows-episode', 'mas' );
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
		return array( 'mas-tv-shows-episodes-stylesheet' );
	}

	/**
	 * Register Controls in Layout Section.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_layout',
			array(
				'label' => __( 'Layout', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$templates = function_exists( 'mas_template_options' ) ? mas_template_options() : array();
		$this->add_control(
			'select_template',
			array(
				'label'   => esc_html__( 'MAS Templates', 'mas-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $templates,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_tab',
			array(
				'label' => __( 'Layout', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'me_tabs_style' );

		$this->start_controls_tab(
			'me_tab_normal',
			array(
				'label' => esc_html__( 'Normal', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'me_tab_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
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
				'label'     => esc_html__( 'Background Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .me-tabs .mas-tab-flex' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'me_tab_hover',
			array(
				'label' => esc_html__( 'Hover', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'me_hover_tab_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
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
				'label'     => esc_html__( 'Background Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .me-tabs .mas-tab-flex:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'me_tab_active',
			array(
				'label' => esc_html__( 'Active', 'mas-addons-for-elementor' ),
			)
		);
		$this->add_control(
			'me_tab_active_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
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
				'label'     => esc_html__( 'Background Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-tab-flex:has( .active )' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		// Title Padding Controls.
		$this->add_responsive_control(
			'me_nav_tab_li_padding',
			array(
				'label'      => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .me-tabs .mas-tab-flex' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Title Margin Controls.
		$this->add_responsive_control(
			'me_nav_tab_li_margin',
			array(
				'label'      => esc_html__( 'Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .me-tabs .mas-tab-flex' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'me_nav_tab_li_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .me-tabs .mas-tab-flex' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'me_nav_tab_li_align',
			array(
				'label'     => esc_html__( 'Title Alignment', 'mas-addons-for-elementor' ),
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
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .me-tabs' => 'justify-content: {{VALUE}};',
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
		if ( empty( $tv_shows ) ) {
			return;
		}
		$seasons = $tv_shows->get_seasons();

		?>
		<div class="mas-episodes-tabs mas-episodes-tabs-wrapper">
			<?php if ( ! empty( $seasons ) ) : ?>
				<ul class="me-tabs nav nav-tabs" role="tablist">
					<?php

					foreach ( $seasons as $key => $season ) {
						$active = '';

						$count = $this->get_id() . (string) ( $key + 1 );

						if ( 1 === $key + 1 ) {
							$active = ' active';
						}

						$this->add_render_attribute(
							'mas_epi_li' . $count,
							array(
								'class'         => 'mas-tab-flex  nav-item',
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
								'class'           => 'tab-pane' . $active,
								'id'              => 'me-tab-' . $count,
								'role'            => 'tabpanel',
								'aria-labelledby' => 'tab-title-' . $count,
								'aria-selected'   => $selected,
							)
						);
						?>
						<div <?php $this->print_render_attribute_string( 'list_link_item' . $count ); ?>>
							<?php
							$original_post = $GLOBALS['post'];
							foreach ( $season['episodes'] as $key => $episode_id ) {
								if ( get_the_ID() === (int) $episode_id ) {
									continue;
								}
								$GLOBALS['post'] = get_post( $episode_id ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
								setup_postdata( $GLOBALS['post'] );

								setup_postdata( masvideos_setup_episode_data( $episode_id ) ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found
								print( mas_render_template( $settings['select_template'], false ) );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

							}
							$GLOBALS['post'] = $original_post;// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
								wp_reset_postdata();
							?>
						</div>
						<?php
					}
					?>
				</div>		
			<?php endif; ?>
		</div>
		<?php
	}
}
