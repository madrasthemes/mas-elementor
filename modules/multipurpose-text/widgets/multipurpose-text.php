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
		return esc_html__( 'Multipurpose Text', 'mas-addons-for-elementor' );
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
		return array( 'mas-elements' );
	}

	/**
	 * Get the script dependencies for this widget.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array( 'mas-typed', 'mas-typed-init' );
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
				'label' => esc_html__( 'Title', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'before_title',
			array(
				'label'       => esc_html__( 'Before Highlighted Text', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'placeholder' => esc_html__( 'Enter your title', 'mas-addons-for-elementor' ),
				'default'     => 'Welcome to ',
				'description' => esc_html__( 'Use <br> to break into two lines', 'mas-addons-for-elementor' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'highlighted_text',
			array(
				'label'       => esc_html__( 'Highlighted Text', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => '',
				'label_block' => true,
			)
		);

		$this->add_control(
			'after_title',
			array(
				'label'       => esc_html__( 'After Highlighted Text', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => '',
				'placeholder' => esc_html__( 'Enter your title', 'mas-addons-for-elementor' ),
				'description' => esc_html__( 'Use <br> to break into two lines', 'mas-addons-for-elementor' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'enable_typing_text',
			array(
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Typing Text', 'mas-addons-for-elementor' ),
				'default'   => 'no',
				'label_off' => esc_html__( 'Disable', 'mas-addons-for-elementor' ),
				'label_on'  => esc_html__( 'Enable', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'typing_text',
			array(
				'label'              => esc_html__( 'Typing Text', 'mas-addons-for-elementor' ),
				'description'        => esc_html__( 'Enter each word in a separate line', 'mas-addons-for-elementor' ),
				'type'               => Controls_Manager::TEXTAREA,
				'placeholder'        => esc_html__( 'Enter Typing Text', 'mas-addons-for-elementor' ),
				'separator'          => 'before',
				'default'            => "startup.\nfuture.\nsuccess.",
				'frontend_available' => true,
				'condition'          => array(
					'enable_typing_text' => 'yes',
				),
			)
		);

		$this->add_control(
			'type_speed',
			array(
				'label'     => esc_html__( 'Typing Speed', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => '40',
				),
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 5000,
						'step' => 10,
					),
				),
				'condition' => array(
					'typing_text!'       => '',
					'enable_typing_text' => 'yes',
				),
			)
		);

		$this->add_control(
			'back_speed',
			array(
				'label'     => esc_html__( 'Back Speed', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => '40',
				),
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 5000,
						'step' => 10,
					),
				),
				'condition' => array(
					'typing_text!'       => '',
					'enable_typing_text' => 'yes',
				),
			)
		);

		$this->add_control(
			'back_delay',
			array(
				'label'     => esc_html__( 'Back Delay', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => '500',
				),
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 5000,
						'step' => 50,
					),
				),
				'condition' => array(
					'typing_text!'       => '',
					'enable_typing_text' => 'yes',
				),
			)
		);

		$this->add_control(
			'typing_loop',
			array(
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Enable Typing Loop', 'mas-addons-for-elementor' ),
				'default'   => 'yes',
				'label_off' => esc_html__( 'Disable', 'mas-addons-for-elementor' ),
				'label_on'  => esc_html__( 'Enable', 'mas-addons-for-elementor' ),
				'condition' => array(
					'typing_text!'       => '',
					'enable_typing_text' => 'yes',
				),
			)
		);

		$this->add_control(
			'link',
			array(
				'label'     => esc_html__( 'Link', 'mas-addons-for-elementor' ),
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
				'label'       => esc_html__( 'Anchor Tag CSS', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Additional CSS classes separated by space that you\'d like to apply to the <a> tag', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'header_size',
			array(
				'label'   => esc_html__( 'HTML Tag', 'mas-addons-for-elementor' ),
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
				'label'     => esc_html__( 'Alignment', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => esc_html__( 'Center', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'   => array(
						'title' => esc_html__( 'Right', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => esc_html__( 'Justified', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'text_overflow',
			array(
				'label'     => esc_html__( 'Text Overflow', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => array(
					''       => esc_html__( 'Default', 'mas-addons-for-elementor' ),
					'hidden' => esc_html__( 'Hidden', 'mas-addons-for-elementor' ),
					'auto'   => esc_html__( 'Auto', 'mas-addons-for-elementor' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-multipurpose-text__title' => 'overflow: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_highlighted_style',
			array(
				'label' => esc_html__( 'Title', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'section_highlighted_title_tab' );

			$this->start_controls_tab(
				'normal',
				array(
					'label' => esc_html__( 'Normal', 'mas-addons-for-elementor' ),
				)
			);

			$this->add_control(
				'title_color',
				array(
					'label'     => esc_html__( 'Title Color', 'mas-addons-for-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'global'    => array(
						'default' => Global_Colors::COLOR_PRIMARY,
					),
					'selectors' => array(
						'{{WRAPPER}} .mas-elementor-multipurpose-text__title' => 'color: {{VALUE}} !important;',
						'{{WRAPPER}} .mas-elementor-multipurpose-text__title a' => 'color: {{VALUE}} !important;',
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

			$this->end_controls_tab();

			$this->start_controls_tab(
				'hover',
				array(
					'label' => esc_html__( 'Hover', 'mas-addons-for-elementor' ),
				)
			);

			$this->add_control(
				'card_hover',
				array(
					'label'        => esc_html__( 'Card Hover', 'mas-addons-for-elementor' ),
					'type'         => Controls_Manager::SWITCHER,
					'return_value' => 'enable',
					'description'  => esc_html__( 'Below title color and typography will work on hovering card on mas-post for products', 'mas-addons-for-elementor' ),
					'prefix_class' => 'mas-product-card-hover-',
				)
			);

			$this->add_control(
				'hover_title_color',
				array(
					'label'     => esc_html__( 'Title Color', 'mas-addons-for-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'global'    => array(
						'default' => Global_Colors::COLOR_PRIMARY,
					),
					'selectors' => array(
						'{{WRAPPER}}:hover .mas-elementor-multipurpose-text__title' => 'color: {{VALUE}} !important;',
						'{{WRAPPER}}:hover .mas-elementor-multipurpose-text__title a' => 'color: {{VALUE}} !important;',
						'.mas-product:hover {{WRAPPER}}.mas-product-card-hover-enable .mas-elementor-multipurpose-text__title' => 'color: {{VALUE}} !important;',
						'.mas-product:hover {{WRAPPER}}.mas-product-card-hover-enable .mas-elementor-multipurpose-text__title a' => 'color: {{VALUE}} !important;',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'hover_title_typography',
					'selector' => '{{WRAPPER}}:hover .mas-elementor-multipurpose-text__title, .mas-product:hover {{WRAPPER}}.mas-product-card-hover-enable .mas-elementor-multipurpose-text__title',
					'global'   => array(
						'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
					),
				)
			);

			$this->add_control(
				'underline_offset',
				array(
					'label'     => esc_html__( 'Underline Offset', 'mas-addons-for-elementor' ),
					'type'      => Controls_Manager::SLIDER,
					'default'   => array(
						'size' => 1,
						'unit' => 'px',
					),
					'selectors' => array(
						'{{WRAPPER}} .mas-elementor-multipurpose-text__title' => 'text-underline-offset: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'title_css',
			array(
				'label'       => esc_html__( 'Additional CSS', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Additional CSS classes separated by space that you\'d like to apply to the title', 'mas-addons-for-elementor' ),
				'separator'   => 'before',
			)
		);

		$this->register_webkit_style_controls( 'title', '{{WRAPPER}} .mas-elementor-multipurpose-text__title' );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_title_style',
			array(
				'label' => esc_html__( 'Highlighted Text', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'section_highlight_title_tab' );

			$this->start_controls_tab(
				'highlight_normal',
				array(
					'label' => esc_html__( 'Normal', 'mas-addons-for-elementor' ),
				)
			);

			$this->add_control(
				'highlight_color',
				array(
					'label'     => esc_html__( 'Highlight Color', 'mas-addons-for-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'global'    => array(
						'default' => Global_Colors::COLOR_PRIMARY,
					),
					'selectors' => array(
						'{{WRAPPER}} .mas-elementor-multipurpose-text__highlighted-text' => 'color: {{VALUE}};',
						'{{WRAPPER}} .mas-elementor-multipurpose-text__highlighted-text a' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'highlighted_typography',
					'selector' => '{{WRAPPER}} .mas-elementor-multipurpose-text__highlighted-text, {{WRAPPER}} .mas-elementor-multipurpose-text__highlighted-text a',
					'global'   => array(
						'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
					),
				)
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'highlight_hover',
				array(
					'label' => esc_html__( 'Hover', 'mas-addons-for-elementor' ),
				)
			);

			$this->add_control(
				'hover_highlight_color',
				array(
					'label'     => esc_html__( 'Highlight Color', 'mas-addons-for-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'global'    => array(
						'default' => Global_Colors::COLOR_PRIMARY,
					),
					'selectors' => array(
						'{{WRAPPER}} .mas-elementor-multipurpose-text__highlighted-text:hover' => 'color: {{VALUE}};',
						'{{WRAPPER}} .mas-elementor-multipurpose-text__highlighted-text a:hover' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'hover_highlighted_typography',
					'selector' => '{{WRAPPER}} .mas-elementor-multipurpose-text__highlighted-text:hover, {{WRAPPER}} .mas-elementor-multipurpose-text__highlighted-text a:hover',
					'global'   => array(
						'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
					),
				)
			);

			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'before_css',
			array(
				'label'       => esc_html__( 'Before Text CSS', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Additional CSS classes separated by space that you\'d like to apply to the before highlighted text', 'mas-addons-for-elementor' ),
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'highlighted_css',
			array(
				'label'       => esc_html__( 'Highlighted CSS', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Additional CSS classes separated by space that you\'d like to apply to the highlighted text', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'after_css',
			array(
				'label'       => esc_html__( 'After Text CSS', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Additional CSS classes separated by space that you\'d like to apply to the after highlighted text', 'mas-addons-for-elementor' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'heading_words_style',
			array(
				'label'     => esc_html__( 'Typed Text', 'mas-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'typing_text!'       => '',
					'enable_typing_text' => 'yes',
				),

			)
		);

		$this->start_controls_tabs( 'section_heading_words_tab' );

			$this->start_controls_tab(
				'words_normal',
				array(
					'label' => esc_html__( 'Normal', 'mas-addons-for-elementor' ),
				)
			);

			$this->add_control(
				'words_color',
				array(
					'label'     => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
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
				)
			);

			$this->add_control(
				'words_color_underline',
				array(
					'label'     => esc_html__( 'Underline Color', 'mas-addons-for-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .mas-elementor-headline-dynamic-text_underline' => 'border-bottom-color: {{VALUE}}; border-bottom-width: 12px; border-bottom-style: solid;',
					),
					'default'   => '#fae4cc',
				)
			);

			$this->add_responsive_control(
				'underline_width',
				array(
					'label'          => esc_html__( 'Underline Width', 'mas-addons-for-elementor' ),
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
						'{{WRAPPER}} .mas-elementor-headline-dynamic-text_underline' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'words_hover',
				array(
					'label' => esc_html__( 'Hover', 'mas-addons-for-elementor' ),
				)
			);

			$this->add_control(
				'hover_words_color',
				array(
					'label'     => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}}:hover .mas-elementor-headline-dynamic-text' => 'color: {{VALUE}}',
					),
					'default'   => '#42BA96',
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'hover_words_typography',
					'selector' => '{{WRAPPER}}:hover .mas-elementor-headline-dynamic-text',
				)
			);

			$this->add_control(
				'hover_words_color_underline',
				array(
					'label'     => esc_html__( 'Underline Color', 'mas-addons-for-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}}:hover .mas-elementor-headline-dynamic-text_underline' => 'border-bottom-color: {{VALUE}}; border-bottom-style: solid;',
					),
					'default'   => '#fae4cc',
				)
			);

			$this->add_responsive_control(
				'hover_underline_width',
				array(
					'label'          => esc_html__( 'Underline Width', 'mas-addons-for-elementor' ),
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
						'{{WRAPPER}}:hover .mas-elementor-headline-dynamic-text_underline' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'typing_text_classes',
			array(
				'label'       => esc_html__( 'Typed Text CSS Classes', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic'     => array(
					'active' => true,
				),
				'separator'   => 'before',
				'title'       => esc_html__( 'Add your custom class WITHOUT the dot. e.g: my-class', 'mas-addons-for-elementor' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_heading',
			array(
				'label' => esc_html__( 'Content', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'width',
			array(
				'label'          => esc_html__( 'Width', 'mas-addons-for-elementor' ),
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
				'label'          => esc_html__( 'Max Width', 'mas-addons-for-elementor' ),
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
				'label'          => esc_html__( 'Height', 'mas-addons-for-elementor' ),
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

		$this->add_responsive_control(
			'title_tag_margin',
			array(
				'label'      => esc_html__( 'Title Margin', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .mas-elementor-multipurpose-text__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

	}

	/**
	 * Register controls for this widget.
	 *
	 * @param string $name Control name.
	 * @param string $wrapper Selectors.
	 *
	 * @return void
	 */
	protected function register_webkit_style_controls( $name, $wrapper ) {
		$this->add_responsive_control(
			$name . '_enable_webkit',
			array(
				'label'     => esc_html__( 'Webkit', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex'        => array(
						'title' => esc_html__( 'Flex', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-wrap',
					),
					'-webkit-box' => array(
						'title' => esc_html__( 'Webkit Box', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-ban',
					),
				),
				'selectors' => array(
					$wrapper => 'display: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			$name . '_webkit_orient',
			array(
				'label'     => esc_html__( 'Webkit Orient', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'vertical' => 'Vertical',
				),
				'selectors' => array(
					$wrapper => '-webkit-box-orient: {{VALUE}}; overflow:hidden;',
				),
				'condition' => array(
					$name . '_enable_webkit' => '-webkit-box',
				),
			)
		);

		$this->add_responsive_control(
			$name . '_webkit_line_clamp',
			array(
				'label'       => esc_html__( 'Webkit Line Clamp', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'selectors'   => array(
					$wrapper => '-webkit-line-clamp: {{VALUE}};',
				),
				'min'         => 1,
				'placeholder' => 1,
				'condition'   => array(
					$name . '_enable_webkit' => '-webkit-box',
				),
			)
		);
	}

	/**
	 * Get typed options
	 *
	 * @param array $settings The widget settings.
	 * @return array
	 */
	protected function get_typed_options( $settings ) {
		$typed_strings = explode( "\n", $settings['typing_text'] );
		array_unshift( $typed_strings, '' );
		$typed_options = array(
			'strings' => $typed_strings,
			'loop'    => 'yes' === $settings['typing_loop'] ? true : false,
		);

		if ( 'yes' === $settings['enable_typing_text'] && ! empty( $settings['typing_text'] ) ) {
			$typed_options['typeSpeed'] = ! empty( (int) $settings['type_speed']['size'] ) ? (int) $settings['type_speed']['size'] : '40';
			$typed_options['backSpeed'] = ! empty( (int) $settings['back_speed']['size'] ) ? (int) $settings['back_speed']['size'] : '40';
			$typed_options['backDelay'] = ! empty( (int) $settings['back_delay']['size'] ) ? (int) $settings['back_delay']['size'] : '500';
		}
		return $typed_options;
	}

	/**
	 * Render.
	 *
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$typed_id = uniqid( 'typed-text-' );

		$settings['typed_id'] = $typed_id;

		if ( 'yes' === $settings['enable_typing_text'] && ! empty( $settings['typing_text'] ) ) {
			$this->add_render_attribute(
				'typing_text',
				array(
					'class'              => 'typed-text',
					'data-typed-options' => wp_json_encode( $this->get_typed_options( $settings ) ),
					'id'                 => $typed_id,
				)
			);
		}
		mas_elementor_get_template( 'widgets/multipurpose-text/multipurpose-text.php', array( 'widget' => $this ) );
		$this->render_script( $settings );
	}

	/**
	 * Render script in the editor.
	 *
	 * @param array $settings The widget settings.
	 */
	public function render_script( $settings ) {
		if ( Plugin::elementor()->editor->is_edit_mode() && 'yes' === $settings['enable_typing_text'] && ! empty( $settings['typing_text'] ) ) :
			$typed_options = $this->get_typed_options( $settings );
			?>

		<script type="text/javascript">
			var animatedHeadline = new Typed( '#<?php echo esc_attr( $settings['typed_id'] ); ?>', <?php echo wp_json_encode( $typed_options ); ?> );
		</script>
			<?php

		endif;
	}
}
