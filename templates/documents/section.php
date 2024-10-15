<?php

namespace MASElementor\Templates\Documents;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
// phpcs:ignoreFile
class Premium_Section_Document extends Premium_Document_Base {

	public function get_name() {
		return 'section';
	}

	public static function get_title() {
		return esc_html__( 'Section', 'mas-addons-for-elementor' );
	}

	public function has_conditions() {
		return false;
	}

}
