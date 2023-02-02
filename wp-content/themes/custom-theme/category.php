<?php get_header(); ?>

<div class="header-wrap">
	<div class="wrapper">
		<h1 class="title">
			<?php _e( 'categories for ', 'html5blank' ); single_cat_title(); ?>
		</h1>
	</div>
</div>

<div class="main-container" style="background-image: url('<?php the_field('page_background_image'); ?>');">
	<div class="wrapper overflow">
		<main role="main">
			<?php get_template_part('loop-normal'); ?>

			<?php get_template_part('pagination'); ?>
		</main>

		<?php get_sidebar(); ?>
	</div>
</div>

<?php get_footer(); ?>
