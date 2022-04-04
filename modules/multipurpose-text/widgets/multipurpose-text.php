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
	 * Render Icon.
	 *
	 * @param array $settings the widget settings.
	 * @return void
	 */
	protected function render_icon( $settings ) {

		$has_icon = ! empty( $settings['icon'] );

		if ( ! $has_icon && ! empty( $settings['selected_icon']['value'] ) ) {
			$has_icon = true;
		}

		if ( $has_icon ) : ?>
			<span class="elementor-icon-box-icon">
				<?php Icons_Manager::render_icon( $settings['selected_icon'], array( 'aria-hidden' => 'true' ) ); ?>
			</span>
			<?php
		endif;
	}

	/**
	 * Render.
	 *
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( '' === $settings['highlighted_text'] && '' === $settings['before_title'] ) {
			return;
		}

		$this->add_render_attribute( 'title', 'class', 'mas-elementor-multipurpose-text__title' );

		if ( ! empty( $settings['title_css'] ) ) {
			$this->add_render_attribute( 'title', 'class', $settings['title_css'] );
		}

		$this->add_render_attribute( 'highlight', 'class', 'mas-elementor-multipurpose-text__highlighted-text' );

		if ( ! empty( $settings['highlighted_css'] ) ) {
			$this->add_render_attribute( 'highlight', 'class', $settings['highlighted_css'] );
		}

		if ( ! empty( $settings['highlighted_text'] ) ) {
			$highlighted_text = '<span ' . $this->get_render_attribute_string( 'highlight' ) . '>' . $settings['highlighted_text'] . '</span>';
		} else {
			$highlighted_text = '';
		}

		/**
		 * Wrap before text.
		 */
		$before_text = '';
		$this->add_render_attribute( 'before_text', 'class', 'mas-multipurpose-text__before' );
		if ( ! empty( $settings['before_css'] ) ) {
			$this->add_render_attribute( 'before_text', 'class', $settings['before_css'] );
		}

		if ( ! empty( $settings['before_title'] ) ) {
			$before_text = '<span ' . $this->get_render_attribute_string( 'before_text' ) . '>' . $settings['before_title'] . '</span>';
		}

		/**
		 * Wrap After text.
		 */
		$after_text = '';
		$this->add_render_attribute( 'after_text', 'class', 'mas-multipurpose-text__before' );
		if ( ! empty( $settings['after_css'] ) ) {
			$this->add_render_attribute( 'after_text', 'class', $settings['after_css'] );
		}

		if ( ! empty( $settings['after_title'] ) ) {
			$after_text = '<span ' . $this->get_render_attribute_string( 'after_text' ) . '>' . $settings['after_title'] . '</span>';
		}

		$title = $before_text . $highlighted_text . $after_text;

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes( 'url', $settings['link'] );
			$this->add_render_attribute( 'url', 'class', array( 'text-decoration-none', $settings['link_css'] ) );

			$title = sprintf( '<a %1$s>%2$s</a>', $this->get_render_attribute_string( 'url' ), $title );
		}

		$title_html = sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['header_size'], $this->get_render_attribute_string( 'title' ), $title );

		echo wp_kses_post( $title_html );
	}
}
