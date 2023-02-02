<?php
	$parent = $post->post_parent;
	$current = $post->post_name;

	$args = array(
		'post_type' => 'locations',
		'posts_per_page' => -1,
		'post_parent' => $parent,
		'meta_key' => 'location_service_type',
		'meta_value' => 'service'
	);

	$loop = new WP_Query($args);
?>

<div class="widget">
	<ul class="menu white">
		<li>
			<a href="<?php echo get_permalink($parent); ?>">
				<?php _e('overview', 'html5blank'); ?>
			</a>
		</li>

		<?php if ($loop->have_posts()): while ($loop->have_posts()) : $loop->the_post(); ?>

			<?php $class = ($current == $post->post_name) ? 'current-menu-item' : ''; ?>

			<?php if(get_field('location_service_custom_link')): ?>
				<?php $url = get_field('location_service_custom_link'); ?>
			<?php else: ?>
				<?php $url = get_permalink(); ?>
			<?php endif; ?>

			<li class="<?php echo $class; ?>">
				<a href="<?php echo $url; ?>" title="<?php the_title(); ?>">
					<?php the_title(); ?>
				</a>
			</li>
		<?php endwhile; ?>

		<?php endif; ?>

		<?php wp_reset_query(); ?>

		<li>
			<a href="<?php echo get_permalink($parent); ?>#reviews">
				<?php _e('reviews', 'html5blank'); ?>
			</a>
		</li>

		<li>
			<a href="<?php echo get_permalink($parent); ?>#units">
				<?php _e('available storage units', 'html5blank'); ?>
			</a>
		</li>
	</ul>
</div>
