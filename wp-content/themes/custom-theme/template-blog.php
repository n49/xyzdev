<?php /* Template Name: Blog */ get_header(); ?>

<div class="header-wrap">
	<div class="wrapper">
		<h1 class="title">
			<?php the_title(); ?>
		</h1>
	</div>
</div>

<div class="main-container" style="background-image: url('<?php the_field('page_background_image'); ?>');">
	<div class="wrapper overflow">
		<main role="main">
			<?php get_template_part('loop'); ?>
		</main>

		<?php get_sidebar(); ?>
	</div>
</div>

<?php get_footer(); ?>
