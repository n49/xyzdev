<?php
	$args = array(
		'post_type' => 'faq',
		'posts_per_page' => -1,
	);

	$loop = new WP_Query($args);
?>

<div class="widget">
	<h3 class="title">
		<?php _e('help topics', 'html5blank'); ?>
	</h3>

	<ul class="menu white">
		<?php if ($loop->have_posts()): while ($loop->have_posts()) : $loop->the_post(); ?>
			<li>
				<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
					<?php the_title(); ?>
				</a>
			</li>
		<?php endwhile; ?>

		<?php endif; ?>

		<?php wp_reset_query(); ?>

		<li>
			<a class="underline" href="<?php bloginfo('url'); ?>/help-center/">
				<?php _e('see all help topics', 'html5blank'); ?>
			</a>
		</li>
	</ul>
</div>
