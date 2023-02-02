<?php
	$args = array(
		'post_type' => 'locations',
		'posts_per_page' => -1,
		'meta_key' => 'location_area',
		'meta_value' => 'montreal'
	);

	$loop = new WP_Query($args);
?>

<div class="locations-wrap">
	<div class="columns columns-4 locations flex flex-normal-2 white products has-button">
		<?php if ($loop->have_posts()): while ($loop->have_posts()) : $loop->the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class('col'); ?>>
				<div class="content">
					<?php if(get_field('location_new') === 'yes'): ?>
						<span class="new">
							<?php the_field('location_new_label'); ?>
						</span>
					<?php endif; ?>

					<a class="no-hover" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
						<h2 class="title title-4">
							<?php the_title(); ?>
						</h2>
					</a>

					<?php if(get_field('location_address')): ?>
						<?php
							$location = get_field('location_address');
							$address = $location['address'];
						?>

						<p class="location">
							<?php if(get_field('location_custom_address_2')): ?>
								<?php the_field('location_custom_address_2'); ?>
							<?php else: ?>
								<?php echo $address; ?>
							<?php endif; ?>
						</p>
					<?php endif; ?>

					<?php if(get_field('location_custom_address')): ?>
						<p class="icon location-2">
							<?php the_field('location_custom_address'); ?>
						</p>
					<?php endif; ?>
				</div>

				<div class="bottom">
					<div class="btn-wrap">
						<a class="btn" href="<?php the_permalink(); ?>book-a-unit" title="<?php the_title(); ?>">
							<?php _e('reserve a unit', 'html5blank'); ?>
						</a>
					</div>

					<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
						<?php _e('view location details', 'html5blank'); ?>
					</a>
				</div>
			</article>
		<?php endwhile; ?>

		<?php endif; ?>

		<?php wp_reset_query(); ?>
	</div>
</div>
