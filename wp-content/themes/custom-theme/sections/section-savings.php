<div class="reviews-section wide spacing grey-section">
	<div class="wrapper">
		<div class="content-wrap normal no-bgr overflow">
			<div class="float-left">
				<?php if(get_sub_field('content')): ?>
					<div class="max-width">
						<?php the_sub_field('content'); ?>
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

				<?php if(get_sub_field('savings_image')): ?>
					<?php $image = get_sub_field('savings_image'); ?>

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
					<?php $class = ''; ?>

					<?php if(get_sub_field('savings_features_content')): ?>
						<?php $class = 'normal-tooltip'; ?>
					<?php endif; ?>

					<div class="col has-img">
						<?php if(get_sub_field('savings_features_icon')): ?>
							<?php $image = get_sub_field('savings_features_icon'); ?>

							<div class="img-wrap normal">
								<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
							</div>
						<?php endif; ?>

						<div class="content relative <?php echo $class; ?>">
							<?php if(get_sub_field('savings_features_title')): ?>
								<p>
									<?php the_sub_field('savings_features_title'); ?>
								</p>
							<?php endif; ?>

							<?php if(get_sub_field('savings_features_content')): ?>
								<div class="tooltip">
									<?php the_sub_field('savings_features_content'); ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
				<?php endwhile; ?>
			</div>
		</div>
	</div>
<?php endif; ?>