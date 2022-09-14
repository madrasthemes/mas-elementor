<?php
/**
 * The Products Base.
 *
 * @package MASElementor/Modules/Woocommerce/Widgets
 */

namespace MASElementor\Modules\Woocommerce\Widgets;

use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use MASElementor\Modules\Woocommerce\Classes\Products_Renderer;
use MASElementor\Modules\Woocommerce\Classes\Current_Query_Renderer;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}



/**
 * Class Products_Base
 */
abstract class Products_Base extends Base_Widget {

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
		return array( 'products' );
	}

	/**
	 * Register controls for this widget.
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_pagination_style',
			array(
				'label'     => __( 'Pagination', 'mas-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'paginate' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'product_border',
				'selector'       => '{{WRAPPER}} .woocommerce nav.woocommerce-pagination ul',
				'fields_options' => array(
					'border' => array(
						'default' => 'solid',
					),
					'width'  => array(
						'default' => array(
							'top'      => '0',
							'right'    => '0',
							'bottom'   => '0',
							'left'     => '0',
							'isLinked' => true,
						),
					),
					'color'  => array(
						'default' => '#FFFFFF',
					),
				),
			)
		);

		$this->add_control(
			'pag_heading',
			array(
				'label'     => __( 'Spacing', 'mas-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'pagination_spacing',
			array(
				'label'      => __( 'Top Spacing', 'mas-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'default'    => array(
					'size' => 15,
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} nav.woocommerce-pagination' => 'margin-top: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'product_pagination_spacing',
			array(
				'label'      => __( 'Space Between', 'mas-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'default'    => array(
					'size' => 8,
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce nav.woocommerce-pagination ul li' => 'border:0',
					'body:not(.rtl) {{WRAPPER}} nav.woocommerce-pagination ul li:not(:first-child)' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'body:not(.rtl) {{WRAPPER}} nav.woocommerce-pagination ul li:not(:last-child)' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 );',
					'body.rtl {{WRAPPER}} nav.woocommerce-pagination ul li:not(:first-child)' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 );',
					'body.rtl {{WRAPPER}} nav.woocommerce-pagination ul li:not(:last-child)' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 );',
				),
			)
		);

		$this->add_control(
			'pagination_padding',
			array(
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => esc_html__( 'Padding', 'mas-elementor' ),
				'size_units' => array( 'px', '%', 'em' ),
				'default'    => array(
					'top'      => 8,
					'right'    => 12,
					'bottom'   => 8,
					'left'     => 12,
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					'{{WRAPPER}} nav.woocommerce-pagination ul li a, {{WRAPPER}} nav.woocommerce-pagination ul li span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs(
			'pagination_style_tabs',
			array(
				'separator' => 'before',
			)
		);

		$this->start_controls_tab(
			'pagination_style_normal',
			array(
				'label' => __( 'Normal', 'mas-elementor' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'normal_pagination_typo',
				'global'         => array(
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				),
				'selector'       => '{{WRAPPER}} .woocommerce nav.woocommerce-pagination ul li a',
				'fields_options' => array(
					'typography'  => array( 'default' => 'yes' ),
					// Inner control name.
					'font_weight' => array(
						// Inner control settings.
						'default' => '600',
					),
					'font_family' => array(
						'default' => 'Quicksand',
					),
					'font_size'   => array(
						'default' => array(
							'unit' => 'px',
							'size' => 14,
						),
					),
				),
			)
		);

		$this->add_control(
			'pagination_link_color',
			array(
				'label'     => __( 'Text Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#576366',
				'selectors' => array(
					'{{WRAPPER}} nav.woocommerce-pagination ul li a' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'pagination_link_bg_color',
			array(
				'label'     => __( 'Background Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => array(
					'{{WRAPPER}} nav.woocommerce-pagination ul li a' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'normal_single_pag_border',
				'selector'       => '{{WRAPPER}} nav.woocommerce-pagination ul li a',
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
							'isLinked' => true,
						),
					),
					'color'  => array(
						'default' => '#d3d9dd',
					),
				),
			)
		);

		$this->add_control(
			'normal_single_pag_border_red',
			array(
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => esc_html__( 'Border Radius', 'mas-elementor' ),
				'size_units' => array( 'px', '%', 'em' ),
				'default'    => array(
					'top'      => 5,
					'right'    => 5,
					'bottom'   => 5,
					'left'     => 5,
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					'{{WRAPPER}} nav.woocommerce-pagination ul li a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'pagination_style_hover',
			array(
				'label' => __( 'Hover', 'mas-elementor' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'hover_pagination_typo',
				'global'         => array(
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				),
				'selector'       => '{{WRAPPER}} .woocommerce nav.woocommerce-pagination ul li a:hover',
				'fields_options' => array(
					'typography'  => array( 'default' => 'yes' ),
					// Inner control name.
					'font_weight' => array(
						// Inner control settings.
						'default' => '600',
					),
					'font_family' => array(
						'default' => 'Quicksand',
					),
					'font_size'   => array(
						'default' => array(
							'unit' => 'px',
							'size' => 14,
						),
					),
				),
			)
		);

		$this->add_control(
			'pagination_link_color_hover',
			array(
				'label'     => __( 'Text Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#576366',
				'selectors' => array(
					'{{WRAPPER}} nav.woocommerce-pagination ul li a:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'pagination_link_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#00000000',
				'selectors' => array(
					'{{WRAPPER}} nav.woocommerce-pagination ul li a:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'hover_single_pag_border',
				'selector'       => '{{WRAPPER}} nav.woocommerce-pagination ul a:hover',
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
							'isLinked' => true,
						),
					),
					'color'  => array(
						'default' => '#b9c1c8',
					),
				),
			)
		);

		$this->add_control(
			'hover_single_pag_border_red',
			array(
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => esc_html__( 'Border Radius', 'mas-elementor' ),
				'size_units' => array( 'px', '%', 'em' ),
				'default'    => array(
					'top'      => 5,
					'right'    => 5,
					'bottom'   => 5,
					'left'     => 5,
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					'{{WRAPPER}} nav.woocommerce-pagination ul li a:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'pagination_style_active',
			array(
				'label' => __( 'Active', 'mas-elementor' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'active_pagination_typo',
				'global'         => array(
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				),
				'selector'       => '{{WRAPPER}} .woocommerce nav.woocommerce-pagination ul li span',
				'fields_options' => array(
					'typography'  => array( 'default' => 'yes' ),
					// Inner control name.
					'font_weight' => array(
						// Inner control settings.
						'default' => '600',
					),
					'font_family' => array(
						'default' => 'Quicksand',
					),
					'font_size'   => array(
						'default' => array(
							'unit' => 'px',
							'size' => 14,
						),
					),
				),
			)
		);

		$this->add_control(
			'pagination_link_color_active',
			array(
				'label'     => __( 'Text Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#576366',
				'selectors' => array(
					'{{WRAPPER}} nav.woocommerce-pagination ul li span.current' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'pagination_link_bg_color_active',
			array(
				'label'     => __( 'Background Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => array(
					'{{WRAPPER}} nav.woocommerce-pagination ul li span.current' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'active_single_pag_border',
				'selector'       => '{{WRAPPER}} nav.woocommerce-pagination ul li span.current',
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
							'isLinked' => true,
						),
					),
					'color'  => array(
						'default' => '#f2f4f5',
					),
				),
			)
		);

		$this->add_control(
			'active_single_pag_border_red',
			array(
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => esc_html__( 'Border Radius', 'mas-elementor' ),
				'size_units' => array( 'px', '%', 'em' ),
				'default'    => array(
					'top'      => 5,
					'right'    => 5,
					'bottom'   => 5,
					'left'     => 5,
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					'{{WRAPPER}} nav.woocommerce-pagination ul li span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	/**
	 * Get shortcode object
	 *
	 * @param array $settings settings of the widget.
	 */
	public function get_shortcode_object( $settings ) {
		if ( 'current_query' === $settings[ Products_Renderer::QUERY_CONTROL_NAME . '_post_type' ] ) {
			$type = 'current_query';
			return new Current_Query_Renderer( $settings, $type );
		}
		$type = 'products';
		return new Products_Renderer( $settings, $type );
	}

	/**
	 * Render
	 */
	protected function render() {
		if ( WC()->session ) {
			wc_print_notices();
		}

		// For Products_Renderer.
		if ( ! isset( $GLOBALS['post'] ) ) {
			$GLOBALS['post'] = null; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		}

		$settings = $this->get_settings();

		$shortcode = $this->get_shortcode_object( $settings );

		$content = $shortcode->get_content();

		if ( $content ) {
			echo $content; //phpcs:ignore
		} elseif ( $this->get_settings( 'nothing_found_message' ) ) {
			echo '<div class="elementor-nothing-found elementor-products-nothing-found">' . esc_html( $this->get_settings( 'nothing_found_message' ) ) . '</div>'; //phpcs:ignore
		}

		$this->render_script( 'swiper-products-' . $this->get_id() );
	}

	/**
	 * Render script in the editor.
	 *
	 * @param string $key widget ID.
	 */
	public function render_script( $key = '' ) {
		$key = '.' . $key;
		if ( Plugin::$instance->editor->is_edit_mode() ) :
			?>
			<script type="text/javascript">
			var swiperCarousel = (() => {
				// forEach function
				let forEach = (array, callback, scope) => {
				for (let i = 0; i < array.length; i++) {
					callback.call(scope, i, array[i]); // passes back stuff we need
				}
				};

				// Carousel initialisation
				let swiperCarousels = document.querySelectorAll("<?php echo esc_attr( $key ); ?>");
				forEach(swiperCarousels, (index, value) => {
					let postUserOptions,
					postsPagerOptions;
				if(value.dataset.swiperOptions != undefined) postUserOptions = JSON.parse(value.dataset.swiperOptions);


				// Pager
				if(postUserOptions.pager) {
					postsPagerOptions = {
					pagination: {
						el: postUserOptions.pager,
						clickable: true,
						bulletActiveClass: 'active',
						bulletClass: 'page-item',
						renderBullet: function (index, className) {
						return '<li class="' + className + '"><a href="#" class="page-link btn-icon btn-sm">' + (index + 1) + '</a></li>';
						}
					}
					}
				}

				// Slider init
				let options = {...postUserOptions, ...postsPagerOptions};
				let swiper = new Swiper(value, options);

				// Tabs (linked content)
				if(postUserOptions.tabs) {

					swiper.on('activeIndexChange', (e) => {
					let targetTab = document.querySelector(e.slides[e.activeIndex].dataset.swiperTab),
						previousTab = document.querySelector(e.slides[e.previousIndex].dataset.swiperTab);

					previousTab.classList.remove('active');
					targetTab.classList.add('active');
					});
				}

				});

				})();
			</script>
			<?php
		endif;
	}

}
