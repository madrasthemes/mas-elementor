<?php
/**
 * Woocommerce Module.
 *
 * @package MASElementor/Modules/Woocommerce
 */

namespace MASElementor\Modules\Woocommerce;

use Elementor\Core\Documents_Manager;
use Elementor\Settings;
use MASElementor\Base\Module_Base;
use MASElementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The Module.
 */
class Module extends Module_Base {

	/**
	 * WOOCOMMERCE_GROUP
	 *
	 * @var WOOCOMMERCE_GROUP
	 */
	const WOOCOMMERCE_GROUP = 'woocommerce';

	/**
	 * Use mini cart template
	 *
	 * @var bool
	 */
	protected $use_mini_cart_template;

	const OPTION_NAME_USE_MINI_CART = 'use_mini_cart_template';

	/**
	 * Woocommerce Active
	 *
	 * @return bool
	 */
	public static function is_active() {
		return class_exists( 'Woocommerce' );
	}

	/**
	 * Product Search
	 *
	 * @return bool
	 */
	public static function is_product_search() {
		return is_search() && 'product' === get_query_var( 'post_type' );
	}

	/**
	 * Get Name
	 *
	 * @return string
	 */
	public function get_name() {
		return 'woocommerce';
	}

	/**
	 * Get Widgets
	 *
	 * @return array
	 */
	public function get_widgets() {
		$widgets = array(
			'Products',
			'Add_To_Cart',
			'Product_Related',
			'Product_Data_Tabs',

			'Cart',
			'Checkout',
			'Categories',
			'Product_Meta',
			'Product_Additional_Information',
			'Product_Images',
			'Purchase_Summary',
			'Product_Rating',
			'Product_Short_Description',
			'Product_Stock',
			'Product_Title',
			'Product_Add_To_Cart',
			'Product_Price',
			'Progress_Bar',
			'Product_Categories',
			'Product_Categories_Dropdown',
			'Product_Attributes',
			'Mini_Cart',
			'Customer_Account',
		);

		if ( function_exists( 'electro_header_mini_cart_icon' ) ) {
			$widgets[] = 'Header_Cart';
		}

		if ( function_exists( 'is_yith_woocompare_activated' ) && is_yith_woocompare_activated() ) {
			$widgets[] = 'Add_To_Compare';
		}

		return $widgets;
	}

	/**
	 * Add products post class.
	 *
	 * @param array $classes classes.
	 *
	 * @return array
	 */
	public function add_product_post_class( $classes ) {
		$classes[] = 'product';

		return $classes;
	}

	/**
	 * Add Products post class filter.
	 *
	 * @return void
	 */
	public function add_products_post_class_filter() {
		add_filter( 'post_class', array( $this, 'add_product_post_class' ) );
	}

	/**
	 * Remove products post class filter.
	 *
	 * @return void
	 */
	public function remove_products_post_class_filter() {
		remove_filter( 'post_class', array( $this, 'add_product_post_class' ) );
	}

	/**
	 * Register Tags
	 *
	 * @return void
	 */
	public function register_tags() {
		$tags = array(
			'Product_Gallery',
			'Product_Image',
			'Product_Price',
			'Product_Rating',
			'Product_Sale',
			'Product_Short_Description',
			'Product_SKU',
			'Product_Stock',
			'Product_Terms',
			'Product_Title',
			'Product_Id',
			'Category_Image',
			'Cart_Button_Url',
			'Cart_Button_Text',
			'Product_Content',
			'Product_Sold_Count',
			'Product_Onsale_Percentage',
		);

		$module = Plugin::elementor()->dynamic_tags;

		$module->register_group(
			self::WOOCOMMERCE_GROUP,
			array(
				'title' => __( 'WooCommerce', 'mas-addons-for-elementor' ),
			)
		);

		foreach ( $tags as $tag ) {
			$tag = 'MASElementor\\Modules\\Woocommerce\\tags\\' . $tag;

			$module->register( new $tag() );
		}
	}

	/**
	 * Register Woocommerce Hooks.
	 *
	 * @return void
	 */
	public function register_wc_hooks() {
		wc()->frontend_includes();
	}

	/**
	 * Register Woocommerce Hooks.
	 *
	 * @param Conditions_Manager $conditions_manager conditions manager.
	 */
	public function register_conditions( $conditions_manager ) {
		$woocommerce_condition = new Woocommerce();

		$conditions_manager->get_condition( 'general' )->register_sub_condition( $woocommerce_condition );
	}

	/**
	 * Register Woocommerce Hooks.
	 *
	 * @param Settings $settings settings.
	 */
	public function register_admin_fields( Settings $settings ) {
		$settings->add_section(
			Settings::TAB_INTEGRATIONS,
			'woocommerce',
			array(
				'callback' => function() {
					echo '<hr><h2>' . esc_html__( 'WooCommerce', 'mas-addons-for-elementor' ) . '</h2>';
				},
				'fields'   => array(
					self::OPTION_NAME_USE_MINI_CART => array(
						'label'      => esc_html__( 'Mini Cart Template', 'mas-addons-for-elementor' ),
						'field_args' => array(
							'type'    => 'select',
							'std'     => 'initial',
							'options' => array(
								'initial' => '', // Relevant until either menu-cart widget is used or option is explicitly set to 'no'.
								'no'      => esc_html__( 'Disable', 'mas-addons-for-elementor' ),
								'yes'     => esc_html__( 'Enable', 'mas-addons-for-elementor' ),
							),
							'desc'    => esc_html__( 'Set to `Disable` in order to use your Theme\'s or WooCommerce\'s mini-cart template instead of Elementor\'s.', 'mas-addons-for-elementor' ),
						),
					),
				),
			)
		);
	}

	/**
	 * Render Menu cart toggle button.
	 *
	 * @return void
	 */
	public static function render_menu_cart_toggle_button() {
		if ( null === WC()->cart ) {
			return;
		}
		$product_count = WC()->cart->get_cart_contents_count();
		$sub_total     = WC()->cart->get_cart_subtotal();
		$counter_attr  = 'data-counter="' . $product_count . '"';

		?>
		<div class="elementor-menu-cart__toggle elementor-button-wrapper">
			<a id="elementor-menu-cart__toggle_button" href="#" class="elementor-button elementor-size-sm">
				<span class="elementor-button-text"><?php echo wp_kses_post( $sub_total ); ?></span>
				<span class="elementor-button-icon" <?php echo wp_kses_post( $counter_attr ); ?>>
					<i class="eicon" aria-hidden="true"></i>
					<span class="elementor-screen-only"><?php esc_html_e( 'Cart', 'mas-addons-for-elementor' ); ?></span>
				</span>
			</a>
		</div>

		<?php
	}

	/**
	 * Render menu cart markup.
	 * The `widget_shopping_cart_content` div will be populated by woocommerce js.
	 */
	public static function render_menu_cart() {
		if ( null === WC()->cart ) {
			return;
		}

		$widget_cart_is_hidden = apply_filters( 'woocommerce_widget_cart_is_hidden', false );
		?>
		<div class="elementor-menu-cart__wrapper">
			<?php if ( ! $widget_cart_is_hidden ) : ?>
			<div class="elementor-menu-cart__container elementor-lightbox" aria-expanded="false">
				<div class="elementor-menu-cart__main" aria-expanded="false">
					<div class="elementor-menu-cart__close-button"></div>
					<div class="widget_shopping_cart_content"></div>
				</div>
			</div>
				<?php self::render_menu_cart_toggle_button(); ?>
			<?php endif; ?>
			</div> <!-- close elementor-menu-cart__wrapper -->
		<?php
	}

	/**
	 * Refresh the Menu Cart button and items counter.
	 *
	 * @param array $fragments fragments.
	 *
	 * @return array
	 */
	public function menu_cart_fragments( $fragments ) {
		$has_cart = is_a( WC()->cart, 'WC_Cart' );
		if ( ! $has_cart || ! $this->use_mini_cart_template ) {
			return $fragments;
		}

		ob_start();
		self::render_menu_cart_toggle_button();
		$menu_cart_toggle_button_html = ob_get_clean();

		if ( ! empty( $menu_cart_toggle_button_html ) ) {
			$fragments['body:not(.elementor-editor-active) div.elementor-element.elementor-widget.elementor-widget-woocommerce-menu-cart div.elementor-menu-cart__toggle.elementor-button-wrapper'] = $menu_cart_toggle_button_html;
		}

		return $fragments;
	}

	/**
	 * Initialize Cart
	 *
	 * @return void
	 */
	public function maybe_init_cart() {
		$has_cart = is_a( WC()->cart, 'WC_Cart' );

		if ( ! $has_cart ) {
			$session_class = apply_filters( 'woocommerce_session_handler', 'WC_Session_Handler' );
			WC()->session  = new $session_class();
			WC()->session->init();
			WC()->cart     = new \WC_Cart();
			WC()->customer = new \WC_Customer( get_current_user_id(), true );
		}
	}

	/**
	 * Localized Settings frontend.
	 *
	 * @param array $settings settings.
	 *
	 * @return array
	 */
	public function localized_settings_frontend( $settings ) {
		$has_cart = is_a( WC()->cart, 'WC_Cart' );

		if ( $has_cart ) {
			$settings['menu_cart'] = array(
				'cart_page_url'     => wc_get_cart_url(),
				'checkout_page_url' => wc_get_checkout_url(),
			);
		}
		return $settings;
	}

	/**
	 * Theme template include.
	 *
	 * @param bool   $need_override_location need override location.
	 * @param string $location location.
	 * @return array
	 */
	public function theme_template_include( $need_override_location, $location ) {
		if ( is_product() && 'single' === $location ) {
			$need_override_location = true;
		}

		return $need_override_location;
	}

	/**
	 * Add plugin path to wc template search path.
	 *
	 * @param string $template the template.
	 * @param string $template_name the template name.
	 * @param string $template_path the template path.
	 *
	 * @return string
	 */
	public function woocommerce_locate_template( $template, $template_name, $template_path ) {

		if ( self::TEMPLATE_MINI_CART !== $template_name ) {
			return $template;
		}

		if ( ! $this->use_mini_cart_template ) {
			return $template;
		}

		$plugin_path = plugin_dir_path( __DIR__ ) . 'woocommerce/wc-templates/';

		if ( file_exists( $plugin_path . $template_name ) ) {
			$template = $plugin_path . $template_name;
		}

		return $template;
	}

	/**
	 * WooCommerce/WordPress widget(s), some of the widgets have css classes that used by final selectors.
	 * before this filter, all those widgets were warped by `.elementor-widget-container` without chain original widget
	 * classes, now they will be warped by div with the original css classes.
	 *
	 * @param array                       $default_widget_args default widgets args.
	 * @param \Elementor\Widget_WordPress $widget the widget.
	 *
	 * @return array $default_widget_args
	 */
	public function woocommerce_wordpress_widget_css_class( $default_widget_args, $widget ) {
		$widget_instance = $widget->get_widget_instance();

		if ( ! empty( $widget_instance->widget_cssclass ) ) {
			$default_widget_args['before_widget'] .= '<div class="' . $widget_instance->widget_cssclass . '">';
			$default_widget_args['after_widget']  .= '</div>';
		}

		return $default_widget_args;
	}

	/**
	 * Is Preview
	 *
	 * Helper to check if we are doing either:
	 * - Viewing the WP Preview page - also used as the Elementor preview page when clicking "Preview Changes" in the editor
	 * - Viewing the page in the editor, but not the active page being edited e.g. if you click Edit Header while editing a page
	 *
	 * @since 3.7.0
	 *
	 * @return bool
	 */
	public static function is_preview() {
		return Plugin::elementor()->preview->is_preview_mode() || is_preview();
	}

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();

		add_action( 'elementor/dynamic_tags/register', array( $this, 'register_tags' ) );
		add_action( 'elementor/frontend/before_register_styles', array( $this, 'register_frontend_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_frontend_scripts' ), 9999 );
		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'maybe_init_cart' ) );

		// On Editor - Register WooCommerce frontend hooks before the Editor init.
		// Priority = 5, in order to allow plugins remove/add their wc hooks on init.
		if ( ! empty( $_REQUEST['action'] ) && 'elementor' === $_REQUEST['action'] && is_admin() ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			add_action( 'init', array( $this, 'register_wc_hooks' ), 5 );
		}

		if ( $this->use_mini_cart_template ) {
			add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'menu_cart_fragments' ) );
			add_filter( 'woocommerce_locate_template', array( $this, 'woocommerce_locate_template' ), 10, 3 );
		}

		add_filter( 'elementor/widgets/wordpress/widget_args', array( $this, 'woocommerce_wordpress_widget_css_class' ), 10, 2 );

		// Add options on Inventry Tab.
		add_action( 'woocommerce_product_options_inventory_product_data', array( $this, 'product_options_inventory_product_data' ) );

		/**
		 * Recently Viewed Products
		 */
		add_action( 'template_redirect', array( $this, 'mas_elementor_wc_track_product_view' ), 10 );
	}

	/**
	 * Register frontend styles.
	 */
	public function register_frontend_scripts() {
		wp_register_script(
			'mas-progress-script',
			MAS_ELEMENTOR_ASSETS_URL . 'js/progress/progress.js',
			array( 'elementor-frontend' ),
			MAS_ELEMENTOR_VERSION,
			true
		);

		wp_register_script(
			'mas-accordion-script',
			MAS_ELEMENTOR_ASSETS_URL . 'js/tabs/base-tabs.js',
			array( 'elementor-frontend' ),
			MAS_ELEMENTOR_VERSION,
			true
		);

		wp_register_script(
			'mas-shop-view-switcher',
			MAS_ELEMENTOR_ASSETS_URL . 'js/shop-control-bar/shop-view-switcher.js',
			array(),
			MAS_ELEMENTOR_VERSION,
			true
		);

		wp_register_script(
			'mas-add-to-compare',
			MAS_ELEMENTOR_ASSETS_URL . 'js/add-to-compare/add-to-compare.js',
			array(),
			MAS_ELEMENTOR_VERSION,
			true
		);

		wp_register_script(
			'mas-add-to-cart',
			MAS_ELEMENTOR_ASSETS_URL . 'js/add-to-cart/add-to-cart.js',
			array(),
			MAS_ELEMENTOR_VERSION,
			true
		);
	}

	/**
	 * Register frontend styles.
	 */
	public function register_frontend_styles() {
		wp_register_style(
			'products-stylesheet',
			MAS_ELEMENTOR_ASSETS_URL . 'css/main.css',
			array(),
			MAS_ELEMENTOR_VERSION
		);

		wp_register_style(
			'mas-cart-stylesheet',
			MAS_ELEMENTOR_ASSETS_URL . 'css/cart/mas-cart.css',
			array(),
			MAS_ELEMENTOR_VERSION
		);

		wp_register_style(
			'mas-woocommerce-cart',
			MAS_ELEMENTOR_ASSETS_URL . 'css/woo-cart/mas-woocommerce-cart.css',
			array(),
			MAS_ELEMENTOR_VERSION
		);

		wp_register_style(
			'mas-woocommerce-checkout',
			MAS_ELEMENTOR_ASSETS_URL . 'css/woo-checkout/mas-woocommerce-checkout.css',
			array(),
			MAS_ELEMENTOR_VERSION
		);

		wp_register_style(
			'mas-product-meta',
			MAS_ELEMENTOR_ASSETS_URL . 'css/product-meta/mas-product-meta.css',
			array(),
			MAS_ELEMENTOR_VERSION
		);

		wp_register_style(
			'mas-product-additional-information',
			MAS_ELEMENTOR_ASSETS_URL . 'css/product-additional-information/mas-product-additional-information.css',
			array(),
			MAS_ELEMENTOR_VERSION
		);

		wp_register_style(
			'mas-product-images',
			MAS_ELEMENTOR_ASSETS_URL . 'css/product-images/mas-product-images.css',
			array(),
			MAS_ELEMENTOR_VERSION
		);

		wp_register_style(
			'mas-purchase-summary',
			MAS_ELEMENTOR_ASSETS_URL . 'css/purchase-summary/mas-purchase-summary.css',
			array(),
			MAS_ELEMENTOR_VERSION
		);

		wp_register_style(
			'mas-product-rating',
			MAS_ELEMENTOR_ASSETS_URL . 'css/product-rating/mas-product-rating.css',
			array(),
			MAS_ELEMENTOR_VERSION
		);

		wp_register_style(
			'mas-product-add-to-cart',
			MAS_ELEMENTOR_ASSETS_URL . 'css/product-add-to-cart/add-to-cart.css',
			array(),
			MAS_ELEMENTOR_VERSION
		);

		wp_register_style(
			'mas-shop-view-switcher',
			MAS_ELEMENTOR_ASSETS_URL . 'css/shop-control-bar/shop-view-switcher.css',
			array(),
			MAS_ELEMENTOR_VERSION
		);
	}

	/**
	 * Add product options in inventory tab of single product data.
	 */
	public function product_options_inventory_product_data() {
		if ( apply_filters( 'mas_elementor_product_options_inventory_product_data', true ) ) {
			echo '<div class="options_group">';
				woocommerce_wp_text_input(
					array(
						'id'          => '_total_stock_quantity',
						'label'       => esc_html__( 'Total Stock Quantity', 'mas-addons-for-elementor' ),
						'desc_tip'    => 'true',
						'description' => esc_html__( 'Total Stock Quantity Available. This will be used to calculate prograss bar in onsale element.', 'mas-addons-for-elementor' ),
						'type'        => 'text',
					)
				);
			echo '</div>';
		}
	}

	/**
	 * Set Product ids in cookies.
	 */
	public function mas_elementor_wc_track_product_view() {
		if ( ! is_singular( 'product' ) ) {
			return;
		}

		global $post;

		$recently_viewed_cookie = isset( $_COOKIE['mas_elementor_wc_recently_viewed'] ) ? wp_unslash( $_COOKIE['mas_elementor_wc_recently_viewed'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

		if ( empty( $recently_viewed_cookie ) ) {
			$viewed_products = array();
		} else {
			$viewed_products = (array) explode( '|', $recently_viewed_cookie );
		}

		if ( ! in_array( $post->ID, $viewed_products ) ) {
			$viewed_products[] = $post->ID;
		}

		if ( count( $viewed_products ) > 15 ) {
			array_shift( $viewed_products );
		}

		// Store for session only.
		wc_setcookie( 'mas_elementor_wc_recently_viewed', implode( '|', $viewed_products ) );
	}
}
