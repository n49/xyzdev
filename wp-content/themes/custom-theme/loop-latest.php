<?php
	$args_featured = array(
		'post_type' => 'post',
		'posts_per_page' => 3,
	);

	$loop = new WP_Query($args_featured);
?>

<?php if ($loop->have_posts()): while ($loop->have_posts()) : $loop->the_post(); ?>
	<?php $exclude_post = get_the_ID(); ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class('col'); ?>>
		<?php if ( has_post_thumbnail()) : ?>
			<a class="img-wrap" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
				<?php the_post_thumbnail('medium'); ?>
			</a>
		<?php endif; ?>

		<div class="content">
			<p class="category margin">
				<?php the_category(', '); ?>
			</p>

			<h3 class="title-4">
				<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
					<?php the_title(); ?>
				</a>
			</h3>

			<?php html5wp_excerpt(); ?>

			<a class="btn" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
				<?php _e('learn more', 'html5blank'); ?>
			</a>
		</div>
	</article>
<?php endwhile; ?>

<?php endif; ?>

<?php wp_reset_query(); ?>
