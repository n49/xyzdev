<?php /* Template Name: Customer Details */ get_header();
	$locations_array = array(
	                          3 => 'Toronto West',
	                          4 => 'Etobicoke',
	                          1 => 'Scarborough',
	                          2 => 'Mississauga',
	                          5 => 'Mobile Storage',
	                          6 => 'Toronto Midtown',
	                          7 => 'Toronto Downtown'
	                        );

	$locations_street_address_array = array(
	                          3 => '207 Weston Road, Toronto, Ontario M6N 4Z3, Canada',
	                          4 => '2256 Lake Shore Blvd W, Etobicoke, ON M8V 1A9, Canada',
	                          1 => '135 Beechgrove Drive, Scarborough, Ontario M1E 3Z3, Canada',
	                          2 => '2480 Stanfield Road, Mississauga, ON L4Y 1R6, Canada',
	                          5 => 'Mobile Storage',
	                          6 => '135 Beechgrove Drive, Scarborough, Ontario M1E 3Z3, Canada',
	                          7 => '459 Eastern Avenue, Toronto, ON M4M 1B7, Canada'
	                        );

	$locations_email = array(
	                          3 => 'weston@xyzstorage.com',
	                          4 => 'lakeshore@xyzstorage.com',
	                          1 => 'scarborough@xyzstorage.com',
	                          2 => 'mississauga@xyzstorage.com',
	                          5 => '',
	                          6 => 'laird@xyzstorage.com',
	                          7 => 'eastern@xyzstorage.com'
	                        );

	$location_name = $locations_array[intval($_COOKIE['unitLocation'])];
	$location_street_address = $locations_street_address_array[intval($_COOKIE['unitLocation'])];
	$location_email = $locations_email[intval($_COOKIE['unitLocation'])];
	 //echo "HERE'S THE LOCATION NAME" .$location_name;


?>


<div class="header-wrap">
	<div class="wrapper">
		<h1 class="title">
			<?php the_title(); ?>
		</h1>
	</div>
</div>

<div class="main-container spacing-3" style="background-image: url('<?php the_field('page_background_image'); ?>');">
	<div class="wrapper">
		<main role="main" class="fullwidth padding tabs active">
			<ul class="horizontal icons has-event slider1">
				<li class="first">
					<a class="normal icon inactive" href="#first">
						<p class="title-5">
							<?php if(get_field('customer_label_tab_1')): ?>
								<?php the_field('customer_label_tab_1'); ?>
							<?php else: ?>
								<?php _e('select a unit', 'html5blank'); ?>
							<?php endif; ?>
						</p>
					</a>
				</li>

				<li class="second">
					<span class="icon sep"></span>

					<a class="normal inactive" id="customerDetailsTab" href="#third">
						<p class="title-5">
							<?php if(get_field('customer_label_tab_2')): ?>
								<?php the_field('customer_label_tab_2'); ?>
							<?php else: ?>
								<?php _e('customer details', 'html5blank'); ?>
							<?php endif; ?>
						</p>
					</a>
					<a class="normal active" id="triggerTab3" href="#third" style="display:none">
						<p class="title-5">
							<?php if(get_field('customer_label_tab_3')): ?>
								<?php the_field('customer_label_tab_3'); ?>
							<?php else: ?>
								<?php _e('complete reservation', 'html5blank'); ?>
							<?php endif; ?>
						</p>
					</a>
				</li>

				<li class="third">
					<span class="icon sep"></span>

					<a class="normal inactive" href="#third">
						<p class="title-5">
							<?php if(get_field('customer_label_tab_3')): ?>
								<?php the_field('customer_label_tab_3'); ?>
							<?php else: ?>
								<?php _e('complete reservation', 'html5blank'); ?>
							<?php endif; ?>
						</p>
					</a>

				</li>
			</ul>
			<style>
			.fake-btn {
				  margin: 10px 0 60px;
				}

				.real-btn {
				  display: none;
				}
			</style>
			<div id="second" class="content-wrap overflow tab">
				<?php if(get_field('customer_content')): ?>
					<div class="float-left section white">
						<?php the_field('customer_content'); ?>
            			<input type="submit" value="continue to next step" class="wpcf7-form-control wpcf7-submit btn fake-btn">
						<?php if( have_rows('customer_features') ): ?>
							<div class="columns columns-3 services bottom">
								<?php while( have_rows('customer_features') ): the_row(); ?>
									<div class="col">
										<?php if( get_sub_field('customer_features_icon') ): ?>
											<?php $image = get_sub_field('customer_features_icon'); ?>

											<div class="img-wrap">
												<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
											</div>
										<?php endif; ?>

										<div class="content">
											<p class="title-5">
												<?php the_sub_field('customer_features_title'); ?>
											</p>

											<p>
												<?php the_sub_field('customer_features_description'); ?>
											</p>
										</div>
									</div>
								<?php endwhile; ?>
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<div class="float-right grey">
					<?php get_template_part('loop-units-single'); ?>
				</div>
			</div>

			<div id="third" class="content-wrap overflow tab">
				<?php if(get_field('customer_content_2')): ?>
					<div class="float-left section white last">
						<?php the_field('customer_content_2'); ?>

						<?php if( have_rows('customer_features') ): ?>
							<div class="columns columns-3 services bottom">
								<?php while( have_rows('customer_features') ): the_row(); ?>
									<div class="col">
										<?php if( get_sub_field('customer_features_icon') ): ?>
											<?php $image = get_sub_field('customer_features_icon'); ?>

											<div class="img-wrap">
												<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
											</div>
										<?php endif; ?>

										<p class="title-5">
											<?php the_sub_field('customer_features_title'); ?>
										</p>

										<p>
											<?php the_sub_field('customer_features_description'); ?>
										</p>
									</div>
								<?php endwhile; ?>
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<div class="float-right grey">
					<?php get_template_part('loop-units-single'); ?>
				</div>
			</div>
		</main>
	</div>
</div>

<?php get_footer(); ?>
