<?php /* Template Name: Promotions */ get_header(); ?>

<div class="header-wrap">
	<div class="wrapper">
		<h1 class="title">
			<?php the_title(); ?>
		</h1>
	</div>
</div>

<div class="main-container" style="background-image: url('<?php the_field('page_background_image'); ?>');">
	<div class="wrapper">
		<main role="main" class="fullwidth">
			<?php if (have_posts()): while (have_posts()) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php the_content(); ?>
				</article>
			<?php endwhile; ?>

			<?php endif; ?>
		</main>

		<?php get_template_part('loop-promotions'); ?>
	</div>
</div>

<?php get_template_part('module-cta'); ?>

<?php get_footer(); 