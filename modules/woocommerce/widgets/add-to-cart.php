<?php
/**
 * The Base Widget.
 *
 * @package MASElementor/Modules/Woocommerce/Widgets
 */

namespace MASElementor\Modules\Woocommerce\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Button;
use MASElementor\Base\Base_Widget_Trait;
use MASElementor\Modules\QueryControl\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Add_To_Cart
 */
class Add_To_Cart extends Widget_Button {

	use Base_Widget_Trait;

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-add-to-cart';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Custom Add To Cart', 'mas-elementor' );
	}

	/**
	 * Get the icon of the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-woocommerce';
	}

	/**
	 * Get the categories related to the widget.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( 'woocommerce-elements' );
	}

	/**
	 * Get the keywords related to the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'woocommerce', 'shop', 'store', 'cart', 'product', 'button', 'add to cart' );
	}

	/**
	 * On Export.
	 *
	 * @param array $element element.
	 * @return array
	 */
	public function on_export( $element ) {
		unset( $element['settings']['product_id'] );

		return $element;
	}

	/**
	 * Unescape HTML.
	 *
	 * @param string $safe_text safe_text.
	 * @param string $text text.
	 * @return string
	 */
	public function unescape_html( $safe_text, $text ) {
		return $text;
	}

	/**
	 * Register controls for this widget.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_product',
			array(
				'label' => esc_html__( 'Product', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'product_id',
			array(
				'label'   => esc_html__( 'Product ID', 'mas-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'show_quantity',
			array(
				'label'       => esc_html__( 'Show Quantity', 'mas-elementor' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_off'   => esc_html__( 'Hide', 'mas-elementor' ),
				'label_on'    => esc_html__( 'Show', 'mas-elementor' ),
				'description' => esc_html__( 'Please note that switching on this option will disable some of the design controls.', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'quantity',
			array(
				'label'     => esc_html__( 'Quantity', 'mas-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 1,
				'condition' => array(
					'show_quantity' => '',
				),
			)
		);

		$this->end_controls_section();

		parent::register_controls();

		$this->start_controls_section(
			'section_layout',
			array(
				'label' => esc_html__( 'Layout', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'        => esc_html__( 'Layout', 'mas-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => array(
					''        => esc_html__( 'Inline', 'mas-elementor' ),
					'stacked' => esc_html__( 'Stacked', 'mas-elementor' ),
					'auto'    => esc_html__( 'Auto', 'mas-elementor' ),
				),
				'prefix_class' => 'elementor-add-to-cart--layout-',
				'render_type'  => 'template',
			)
		);

		$this->end_controls_section();

		$this->update_control(
			'link',
			array(
				'type'    => Controls_Manager::HIDDEN,
				'default' => array(
					'url' => '',
				),
			)
		);

		$this->update_control(
			'text',
			array(
				'default'     => esc_html__( 'Add to Cart', 'mas-elementor' ),
				'placeholder' => esc_html__( 'Add to Cart', 'mas-elementor' ),
			)
		);

		$this->update_responsive_control(
			'align',
			array(
				'prefix_class' => 'elementor-add-to-cart%s--align-',
			)
		);

		$this->update_control(
			'selected_icon',
			array(
				'default' => array(
					'value'   => 'fas fa-shopping-cart',
					'library' => 'fa-solid',
				),
			)
		);

		$this->update_control(
			'size',
			array(
				'condition' => array(
					'show_quantity' => '',
				),
			)
		);
	}

	/**
	 * Render
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( ! empty( $settings['product_id'] ) ) {
			$product_id = $settings['product_id'];
		} elseif ( wp_doing_ajax() && ! empty( $settings['product_id'] ) ) {
			// PHPCS - No nonce is required.
			$product_id = $_POST['post_id']; // phpcs:ignore
		} else {
			$product_id = get_queried_object_id();
		}

		global $product;
		$product = wc_get_product( $product_id );

		$settings = $this->get_settings_for_display();

		if ( in_array( $settings['layout'], array( 'auto', 'stacked' ) ) ) {
			add_action( 'woocommerce_before_add_to_cart_quantity', array( $this, 'before_add_to_cart_quantity' ), 95 );
			add_action( 'woocommerce_after_add_to_cart_button', array( $this, 'after_add_to_cart_button' ), 5 );
		}

		if ( 'yes' === $settings['show_quantity'] ) {
			$this->render_form_button( $product );
		} else {
			$this->render_ajax_button( $product );
		}

		if ( in_array( $settings['layout'], array( 'auto', 'stacked' ) ) ) {
			remove_action( 'woocommerce_before_add_to_cart_quantity', array( $this, 'before_add_to_cart_quantity' ), 95 );
			remove_action( 'woocommerce_after_add_to_cart_button', array( $this, 'after_add_to_cart_button' ), 5 );
		}
	}

	/**
	 * Before Add to Cart Quantity
	 *
	 * Added wrapper tag around the quantity input and "Add to Cart" button
	 * used to more solidly accommodate the layout when additional elements
	 * are added by 3rd party plugins.
	 *
	 * @since 3.6.0
	 */
	public function before_add_to_cart_quantity() {
		?>
		<div class="e-atc-qty-button-holder">
		<?php
	}

	/**
	 * After Add to Cart Quantity
	 *
	 * @since 3.6.0
	 */
	public function after_add_to_cart_button() {
		?>
		</div>
		<?php
	}

	/**
	 * Render Ajax Button.
	 *
	 * @param \WC_Product $product product.
	 */
	private function render_ajax_button( $product ) {
		$settings = $this->get_settings_for_display();

		if ( $product ) {
			if ( version_compare( WC()->version, '3.0.0', '>=' ) ) {
				$product_type = $product->get_type();
			} else {
				$product_type = $product->product_type;
			}

			$class = implode(
				' ',
				array_filter(
					array(
						'product_type_' . $product_type,
						$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
						$product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : '',
					)
				)
			);

			$this->add_render_attribute(
				'button',
				array(
					'rel'             => 'nofollow',
					'href'            => $product->add_to_cart_url(),
					'data-quantity'   => ( isset( $settings['quantity'] ) ? $settings['quantity'] : 1 ),
					'data-product_id' => $product->get_id(),
					'class'           => $class,
				)
			);

		} elseif ( current_user_can( 'manage_options' ) ) {
			$settings['text'] = esc_html__( 'Please set a valid product', 'mas-elementor' );
			$this->set_settings( $settings );
		}

		parent::render();
	}

	/**
	 * Render Form Button.
	 *
	 * @param \WC_Product $product product.
	 */
	private function render_form_button( $product ) {
		if ( ! $product && current_user_can( 'manage_options' ) ) {
			echo esc_html__( 'Please set a valid product', 'mas-elementor' );

			return;
		}

		$text_callback = function() {
			ob_start();
			$this->render_text();

			return ob_get_clean();
		};

		add_filter( 'woocommerce_get_stock_html', '__return_empty_string' );
		add_filter( 'woocommerce_product_single_add_to_cart_text', $text_callback );
		add_filter( 'esc_html', array( $this, 'unescape_html' ), 10, 2 );

		ob_start();
		woocommerce_template_single_add_to_cart();
		$form = ob_get_clean();
		$form = str_replace( 'single_add_to_cart_button', 'single_add_to_cart_button elementor-button', $form );

		// PHPCS - The HTML from 'woocommerce_template_single_add_to_cart' is safe.
		echo $form; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		remove_filter( 'woocommerce_product_single_add_to_cart_text', $text_callback );
		remove_filter( 'woocommerce_get_stock_html', '__return_empty_string' );
		remove_filter( 'esc_html', array( $this, 'unescape_html' ) );
	}

	/**
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 2.9.0
	 */
	protected function content_template() {}

	/**
	 * Render
	 */
	public function get_group_name() {
		return 'woocommerce';
	}
}
