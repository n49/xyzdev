<?php
	$args = array(
		'post_type' => 'services',
		'posts_per_page' => -1,
		'meta_key' => 'service_visible',
		'meta_value' => 'yes',
		'post__not_in' => array(12424)
	);

	$loop = new WP_Query($args);
?>

<div class="services-wrap spacing">
	<div class="wrapper">
		<p class="title-5 icon icon-arrow">
			<?php _e('services', 'html5blank'); ?>
		</p>

		<h2 class="title">
			<?php _e('we offer more than just space', 'html5blank'); ?>
		</h2>

		<div class="columns columns-4 services flex flex-normal">
			<?php if ($loop->have_posts()): while ($loop->have_posts()) : $loop->the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class('col'); ?>>
					<div class="content-wrap">
						<?php if(get_field('service_custom_link')): ?>
							<?php $url = get_field('service_custom_link'); ?>
						<?php else: ?>
							<?php $url = get_permalink(); ?>
						<?php endif; ?>

						<?php if( get_field('service_icon') ): ?>
							<?php $image = get_field('service_icon'); ?>

							<a href="<?php echo $url; ?>" title="<?php the_title(); ?>" class="img-wrap disable no-radius">
								<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
							</a>
						<?php endif; ?>

						<div class="content">
							<h3 class="title-4">
								<?php the_title(); ?>
							</h3>

							<?php if(get_field('service_description')): ?>
								<p>
									<?php the_field('service_description'); ?>
								</p>
							<?php endif; ?>

							<a class="btn" href="<?php echo $url; ?>" title="<?php the_title(); ?>">
								<?php _e('learn more', 'html5blank'); ?>
							</a>
						</div>
					</div>
				</article>
			<?php endwhile; ?>

			<?php endif; ?>

			<?php wp_reset_query(); ?>
		</div>
	</div>
</div>
