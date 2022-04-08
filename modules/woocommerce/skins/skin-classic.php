<?php
/**
 * The Skin Classic.
 *
 * @package MASElementor/Modules/Woocommerce/Skins
 */

namespace MASElementor\Modules\Woocommerce\Skins;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use MASElementor\Modules\Woocommerce\Module;
use MASElementor\Modules\Woocommerce\Widgets\Products;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Skin_Classic
 */
class Skin_Classic extends Skin_Base {

	/**
	 * Get the id of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'classic';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Classic', 'mas-elementor' );
	}

	/**
	 * Render
	 */
	public function render() {
		$widget = $this->parent;
		if ( WC()->session ) {
			wc_print_notices();
		}

		// For Products_Renderer.
		if ( ! isset( $GLOBALS['post'] ) ) {
			$GLOBALS['post'] = null; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		}

		$settings = $widget->get_settings();

		$shortcode = $widget->get_shortcode_object( $settings );

		$content = $shortcode->mas_product_classic();

		if ( $content ) {
			echo $content; //phpcs:ignore
		} elseif ( $widget->get_settings( 'nothing_found_message' ) ) {
			echo '<div class="elementor-nothing-found elementor-products-nothing-found">' . esc_html( $widget->get_settings( 'nothing_found_message' ) ) . '</div>'; //phpcs:ignore
		}
	}

	/**
	 * Render amp.
	 */
	public function render_amp() {

	}
}
