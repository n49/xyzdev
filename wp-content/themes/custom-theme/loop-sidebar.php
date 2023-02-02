<?php
	$terms = get_the_terms($post->ID, 'category');

	$args = array(
		'post_type' => 'post',
		'posts_per_page' => 2,
		'post__not_in' => array($post->ID),
		'tax_query' => array(
			array(
				'taxonomy' => 'category',
				'field'    => 'slug',
				'terms'    => $terms[0]->slug
			),
		)
	);

	$loop = new WP_Query($args);
?>

<div class="widget">
	<p class="title-5 icon icon-arrow">
		<?php _e( 'articles you may like', 'html5blank' ); ?>
	</p>

	<div class="columns columns white flex vertical">
		<?php if ($loop->have_posts()): while ($loop->have_posts()) : $loop->the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class('col'); ?>>
				<div class="content">
					<p class="category">
						<?php the_category(', '); ?>
					</p>

					<h3 class="title-4">
						<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
							<?php the_title(); ?>
						</a>
					</h3>

					<div class="meta-wrap vertical">
						<p class="category">
							<?php _e( 'under: ', 'html5blank' ); the_category(', '); ?>
						</p>

						<p class="date">
							<?php _e( 'published: ', 'html5blank' ); ?>

							<span><?php the_time('j F Y'); ?></span>
						</p>
					</div>
				</div>
			</article>
		<?php endwhile; ?>

		<?php endif; ?>

		<?php wp_reset_query(); ?>
	</div>
</div>
