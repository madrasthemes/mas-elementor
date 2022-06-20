<?php
/**
 * The Skin Base.
 *
 * @package MASElementor/Modules/Woocommerce/Skins
 */

namespace MASElementor\Modules\Woocommerce\Skins;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Skin_Base as Elementor_Skin_Base;
use Elementor\Widget_Base;
use MASElementor\Plugin;
use MASElementor\Modules\Woocommerce\Classes;
use MASElementor\Modules\Woocommerce\Classes\Products_Renderer;
use MASElementor\Modules\Woocommerce\Classes\Current_Query_Renderer;
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The posts skin base
 */
abstract class Skin_Base extends Elementor_Skin_Base {

	/**
	 * Get shortcode object
	 *
	 * @param array $settings settings of the widget.
	 * @param array $template template path and arguments.
	 */
	public function get_shortcode_object( $settings, $template = array( 'path' => 'widgets/product-classic.php', 'args' => array() ) ) { //phpcs:ignore
		if ( 'current_query' === $settings[ Products_Renderer::QUERY_CONTROL_NAME . '_post_type' ] ) {
			$type = 'current_query';
			return new Current_Query_Renderer( $settings, $type );
		}
		$type = 'products';
		return new Products_Renderer( $settings, $type, $template );
	}
}
