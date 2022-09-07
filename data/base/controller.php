<?php
/**
 * Controller.
 *
 * @package MASElementor\Dta\Base
 */

namespace MASElementor\Data\Base;

use Elementor\Data\Base\Controller as Controller_Base;

/**
 * The MAS Elementor controller base class.
 */
abstract class Controller extends Controller_Base {

	/**
	 * Instantiate the class.
	 */
	public function __construct() {
		parent::__construct();

		$this->namespace = 'mas-elementor/v1';
	}
}
