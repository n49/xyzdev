<?php /* Template Name: Community */ get_header(); ?>

<div class="header-wrap">
	<div class="wrapper">
		<h1 class="title">
			<?php the_title(); ?>
		</h1>
	</div>
</div>

<?php if(get_field('community_description_content')): ?>
	<div class="services-wrap spacing-2 full" style="background-image: url('<?php the_field('community_description_background'); ?>');">
		<div class="wrapper">
			<div class="content">
				<?php the_field('community_description_content'); ?>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php if( have_rows('organizations') ): ?>
	<div class="main-container spacing services-wrap">
		<div class="wrapper">
			<?php while( have_rows('organizations') ): the_row(); ?>
				<?php if(get_sub_field('organizations_title')): ?>
					<?php the_sub_field('organizations_title'); ?>
				<?php endif; ?>
			
				<div class="columns columns-3 features logos margin">
					<?php if( have_rows('organizations_items') ): ?>
						<?php while( have_rows('organizations_items') ): the_row(); ?>
							<div class="col">
								<?php if(get_sub_field('organizations_items_thumbnail')): ?>
									<?php $image = get_sub_field('organizations_items_thumbnail'); ?>

									<div class="img-wrap">
										<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
									</div>
								<?php endif; ?>

								<h3 class="title-5">
									<?php the_sub_field('organizations_items_title'); ?>
								</h3>

								<?php the_sub_field('organizations_items_description'); ?>
							</div>
						<?php endwhile; ?>
					<?php endif; ?>
				</div>
		<?php endwhile; ?>
		</div>
	</div>
<?php endif; ?>

<?php get_footer(); ?>
