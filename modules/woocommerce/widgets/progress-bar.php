<?php
/**
 * The Progress Bar Widget.
 *
 * @package MASElementor/Modules/Woocommerce/Widgets
 */

namespace MASElementor\Modules\Woocommerce\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Progress;
use Elementor\Utils;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Progress Bar
 */
class Progress_Bar extends Widget_Progress {

	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mas-woocommerce-progress-bar';
	}

	/**
	 * Get inline css to the widget.
	 *
	 * @return array
	 */
	public function get_inline_css_depends() {
		return array(
			array(
				'name'               => 'progress',
				'is_core_dependency' => true,
			),
		);
	}

	/**
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Progress Bar', 'mas-addons-for-elementor' );
	}

	/**
	 * Get the icon of the widget.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-product-price';
	}

	/**
	 * Get the keywords related to the widget.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'woocommerce', 'shop', 'store', 'price', 'product', 'sale' );
	}

	/**
	 * Get the script dependencies for this widget.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array( 'mas-progress-script' );
	}

	/**
	 * Return the style dependencies of the module.
	 *
	 * @return array
	 */
	public function get_style_depends(): array {
		return array( 'widget-progress' );
	}

	/**
	 * Register controls for this widget.
	 */
	protected function register_controls() {
		parent::register_controls();

		$this->start_injection(
			array(
				'of' => 'title',
				'at' => 'before',
			)
		);

		$this->add_control(
			'hide_when_stocks_not_available',
			array(
				'label'       => esc_html__( 'Hide Progress', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_on'    => esc_html__( 'Yes', 'mas-addons-for-elementor' ),
				'label_off'   => esc_html__( 'No', 'mas-addons-for-elementor' ),
				'description' => 'Hide when the product do not have available stock',
				'default'     => 'yes',
			)
		);

		$this->end_injection();

		$this->start_injection(
			array(
				'of' => 'title',
				'at' => 'after',
			)
		);

		$this->add_control(
			'second_title',
			array(
				'label'       => esc_html__( 'Second Title', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'placeholder' => esc_html__( 'Enter your title', 'mas-addons-for-elementor' ),
				'default'     => esc_html__( 'My Skill', 'mas-addons-for-elementor' ),
				'label_block' => true,
			)
		);

		$this->end_injection();
		$this->start_injection(
			array(
				'of' => 'bar_border_radius',
				'at' => 'after',
			)
		);
		$this->add_responsive_control(
			'progress_bar_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-progress-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->end_injection();

		$this->start_injection(
			array(
				'of' => 'title_color',
				'at' => 'before',
			)
		);

		$this->add_responsive_control(
			'titles_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .deal-stock' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'second_label',
			array(
				'label'     => __( 'Second Title', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'second_title_color',
			array(
				'label'     => esc_html__( 'Second Text Color', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-second-title' => 'color: {{VALUE}};',
				),
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'second_typography',
				'selector' => '{{WRAPPER}} .elementor-second-title',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				),
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'second_title_shadow',
				'selector' => '{{WRAPPER}} .elementor-second-title',
			)
		);

		$this->add_control(
			'title_label',
			array(
				'label'     => __( 'Title', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->end_injection();

		$this->start_controls_section(
			'section_titles_flex',
			array(
				'label' => esc_html__( 'Titles Flex Style', 'mas-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->register_flex_style_controls();

		$this->end_controls_section();

	}

	/**
	 * Register flex style controls for this widget.
	 */
	protected function register_flex_style_controls() {

		$start   = is_rtl() ? 'right' : 'left';
		$end     = is_rtl() ? 'left' : 'right';
		$wrapper = '{{WRAPPER}} .deal-stock';

		$this->add_responsive_control(
			'enable_flex',
			array(
				'label'     => esc_html__( 'Enable Flex', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'block' => array(
						'title' => esc_html__( 'Block', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-ban',
					),
					'flex'  => array(
						'title' => esc_html__( 'Flex', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-wrap',
					),
				),
				'default'   => 'flex',
				'selectors' => array(
					$wrapper => 'display: {{VALUE}};',
				),

			)
		);

		$this->add_responsive_control(
			'flex_direction',
			array(
				'label'     => esc_html__( 'Direction', 'mas-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'row'            => array(
						'title' => esc_html__( 'Row - horizontal', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-arrow-' . $end,
					),
					'column'         => array(
						'title' => esc_html__( 'Column - vertical', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-arrow-down',
					),
					'row-reverse'    => array(
						'title' => esc_html__( 'Row - reversed', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-arrow-' . $start,
					),
					'column-reverse' => array(
						'title' => esc_html__( 'Column - reversed', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-arrow-up',
					),
				),
				'default'   => 'row',
				'selectors' => array(
					$wrapper => 'flex-direction:{{VALUE}};',
				),
				'condition' => array(
					'enable_flex' => 'flex',
				),
			)
		);

		$this->add_responsive_control(
			'justify_content',
			array(
				'label'       => esc_html__( 'Justify Content', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => true,
				'description' => esc_html__( 'Used for alignment in Flex Direction row and row-reversed', 'mas-addons-for-elementor' ),
				'default'     => '',
				'options'     => array(
					'flex-start'    => array(
						'title' => esc_html__( 'Start', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-justify-start-h',
					),
					'center'        => array(
						'title' => esc_html__( 'Center', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-justify-center-h',
					),
					'flex-end'      => array(
						'title' => esc_html__( 'End', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-justify-end-h',
					),
					'space-between' => array(
						'title' => esc_html__( 'Space Between', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-justify-space-between-h',
					),
					'space-around'  => array(
						'title' => esc_html__( 'Space Around', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-justify-space-around-h',
					),
					'space-evenly'  => array(
						'title' => esc_html__( 'Space Evenly', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-justify-space-evenly-h',
					),
				),
				'selectors'   => array(
					$wrapper => 'justify-content: {{VALUE}};',
				),
				'default'     => 'space-between',
			)
		);

		$this->add_responsive_control(
			'align_items',
			array(
				'label'       => esc_html__( 'Align Items', 'mas-addons-for-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'default'     => '',
				'description' => esc_html__( 'Used for alignment in Flex Direction column and column-reversed', 'mas-addons-for-elementor' ),
				'options'     => array(
					'flex-start' => array(
						'title' => esc_html__( 'Start', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-align-start-v',
					),
					'center'     => array(
						'title' => esc_html__( 'Center', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-align-center-v',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'End', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-align-end-v',
					),
					'stretch'    => array(
						'title' => esc_html__( 'Stretch', 'mas-addons-for-elementor' ),
						'icon'  => 'eicon-flex eicon-align-stretch-v',
					),
				),
				'selectors'   => array(
					$wrapper => 'align-items: {{VALUE}};',
				),
				'default'     => 'stretch',
			)
		);

		$this->add_responsive_control(
			'gap',
			array(
				'label'      => esc_html__( 'Gap', 'mas-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 500,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
					'vw' => array(
						'min' => 0,
						'max' => 100,
					),
					'em' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					$wrapper => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

	}

	/**
	 * Render.
	 */
	protected function render() {
		$settings             = $this->get_settings_for_display();
		$allow                = 'yes' === $settings['hide_when_stocks_not_available'] ? true : false;
		$total_stock_quantity = get_post_meta( get_the_ID(), '_total_stock_quantity', true );
		$stock                = get_post_meta( get_the_ID(), '_stock', true );
		$total_sales          = get_post_meta( get_the_ID(), 'total_sales', true );
		if ( ! empty( $total_stock_quantity ) ) {
			$stock_quantity  = round( $total_stock_quantity );
			$stock_available = $stock ? round( $stock ) : 0;
			$stock_sold      = ( $stock_quantity > $stock_available ? $stock_quantity - $stock_available : 0 );
			$percentage      = ( $stock_sold > 0 ? round( $stock_sold / $stock_quantity * 100 ) : 0 );
		} else {
			$stock_available = $stock ? round( $stock ) : 0;
			$stock_sold      = $total_sales ? round( $total_sales ) : 0;
			$stock_quantity  = $stock_sold + $stock_available;
			$percentage      = ( ( $stock_available > 0 && $stock_quantity > 0 ) ? round( $stock_sold / $stock_quantity * 100 ) : 0 );
		}

		if ( ! ( $stock_available > 0 ) && $allow ) {
			return;
		}

		$progress_percentage = is_numeric( $settings['percent']['size'] ) ? $settings['percent']['size'] : '0';
		if ( 100 < $progress_percentage ) {
			$progress_percentage = 100;
		}

		$this->add_render_attribute( 'title', 'class', 'elementor-title' );

		$this->add_render_attribute( 'second_title', 'class', 'elementor-second-title' );

		$this->add_inline_editing_attributes( 'title' );

		$this->add_inline_editing_attributes( 'second_title' );

		$this->add_render_attribute(
			'wrapper',
			array(
				'class'         => 'elementor-progress-wrapper',
				'role'          => 'progressbar',
				'aria-valuemin' => '0',
				'aria-valuemax' => '100',
				'aria-valuenow' => $progress_percentage,
			)
		);

		if ( ! empty( $settings['inner_text'] ) ) {
			$this->add_render_attribute( 'wrapper', 'aria-valuetext', "{$progress_percentage}% ({$settings['inner_text']})" );
		}

		if ( ! empty( $settings['progress_type'] ) ) {
			$this->add_render_attribute( 'wrapper', 'class', 'progress-' . $settings['progress_type'] );
		}

		$this->add_render_attribute(
			'progress-bar',
			array(
				'class'    => 'elementor-progress-bar',
				'data-max' => $progress_percentage,
			)
		);

		$this->add_render_attribute( 'inner_text', 'class', 'elementor-progress-text' );

		$this->add_inline_editing_attributes( 'inner_text' );
		if ( ! Utils::is_empty( $settings['title'] ) || ! Utils::is_empty( $settings['second_title'] ) ) {
			?><div class="deal-stock">
			<?php
			if ( ! Utils::is_empty( $settings['title'] ) ) {
				?>
				<<?php Utils::print_validated_html_tag( $settings['title_tag'] ); ?> <?php $this->print_render_attribute_string( 'title' ); ?>>
					<?php $this->print_unescaped_setting( 'title' ); ?>
				</<?php Utils::print_validated_html_tag( $settings['title_tag'] ); ?>>
				<?php
			}
			if ( ! Utils::is_empty( $settings['second_title'] ) ) {
				?>
				<<?php Utils::print_validated_html_tag( $settings['title_tag'] ); ?> <?php $this->print_render_attribute_string( 'second_title' ); ?>>
					<?php $this->print_unescaped_setting( 'second_title' ); ?>
				</<?php Utils::print_validated_html_tag( $settings['title_tag'] ); ?>>
				<?php
			}
			?>
			</div>
			<?php
		}
		?>

		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<div <?php $this->print_render_attribute_string( 'progress-bar' ); ?>>
				<span <?php $this->print_render_attribute_string( 'inner_text' ); ?>><?php $this->print_unescaped_setting( 'inner_text' ); ?></span>
				<?php if ( 'show' === $settings['display_percentage'] ) { ?>
					<span class="elementor-progress-percentage"><?php echo $progress_percentage; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>%</span>
				<?php } ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render progress widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 */
	protected function content_template() {
		?>
		<#
		const title_tag = elementor.helpers.validateHTMLTag( settings.title_tag );

		let progress_percentage = 0;
		if ( ! isNaN( settings.percent.size ) ) {
			progress_percentage = 100 < settings.percent.size ? 100 : settings.percent.size;
		}

		view.addRenderAttribute( 'title', 'class', 'elementor-title' );

		view.addRenderAttribute( 'second_title', 'class', 'elementor-second-title' );

		view.addInlineEditingAttributes( 'title' );
		view.addInlineEditingAttributes( 'second_title' );

		view.addRenderAttribute(
			'progressWrapper',
			{
				'class': [ 'elementor-progress-wrapper', 'progress-' + settings.progress_type ],
				'role': 'progressbar',
				'aria-valuemin': '0',
				'aria-valuemax': '100',
				'aria-valuenow': progress_percentage,
			}
		);

		if ( '' !== settings.inner_text ) {
			view.addRenderAttribute( 'progressWrapper', 'aria-valuetext', progress_percentage + '% (' + settings.inner_text + ')' );
		}

		view.addRenderAttribute( 'inner_text', 'class', 'elementor-progress-text' );

		view.addInlineEditingAttributes( 'inner_text' );
		#>
		<# if ( settings.title || settings.second_title ) { #>
			<div class="deal-stock">
				<# if ( settings.title ) { #>
				<{{ title_tag }} {{{ view.getRenderAttributeString( 'title' ) }}}>{{{ settings.title }}}</{{ title_tag }}>
				<# } #>
				<# if ( settings.second_title ) { #>
					<{{ title_tag }} {{{ view.getRenderAttributeString( 'second_title' ) }}}>{{{ settings.second_title }}}</{{ title_tag }}>
				<# } #>
			</div>
		<# } #>
		<div {{{ view.getRenderAttributeString( 'progressWrapper' ) }}}>
			<div class="elementor-progress-bar" data-max="{{ progress_percentage }}">
				<span {{{ view.getRenderAttributeString( 'inner_text' ) }}}>{{{ settings.inner_text }}}</span>
				<# if ( 'show' === settings.display_percentage ) { #>
					<span class="elementor-progress-percentage">{{{ progress_percentage }}}%</span>
				<# } #>
			</div>
		</div>
		<?php
	}
}
