<?php
/**
 * The base widget abstract class.
 *
 * @package mas-elementor
 */

namespace MASElementor\Base;

use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The Base Widget class.
 */
abstract class Base_Widget extends Widget_Base {

	use Base_Widget_Trait;
}
