<?php /* Template Name: Storage Sizes */ get_header(); ?>

<div class="header-wrap">
	<div class="wrapper">
		<h1 class="title">
			<?php the_title(); ?>
		</h1>
	</div>
</div>

<?php if(get_field('storage_description_content')): ?>
	<div class="services-wrap spacing-2 full" style="background-image: url('<?php the_field('storage_description_background'); ?>');">
		<div class="wrapper">
			<div class="content">
				<?php the_field('storage_description_content'); ?>
			</div>
		</div>
	</div>
<?php endif; ?>

<div class="main-container spacing-3 icon has-arrow" style="background-image: url('<?php the_field('page_background_image'); ?>');">
	<div class="wrapper">
		<?php get_template_part('loop-units-category'); ?>
	</div>
</div>

<?php get_template_part('loop-locations-all'); ?>

<?php get_footer(); ?>
