<?php
/**
 * The Skin Grid.
 *
 * @package MASElementor/Modules/Woocommerce/Skins
 */

namespace MASElementor\Modules\Woocommerce\Skins;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use MASElementor\Modules\Woocommerce\Module;
use MASElementor\Modules\Woocommerce\Widgets\Products;
use MASElementor\Modules\Woocommerce\Classes\Products_Renderer;
use MASElementor\Modules\Woocommerce\Classes\Current_Query_Renderer;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Skin_Grid
 */
class Skin_Grid extends Skin_Base {

	/**
	 * Get the id of the skin.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'grid';
	}

	/**
	 * Get the title of the skin.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Grid', 'mas-elementor' );
	}

	/**
	 * Render
	 */
	public function render() {
		$widget   = $this->parent;
		$settings = $widget->get_settings_for_display();
		if ( WC()->session ) {
			wc_print_notices();
		}

		// For Products_Renderer.
		if ( ! isset( $GLOBALS['post'] ) ) {
			$GLOBALS['post'] = null; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		}

		$settings = $widget->get_settings();

		$template = $this->mas_template_path();

		$shortcode = $this->get_shortcode_object( $settings, $template );

		$content = $shortcode->mas_product_content();

		if ( $content ) {
			echo $content; //phpcs:ignore
		} elseif ( $widget->get_settings( 'nothing_found_message' ) ) {
			echo '<div class="elementor-nothing-found elementor-products-nothing-found">' . esc_html( $widget->get_settings( 'nothing_found_message' ) ) . '</div>'; //phpcs:ignore
		}
	}

	/**
	 * MAS Elementor Template Path.
	 */
	public function mas_template_path() {
		$args = array(
			'widget' => $this->parent,
			'skin'   => $this,
		);
		return array(
			'path' => 'widgets/product-classic.php',
			'args' => $args,
		);
	}

}
