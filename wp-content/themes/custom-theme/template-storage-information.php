<?php /* Template Name: Storage Information */ get_header(); ?>

	<div class="header-wrap">
		<div class="wrapper">
			<h1 class="title">
				<?php the_title(); ?>
			</h1>
		</div>
	</div>

	<div class="reviews-section grey has-bgr-small narrow spacing">
		<div class="wrapper overflow" style="background-image: url('<?php the_field('reviews_background'); ?>');">
			<div class="title-wrap float-left">
				<?php if(get_field('reviews_content')): ?>
					<?php the_field('reviews_content'); ?>
				<?php endif; ?>
			</div>

			<div class="content float-right">
				<?php get_template_part('loop-reviews-2'); ?>
			</div>
		</div>
	</div>

	<div class="reviews-section wide spacing grey-section">
		<div class="wrapper">
			<div class="content-wrap normal no-bgr overflow">
				<div class="float-left">
					<?php if(get_field('savings_content')): ?>
						<div class="max-width">
							<?php the_field('savings_content'); ?>
						</div>
					<?php endif; ?>

					<?php if(have_rows('savings_rates')): ?>
						<h3 class="top-m title-4">
							<?php _e('monthly vs 4-week rate', 'html5blank'); ?>
						</h3>

						<div class="columns columns-3 table-simple first">
							<div class="col col-1"></div>

							<div class="col col-2">
								<strong>
									<?php _e('monthly rate', 'html5blank'); ?>
								</strong>
							</div>

							<div class="col col-3">
								<strong>
									<?php _e('4-week rate', 'html5blank'); ?>
								</strong>
							</div>
						</div>

						<?php while(have_rows('savings_rates')): the_row(); ?>
							<div class="columns columns-3 table-simple">
								<div class="col col-1">
									<strong>
										<?php the_sub_field('savings_rates_title'); ?>
									</strong>
								</div>

								<div class="col col-2">
									<?php the_sub_field('savings_rates_monthly_rate'); ?>
								</div>

								<div class="col col-3">
									<?php the_sub_field('savings_rates_week_rate'); ?>
								</div>
							</div>
						<?php endwhile; ?>
					<?php endif; ?>
				</div>

				<div class="float-right">
					<div class="calc-wrap">
						<h4>
							<?php _e('monthly saving calculator', 'html5blank'); ?>
						</h4>

						<form action="" method="post">
							<p>
								<?php _e('type in the 4-week rate', 'html5blank'); ?>
							</p>

							<div class="columns columns-2">
								<div class="col">
									<label for="price" class="hidden"><?php _e('Enter amount', 'html5blank'); ?></label>

									<input id="price" type="text" name="price" value="" class="price" placeholder="Enter amount">
								</div>

								<div class="col">
									<button class="btn btn-calculate" type="button" name="button" >
										<?php _e('calculate savings', 'html5blank'); ?>
									</button>
								</div>
							</div>

							<p class="total">
								<?php _e('your real monthly rate is ', 'html5blank'); ?><strong></strong>
							</p>
						</form>
					</div>

					<div class="calc-wrap calc-result">
						<div class="top">
							<p>
								<?php _e('this is your savings with XYZ Storage', 'html5blank'); ?><strong></strong>
							</p>

							<div class="columns columns-2">
								<div class="col">
									<h5>
										<?php _e('monthly savings', 'html5blank'); ?>
									</h5>

									<p class="monthly savings"></p>
								</div>

								<div class="col">
									<h5>
										<?php _e('yearly savings', 'html5blank'); ?>
									</h5>

									<p class="yearly savings"></p>
								</div>
							</div>
						</div>

						<div class="bottom">
							<p>
								<?php _e('start saving today', 'html5blank'); ?>
							</p>

							<a class="btn btn-white" href="<?php bloginfo('url'); ?>/storage-locations/">
								<?php _e('book now', 'html5blank'); ?>
							</a>
						</div>
					</div>

					<?php if(get_field('savings_image')): ?>
						<?php $image = get_field('savings_image'); ?>

						<div class="img-wrap center normal">
							<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

	<?php if(have_rows('savings_features')): ?>
		<div class="reviews-section wide spacing grey-section light text-white arrow-sep">
			<div class="wrapper narrow-2">
				<div class="columns columns-2">
					<?php while(have_rows('savings_features')): the_row(); ?>
						<div class="col has-img">
							<?php if(get_sub_field('savings_features_icon')): ?>
								<?php $image = get_sub_field('savings_features_icon'); ?>

								<div class="img-wrap normal">
									<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
								</div>
							<?php endif; ?>

							<div class="content">
								<?php if(get_sub_field('savings_features_title')): ?>
									<strong>
										<?php the_sub_field('savings_features_title'); ?>
									</strong>
								<?php endif; ?>

								<?php if(get_sub_field('savings_features_content')): ?>
									<p>
										<?php the_sub_field('savings_features_content'); ?>
									</p>
								<?php endif; ?>
							</div>
						</div>
					<?php endwhile; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<?php if(have_rows('reasons')): ?>
		<div class="services-wrap spacing">
			<div class="wrapper">
				<?php if(get_field('reasons_title')): ?>
					<h2>
						<?php the_field('reasons_title'); ?>
					</h2>
				<?php endif; ?>

				<div class="columns columns-4 services flex flex-normal top-m">
					<?php while(have_rows('reasons')): the_row(); ?>
						<?php if(get_sub_field('reasons_link')): ?>
							<?php
								$link = get_sub_field('reasons_link');
								$link_url = $link['url'];
								$link_title = $link['title'];
								$link_target = $link['target'] ? $link['target'] : '_self';
							?>
						<?php endif; ?>

						<div class="col">
							<div class="content-wrap text-center">
								<?php if( get_sub_field('reasons_icon') ): ?>
									<?php $image = get_sub_field('reasons_icon'); ?>

									<?php if($link): ?>
										<a class="img-wrap disable no-radius round" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
									<?php else: ?>
										<div class="img-wrap disable no-radius round">
									<?php endif; ?>

										<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />

										<?php if(get_sub_field('reasons_title')): ?>
											<h3 class="title title-5">
												<?php the_sub_field('reasons_title'); ?>
											</h3>
										<?php endif; ?>

									<?php if($link): ?>
										</a>
									<?php else: ?>
										</div>
									<?php endif; ?>
								<?php endif; ?>

								<?php if(get_sub_field('reasons_link')): ?>
									<div class="content round">
										<?php
											$link = get_sub_field('reasons_link');
											$link_url = $link['url'];
											$link_title = $link['title'];
											$link_target = $link['target'] ? $link['target'] : '_self';
										?>

										<a href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
											<?php echo esc_html($link_title); ?>
										</a>
									</div>
								<?php endif; ?>
							</div>
						</div>
					<?php endwhile; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<?php if(get_field('cta_title')): ?>
		<div class="cta-wrap simple" style="background-image: url(<?php the_field('cta_background_image'); ?>); background-color: <?php the_field('cta_background_color'); ?>">
			<div class="wrapper text-center">
				<h2>
					<?php the_field('cta_title'); ?>
				</h2>

				<?php if(get_field('cta_link')): ?>
					<?php
						$link = get_field('cta_link');
						$link_url = $link['url'];
						$link_title = $link['title'];
						$link_target = $link['target'] ? $link['target'] : '_self';
					?>

					<a class="btn btn-white" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
						<?php echo esc_html($link_title); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>

	<?php get_template_part('loop-locations-single-2'); ?>

<?php get_footer(); ?>
