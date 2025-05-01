<?php
/**
 * Image.
 *
 * @package MASElementor\Modules\Image
 */

namespace MASElementor\Modules\Image;

use MASElementor\Base\Module_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Module
 */
class Module extends Module_Base {

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();

		$this->add_actions();
	}

	/**
	 * Get Name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'image';
	}

	/**
	 * Add Action.
	 *
	 * @return void
	 */
	public function add_actions() {
		add_action( 'elementor/element/image/section_style_image/before_section_end', array( $this, 'add_content_style_controls' ), 15 );
	}

	/**
	 * Add style controls to the element.
	 *
	 * @param Element $element element object.
	 */
	public function add_content_style_controls( $element ) {

		$element->add_responsive_control(
			'a_tag_width',
			array(
				'label'          => esc_html__( 'Wrapper Width', 'mas-addons-for-elementor' ),
				'type'           => Controls_Manager::SLIDER,
				'description'    => esc_html__( 'Width for a tag of image', 'mas-addons-for-elementor' ),
				'default'        => array(
					'unit' => '%',
				),
				'tablet_default' => array(
					'unit' => '%',
				),
				'mobile_default' => array(
					'unit' => '%',
				),
				'size_units'     => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'range'          => array(
					'%'  => array(
						'min' => 1,
						'max' => 100,
					),
					'px' => array(
						'min' => 1,
						'max' => 1000,
					),
					'vw' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'      => array(
					'{{WRAPPER}} a' => 'width: {{SIZE}}{{UNIT}};',
				),
			),
			array(
				'position' => array(
					'at' => 'before',
					'of' => 'width',
				),
			)
		);

		$element->add_control(
			'enable_img_aspect_ratio',
			array(
				'type'    => Controls_Manager::SWITCHER,
				'label'   => esc_html__( 'Enable Aspect Ratio', 'mas-addons-for-elementor' ),
				'default' => 'no',
			),
			array(
				'position' => array(
					'at' => 'before',
					'of' => 'width',
				),
			)
		);

		$element->add_responsive_control(
			'img_aspect_ratio',
			array(
				'label' => esc_html__( 'Aspect Ratio', 'mas-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => array(
					'169' => '16:9',
					'219' => '21:9',
					'43' => '4:3',
					'32' => '3:2',
					'11' => '1:1',
					'916' => '9:16',
				),
				'selectors_dictionary' => array(
					'169' => '1.77777', // 16 / 9
					'219' => '2.33333', // 21 / 9
					'43' => '1.33333', // 4 / 3
					'32' => '1.5', // 3 / 2
					'11' => '1', // 1 / 1
					'916' => '0.5625', // 9 / 16
				),
				'selectors' => array(
					'{{WRAPPER}} img' => 'aspect-ratio: {{VALUE}}',
				),
				'condition' => array(
					'enable_img_aspect_ratio' => 'yes',
				),
			),
			array(
				'position' => array(
					'at' => 'before',
					'of' => 'width',
				),
			)
		);
	}

}
