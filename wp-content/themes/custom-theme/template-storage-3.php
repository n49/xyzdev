<?php /* Template Name: Storage 3 */ get_header(); ?>

<div class="header-wrap">
	<div class="wrapper">
		<h1 class="title">
			<?php the_title(); ?>
		</h1>
	</div>
</div>

<div class="main-container no-padding">
	<?php if(get_field('storage_description_content')): ?>
		<div class="services-wrap spacing-2 full" style="background-image: url('<?php the_field('storage_description_background'); ?>');">
			<div class="wrapper">
				<div class="content">
					<?php the_field('storage_description_content'); ?>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<?php if( have_rows('storage_features') ): ?>
		<div class="gallery-wrap narrow spacing white" style="background-image: url('<?php the_field('storage_features_background'); ?>');">
			<div class="wrapper overflow">
				<div class="float-left">
					<p class="title-5 icon icon-arrow">
						<?php _e('overview', 'html5blank'); ?>
					</p>

					<h2 class="title">
						<?php _e('features', 'html5blank'); ?>
					</h2>

					<div class="columns columns-4 services large">
						<?php while( have_rows('storage_features') ): the_row(); ?>
							<div class="col">
								<?php if( get_sub_field('storage_features_icon') ): ?>
									<?php $image = get_sub_field('storage_features_icon'); ?>

									<div class="img-wrap no-radius">
										<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
									</div>
								<?php endif; ?>

								<div class="content">
									<p class="title-5">
										<?php the_sub_field('storage_features_title'); ?>
									</p>
								</div>
							</div>
						<?php endwhile; ?>
					</div>
				</div>

				<?php if(get_field('storage_features_content')): ?>
					<div class="float-right border hidden-1024">
						<?php the_field('storage_features_content'); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>

	<div class="wrapper">
		<div class="gallery-wrap overflow">
			<?php if( get_field('storage_gallery') ): ?>
				<div class="float-left">
					<?php
						$images = get_field('storage_gallery');
						$count = count($images);
					?>

					<div class="wp-slick-slider has-lightbox slide-count-<?php echo $count; ?>">
						<?php foreach($images as $image): ?>
							<div class="img-wrap">
								<a href="<?php echo $image['url']; ?>">
									<img src="<?php echo $image['sizes']['medium-2']; ?>" alt="<?php echo $image['alt']; ?>" />

									<span class="btn btn-grey"><?php _e('view all photos', 'html5blank'); ?></span>
								</a>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if( get_field('storage_gallery_content') ): ?>
				<div class="float-right content">
					<?php the_field('storage_gallery_content'); ?>

					<?php if( have_rows('storage_gallery_units') ): ?>
						<div class="columns buttons">
							<?php while( have_rows('storage_gallery_units') ): the_row(); ?>
								<div class="col">
									<h3 class="title-5">
										<?php the_sub_field('storage_gallery_units_title'); ?>
									</h3>

									<p>
										<?php the_sub_field('storage_gallery_units_description'); ?>
									</p>

									<?php if( have_rows('storage_gallery_units_buttons') ): ?>
						            	<div class="btn-wrap">
						            		<?php while( have_rows('storage_gallery_units_buttons') ): the_row(); ?>
						                        <a class="btn icon <?php the_sub_field('storage_gallery_units_buttons_icon'); ?>" href="<?php the_sub_field('storage_gallery_units_buttons_url'); ?>">
						                            <?php the_sub_field('storage_gallery_units_buttons_title'); ?>
						                        </a>
						            		<?php endwhile; ?>
						            	</div>
						            <?php endif; ?>
								</div>
							<?php endwhile; ?>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<?php if(get_field('storage_features_content')): ?>
		<div class="gallery-wrap narrow spacing white hidden-all show-1024">
			<div class="wrapper overflow">
				<?php the_field('storage_features_content'); ?>
			</div>
		</div>
	<?php endif; ?>

	<?php get_template_part('loop-locations-single'); ?>

	<?php if(get_field('storage_about_left_content') || get_field('storage_about_right_content')): ?>
		<div class="gallery-wrap white spacing content" style="background-image: url('<?php the_field('storage_about_background'); ?>');">
			<div class="wrapper overflow">
				<?php if(get_field('storage_about_left_content')): ?>
					<div class="float-left">
						<?php the_field('storage_about_left_content'); ?>
					</div>
				<?php endif; ?>

				<?php if(get_field('storage_about_right_content')): ?>
					<div class="float-right">
						<?php the_field('storage_about_right_content'); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>

	<?php get_template_part('module-cta'); ?>

	<?php if( have_rows('storage_solutions') ): ?>
		<div class="solutions-section spacing" style="background-image: url('<?php the_field('storage_solutions_background'); ?>');">
			<div class="wrapper">
				<?php if(get_field('storage_solutions_subtitle')): ?>
					<p class="title-5 icon icon-arrow">
						<?php the_field('storage_solutions_subtitle'); ?>
					</p>
				<?php endif; ?>

				<?php if(get_field('storage_solutions_title')): ?>
					<h2 class="title">
						<?php the_field('storage_solutions_title'); ?>
					</h2>
				<?php endif; ?>

				<div class="columns columns-4 features">
					<?php while( have_rows('storage_solutions') ): the_row(); ?>
						<div class="col">
							<?php if(get_sub_field('storage_solutions_image')): ?>
								<?php $image = get_sub_field('storage_solutions_image'); ?>

								<div class="img-wrap mobile-hidden">
									<img src="<?php echo $image['sizes']['medium-4']; ?>" alt="<?php echo $image['alt']; ?>" />
								</div>
							<?php endif; ?>

							<h3 class="title-4">
								<?php the_sub_field('storage_solutions_title'); ?>
							</h3>

							<p>
								<?php the_sub_field('storage_solutions_description'); ?>
							</p>
						</div>
					<?php endwhile; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<?php get_template_part('loop-services-single'); ?>
</div>

<?php get_footer(); ?>
