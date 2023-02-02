<?php /* Template Name: Services */ get_header(); ?>

<div class="header-wrap">
	<div class="wrapper">
		<h1 class="title">
			<?php the_title(); ?>
		</h1>
	</div>
</div>

<div class="main-container" style="background-image: url('<?php the_field('page_background_image'); ?>');">
	<div class="wrapper">
		<?php get_template_part('loop-services'); ?>
	</div>
</div>

<?php get_template_part('module-cta'); ?>

<?php get_footer(); ?>
