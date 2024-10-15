<?php
/**
 * The Product meta Widget.
 *
 * @package MASElementor/Modules/Woocommerce/Widgets
 */

namespace MASElementor\Modules\Woocommerce\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Product Meta
 */
class Product_Meta extends Base_Widget {

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-woocommerce-product-meta';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'MAS Product Meta', 'mas-addons-for-elementor' );
	}

	/**
	 * Get the icon of the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-product-meta';
	}

	/**
	 * Get the keywords related to the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'woocommerce', 'shop', 'store', 'meta', 'data', 'product' );
	}

	/**
	 * Return the style dependencies of the module.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array( 'mas-product-meta' );
	}

	/**
	 * Register controls for this widget.
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_product_meta_style',
			array(
				'label' => esc_html__( 'Style', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'wc_style_warning',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'The style of this widget is often affected by your theme and plugins. If you experience any such issue, try to switch to a basic theme and deactivate related plugins.', 'mas-addons-for-elementor' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		$this->add_control(
			'view',
			array(
				'label'        => esc_html__( 'View', 'mas-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'inline',
				'options'      => array(
					'table'   => esc_html__( 'Table', 'mas-addons-for-elementor' ),
					'stacked' => esc_html__( 'Stacked', 'mas-addons-for-elementor' ),
					'inline'  => esc_html__( 'Inline', 'mas-addons-for-elementor' ),
				),
				'prefix_class' => 'mas-elementor-woo-meta--view-',
			)
		);

		$this->add_responsive_control(
			'space_between',
			array(
				'label'      => esc_html__( 'Space Between', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'range'      => array(
					'px' => array(
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}}:not(.mas-elementor-woo-meta--view-inline) .product_meta .detail-container:not(:last-child)' => 'padding-bottom: calc({{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}}:not(.mas-elementor-woo-meta--view-inline) .product_meta .detail-container:not(:first-child)' => 'margin-top: calc({{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}}.mas-elementor-woo-meta--view-inline .product_meta .detail-container' => 'margin-right: calc({{SIZE}}{{UNIT}}/2); margin-left: calc({{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}}.mas-elementor-woo-meta--view-inline .product_meta' => 'margin-right: calc(-{{SIZE}}{{UNIT}}/2); margin-left: calc(-{{SIZE}}{{UNIT}}/2)',
					'body:not(.rtl) {{WRAPPER}}.mas-elementor-woo-meta--view-inline .detail-container:after' => 'right: calc( (-{{SIZE}}{{UNIT}}/2) + (-{{divider_weight.SIZE}}px/2) )',
					'body:not.rtl {{WRAPPER}}.mas-elementor-woo-meta--view-inline .detail-container:after' => 'left: calc( (-{{SIZE}}{{UNIT}}/2) - ({{divider_weight.SIZE}}px/2) )',
				),
			)
		);

		$this->add_control(
			'divider',
			array(
				'label'        => esc_html__( 'Divider', 'mas-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_off'    => esc_html__( 'Off', 'mas-addons-for-elementor' ),
				'label_on'     => esc_html__( 'On', 'mas-addons-for-elementor' ),
				'selectors'    => array(
					'{{WRAPPER}} .product_meta .detail-container:not(:last-child):after' => 'content: ""',
				),
				'return_value' => 'yes',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'divider_style',
			array(
				'label'     => esc_html__( 'Style', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'solid'  => esc_html__( 'Solid', 'mas-addons-for-elementor' ),
					'double' => esc_html__( 'Double', 'mas-addons-for-elementor' ),
					'dotted' => esc_html__( 'Dotted', 'mas-addons-for-elementor' ),
					'dashed' => esc_html__( 'Dashed', 'mas-addons-for-elementor' ),
				),
				'default'   => 'solid',
				'condition' => array(
					'divider' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}}:not(.mas-elementor-woo-meta--view-inline) .product_meta .detail-container:not(:last-child):after' => 'border-top-style: {{VALUE}}',
					'{{WRAPPER}}.mas-elementor-woo-meta--view-inline .product_meta .detail-container:not(:last-child):after' => 'border-left-style: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'divider_weight',
			array(
				'label'      => esc_html__( 'Weight', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'default'    => array(
					'size' => 1,
				),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 20,
					),
				),
				'condition'  => array(
					'divider' => 'yes',
				),
				'selectors'  => array(
					'{{WRAPPER}}:not(.mas-elementor-woo-meta--view-inline) .product_meta .detail-container:not(:last-child):after' => 'border-top-width: {{SIZE}}{{UNIT}}; margin-bottom: calc(-{{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}}.mas-elementor-woo-meta--view-inline .product_meta .detail-container:not(:last-child):after' => 'border-left-width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'divider_width',
			array(
				'label'      => esc_html__( 'Width', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'default'    => array(
					'unit' => '%',
				),
				'condition'  => array(
					'divider' => 'yes',
					'view!'   => 'inline',
				),
				'selectors'  => array(
					'{{WRAPPER}} .product_meta .detail-container:not(:last-child):after' => 'width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'divider_height',
			array(
				'label'      => esc_html__( 'Height', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vh', 'custom' ),
				'default'    => array(
					'unit' => '%',
				),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 100,
					),
					'%'  => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'condition'  => array(
					'divider' => 'yes',
					'view'    => 'inline',
				),
				'selectors'  => array(
					'{{WRAPPER}} .product_meta .detail-container:not(:last-child):after' => 'height: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'divider_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ddd',
				'global'    => array(
					'default' => Global_Colors::COLOR_TEXT,
				),
				'condition' => array(
					'divider' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} .product_meta .detail-container:not(:last-child):after' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'heading_text_style',
			array(
				'label'     => esc_html__( 'Text', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'text_typography',
				'selector' => '{{WRAPPER}}',
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'heading_link_style',
			array(
				'label'     => esc_html__( 'Link', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'link_typography',
				'selector' => '{{WRAPPER}} a',
			)
		);

		$this->add_control(
			'link_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} a' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_product_meta_captions',
			array(
				'label' => esc_html__( 'Captions', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'heading_category_caption',
			array(
				'label' => esc_html__( 'Category', 'mas-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'category_caption_single',
			array(
				'label'       => esc_html__( 'Singular', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Category', 'mas-addons-for-elementor' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'category_caption_plural',
			array(
				'label'       => esc_html__( 'Plural', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Categories', 'mas-addons-for-elementor' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'heading_tag_caption',
			array(
				'label'     => esc_html__( 'Tag', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'tag_caption_single',
			array(
				'label'       => esc_html__( 'Singular', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Tag', 'mas-addons-for-elementor' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'tag_caption_plural',
			array(
				'label'       => esc_html__( 'Plural', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Tags', 'mas-addons-for-elementor' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'heading_sku_caption',
			array(
				'label'     => esc_html__( 'SKU', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'sku_caption',
			array(
				'label'       => esc_html__( 'SKU', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'SKU', 'mas-addons-for-elementor' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'sku_missing_caption',
			array(
				'label'       => esc_html__( 'Missing', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'N/A', 'mas-addons-for-elementor' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Get plural or single.
	 *
	 * @param string $single single.
	 * @param string $plural plural.
	 * @param int    $count count.
	 */
	private function get_plural_or_single( $single, $plural, $count ) {
		return 1 === $count ? $single : $plural;
	}

	/**
	 * Render.
	 */
	protected function render() {
		global $product;

		$product = $this->get_product();

		if ( ! $product ) {
			return;
		}

		$sku = esc_html( $product->get_sku() );

		$settings                = $this->get_settings_for_display();
		$sku_caption             = ! empty( $settings['sku_caption'] ) ? esc_html( $settings['sku_caption'] ) : esc_html__( 'SKU', 'mas-addons-for-elementor' );
		$sku_missing             = ! empty( $settings['sku_missing_caption'] ) ? esc_html( $settings['sku_missing_caption'] ) : esc_html__( 'N/A', 'mas-addons-for-elementor' );
		$category_caption_single = ! empty( $settings['category_caption_single'] ) ? $settings['category_caption_single'] : esc_html__( 'Category', 'mas-addons-for-elementor' );
		$category_caption_plural = ! empty( $settings['category_caption_plural'] ) ? $settings['category_caption_plural'] : esc_html__( 'Categories', 'mas-addons-for-elementor' );
		$tag_caption_single      = ! empty( $settings['tag_caption_single'] ) ? $settings['tag_caption_single'] : esc_html__( 'Tag', 'mas-addons-for-elementor' );
		$tag_caption_plural      = ! empty( $settings['tag_caption_plural'] ) ? $settings['tag_caption_plural'] : esc_html__( 'Tags', 'mas-addons-for-elementor' );
		?>
		<div class="product_meta">

			<?php do_action( 'woocommerce_product_meta_start' ); ?>

			<?php if ( wc_product_sku_enabled() && ( $sku || $product->is_type( 'variable' ) ) ) : ?>
				<span class="sku_wrapper detail-container">
					<span class="detail-label">
						<?php // PHPCS - the $sku_caption variable is safe. ?>
						<?php echo $sku_caption; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</span>
					<span class="sku">
						<?php // PHPCS - the $sku && $sku_missing variables are safe. ?>
						<?php echo $sku ? $sku : $sku_missing; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</span>
				</span>
			<?php endif; ?>

			<?php if ( count( $product->get_category_ids() ) ) : ?>
				<span class="posted_in detail-container"><span class="detail-label"><?php echo esc_html( $this->get_plural_or_single( $category_caption_single, $category_caption_plural, count( $product->get_category_ids() ) ) ); ?></span> <span class="detail-content"><?php echo get_the_term_list( $product->get_id(), 'product_cat', '', ', ' ); ?></span></span>
			<?php endif; ?>

			<?php if ( count( $product->get_tag_ids() ) ) : ?>
				<span class="tagged_as detail-container"><span class="detail-label"><?php echo esc_html( $this->get_plural_or_single( $tag_caption_single, $tag_caption_plural, count( $product->get_tag_ids() ) ) ); ?></span> <span class="detail-content"><?php echo get_the_term_list( $product->get_id(), 'product_tag', '', ', ' ); ?></span></span>
			<?php endif; ?>

			<?php do_action( 'woocommerce_product_meta_end' ); ?>

		</div>
		<?php
	}

	/**
	 * Render Plain Content.
	 */
	public function render_plain_content() {}

	/**
	 * Get group name.
	 */
	public function get_group_name() {
		return 'woocommerce';
	}
}
