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

}
