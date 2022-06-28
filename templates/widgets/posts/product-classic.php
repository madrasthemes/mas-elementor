<?php
/**
 * Template for displaying Post Classic widget.
 *
 * @package MASElementor/Templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<li class="product elementor-post elementor-grid-item post-1 post type-post status-publish format-standard hentry category-uncategorized">
	<a class="elementor-post__thumbnail__link" href="<?php the_permalink(); ?>">
		<div class="elementor-post__thumbnail"><?php the_post_thumbnail( 'full' ); ?></div>
	</a>
	<div class="elementor-post__text">
		<h3 class="elementor-post__title">
			<a href="<?php echo esc_url( get_permalink() ); ?>" ><?php the_title(); ?></a>
		</h3>
		<div class="elementor-post__meta-data">
            <span class="elementor-post-date"><?php echo apply_filters( 'the_date', get_the_modified_date(), get_option( 'date_format' ), '', '' ); //phpcs:ignore ?></span>
			<span class="elementor-post-avatar"><?php comments_number(); ?></span>
		</div>
		<div class="elementor-post__excerpt">
			<p><?php the_excerpt(); ?></p>
		</div>
		<a class="elementor-post__read-more" href="<?php echo esc_url( get_permalink() ); ?>">
				<?php echo esc_html( apply_filters( 'read_more_text', 'Read More >>' ) ); ?>
			</a>
	</div>
</li>
<?php
