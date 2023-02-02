<?php
	$args_featured = array(
		'post_type' => 'post',
		'posts_per_page' => 1,
		'paged' => $paged
	);

	$loop = new WP_Query($args_featured);
?>

<div class="columns posts">
	<?php if ($loop->have_posts()): while ($loop->have_posts()) : $loop->the_post(); ?>
		<?php $exclude_post = get_the_ID(); ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class('col'); ?>>
			<?php if ( has_post_thumbnail()) : ?>
				<a class="img-wrap radius" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
					<?php the_post_thumbnail('medium-3'); ?>
				</a>
			<?php endif; ?>

			<p class="category">
				<?php the_category(', '); ?>
			</p>

			<h2 class="title-2">
				<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
					<?php the_title(); ?>
				</a>
			</h2>
		</article>
	<?php endwhile; ?>

	<?php endif; ?>

	<?php wp_reset_query(); ?>
</div>

<?php
	$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;

	$args = array(
		'post_type' => 'post',
		'posts_per_page' => 4,
		'paged' => $paged,
		'post__not_in' => array($exclude_post)
	);

	$wp_query = new WP_Query($args);
?>

<div class="columns columns-2 posts">
	<?php if ($wp_query->have_posts()): while ($wp_query->have_posts()) : $wp_query->the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class('col'); ?>>
			<?php if ( has_post_thumbnail()) : ?>
				<a class="img-wrap radius" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
					<?php the_post_thumbnail('medium-2'); ?>
				</a>
			<?php endif; ?>

			<p class="category">
				<?php the_category(', '); ?>
			</p>

			<h3 class="title">
				<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
					<?php the_title(); ?>
				</a>
			</h3>
		</article>
	<?php endwhile; ?>

	<?php endif; ?>

	<?php get_template_part('pagination'); ?>

	<?php wp_reset_query(); ?>
</div>
