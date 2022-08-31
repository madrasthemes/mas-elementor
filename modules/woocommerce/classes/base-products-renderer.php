<?php
/**
 * Base Products Renderer.
 *
 * @package MASElementor/Modules/Woocommerce/Classes
 */

namespace MASElementor\Modules\Woocommerce\Classes;

use MASElementor\Modules\Woocommerce\Widgets\Products;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The Base Products Renderer class
 */
abstract class Base_Products_Renderer extends \WC_Shortcode_Products {

	/**
	 * Base Products Renderer constructor.
	 *
	 * @param array  $settings the settings.
	 * @param string $type post type.
	 * @param array  $tem_path template path and args.
	 */
	public function __construct( $settings = array(), $type = 'products', $tem_path = array(
		'path' => 'widgets/product-classic.php',
		'args' => array(),
	) ) {
		$this->settings = $settings;
		$this->tem_path = $tem_path['path'];
		$this->tem_args = $tem_path['args'];
	}

	/**
	 * Override original `get_content` that returns an HTML wrapper even if no results found.
	 *
	 * @return string Products HTML
	 */
	public function get_content() {
		$result = $this->get_query_results();

		if ( empty( $result->total ) ) {
			return '';
		}

		return parent::get_content();
	}

	/**
	 * Loop over found products.
	 *
	 * @param array $widget widget.
	 * @param array $settings settings.
	 *
	 * @since  3.2.0
	 * @return string
	 */
	public function mas_product_content( $widget, $settings ) {

		$columns  = absint( $this->attributes['columns'] );
		$classes  = $this->get_wrapper_classes( $columns );
		$products = $this->get_query_results();

		ob_start();

		if ( $products && $products->ids ) {
			// Prime caches to reduce future queries.
			if ( is_callable( '_prime_post_caches' ) ) {
				_prime_post_caches( $products->ids );
			}

			// Setup the loop.
			wc_setup_loop(
				array(
					'columns'      => $columns,
					'name'         => $this->type,
					'is_shortcode' => true,
					'is_search'    => false,
					'is_paginated' => wc_string_to_bool( $this->attributes['paginate'] ),
					'total'        => $products->total,
					'total_pages'  => $products->total_pages,
					'per_page'     => $products->per_page,
					'current_page' => $products->current_page,
				)
			);

			$original_post = $GLOBALS['post'];

			do_action( "woocommerce_shortcode_before_{$this->type}_loop", $this->attributes );

			// Fire standard shop loop hooks when paginating results so we can show result counts and so on.
			if ( wc_string_to_bool( $this->attributes['paginate'] ) ) {
				do_action( 'woocommerce_before_shop_loop' );
			}

			// woocommerce_product_loop_start();.

			if ( wc_get_loop_prop( 'total' ) ) {
				foreach ( $products->ids as $product_id ) {
					$GLOBALS['post'] = get_post( $product_id ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
					setup_postdata( $GLOBALS['post'] );

					// Set custom product visibility when quering hidden products.
					add_action( 'woocommerce_product_is_visible', array( $this, 'set_product_as_visible' ) );

					// Render product template.
					// wc_get_template_part( 'content', 'product' );.

					$this->skin_template_path();

					// Restore product visibility.
					remove_action( 'woocommerce_product_is_visible', array( $this, 'set_product_as_visible' ) );
				}
			}

			$GLOBALS['post'] = $original_post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			// woocommerce_product_loop_end();.

			// Fire standard shop loop hooks when paginating results so we can show result counts and so on.
			if ( wc_string_to_bool( $this->attributes['paginate'] ) ) {
				do_action( 'woocommerce_after_shop_loop' );
			}

			do_action( "woocommerce_shortcode_after_{$this->type}_loop", $this->attributes );

			wp_reset_postdata();
			wc_reset_loop();
		} else {
			do_action( "woocommerce_shortcode_{$this->type}_loop_no_results", $this->attributes );
		}

		$before_render = '<div class="' . esc_attr( implode( ' ', $classes ) ) . '">' . $this->carousel_loop_header( $widget, $settings );
		$after_render  = $this->carousel_loop_footer( $widget, $settings ) . '</div>';

		return $before_render . ob_get_clean() . $after_render;
	}

	/**
	 * Skin Template Path.
	 */
	public function skin_template_path() {

		$path     = &$this->tem_path;
		$args     = &$this->tem_args;
		$settings = &$this->settings;
		if ( 'yes' === $settings['enable_carousel'] ) {
			?>
			<div class="swiper-slide">
			<?php
		}

		print( mas_render_template( $settings['select_template'], false ) );//phpcs:ignore

		if ( 'yes' === $settings['enable_carousel'] ) {
			?>
			</div>
			<?php
		}
		// mas_elementor_get_template( $path, $args );.
	}

	/**
	 * Carousel Loop Header.
	 *
	 * @param array $widget widget.
	 * @param array $settings Settings of this widget.
	 * @return string
	 */
	public function carousel_loop_header( $widget, $settings ) {
		ob_start();
		if ( 'yes' === $settings['enable_carousel'] ) {
			$json = wp_json_encode( $this->get_swiper_carousel_options( $widget, $settings ) );
			$widget->add_render_attribute( 'post_swiper', 'class', 'swiper-' . $widget->get_id() );
			$widget->add_render_attribute( 'post_swiper', 'class', 'swiper' );
			$widget->add_render_attribute( 'post_swiper', 'data-swiper-options', $json );
			?>
			<div <?php $widget->print_render_attribute_string( 'post_swiper' ); ?>>
				<div class="swiper-wrapper">
			<?php
		}
		return ob_get_clean();

	}

	/**
	 * Carousel Loop Footer.
	 *
	 * @param array $widget widget.
	 * @param array $settings Settings of this widget.
	 * @return string
	 */
	public function carousel_loop_footer( $widget, array $settings = array() ) {
		ob_start();
		if ( 'yes' === $settings['enable_carousel'] ) {
			?>
			</div>
			<?php
			$widget_id = $widget->get_id();
			if ( ! empty( $widget_id ) && 'yes' === $settings['show_pagination'] ) {
				$widget->add_render_attribute( 'swiper-pagination', 'id', 'pagination-' . $widget_id );
			}
			$widget->add_render_attribute( 'swiper-pagination', 'class', 'swiper-pagination' );
			if ( 'yes' === $settings['show_pagination'] ) :
				?>
			<div <?php $widget->print_render_attribute_string( 'swiper-pagination' ); ?>></div>
				<?php
			endif;
			if ( 'yes' === $settings['show_arrows'] ) :
				$prev_id = ! empty( $widget_id ) ? 'prev-' . $widget_id : '';
				$next_id = ! empty( $widget_id ) ? 'next-' . $widget_id : '';
				?>
				<!-- If we need navigation buttons -->
				<div class="d-flex mas-swiper-arrows">
				<?php
				$widget->render_button( $widget, $prev_id, $next_id );
				?>
				</div>
				<?php
			endif;
			?>
			</div>
			<?php
		}
		return ob_get_clean();
	}


	/**
	 * Get carousel settings
	 *
	 * @param array $widget widget.
	 * @param array $settings Settings of this widget.
	 * @return array
	 */
	public function get_swiper_carousel_options( $widget, array $settings ) {
		$section_id      = $widget->get_id();
		$swiper_settings = array();
		if ( 'yes' === $settings['show_pagination'] ) {
			$swiper_settings['pagination'] = array(
				'el' => '#pagination-' . $section_id,
			);
		}

		if ( 'bullets' === $settings['pagination'] ) {
			$swiper_settings['pagination']['type']      = 'bullets';
			$swiper_settings['pagination']['clickable'] = true;
		}
		if ( 'fraction' === $settings['pagination'] ) {
			$swiper_settings['pagination']['type'] = 'fraction';
		}
		if ( 'progressbar' === $settings['pagination'] ) {
			$swiper_settings['pagination']['type'] = 'progressbar';
		}

		if ( 'fade' === $settings['carousel_effect'] ) {
			$swiper_settings['effect']                  = 'fade';
			$swiper_settings['fadeEffect']['crossFade'] = true;
		}
		if ( 'slide' === $settings['carousel_effect'] ) {
			$swiper_settings['breakpoints']['1440']['slidesPerView'] = isset( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 3;
			$swiper_settings['breakpoints']['1024']['slidesPerView'] = isset( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 3;
			$swiper_settings['breakpoints']['500']['slidesPerView']  = isset( $settings['slides_per_view_tablet'] ) ? $settings['slides_per_view_tablet'] : 3;
			$swiper_settings['breakpoints']['0']['slidesPerView']    = isset( $settings['slides_per_view_mobile'] ) ? $settings['slides_per_view_mobile'] : 1;

		}

		if ( 'yes' === $settings['enable_space_between'] ) {
			if ( ! empty( $settings['space_between'] ) ) {
				$swiper_settings['breakpoints']['1440']['spaceBetween'] = $settings['space_between'];

			}
			if ( ! empty( $settings['space_between_tablet'] ) ) {
				$swiper_settings['breakpoints']['1024']['spaceBetween'] = $settings['space_between_tablet'];
				$swiper_settings['breakpoints']['500']['spaceBetween']  = $settings['space_between_tablet'];

			}
			if ( ! empty( $settings['space_between_mobile'] ) ) {
				$swiper_settings['breakpoints']['0']['spaceBetween'] = $settings['space_between_mobile'];

			}
		}

		$prev_id = ! empty( $section_id ) ? 'prev-' . $section_id : '';
		$next_id = ! empty( $section_id ) ? 'next-' . $section_id : '';
		if ( 'yes' === $settings['show_arrows'] && 'yes' !== $settings['show_custom_arrows'] ) {
			$swiper_settings['navigation'] = array(
				'prevEl' => '#' . $prev_id,
				'nextEl' => '#' . $next_id,

			);
		}
		if ( 'yes' === $settings['show_custom_arrows'] && 'yes' !== $settings['show_arrows'] ) {
			$swiper_settings['navigation'] = array(
				'prevEl' => '#' . $settings['custom_prev_id'],
				'nextEl' => '#' . $settings['custom_next_id'],

			);
		}

		if ( 'yes' === $settings['center_slides'] ) {
			$swiper_settings['centeredSlides'] = true;
		}

		if ( $settings['loop'] ) {
			$swiper_settings['loop'] = 'true';
		}
		if ( $settings['autoplay'] && $settings['autoplay_speed'] ) {
			$swiper_settings['autoplay']['delay'] = $settings['autoplay_speed'];
		}
		if ( $settings['autoplay'] && $settings['pause_on_hover'] ) {
			$swiper_settings['autoplay']['pauseOnMouseEnter']    = true;
			$swiper_settings['autoplay']['disableOnInteraction'] = false;
		}
		if ( $settings['speed'] ) {
			$swiper_settings['speed'] = $settings['speed'];
		}

		return $swiper_settings;
	}


}
