<?php
	$taxonomy = 'faq-category';

	$terms = get_terms(array(
		'taxonomy' => $taxonomy,
		'hide_empty' => false
	));
?>

<div class="category-wrap white">
	<div class="wrapper narrow">
		<div class="columns category">
			<?php foreach ($terms as $term) : ?>
				<?php

					$args = array(
						'post_type' => 'faq',
						'posts_per_page' => -1,
						'tax_query' => array(
							array(
								'taxonomy' => $taxonomy,
								'field'    => 'slug',
								'terms'    => $term->slug
							),
						)
					);

					$loop = new WP_Query($args);
				?>

				<article id="<?php echo $term->slug; ?>" <?php post_class('col overflow'); ?>>
					<div class="float-left icon-wrap">
						<?php if( get_field('faq_icon', $term) ): ?>
							<?php $image = get_field('faq_icon', $term); ?>

							<div class="img-wrap no-radius">
								<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
							</div>
						<?php endif; ?>

						<h3 class="title-4">
							<?php echo $term->name; ?>
						</h3>
					</div>

					<div class="float-right">
						<?php if ($loop->have_posts()): while ($loop->have_posts()) : $loop->the_post(); ?>
							<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
								<?php the_title(); ?>
							</a>
						<?php endwhile; ?>

						<?php endif; ?>

						<?php wp_reset_query(); ?>
					</div>
				</article>
			<?php endforeach; ?>

			<?php wp_reset_query(); ?>
		</div>
	</div>
</div>
