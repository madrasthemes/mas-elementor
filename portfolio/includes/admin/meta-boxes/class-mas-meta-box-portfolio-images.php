<?php
/**
 * Portfolio Images
 *
 * Display the portfolio images meta box.
 *
 * @author      MadrasThemes
 * @category    Admin
 * @package     MAS/Admin/Meta Boxes
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * MAS_Meta_Box_Portfolio_Images Class.
 */
class MAS_Meta_Box_Portfolio_Images {

	/**
	 * Output the metabox.
	 *
	 * @param WP_Post $post
	 */
	public static function output( $post ) {
		global $thepostid;

		$thepostid      = $post->ID;
		wp_nonce_field( 'mas_save_data', 'mas_meta_nonce' );
		?>
		<div id="portfolio_images_container">
			<ul class="portfolio_images">
				<?php
				$portfolio_image_gallery = explode( ',', get_post_meta( $post->ID, '_portfolio_image_gallery', true ) );
				$attachments         = array_filter( $portfolio_image_gallery );
				$update_meta         = false;
				$updated_gallery_ids = array();

				if ( ! empty( $attachments ) ) {
					foreach ( $attachments as $attachment_id ) {
						$attachment = wp_get_attachment_image( $attachment_id, 'thumbnail' );

						// if attachment is empty skip.
						if ( empty( $attachment ) ) {
							$update_meta = true;
							continue;
						}

						echo '<li class="image" data-attachment_id="' . esc_attr( $attachment_id ) . '">
								' . $attachment . '
								<ul class="actions">
									<li><a href="#" class="delete tips" data-tip="' . esc_attr__( 'Delete image', 'mas-elementor' ) . '">' . __( 'Delete', 'mas-elementor' ) . '</a></li>
								</ul>
							</li>';

						// rebuild ids to be saved.
						$updated_gallery_ids[] = $attachment_id;
					}

					// need to update portfolio meta to set new gallery ids
					if ( $update_meta ) {
						update_post_meta( $post->ID, '_portfolio_image_gallery', implode( ',', $updated_gallery_ids ) );
					}
				}
				?>
			</ul>

			<input type="hidden" id="portfolio_image_gallery" name="portfolio_image_gallery" value="<?php echo esc_attr( implode( ',', $updated_gallery_ids ) ); ?>" />

		</div>
		<p class="add_portfolio_images hide-if-no-js">
			<a href="#" data-choose="<?php esc_attr_e( 'Add images to portfolio gallery', 'mas-elementor' ); ?>" data-update="<?php esc_attr_e( 'Add to gallery', 'mas-elementor' ); ?>" data-delete="<?php esc_attr_e( 'Delete image', 'mas-elementor' ); ?>" data-text="<?php esc_attr_e( 'Delete', 'mas-elementor' ); ?>"><?php _e( 'Add portfolio gallery images', 'mas-elementor' ); ?></a>
		</p><?php
		
	}

	/**
	 * Save meta box data.
	 *
	 * @param int     $post_id
	 * @param WP_Post $post
	 */
	public static function save( $post_id, $post ) {
		$attachment_ids = isset( $_POST['portfolio_image_gallery'] ) ? array_filter( explode( ',', mas_clean( $_POST['portfolio_image_gallery'] ) ) ) : array();
		$updated_gallery_ids = implode( ',', $attachment_ids );
		update_post_meta( $post->ID, '_portfolio_image_gallery', $updated_gallery_ids );
	}
}
