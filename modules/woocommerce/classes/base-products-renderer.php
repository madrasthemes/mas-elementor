<?php
/**
 * Base Products Renderer.
 *
 * @package MASElementor/Modules/Woocommerce/Classes
 */

namespace MASElementor\Modules\Woocommerce\Classes;

use MASElementor\Modules\Woocommerce\Widgets\Products;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The Base Products Renderer class
 */
abstract class Base_Products_Renderer extends \WC_Shortcode_Products {

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

			if ( 'yes' === $settings['enable_shop_control_bar'] ) {

				remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
				add_action( 'mas_elementor_shop_control_bar', array( $this, 'mas_elementor_shop_view_switcher' ), 10 );
				add_action( 'mas_elementor_shop_control_bar', 'woocommerce_catalog_ordering', 50 );
				add_action( 'woocommerce_before_shop_loop', array( $this, 'mas_elementor_shop_control_bar' ) );
				add_action( 'mas_elementor_shop_control_bar', array( $this, 'mas_elementor_advanced_pagination' ), 70 );

			}

			do_action( "woocommerce_shortcode_before_{$this->type}_loop", $this->attributes );

			add_filter( 'woocommerce_pagination_args', array( $this, 'custom_woocommerce_pagination_args' ) );

			// Fire standard shop loop hooks when paginating results so we can show result counts and so on.
			if ( wc_string_to_bool( ( $this->attributes['paginate'] ) && 'yes' !== $settings['enable_carousel'] ) ) {
				do_action( 'woocommerce_before_shop_loop' );
			}

			if ( 'yes' !== $settings['enable_carousel'] ) {
				if ( 'yes' === $settings['enable_shop_control_bar'] ) {
					?>
					<div data-bs-toggle="mas-shop-products" class="mas-products mas-grid">
					<?php
				} else {
					?>
					<div class="mas-products mas-grid">
					<?php
				}
			}

			if ( wc_get_loop_prop( 'total' ) ) {
				$index__id = 1;
				foreach ( $products->ids as $product_id ) {
					$GLOBALS['post'] = get_post( $product_id ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
					setup_postdata( $GLOBALS['post'] );

					// Set custom product visibility when quering hidden products.
					add_action( 'woocommerce_product_is_visible', array( $this, 'set_product_as_visible' ) );

					// Render product template.
					// wc_get_template_part( 'content', 'product' );.

					$this->skin_template_path( $index__id );

					// Restore product visibility.
					remove_action( 'woocommerce_product_is_visible', array( $this, 'set_product_as_visible' ) );
					$index__id++;
				}
			}

			$GLOBALS['post'] = $original_post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			if ( 'yes' !== $settings['enable_carousel'] ) {
				?>
				</div>
				<?php
			}

			// Fire standard shop loop hooks when paginating results so we can show result counts and so on.
			if ( wc_string_to_bool( $this->attributes['paginate'] ) && 'yes' !== $settings['enable_carousel'] ) {
				if ( 'yes' === $settings['show_result_count'] && 'after' === $settings['result_count_position'] ) :
					?>
					<div class="mas-shop-control-bar-bottom">
				<?php endif; ?>
					<?php
					do_action( 'woocommerce_after_shop_loop' );
					if ( 'yes' === $settings['show_result_count'] && 'after' === $settings['result_count_position'] ) :
						?>
				 
				</div>
					<?php endif; ?>

				<?php
			}

			do_action( "woocommerce_shortcode_after_{$this->type}_loop", $this->attributes );

			wp_reset_postdata();
			wc_reset_loop();
			$before_render = '<div class="' . esc_attr( implode( ' ', $classes ) ) . '">' . $this->carousel_loop_header( $widget, $settings );
			$after_render  = $this->carousel_loop_footer( $widget, $settings ) . '</div>';
		} else {
			do_action( "woocommerce_shortcode_{$this->type}_loop_no_results", $this->attributes );
			$before_render = '';
			$after_render  = '';
		}

		return $before_render . ob_get_clean() . $after_render;
	}

	/**
	 * Custom Woocommerce pagination arguments.
	 *
	 * @param array $args arguments.
	 */
	public function custom_woocommerce_pagination_args( $args ) {
		$settings          = &$this->settings;
		$args['mid_size']  = $settings['pag_mid_size'];
		$args['end_size']  = $settings['pag_end_size'];  // Number of page links to show before and after the current page.
		$args['prev_next'] = ( 'yes' === $settings['enable_prev_next'] );  // Remove "Previous" and "Next" links.
		return $args;
	}

	/**
	 * Skin Template Path.
	 *
	 * @param array $index__id loop count.
	 */
	public function skin_template_path( $index__id ) {

		$settings = &$this->settings;
		if ( 'yes' === $settings['enable_carousel'] ) {
			?>
			<div class="swiper-slide">
			<?php
		}
		$count_class = 'mas-product';
		if ( 0 === $index__id % $settings['columns'] ) {
			$count_class .= ' last';
		}
		if ( 1 === $index__id % $settings['columns'] ) {
			$count_class .= ' first';
		}

		?>
		<div class="<?php echo esc_attr( $count_class ); ?>">
		<?php
		if ( 'id' === $settings['template_options'] ) {
			print( mas_render_template( $settings['select_template'], false ) ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			$template                 = get_page_by_path( $settings['slug_select_template'], OBJECT, 'elementor_library' );
			if ( ! empty( $template->ID ) ) {
				print( mas_render_template( $template->ID, false ) ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

		?>
		</div>
		<?php
		if ( 'yes' === $settings['enable_carousel'] ) {
			?>
			</div>
			<?php
		}
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
			$widget->add_render_attribute( 'post_swiper', 'class', 'swiper-products-' . $widget->get_id() );
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
			$widget->add_render_attribute( 'swiper-pagination', 'style', 'position: ' . $settings['mas_swiper_pagination_position'] . ';' );
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
		$active_breakpoint_instances = Plugin::$instance->breakpoints->get_active_breakpoints();
		// Devices need to be ordered from largest to smallest.
		$active_devices  = array_reverse( array_keys( $active_breakpoint_instances ) );
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
			$grid       = 'yes' === $settings['enable_grid'] && 'yes' !== $settings['loop'] && 'yes' !== $settings['center_slides'];
			$breakpoint = '1441';
			$swiper_settings['breakpoints'][ $breakpoint ]['slidesPerView'] = isset( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 3;

			if ( $grid ) {
				$swiper_settings['grid']['fill']                               = 'row';
				$swiper_settings['breakpoints'][ $breakpoint ]['grid']['rows'] = isset( $settings['carousel_rows'] ) ? $settings['carousel_rows'] : 1;
			}

			foreach ( $active_breakpoint_instances as $active_breakpoint_instance ) {
				$array_key = 'slides_per_view_' . $active_breakpoint_instance->get_name();
				$rows_key  = 'carousel_rows_' . $active_breakpoint_instance->get_name();
				if ( 'mobile' === $active_breakpoint_instance->get_name() ) {
					$breakpoint = '0';
				}
				if ( 'widescreen' === $active_breakpoint_instance->get_name() ) {
					$breakpoint = (string) $active_breakpoint_instance->get_default_value();
					if ( property_exists( $active_breakpoint_instance, 'value' ) ) {
						$breakpoint = (string) ( $active_breakpoint_instance->get_value() );
					}
					$swiper_settings['breakpoints'][ $breakpoint ]['slidesPerView'] = isset( $settings[ $array_key ] ) ? $settings[ $array_key ] : 1;
					if ( $grid ) {
						$swiper_settings['breakpoints'][ $breakpoint ]['grid']['rows'] = isset( $settings[ $rows_key ] ) ? $settings[ $rows_key ] : 1;
					}
					continue;
				}
				$swiper_settings['breakpoints'][ $breakpoint ]['slidesPerView'] = isset( $settings[ $array_key ] ) ? $settings[ $array_key ] : 1;
				if ( $grid ) {
					$swiper_settings['breakpoints'][ $breakpoint ]['grid']['rows'] = isset( $settings[ $rows_key ] ) ? $settings[ $rows_key ] : 1;
				}
				$breakpoint = (string) $active_breakpoint_instance->get_default_value() + 1;
				if ( property_exists( $active_breakpoint_instance, 'value' ) ) {
					$breakpoint = (string) ( $active_breakpoint_instance->get_value() + 1 );
				}
			}
		}

		if ( 'slide' === $settings['carousel_effect'] ) {
			$breakpoint = '1441';
			$swiper_settings['breakpoints'][ $breakpoint ]['slidesPerGroup'] = isset( $settings['slides_to_scroll'] ) ? $settings['slides_to_scroll'] : 3;
			foreach ( $active_breakpoint_instances as $active_breakpoint_instance ) {
				$array_key = 'slides_to_scroll_' . $active_breakpoint_instance->get_name();
				if ( 'mobile' === $active_breakpoint_instance->get_name() ) {
					$breakpoint = '0';
				}
				if ( 'widescreen' === $active_breakpoint_instance->get_name() ) {
					$breakpoint = (string) $active_breakpoint_instance->get_default_value();
					if ( property_exists( $active_breakpoint_instance, 'value' ) ) {
						$breakpoint = (string) ( $active_breakpoint_instance->get_value() );
					}
					$swiper_settings['breakpoints'][ $breakpoint ]['slidesPerGroup'] = isset( $settings[ $array_key ] ) ? $settings[ $array_key ] : 1;
					continue;
				}
				$swiper_settings['breakpoints'][ $breakpoint ]['slidesPerGroup'] = isset( $settings[ $array_key ] ) ? $settings[ $array_key ] : 1;
				$breakpoint = (string) $active_breakpoint_instance->get_default_value() + 1;
				if ( property_exists( $active_breakpoint_instance, 'value' ) ) {
					$breakpoint = (string) ( $active_breakpoint_instance->get_value() + 1 );
				}
			}
		}

		if ( 'yes' === $settings['enable_space_between'] ) {
			$breakpoint = '1441';
			$swiper_settings['breakpoints'][ $breakpoint ]['spaceBetween'] = isset( $settings['space_between'] ) ? (int) $settings['space_between'] : 8;
			foreach ( $active_breakpoint_instances as $active_breakpoint_instance ) {
				$array_key = 'space_between_' . $active_breakpoint_instance->get_name();
				if ( 'mobile' === $active_breakpoint_instance->get_name() ) {
					$breakpoint = '0';
				}
				if ( 'widescreen' === $active_breakpoint_instance->get_name() ) {
					$breakpoint = (string) $active_breakpoint_instance->get_default_value();
					if ( property_exists( $active_breakpoint_instance, 'value' ) ) {
						$breakpoint = (string) ( $active_breakpoint_instance->get_value() );
					}
					$swiper_settings['breakpoints'][ $breakpoint ]['spaceBetween'] = isset( $settings[ $array_key ] ) ? (int) $settings[ $array_key ] : 8;
					continue;
				}
				$swiper_settings['breakpoints'][ $breakpoint ]['spaceBetween'] = isset( $settings[ $array_key ] ) ? (int) $settings[ $array_key ] : 8;
				$breakpoint = (string) $active_breakpoint_instance->get_default_value() + 1;
				if ( property_exists( $active_breakpoint_instance, 'value' ) ) {
					$breakpoint = (string) ( $active_breakpoint_instance->get_value() + 1 );
				}
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
			$swiper_settings['centeredSlides']       = true;
			$swiper_settings['centeredSlidesBounds'] = true;
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


	/**
	 * Outputs shop control bar.
	 */
	public function mas_elementor_shop_control_bar() {

		$products = $this->get_query_results();

		if ( empty( $products->ids ) ) {
			return;
		}

		?>
		<div class="mas-shop-control-bar">
			<?php
			/**
			 * Hooks.
			 *
			 * @hooked mas_elementor_shop_view_switcher - 10
			 * @hooked woocommerce_sorting - 20
			 */
			do_action( 'mas_elementor_shop_control_bar' );
			?>
		</div>
		<?php
	}

	/**
	 * Outputs view switcher
	 */
	public function mas_elementor_shop_view_switcher() {

		$products = $this->get_query_results();

		if ( empty( $products->ids ) ) {
			return;
		}

		$shop_views = $this->mas_elementor_get_shop_views();
		?>
		<ul class="shop-view-switcher nav nav-tabs" role="tablist">
		<?php foreach ( $shop_views as $view_id => $shop_view ) : ?>
			<li class="nav-item"><a class="nav-link 
			<?php
			$active_class = $shop_view['active'] ? 'active' : '';
			echo esc_attr( $active_class );
			?>
			" data-bs-toggle="tab" data-archive-class="<?php echo esc_attr( $view_id ); ?>" title="<?php echo esc_attr( $shop_view['label'] ); ?>" href="#<?php echo esc_attr( $view_id ); ?>"><i class="<?php echo esc_attr( $shop_view['icon'] ); ?>"></i></a></li>
		<?php endforeach; ?>
		</ul>
		<?php
	}

	/**
	 * Get shop views available by mas_elementor
	 */
	public function mas_elementor_get_shop_views() {

		$shop_views = apply_filters(
			'mas_elementor_shop_views_args',
			array(
				'mas-grid'            => array(
					'label'   => esc_html__( 'Grid View', 'mas-addons-for-elementor' ),
					'icon'    => 'fa fa-th',
					'enabled' => true,
					'active'  => true,
					// 'template'      => array( 'slug' => 'content', 'name' => 'product' ),
				),
				'mas-grid-extended'   => array(
					'label'   => esc_html__( 'Grid Extended View', 'mas-addons-for-elementor' ),
					'icon'    => 'fa fa-align-justify',
					'enabled' => true,
					'active'  => false,
					   // 'template'      => array( 'slug' => 'templates/contents/content', 'name' => 'product-grid-extended' ),
				),
				'mas-list-view'       => array(
					'label'   => esc_html__( 'List View', 'mas-addons-for-elementor' ),
					'icon'    => 'fa fa-list',
					'enabled' => true,
					'active'  => false,
					   // 'template'      => array( 'slug' => 'templates/contents/content', 'name' => 'product-list-view' ),
				),
				'mas-list-view-small' => array(
					'label'   => esc_html__( 'List View Small', 'mas-addons-for-elementor' ),
					'icon'    => 'fa fa-th-list',
					'enabled' => true,
					'active'  => false,
					   // 'template'      => array( 'slug' => 'templates/contents/content', 'name' => 'product-list-small' ),
				),
			)
		);

		return $shop_views;
	}

	/**
	 * Displays an advanced pagination
	 */
	public function mas_elementor_advanced_pagination() {

		global $wp_query, $wp_rewrite;

		$products = $this->get_query_results();

		$total_pages = $products->total_pages;

		if ( $total_pages <= 1 ) {
			return;
		}

		// Setting up default values based on the current URL.
		$pagenum_link = html_entity_decode( get_pagenum_link() );
		$url_parts    = explode( '?', $pagenum_link );

		// Get max pages and current page out of the current query, if available.
		$total   = isset( $total_pages ) ? $total_pages : 1;
		$current = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;

		// Append the format placeholder to the base URL.
		$pagenum_link = trailingslashit( $url_parts[0] ) . '%_%';

		// URL base depends on permalink settings.
		$format  = $wp_rewrite->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
		$format .= $wp_rewrite->using_permalinks() ? user_trailingslashit( $wp_rewrite->pagination_base . '/%#%', 'paged' ) : '?paged=%#%';

		$base     = esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) );
		$add_args = false;

		$output     = '';
		$prev_arrow = is_rtl() ? '&rarr;' : '&larr;';
		$next_arrow = is_rtl() ? '&larr;' : '&rarr;';

		if ( $current && 1 < $current ) :
			$link    = str_replace( '%_%', 2 == $current ? '' : $format, $base );
			$link    = str_replace( '%#%', $current - 1, $link );
			$output .= '<a class="prev page-numbers" href="' . esc_url( apply_filters( 'paginate_links', $link ) ) . '">' . $prev_arrow . '</a>';
		endif;

		$number_input = '<form method="post" class="form-adv-pagination"><input id="goto-page" size="2" min="1" max="' . esc_attr( $total ) . '" step="1" type="number" class="form-control" value="' . esc_attr( $current ) . '" /></form>';
		// Translators: %1$s represents the number input, %2$s represents the total.
		$output      .= sprintf( esc_html__( '%1$s of %2$s', 'mas-addons-for-elementor' ), $number_input, $total );

		if ( $current && ( $current < $total || -1 == $total ) ) :
			$link    = str_replace( '%_%', $format, $base );
			$link    = str_replace( '%#%', $current + 1, $link );
			$output .= '<a class="next page-numbers" href="' . esc_url( apply_filters( 'paginate_links', $link ) ) . '">' . $next_arrow . '</a>';
		endif;

		$link = str_replace( '%_%', $format, $base );
		?>
		<nav class="mas-elementor-advanced-pagination">
			<?php print $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<script>
				jQuery(document).ready(function($){
					$( '.form-adv-pagination' ).on( 'submit', function() {
						var link        = '<?php echo esc_url( apply_filters( 'paginate_links', $link ) ); ?>',
							goto_page   = $( '#goto-page' ).val(),
							new_link    = link.replace( '%#%', goto_page ).replace(/&#038;/g, '&');

						window.location.href = new_link;
						return false;
					});
				});
			</script>
		</nav>
		<?php
	}


}
