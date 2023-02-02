<?php /* Template Name: Pricing */ get_header(); ?>

<div class="header-wrap">
	<div class="wrapper">
		<h1 class="title">
			<?php the_title(); ?>
		</h1>
	</div>
</div>

<div class="main-container no-padding has-right-sidebar">
	<?php if(get_field('prices_description_content')): ?>
		<div class="services-wrap spacing-2 full" style="background-image: url('<?php the_field('prices_description_background'); ?>');">
			<div class="wrapper">
				<div class="content">
					<?php the_field('prices_description_content'); ?>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<div class="services-wrap spacing" style="background-image: url('<?php the_field('page_background_image'); ?>');">
		<div class="wrapper overflow">
			<main role="main">
				<?php if (have_posts()): while (have_posts()) : the_post(); ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<?php the_content(); ?>
					</article>
				<?php endwhile; ?>

				<?php endif; ?>
			</main>

			<div class="sidebar">
				<?php if( have_rows('features') ): ?>
					<div class="widget features-2">
						<?php if(get_field('features_title')): ?>
							<?php the_field('features_title'); ?>
						<?php endif; ?>

						<div class="columns services side">
							<?php while( have_rows('features') ): the_row(); ?>
								<div class="col">
									<div class="title-wrap overflow">
										<?php if( get_sub_field('features_icon') ): ?>
											<?php $image = get_sub_field('features_icon'); ?>

											<div class="img-wrap small no-radius">
												<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
											</div>
										<?php endif; ?>

										<p class="title-5">
											<?php the_sub_field('features_title'); ?>
										</p>
									</div>

									<?php the_sub_field('features_content'); ?>
								</div>
							<?php endwhile; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

	<?php if( have_rows('prices') ): ?>
		<div class="services-wrap spacing grey" style="background-image: url('<?php the_field('prices_background'); ?>');">
			<div class="wrapper">
				<?php if(get_field('prices_title')): ?>
					<h2 class="title full">
						<?php the_field('prices_title'); ?>
					</h2>
				<?php endif; ?>

				<div class="columns columns-3 flex sizes white">
					<?php while( have_rows('prices') ): the_row(); ?>
						<div class="col">
							<div class="content">
								<?php if( get_sub_field('prices_image') ): ?>
									<?php $image = get_sub_field('prices_image'); ?>

									<a class="img-wrap disable">
										<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
									</a>
								<?php endif; ?>

								<?php if(get_sub_field('prices_content')): ?>
									<?php the_sub_field('prices_content'); ?>
								<?php endif; ?>

								<?php if(get_sub_field('button_link')): ?>
									<a class="btn" href="<?php the_sub_field('button_link'); ?>">
										<?php the_sub_field('prices_button_label'); ?>
									</a>
								<?php endif; ?>
							</div>
						</div>
					<?php endwhile; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<?php if( get_field('prices_breakdown_description') || get_field('prices_comparison_description')): ?>
		<div class="breakdown-section spacing" style="background-image: url('<?php the_field('prices_breakdown_background'); ?>');">
			<div class="wrapper">
				<?php if(get_field('prices_breakdown_content')): ?>
					<div class="content">
						<?php the_field('prices_breakdown_content'); ?>
					</div>
				<?php endif; ?>

				<div class="breakdown overflow">
					<?php if(get_field('prices_breakdown_description')): ?>
						<div class="float-left">
							<?php the_field('prices_breakdown_description'); ?>
						</div>
					<?php endif; ?>

					<?php if( have_rows('prices_breakdown') ): ?>
						<div class="float-right columns">
							<?php while( have_rows('prices_breakdown') ): the_row(); ?>
								<div class="col">
									<?php if( get_sub_field('prices_breakdown_image') ): ?>
										<?php $image = get_sub_field('prices_breakdown_image'); ?>

										<div class="img-wrap">
											<img src="<?php echo $image['sizes']['small-3']; ?>" alt="<?php echo $image['alt']; ?>" />
										</div>
									<?php endif; ?>

									<?php if(get_sub_field('prices_breakdown_title')): ?>
										<p>
											<?php the_sub_field('prices_breakdown_title'); ?>
										</p>
									<?php endif; ?>

									<?php if(get_sub_field('prices_breakdown_price')): ?>
										<p class="price">
											<?php the_sub_field('prices_breakdown_price'); ?>
										</p>
									<?php endif; ?>
								</div>

								<?php $sep = get_sub_field('prices_breakdown_separator'); ?>

								<?php if(($sep === '+') || ($sep === '=') ): ?>
									<div class="col sep">
										<?php the_sub_field('prices_breakdown_separator'); ?>
									</div>
								<?php endif; ?>
							<?php endwhile; ?>
						</div>
					<?php endif; ?>
				</div>

				<div class="breakdown overflow range">
					<?php if(get_field('prices_comparison_description')): ?>
						<div class="float-left">
							<?php the_field('prices_comparison_description'); ?>
						</div>
					<?php endif; ?>

					<?php if( have_rows('prices_comparison_difference') ): ?>
						<div class="float-right">
							<?php while( have_rows('prices_comparison_difference') ): the_row(); ?>
								<div class="columns">
									<div class="col first">
										<div class="bar-wrap">
											<div class="bar" style="width: <?php the_sub_field('prices_comparison_difference_range'); ?>%; background-color: <?php the_sub_field('prices_comparison_difference_range_color'); ?>"></div>
										</div>
									</div>

									<div class="col">
										<?php if(get_sub_field('prices_comparison_difference_title')): ?>
											<p>
												<?php the_sub_field('prices_comparison_difference_title'); ?>
											</p>
										<?php endif; ?>

										<?php if(get_sub_field('prices_comparison_difference_price')): ?>
											<p class="price">
												<?php the_sub_field('prices_comparison_difference_price'); ?>
											</p>
										<?php endif; ?>
									</div>
								</div>
							<?php endwhile; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<?php get_template_part('loop-locations-single'); ?>
</div>

<?php get_footer(); ?>
