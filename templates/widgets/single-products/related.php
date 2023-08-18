<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $related_products ) : ?>

	<section class="related products">
		<div class="mas-products mas-grid">
		<?php
		$instance_settings = $widget->get_settings_for_display();
		foreach ( $related_products as $related_product ) :
			$post_object = get_post( $related_product->get_id() );

			setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found
			?>
			<div class="mas-product">
			<?php

			print( mas_render_template( $instance_settings['select_template'], false ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?>
			</div>
			<?php
		endforeach;
		?>
		</div>
	</section>
	<?php
endif;

wp_reset_postdata();
