<?php
/**
 * Blockquote Widget.
 *
 * @package mas-elementor
 */

namespace MASElementor\Modules\Blockquote\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Plugin;
use MASElementor\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Blockquote Widget.
 */
class Blockquote extends Base_Widget {

	/**
	 * Get style dependencies.
	 *
	 * @return array Element styles dependencies.
	 */
	public function get_style_depends() {
		$styles = array( 'mas-blockquote' );
		if ( Icons_Manager::is_migration_allowed() ) {
			$styles[] = 'elementor-icons-fa-brands';
		}

		return $styles;
	}

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-blockquote';
	}

	/**
	 * Get the name of the title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Blockquote', 'mas-addons-for-elementor' );
	}

	/**
	 * Get the name of the icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-blockquote';
	}

	/**
	 * Get the keywords associated with the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'blockquote', 'quote', 'paragraph', 'testimonial', 'text', 'twitter', 'tweet' );
	}

	/**
	 * Register controls for this widget.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_blockquote_content',
			array(
				'label' => esc_html__( 'Blockquote', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'blockquote_skin',
			array(
				'label'        => esc_html__( 'Skin', 'mas-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => array(
					'border'    => esc_html__( 'Border', 'mas-addons-for-elementor' ),
					'quotation' => esc_html__( 'Quotation', 'mas-addons-for-elementor' ),
					'boxed'     => esc_html__( 'Boxed', 'mas-addons-for-elementor' ),
					'clean'     => esc_html__( 'Clean', 'mas-addons-for-elementor' ),
				),
				'default'      => 'border',
				'prefix_class' => 'mas-elementor-blockquote--skin-',
			)
		);

		$this->add_control(
			'alignment',
			array(
				'label'        => esc_html__( 'Alignment', 'mas-addons-for-elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'prefix_class' => 'mas-elementor-blockquote--align-',
				'condition'    => array(
					'blockquote_skin!' => 'border',
				),
				'separator'    => 'after',
			)
		);

		$this->add_control(
			'blockquote_content',
			array(
				'label'       => esc_html__( 'Content', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'mas-addons-for-elementor' ) . esc_html__( 'Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'mas-addons-for-elementor' ),
				'placeholder' => esc_html__( 'Enter your quote', 'mas-addons-for-elementor' ),
				'rows'        => 10,
			)
		);

		$this->add_control(
			'author_name',
			array(
				'label'     => esc_html__( 'Author', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => array(
					'active' => true,
				),
				'default'   => esc_html__( 'John Doe', 'mas-addons-for-elementor' ),
				'separator' => 'after',
			)
		);

		$this->add_control(
			'tweet_button',
			array(
				'label'     => esc_html__( 'Tweet Button', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'On', 'mas-addons-for-elementor' ),
				'label_off' => esc_html__( 'Off', 'mas-addons-for-elementor' ),
				'default'   => 'yes',
			)
		);

		$this->add_control(
			'tweet_button_view',
			array(
				'label'        => esc_html__( 'View', 'mas-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => array(
					'icon-text' => 'Icon & Text',
					'icon'      => 'Icon',
					'text'      => 'Text',
				),
				'prefix_class' => 'mas-elementor-blockquote--button-view-',
				'default'      => 'icon-text',
				'render_type'  => 'template',
				'condition'    => array(
					'tweet_button' => 'yes',
				),
			)
		);

		$this->add_control(
			'tweet_button_skin',
			array(
				'label'        => esc_html__( 'Skin', 'mas-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => array(
					'classic' => 'Classic',
					'bubble'  => 'Bubble',
					'link'    => 'Link',
				),
				'default'      => 'classic',
				'prefix_class' => 'mas-elementor-blockquote--button-skin-',
				'condition'    => array(
					'tweet_button' => 'yes',
				),
			)
		);

		$this->add_control(
			'tweet_button_label',
			array(
				'label'     => esc_html__( 'Label', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Tweet', 'mas-addons-for-elementor' ),
				'condition' => array(
					'tweet_button'       => 'yes',
					'tweet_button_view!' => 'icon',
				),
				'dynamic'   => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'user_name',
			array(
				'label'       => esc_html__( 'Username', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => '@username',
				'condition'   => array(
					'tweet_button' => 'yes',
				),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'url_type',
			array(
				'label'     => esc_html__( 'Target URL', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'current_page' => esc_html__( 'Current Page', 'mas-addons-for-elementor' ),
					'none'         => esc_html__( 'None', 'mas-addons-for-elementor' ),
					'custom'       => esc_html__( 'Custom', 'mas-addons-for-elementor' ),
				),
				'default'   => 'current_page',
				'condition' => array(
					'tweet_button' => 'yes',
				),
			)
		);

		$this->add_control(
			'url',
			array(
				'label'       => esc_html__( 'Link', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'input_type'  => 'url',
				'dynamic'     => array(
					'active'     => true,
					'categories' => array(
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					),
				),
				'placeholder' => esc_html__( 'https://your-link.com', 'mas-addons-for-elementor' ),
				'label_block' => true,
				'condition'   => array(
					'url_type' => 'custom',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_style',
			array(
				'label' => esc_html__( 'Content', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'content_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_TEXT,
				),
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-blockquote__content' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'content_typography',
				'selector' => '{{WRAPPER}} .mas-elementor-blockquote__content',
			)
		);

		$this->add_responsive_control(
			'content_gap',
			array(
				'label'     => esc_html__( 'Gap', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-blockquote__content +footer' => 'margin-top: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'heading_author_style',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Author', 'mas-addons-for-elementor' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'author_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_SECONDARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-blockquote__author' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'author_typography',
				'selector' => '{{WRAPPER}} .mas-elementor-blockquote__author',
			)
		);

		$this->add_responsive_control(
			'author_gap',
			array(
				'label'     => esc_html__( 'Gap', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 20,
				),
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-blockquote__author' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
				'condition' => array(
					'alignment'    => 'center',
					'tweet_button' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button_style',
			array(
				'label' => esc_html__( 'Button', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'button_size',
			array(
				'label'     => esc_html__( 'Size', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0.5,
						'max'  => 2,
						'step' => 0.1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-blockquote__tweet-button' => 'font-size: calc({{SIZE}}{{UNIT}} * 10);',
				),
			)
		);

		$this->add_control(
			'button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'selectors'  => array(
					'{{WRAPPER}} .mas-elementor-blockquote__tweet-button' => 'border-radius: {{SIZE}}{{UNIT}}',
				),
				'range'      => array(
					'em'  => array(
						'min'  => 0,
						'max'  => 5,
						'step' => 0.1,
					),
					'rem' => array(
						'min'  => 0,
						'max'  => 5,
						'step' => 0.1,
					),
					'px'  => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
					'%'   => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', '%', 'em', 'rem' ),
			)
		);

		$this->add_control(
			'button_color_source',
			array(
				'label'        => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => array(
					'official' => esc_html__( 'Official', 'mas-addons-for-elementor' ),
					'custom'   => esc_html__( 'Custom', 'mas-addons-for-elementor' ),
				),
				'default'      => 'official',
				'prefix_class' => 'mas-elementor-blockquote--button-color-',
			)
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label'     => esc_html__( 'Normal', 'mas-addons-for-elementor' ),
				'condition' => array(
					'button_color_source' => 'custom',
				),
			)
		);

		$this->add_control(
			'button_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-blockquote__tweet-button' => 'background-color: {{VALUE}}',
					'body:not(.rtl) {{WRAPPER}} .mas-elementor-blockquote__tweet-button:before, body {{WRAPPER}}.mas-elementor-blockquote--align-left .mas-elementor-blockquote__tweet-button:before' => 'border-right-color: {{VALUE}}; border-left-color: transparent',
					'body.rtl {{WRAPPER}} .mas-elementor-blockquote__tweet-button:before, body {{WRAPPER}}.mas-elementor-blockquote--align-right .mas-elementor-blockquote__tweet-button:before' => 'border-left-color: {{VALUE}}; border-right-color: transparent',
				),
				'condition' => array(
					'button_color_source' => 'custom',
					'tweet_button_skin!'  => 'link',
				),
			)
		);

		$this->add_control(
			'button_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-blockquote__tweet-button' => 'color: {{VALUE}}',
					'{{WRAPPER}} .mas-elementor-blockquote__tweet-button svg' => 'fill: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			array(
				'label'     => esc_html__( 'Hover', 'mas-addons-for-elementor' ),
				'condition' => array(
					'button_color_source' => 'custom',
				),
			)
		);

		$this->add_control(
			'button_background_color_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-blockquote__tweet-button:hover' => 'background-color: {{VALUE}}',

					'body:not(.rtl) {{WRAPPER}} .mas-elementor-blockquote__tweet-button:hover:before, body {{WRAPPER}}.mas-elementor-blockquote--align-left .mas-elementor-blockquote__tweet-button:hover:before' => 'border-right-color: {{VALUE}}; border-left-color: transparent',

					'body.rtl {{WRAPPER}} .mas-elementor-blockquote__tweet-button:before, body {{WRAPPER}}.mas-elementor-blockquote--align-right .mas-elementor-blockquote__tweet-button:hover:before' => 'border-left-color: {{VALUE}}; border-right-color: transparent',
				),
				'condition' => array(
					'button_color_source' => 'custom',
					'tweet_button_skin!'  => 'link',
				),
			)
		);

		$this->add_control(
			'button_text_color_hover',
			array(
				'label'     => esc_html__( 'Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-blockquote__tweet-button:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .mas-elementor-blockquote__tweet-button:hover svg' => 'fill: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$default_fonts = Plugin::$instance->kits_manager->get_current_settings( 'default_generic_fonts' );

		if ( $default_fonts ) {
			$default_fonts = ', ' . $default_fonts;
		}

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'button_typography',
				'selector'       => '{{WRAPPER}} .mas-elementor-blockquote__tweet-button span, {{WRAPPER}} .mas-elementor-blockquote__tweet-button i',
				'separator'      => 'before',
				'fields_options' => array(
					'font_family' => array(
						'selectors' => array(
							'{{WRAPPER}} .mas-elementor-blockquote__tweet-button' => 'font-family: "{{VALUE}}"' . $default_fonts . ';',
						),
					),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_border_style',
			array(
				'label'     => esc_html__( 'Border', 'mas-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'blockquote_skin' => 'border',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_border_style' );

		$this->start_controls_tab(
			'tab_border_normal',
			array(
				'label' => esc_html__( 'Normal', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'border_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-blockquote' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'border_width',
			array(
				'label'     => esc_html__( 'Width', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'body:not(.rtl) {{WRAPPER}} .mas-elementor-blockquote' => 'border-left-width: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .mas-elementor-blockquote' => 'border-right-width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'border_gap',
			array(
				'label'     => esc_html__( 'Gap', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'body:not(.rtl) {{WRAPPER}} .mas-elementor-blockquote' => 'padding-left: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .mas-elementor-blockquote' => 'padding-right: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_border_hover',
			array(
				'label' => esc_html__( 'Hover', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'border_color_hover',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-blockquote:hover' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'border_width_hover',
			array(
				'label'     => esc_html__( 'Width', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'body:not(.rtl) {{WRAPPER}} .mas-elementor-blockquote:hover' => 'border-left-width: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .mas-elementor-blockquote:hover' => 'border-right-width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'border_gap_hover',
			array(
				'label'     => esc_html__( 'Gap', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'body:not(.rtl) {{WRAPPER}} .mas-elementor-blockquote:hover' => 'padding-left: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .mas-elementor-blockquote:hover' => 'padding-right: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'border_vertical_padding',
			array(
				'label'     => esc_html__( 'Vertical Padding', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-blockquote' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}}',
				),
				'separator' => 'before',
				'condition' => array(
					'blockquote_skin' => 'border',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_box_style',
			array(
				'label'     => esc_html__( 'Box', 'mas-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'blockquote_skin' => 'boxed',
				),
			)
		);

		$this->add_responsive_control(
			'box_padding',
			array(
				'label'     => esc_html__( 'Padding', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-blockquote' => 'padding: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_box_style' );

		$this->start_controls_tab(
			'tab_box_normal',
			array(
				'label' => esc_html__( 'Normal', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'box_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-blockquote' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'box_border',
				'selector' => '{{WRAPPER}} .mas-elementor-blockquote',
			)
		);

		$this->add_responsive_control(
			'box_border_radius',
			array(
				'label'     => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-blockquote' => 'border-radius: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'box_box_shadow',
				'exclude'  => array(
					'box_shadow_position',
				),
				'selector' => '{{WRAPPER}} .mas-elementor-blockquote',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_box_hover',
			array(
				'label' => esc_html__( 'Hover', 'mas-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'box_background_color_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-blockquote:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'box_border_hover',
				'selector' => '{{WRAPPER}} .mas-elementor-blockquote:hover',
			)
		);

		$this->add_responsive_control(
			'box_border_radius_hover',
			array(
				'label'     => esc_html__( 'Border Radius', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-blockquote:hover' => 'border-radius: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'box_box_shadow_hover',
				'exclude'  => array(
					'box_shadow_position',
				),
				'selector' => '{{WRAPPER}} .mas-elementor-blockquote:hover',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_quote_style',
			array(
				'label'     => esc_html__( 'Quote', 'mas-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'blockquote_skin' => 'quotation',
				),
			)
		);

		$this->add_control(
			'quote_text_color',
			array(
				'label'     => esc_html__( 'Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-blockquote:before' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'quote_size',
			array(
				'label'     => esc_html__( 'Size', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0.5,
						'max'  => 2,
						'step' => 0.1,
					),
				),
				'default'   => array(
					'size' => 1,
				),
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-blockquote:before' => 'font-size: calc({{SIZE}}{{UNIT}} * 100)',
				),
			)
		);

		$this->add_responsive_control(
			'quote_gap',
			array(
				'label'     => esc_html__( 'Gap', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}} .mas-elementor-blockquote__content' => 'margin-top: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render.
	 *
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$tweet_button_view = $settings['tweet_button_view'];
		$share_link        = 'https://twitter.com/intent/tweet';

		$text = rawurlencode( $settings['blockquote_content'] );

		if ( ! empty( $settings['author_name'] ) ) {
			$text .= ' — ' . $settings['author_name'];
		}

		$share_link = add_query_arg( 'text', $text, $share_link );

		if ( 'current_page' === $settings['url_type'] ) {
			$share_link = add_query_arg( 'url', rawurlencode( home_url() . add_query_arg( false, false ) ), $share_link );
		} elseif ( 'custom' === $settings['url_type'] ) {
			$share_link = add_query_arg( 'url', rawurlencode( $settings['url'] ), $share_link );
		}

		if ( ! empty( $settings['user_name'] ) ) {
			$user_name = $settings['user_name'];
			if ( '@' === substr( $user_name, 0, 1 ) ) {
				$user_name = substr( $user_name, 1 );
			}
			$share_link = add_query_arg( 'via', rawurlencode( $user_name ), $share_link );
		}

		$this->add_render_attribute(
			array(
				'blockquote_content' => array( 'class' => 'mas-elementor-blockquote__content' ),
				'author_name'        => array( 'class' => 'mas-elementor-blockquote__author' ),
				'tweet_button_label' => array( 'class' => 'mas-elementor-blockquote__tweet-label' ),
			)
		);

		$this->add_inline_editing_attributes( 'blockquote_content' );
		$this->add_inline_editing_attributes( 'author_name', 'none' );
		$this->add_inline_editing_attributes( 'tweet_button_label', 'none' );
		?>
		<blockquote class="mas-elementor-blockquote">
			<p <?php $this->print_render_attribute_string( 'blockquote_content' ); ?>>
				<?php $this->print_unescaped_setting( 'blockquote_content' ); ?>
			</p>
			<?php if ( ! empty( $settings['author_name'] ) || 'yes' === $settings['tweet_button'] ) : ?>
				<footer>
					<?php if ( ! empty( $settings['author_name'] ) ) : ?>
						<cite <?php $this->print_render_attribute_string( 'author_name' ); ?>>
																		  <?php
																			$this->print_unescaped_setting( 'author_name' );
																			?>
						</cite>
					<?php endif ?>
					<?php if ( 'yes' === $settings['tweet_button'] ) : ?>
						<a href="<?php echo esc_attr( $share_link ); ?>" class="mas-elementor-blockquote__tweet-button" target="_blank">
							<?php if ( 'text' !== $tweet_button_view ) : ?>
								<?php
								$icon = array(
									'value'   => 'fab fa-twitter',
									'library' => 'fa-brands',
								);
								if ( ! Icons_Manager::is_migration_allowed() || ! Icons_Manager::render_icon( $icon, array( 'aria-hidden' => 'true' ) ) ) :
									?>
									<i class="fa fa-twitter" aria-hidden="true"></i>
								<?php endif; ?>
								<?php if ( 'icon-text' !== $tweet_button_view ) : ?>
									<span class="elementor-screen-only"><?php esc_html_e( 'Tweet', 'mas-addons-for-elementor' ); ?></span>
								<?php endif; ?>
							<?php endif; ?>
							<?php if ( 'icon-text' === $tweet_button_view || 'text' === $tweet_button_view ) : ?>
								<span <?php $this->print_render_attribute_string( 'tweet_button_label' ); ?>>
																				  <?php
																					$this->print_unescaped_setting( 'tweet_button_label' );
																					?>
								</span>
							<?php endif; ?>
						</a>
					<?php endif ?>
				</footer>
			<?php endif ?>
		</blockquote>
		<?php
	}

	/**
	 * Render Blockquote widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 */
	protected function content_template() {
		?>
		<#
			var tweetButtonView = settings.tweet_button_view;
			#>
			<blockquote class="mas-elementor-blockquote">
				<p class="mas-elementor-blockquote__content elementor-inline-editing" data-elementor-setting-key="blockquote_content">
					{{{ settings.blockquote_content }}}
				</p>
				<# if ( 'yes' === settings.tweet_button || settings.author_name ) { #>
					<footer>
						<# if ( settings.author_name ) { #>
							<cite class="mas-elementor-blockquote__author elementor-inline-editing" data-elementor-setting-key="author_name" data-elementor-inline-editing-toolbar="none">{{{ settings.author_name }}}</cite>
						<# } #>
						<# if ( 'yes' === settings.tweet_button ) { #>
							<a href="#" class="mas-elementor-blockquote__tweet-button">
								<# if ( 'text' !== tweetButtonView ) {
									// If FontAwesome migration has been done, load the FA5 version, otherwise load FA4
									if ( ! elementor.config.icons_update_needed ) { #>
										<i class="fab fa-twitter" aria-hidden="true"></i>
									<# } else { #>
										<i class="fa fa-twitter" aria-hidden="true"></i>
									<# } #>
									<# if ( 'icon-text' !== tweetButtonView ) { #>
										<span class="elementor-screen-only"><?php esc_html_e( 'Tweet', 'mas-addons-for-elementor' ); ?></span>
									<# } #>
								<# } #>
								<# if ( 'icon-text' === tweetButtonView || 'text' === tweetButtonView ) { #>
									<span class="elementor-inline-editing mas-elementor-blockquote__tweet-label" data-elementor-setting-key="tweet_button_label" data-elementor-inline-editing-toolbar="none">{{{ settings.tweet_button_label }}}</span>
								<# } #>
							</a>
						<# } #>
					</footer>
				<# } #>
			</blockquote>
		<?php
	}
}
