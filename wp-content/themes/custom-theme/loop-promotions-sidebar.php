<?php
	$args = array(
		'post_type' => 'promotions',
		'posts_per_page' => 2,
		'post__not_in' => array($post->ID),
		'orderby' => 'rand'
	);

	$loop = new WP_Query($args);
?>

<div class="widget">
	<p class="title-5 icon icon-arrow">
		<?php _e( 'other promotions', 'html5blank' ); ?>
	</p>

	<div class="columns white flex vertical">
		<?php if ($loop->have_posts()): while ($loop->have_posts()) : $loop->the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class('col'); ?>>
				<div class="content">
					<p class="title-5">
						<?php the_title(); ?>
					</p>

					<?php if (has_excerpt()) : ?>
						<?php html5wp_excerpt(); ?>
					<?php endif; ?>

					<a class="btn" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
						<?php _e('learn more', 'html5blank'); ?>
					</a>
				</div>
			</article>
		<?php endwhile; ?>

		<?php endif; ?>

		<?php wp_reset_query(); ?>
	</div>
</div>
