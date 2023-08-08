<?php get_header(); ?>

<div class="header-wrap">
	<div class="wrapper">
		<h1 class="title">
			<?php the_title(); ?>
		</h1>
	</div>
</div>

<script type='text/javascript'>
	var locationName;
	function setLocationTag(loc,url)
	{
		locationName = loc;
		window.location = url
	}
</script>

<div class="main-container overflow">
	<div class="wrapper">
		<main role="main" class="padding">
			<?php get_template_part('module-rental'); ?>

			<?php if (have_posts()): while (have_posts()) : the_post(); ?>
				<?php $class = (get_field('service_logo')) ? 'float-left' : ''; ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class('overflow'); ?>>
					<div class="content <?php echo $class; ?>">
						<?php the_content(); ?>
					</div>

					<?php if(get_field('service_logo')): ?>
						<?php $image = get_field('service_logo'); ?>

						<div class="logo-wrap float-right">
							<?php if(get_field('service_logo_link')): ?>
								<a href="<?php the_field('service_logo_link'); ?>" target="_blank" title="<?php echo $image['alt']; ?>">
							<?php endif; ?>

								<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />

							<?php if(get_field('service_logo_link')): ?>
								</a>
							<?php endif; ?>

							<?php if(get_field('service_logo_link')): ?>
								<a class="icon icon-link" href="<?php the_field('service_logo_link'); ?>" target="_blank"><?php the_field('service_logo_label'); ?></a>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</article>
			<?php endwhile; ?>

			<?php endif; ?>

			<?php if( have_rows('service_process') ): ?>
				<?php $i = 1; ?>

				<?php if(get_field('service_process_style') === 'custom') {
					$icon = 'icon arrow big';
				} else {
					$icon = '';
				} ?>

				<div class="section">
					<h2 class="title">
						<?php _e('how it works?', 'html5blank'); ?>
					</h2>

					<div class="columns columns-3 process <?php the_field('service_process_style'); ?>">
						<?php while( have_rows('service_process') ): the_row(); ?>
							<div class="col">
								<?php if( get_sub_field('service_process_image') ): ?>
									<?php $image = get_sub_field('service_process_image'); ?>

									<div class="img-wrap">
										<img src="<?php echo $image['sizes']['small']; ?>" alt="<?php echo $image['alt']; ?>" />
									</div>
								<?php endif; ?>

								<h3 class="title-4 <?php echo $icon; ?>">
									<span class="num">
										<?php echo $i; $i++;?>
									</span>

									<span>
										<?php the_sub_field('service_process_title'); ?>
									</span>
								</h3>

								<div class="content">
									<?php the_sub_field('service_process_content'); ?>
								</div>
							</div>
						<?php endwhile; ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if( get_field('service_locations') ): ?>
				<?php $posts = get_field('service_locations'); ?>

				<div class="section">
					<?php if(get_field('service_locations_content')): ?>
						<?php the_field('service_locations_content'); ?>
					<?php endif; ?>

					<div class="columns columns-3 locations flex flex-normal-2 white products img-inside has-button margin">
						<?php foreach($posts as $post): // variable must be called $post (IMPORTANT) ?>
							<?php setup_postdata($post); ?>

							<article id="post-<?php the_ID(); ?>" <?php post_class('col'); ?>>
								<?php if( get_field('location_image') ): ?>
									<?php $image = get_field('location_image'); ?>

									<a class="img-wrap" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
										<img src="<?php echo $image['sizes']['small-5']; ?>" alt="<?php echo $image['alt']; ?>" />
									</a>
								<?php endif; ?>

								<div class="content">
									<?php if(get_field('location_new') === 'yes'): ?>
										<span class="new">
											<?php the_field('location_new_label'); ?>
										</span>
									<?php endif; ?>

									<a class="no-hover" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
										<h3 class="title title-4">
											<?php the_title(); ?>
										</h3>
									</a>

									<?php if(get_field('location_address')): ?>
										<p class="location">
											<?php if(get_field('location_custom_address_2')): ?>
												<?php the_field('location_custom_address_2'); ?>
											<?php else: ?>
												<?php echo $address; ?>
											<?php endif; ?>
										</p>
									<?php endif; ?>

									<?php if(get_field('location_custom_address')): ?>
										<p class="icon location-2">
											<?php the_field('location_custom_address'); ?>
										</p>
									<?php endif; ?>
								</div>

								<div class="bottom">
									<div class="btn-wrap">
										<?php if(get_field('location_custom_reserve_link')): ?>
											<?php $url = get_field('location_custom_reserve_link'); ?>
										<?php else: ?>
											<?php $url = get_permalink() . 'book-a-unit'; ?>
										<?php endif; ?>

										<a class="btn" href="<?php echo $url; ?>" title="<?php the_title(); ?>">
											<?php _e('reserve a unit', 'html5blank'); ?>
										</a>
									</div>

									<a class="view-location-details" style="cursor : pointer" title="<?php the_title(); ?>" onclick='setLocationTag("<?php the_title(); ?>","<?php the_permalink(); ?>")'>
										<?php _e('view location details', 'html5blank'); ?>
									</a>
								</div>
							</article>
						<?php endforeach; ?>

						<?php wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly ?>
					</div>

					<?php if(get_field('service_locations_notice')): ?>
						<?php the_field('service_locations_notice'); ?>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if( have_rows('service_prices') ): ?>
				<div class="section">
					<h2 class="title">
						<?php _e('pricing', 'html5blank'); ?>
					</h2>

					<?php if(get_field('service_prices_description')): ?>
						<div class="content">
							<?php the_field('service_prices_description'); ?>
						</div>
					<?php endif; ?>

					<div class="columns columns-2 flex prices">
						<?php while( have_rows('service_prices') ): the_row(); ?>
							<div class="col">
								<h3 class="title">
									<?php the_sub_field('service_prices_title'); ?>
								</h3>

								<?php if( have_rows('service_prices_price') ): ?>
									<div class="cost">
										<?php while( have_rows('service_prices_price') ): the_row(); ?>
											<p>
												<span class="num">
													<?php the_sub_field('service_prices_price_cost'); ?>
												</span>

												<span>
													<?php the_sub_field('service_prices_price_description'); ?>
												</span>
											</p>
										<?php endwhile; ?>
									</div>
								<?php endif; ?>
							</div>
						<?php endwhile; ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if( have_rows('service_products') ): ?>
				<div class="section">
					<div class="columns columns-3 flex white products">
						<?php while( have_rows('service_products') ): the_row(); ?>
							<div class="col">
								<div class="content">
									<?php if( get_sub_field('service_products_image') ): ?>
										<?php $image = get_sub_field('service_products_image'); ?>

										<div class="img-wrap transparent color-dark">
											<img src="<?php echo $image['sizes']['small-2']; ?>" alt="<?php echo $image['alt']; ?>" />
										</div>
									<?php endif; ?>

									<h3 class="title-4">
										<?php the_sub_field('service_products_title'); ?>
									</h3>

									<?php if(get_sub_field('service_products_description')): ?>
										<p>
											<?php the_sub_field('service_products_description'); ?>
										</p>
									<?php endif; ?>

									<?php if(get_sub_field('service_products_link')): ?>
										<a class="icon icon-link" href="<?php the_sub_field('service_products_link'); ?>" target="_blank"><?php _e('browse products', 'html5blank'); ?></a>
									<?php endif; ?>
								</div>
							</div>
						<?php endwhile; ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if(get_field('service_left_content') || get_field('service_right_content')): ?>
				<div class="section overflow">
					<div class="columns columns-2 additional">
						<?php if(get_field('service_left_content')): ?>
							<div class="col">
								<?php the_field('service_left_content'); ?>
							</div>
						<?php endif; ?>

						<?php if(get_field('service_right_content')): ?>
							<div class="col">
								<?php the_field('service_right_content'); ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if(get_field('service_form_content')): ?>
				<div class="section white">
					<?php the_field('service_form_content'); ?>

					<?php if( have_rows('service_form_features') ): ?>
						<div class="columns columns-3 services bottom">
							<?php while( have_rows('service_form_features') ): the_row(); ?>
								<div class="col">
									<?php if( get_sub_field('service_form_features_icon') ): ?>
										<?php $image = get_sub_field('service_form_features_icon'); ?>

										<div class="img-wrap">
											<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
										</div>
									<?php endif; ?>

									<div class="content">
										<p class="title-5">
											<?php the_sub_field('service_form_features_title'); ?>
										</p>

										<p>
											<?php the_sub_field('service_form_features_description'); ?>
										</p>
									</div>
								</div>
							<?php endwhile; ?>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</main>

		<div class="sidebar">
			<?php get_template_part('loop-services-sidebar'); ?>
		</div>

		<div class="clear"></div>
	</div>
</div>

<?php get_template_part('loop-services-single'); ?>

<?php get_template_part('module-cta'); ?>

<?php get_footer(); ?>
