<?php
/**
 * Multipurpose Text Widget.
 *
 * @package mas-elementor
 */

namespace MASElementor\Modules\MultipurposeText\Widgets;

use MASElementor\Base\Base_Widget;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use MASElementor\Plugin;
use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Multipurpose Text
 */
class Multipurpose_Text extends Base_Widget {

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'multipurpose-text';
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Multipurpose Text', 'mas-elementor' );
	}

	/**
	 * Get the icon for the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-heading';
	}

	/**
	 * Get the categories for the widget.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( 'mas' );
	}

	/**
	 * Register controls for this widget.
	 *
	 * @return void
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_title',
			array(
				'label' => esc_html__( 'Title', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'before_title',
			array(
				'label'       => esc_html__( 'Before Highlighted Text', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'placeholder' => esc_html__( 'Enter your title', 'mas-elementor' ),
				'default'     => 'Welcome to ',
				'description' => esc_html__( 'Use <br> to break into two lines', 'mas-elementor' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'highlighted_text',
			array(
				'label'       => esc_html__( 'Highlighted Text', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => '',
				'label_block' => true,
			)
		);

		$this->add_control(
			'typing_text',
			array(
				'label'              => esc_html__( 'Typing Text', 'mas-elementor' ),
				'description'        => esc_html__( 'Enter each word in a separate line', 'mas-elementor' ),
				'type'               => Controls_Manager::TEXTAREA,
				'placeholder'        => esc_html__( 'Enter Typing Text', 'mas-elementor' ),
				'separator'          => 'none',
				'default'            => "startup.\nfuture.\nsuccess.",
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'after_title',
			array(
				'label'       => esc_html__( 'After Highlighted Text', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => '',
				'placeholder' => esc_html__( 'Enter your title', 'mas-elementor' ),
				'description' => esc_html__( 'Use <br> to break into two lines', 'mas-elementor' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'link',
			array(
				'label'     => esc_html__( 'Link', 'mas-elementor' ),
				'type'      => Controls_Manager::URL,
				'dynamic'   => array(
					'active' => true,
				),
				'default'   => array(
					'url' => '',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'link_css',
			array(
				'label'       => esc_html__( 'Anchor Tag CSS', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Additional CSS classes separated by space that you\'d like to apply to the <a> tag', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'header_size',
			array(
				'label'   => esc_html__( 'HTML Tag', 'mas-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'h1'    => 'H1',
					'h2'    => 'H2',
					'h3'    => 'H3',
					'h4'    => 'H4',
					'h5'    => 'H5',
					'h6'    => 'H6',
					'div'   => 'div',
					'span'  => 'span',
					'small' => 'small',
					'p'     => 'p',
				),
				'default' => 'h2',
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'     => esc_html__( 'Alignment', 'mas-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'mas-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => esc_html__( 'Center', 'mas-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'   => array(
						'title' => esc_html__( 'Right', 'mas-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => esc_html__( 'Justified', 'mas-elementor' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_highlighted_style',
			array(
				'label' => esc_html__( 'Title', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Title Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-multipurpose-text__title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .mas-elementor-multipurpose-text__title',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
			)
		);

		$this->add_control(
			'title_css',
			array(
				'label'       => esc_html__( 'Additional CSS', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Additional CSS classes separated by space that you\'d like to apply to the title', 'mas-elementor' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_title_style',
			array(
				'label' => esc_html__( 'Highlighted Text', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'highlight_color',
			array(
				'label'     => esc_html__( 'Highlight Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-multipurpose-text__highlighted-text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'highlighted_typography',
				'selector' => '{{WRAPPER}} .mas-elementor-multipurpose-text__highlighted-text',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
			)
		);

		$this->add_control(
			'before_css',
			array(
				'label'       => esc_html__( 'Before Text CSS', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Additional CSS classes separated by space that you\'d like to apply to the before highlighted text', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'highlighted_css',
			array(
				'label'       => esc_html__( 'Highlighted CSS', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Additional CSS classes separated by space that you\'d like to apply to the highlighted text', 'mas-elementor' ),
			)
		);

		$this->add_control(
			'after_css',
			array(
				'label'       => esc_html__( 'After Text CSS', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Additional CSS classes separated by space that you\'d like to apply to the after highlighted text', 'mas-elementor' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'heading_words_style',
			array(
				'label' => esc_html__( 'Typed Text', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'words_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-headline-dynamic-text' => 'color: {{VALUE}}',
				),
				'default'   => '#42BA96',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'words_typography',
				'selector' => '{{WRAPPER}} .mas-elementor-headline-dynamic-text',
				'exclude'  => array( 'font_size' ),
			)
		);

		$this->add_control(
			'words_color_underline',
			array(
				'label'     => esc_html__( 'Underline Color', 'mas-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-headline-dynamic-text_underline' => 'border-bottom-color: {{VALUE}}; border-bottom-width: 12px; border-bottom-style: solid;',
				),
				'default'   => '#fae4cc',
			)
		);

		$this->add_control(
			'typing_text_classes',
			array(
				'label'       => esc_html__( 'Typed Text CSS Classes', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic'     => array(
					'active' => true,
				),
				'title'       => esc_html__( 'Add your custom class WITHOUT the dot. e.g: my-class', 'mas-elementor' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_heading',
			array(
				'label' => esc_html__( 'Style', 'mas-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'width',
			array(
				'label'          => esc_html__( 'Width', 'mas-elementor' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'unit' => '%',
				),
				'tablet_default' => array(
					'unit' => '%',
				),
				'mobile_default' => array(
					'unit' => '%',
				),
				'size_units'     => array( '%', 'px', 'vw' ),
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
					'{{WRAPPER}} .mas-elementor-multipurpose-text__title' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'space',
			array(
				'label'          => esc_html__( 'Max Width', 'mas-elementor' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'unit' => '%',
				),
				'tablet_default' => array(
					'unit' => '%',
				),
				'mobile_default' => array(
					'unit' => '%',
				),
				'size_units'     => array( '%', 'px', 'vw' ),
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
					'{{WRAPPER}} .mas-elementor-multipurpose-text__title' => 'max-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'height',
			array(
				'label'          => esc_html__( 'Height', 'mas-elementor' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'unit' => 'px',
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'size_units'     => array( 'px', 'vh' ),
				'range'          => array(
					'px' => array(
						'min' => 1,
						'max' => 500,
					),
					'vh' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'      => array(
					'{{WRAPPER}} .mas-elementor-multipurpose-text__title' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

	}

	/**
	 * Render.
	 *
	 * @return void
	 */
	protected function render() {
		mas_elementor_get_template( 'widgets/multipurpose-text.php', array( 'widget' => $this ) );
	}
}
