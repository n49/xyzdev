<?php
	$args = array(
		'post_type' => 'promotions',
		'posts_per_page' => -1
	);

	$loop = new WP_Query($args);
?>

<div class="columns columns-3 white flex img-inside">
	<?php if ($loop->have_posts()): while ($loop->have_posts()) : $loop->the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class('col'); ?>>
			<?php if ( has_post_thumbnail()) : ?>
				<a class="img-wrap" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
					<?php the_post_thumbnail('medium'); ?>
				</a>
			<?php endif; ?>

			<div class="content">
				<h3 class="title-4">
					<?php the_title(); ?>
				</h3>

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
