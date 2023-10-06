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
	 * Get the title of the widget.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Progress Bar', 'mas-elementor' );
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
	public function get_style_depends() {
		return array( 'frontend-legacy' );
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
			'second_title',
			array(
				'label'       => esc_html__( 'Second Title', 'mas-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'placeholder' => esc_html__( 'Enter your title', 'mas-elementor' ),
				'default'     => esc_html__( 'My Skill', 'mas-elementor' ),
				'label_block' => true,
			)
		);

		$this->end_injection();

	}

	/**
	 * Render.
	 */
	protected function render() {
		$allow                = false;
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

		if ( ! ( $stock_available > 0 ) || $allow ) {
			return;
		}
		$settings = $this->get_settings_for_display();

		$progress_percentage = is_numeric( $settings['percent']['size'] ) ? $settings['percent']['size'] : '0';
		if ( 100 < $progress_percentage ) {
			$progress_percentage = 100;
		}

		$this->add_render_attribute( 'title', 'class', 'elementor-title' );

		$this->add_inline_editing_attributes( 'title' );

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
}
