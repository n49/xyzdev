<div class="columns columns-2 posts">
	<?php if (have_posts()): while (have_posts()) : the_post(); ?>
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
</div>
