<?php /* Template Name: Storage 2 */ get_header(); ?>

<div class="header-wrap">
	<div class="wrapper">
		<h1 class="title">
			<?php the_title(); ?>
		</h1>
	</div>
</div>


<script type="text/javascript">
  function setCookies2(dimensions, rent, length, width, height, price){
    document.cookie = "unitLocation=5;path=/customer-details";
    var discountNew = parseInt(document.getElementById("savings-new").innerHTML);
    var quantity = parseInt(document.getElementById("quant").innerHTML);
    document.cookie = 'unitType=198; path=/customer-details';
    var url = "<?php bloginfo('url'); ?>/customer-details/";
    document.cookie = 'unitDimensions='+dimensions+'; path=/customer-details';
    document.cookie = 'length='+length+'; path=/customer-details';
    document.cookie = 'width='+width+'; path=/customer-details';
    document.cookie = 'height='+height+'; path=/customer-details';
    document.cookie = 'unitRent='+(price-discountNew/quantity)+'; path=/customer-details';
    document.cookie = 'unitPrice='+(price-discountNew/quantity)+'; path=/customer-details';
    window.location = url;
  }
</script>

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

	<?php if( have_rows('storage_how_features') ): ?>
		<div class="services-wrap spacing-5" style="background-image: url('<?php the_field('storage_how_background'); ?>');">
			<div class="wrapper">
				<?php if(get_field('storage_how_subtitle')): ?>
					<p class="title-5 icon icon-arrow">
						<?php the_field('storage_how_subtitle'); ?>
					</p>
				<?php endif; ?>

				<?php if(get_field('storage_how_title')): ?>
					<h2 class="title full">
						<?php the_field('storage_how_title'); ?>
					</h2>
				<?php endif; ?>

				<div class="columns columns-4 services">
					<?php while( have_rows('storage_how_features') ): the_row(); ?>
						<div class="col">
							<?php if( get_sub_field('storage_how_features_icon') ): ?>
								<?php $image = get_sub_field('storage_how_features_icon'); ?>

								<div class="img-wrap large no-radius">
									<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
								</div>
							<?php endif; ?>

							<div class="content">
								<?php if(get_sub_field('storage_how_features_title')): ?>
									<h3 class="title-4">
										<?php the_sub_field('storage_how_features_title'); ?>
									</h3>
								<?php endif; ?>

								<?php if(get_sub_field('storage_how_features_description')): ?>
									<p>
										<?php the_sub_field('storage_how_features_description'); ?>
									</p>
								<?php endif; ?>
							</div>
						</div>
					<?php endwhile; ?>
				</div>
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
				<div class="float-right content normal">
					<?php the_field('storage_gallery_content'); ?>

					<?php if( have_rows('storage_gallery_units') ): ?>
						<div class="columns buttons">
							<?php while( have_rows('storage_gallery_units') ): the_row(); ?>
								<div class="col">
									<h4 class="title">
										<?php the_sub_field('storage_gallery_units_title'); ?>
									</h4>

									<p>
										<?php the_sub_field('storage_gallery_units_description'); ?>
									</p>

									<?php if( have_rows('storage_gallery_units_buttons') ): ?>
						            	<div class="btn-wrap">
						            		<?php while( have_rows('storage_gallery_units_buttons') ): the_row(); ?>
						                        <a class="btn" href="<?php the_sub_field('storage_gallery_units_buttons_url'); ?>">
						                            <?php if(get_sub_field('storage_gallery_units_buttons_icon')): ?>
						                                <?php $icon = get_sub_field('storage_gallery_units_buttons_icon'); ?>

						                                <img src="<?php echo $icon['url']; ?>" alt="<?php echo $icon['alt']; ?>" />
						                            <?php endif; ?>

						                            <?php the_sub_field('storage_gallery_units_buttons_title'); ?>
						                        </a>
						            		<?php endwhile; ?>
						            	</div>
						            <?php endif; ?>
								</div>
							<?php endwhile; ?>
						</div>
					<?php endif; ?>

					<?php if(get_field('storage_gallery_units_related')): ?>
						<div class="related">
							<?php
								$post = get_field('storage_gallery_units_related');
								setup_postdata($post);
							?>

							<?php if(get_field('unit_amount')): ?>
								<?php
									$price = get_field('unit_amount');
									$price_edit = number_format($price, 2, '.', '');
								?>

								<div class="counter" data-save="<?php the_field('unit_saving'); ?>" data-price="<?php the_field('unit_amount'); ?>">
									<p class="title-5">
										<?php _e('number of units you need', 'html5blank'); ?>
									</p>

									<span class="icon sign dec"></span>
									<span class="num">1</span>
									<span class="icon sign inc"></span>
								</div>

								<p class="title">
									<?php _e('monthly price', 'html5blank'); ?>
								</p>

								<h3 class="title-4 price">
									$<span id="editedprice"><?php echo $price_edit; ?></span>/mo
								</h3>
							<?php endif; ?>

							<?php if(get_field('unit_saving')): ?>
								<p class="savings">
									<?php _e('Savings of', 'html5blank'); ?> $<span class="save" id="savings-new">0</span> <?php _e('for', 'html5blank'); ?> <span class="num" id="quant">1</span> <?php _e('units', 'html5blank'); ?>
								</p>
							<?php endif; ?>

							<a class="btn btn-reserve mobile"
							   data-unit="19"
							   data-location="5"
							   data-amount="1"
							   onclick="setCookies2(`5'd x 8'w x 7'h` , 135.00 , 8 , 5 , 7 , 135.00)">
								<?php _e('reserve a unit', 'html5blank'); ?>
						  	</a>

							<p class="delivery">
								<?php _e('need a re-delivery?', 'html5blank'); ?> <a href="https://www.xyzstorage.com/contact-us/" target="_self"><?php _e(' contact us', 'html5blank'); ?></a>
							</p>

							<?php wp_reset_postdata(); ?>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<?php if( have_rows('storage_features') ): ?>
		<div class="gallery-wrap spacing white second" style="background-image: url('<?php the_field('storage_features_background'); ?>');">
			<div class="wrapper overflow">
				<div class="float-left">
					<p class="title-5 icon icon-arrow">
						<?php _e('overview', 'html5blank'); ?>
					</p>

					<h2 class="title">
						<?php _e('features', 'html5blank'); ?>
					</h2>

					<div class="columns columns-4 services">
						<?php while( have_rows('storage_features') ): the_row(); ?>
							<div class="col">
								<?php if( get_sub_field('storage_features_icon') ): ?>
									<?php $image = get_sub_field('storage_features_icon'); ?>

									<div class="img-wrap no-radius">
										<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
									</div>
								<?php endif; ?>

								<p class="title-5">
									<?php the_sub_field('storage_features_title'); ?>
								</p>
							</div>
						<?php endwhile; ?>
					</div>
				</div>

				<?php if(get_field('storage_features_content')): ?>
					<div class="float-right border">
						<?php the_field('storage_features_content'); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>

	<?php get_template_part('module-cta'); ?>

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

	<?php if(get_field('storage_about_location')): ?>
		<div class="location-section map-wrap" data-zoom="<?php the_field('storage_about_map_zoom'); ?>" style="height: <?php the_field('storage_about_map_height'); ?>px">
			<?php
				$location = get_field('storage_about_location');
				$address = $location['address'];
			?>

			<?php if(get_field('storage_about_service_area')): ?>
				<div class="content">
					<div class="wrapper">
						<p class="title-5 icon icon-arrow">
							<?php _e('service areas', 'html5blank'); ?>
						</p>

						<?php the_field('storage_about_service_area'); ?>
					</div>
				</div>
			<?php endif; ?>

			<div class="acf-map">
				<div class="marker" data-lat="<?php echo $location['lat']; ?>" data-lng="<?php echo $location['lng']; ?>"></div>
			</div>
		</div>
	<?php endif; ?>

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

								<div class="img-wrap">
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

	<?php get_template_part('loop-locations-single'); ?>
</div>

<?php get_footer(); ?>
