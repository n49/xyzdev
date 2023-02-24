<?php
	$args = array(
		'post_type' => 'faq',
		'posts_per_page' => -1,
	);

	$current = $post->post_name;

	$loop = new WP_Query($args);
?>

<div class="widget">
	<ul class="menu white">
		<li>
			<a href="<?php bloginfo('url'); ?>/help-center/">
				<?php _e('overview', 'html5blank'); ?>
			</a>
		</li>

		<?php if ($loop->have_posts()): while ($loop->have_posts()) : $loop->the_post(); ?>

			<?php $class = ($current == $post->post_name) ? 'current-menu-item' : ''; ?>

			<li class="<?php echo $class; ?>">
				<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
					<?php the_title(); ?>
				</a>
			</li>
		<?php endwhile; ?>

		<?php endif; ?>

		<?php wp_reset_query(); ?>
	</ul>
</div>
