<?php
/**
 * Template for displaying Post Grid widget.
 *
 * @package MASElementor/Templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
$query         = $widget->get_query();
		$index = $query->current_post + 1;
		$total = $query->post_count;

if ( 1 === $index % 3 ) {
	?>
			<div class="row mb-4 pb-lg-4 pb-3 si-article">
				<div class="col-lg-5 col-12 mb-lg-0 mb-4">
					<article class="card h-100 border-0 shadow-sm">
				<?php silicon_the_post_thumbnail( 'full', '', 263, 132, 'position-relative card-img-top overflow-hidden' ); ?>
						<div class="card-body pb-4">
							<div class="d-flex align-items-center justify-content-between mb-3">
						<?php silicon_the_post_categories( 'card-grid' ); ?>
						<?php silicon_the_post_date( 'card-grid', true ); ?>
							</div>
							<h3 class="h5 mb-0"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						</div>
						<div class="card-footer py-4">
					<?php silicon_the_post_author( 'card-grid', $args ); ?>
						</div>
					</article>
				</div>
				<div class="col gap-y-[1.5rem]">
			<?php
} else {
	?>
			<article class="card border-0 shadow-sm overflow-hidden">
			<div class="row g-0 position-relative">
				<div class="col-sm-5">
			<?php silicon_the_post_thumbnail( 'full', '', 460, 361 ); ?>
				</div>
				<div class="col-sm-7">
					<div class="card-body">
						<div class="d-flex align-items-center mb-3">
					<?php silicon_the_post_categories( 'card-list' ); ?>
					<?php silicon_the_post_date( 'card-list' ); ?>
						</div>
						<h3 class="h5">
							<a href="<?php the_permalink(); ?>" class="stretched-link"><?php the_title(); ?></a>
						</h3>
						<hr class="my-4 bg-current">
						<div class="d-flex flex-sm-nowrap flex-wrap align-items-center justify-content-between">
					<?php silicon_the_post_author( 'card-list', $args ); ?>
					<?php silicon_the_post_meta_icons( 'card-list' ); ?>
						</div>
					</div>
				</div>
			</div>
		</article>
		<?php
}

if ( 3 === $index % 4 || $index === $total ) {
	?>
				</div><!-- /.col -->
			</div><!-- /.row -->
	<?php
}
