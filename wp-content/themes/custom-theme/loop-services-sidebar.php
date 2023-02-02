<?php
	$args = array(
		'post_type' => 'services',
		'posts_per_page' => -1,
	);

	$current = $post->post_name;

	$loop = new WP_Query($args);
?>

<div class="widget">
	<ul class="menu white">
		<li>
			<a href="<?php bloginfo('url'); ?>/services/">
				<?php _e('overview', 'html5blank'); ?>
			</a>
		</li>

		<?php if ($loop->have_posts()): while ($loop->have_posts()) : $loop->the_post(); ?>
			<?php $class = ($current == $post->post_name) ? 'current-menu-item' : ''; ?>

			<li class="<?php echo $class; ?>">
				<?php if(get_field('service_custom_link')): ?>
					<?php $url = get_field('service_custom_link'); ?>
				<?php else: ?>
					<?php $url = get_permalink(); ?>
				<?php endif; ?>

				<a href="<?php echo $url; ?>" title="<?php the_title(); ?>">
					<?php the_title(); ?>
				</a>
			</li>
		<?php endwhile; ?>

		<?php endif; ?>

		<?php wp_reset_query(); ?>
	</ul>
</div>
