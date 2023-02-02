<?php /* Template Name: Service Area */ get_header(); ?>

<?php $amp = isset($_GET["amp"]); ?>

<div class="header-wrap has-slider no-title">
	<?php if( get_field('page_slider') ): ?>
		<?php
			$images = get_field('page_slider');
			$count = count($images);
		?>

		<?php if($amp): ?>
		      <div class="cta-wrap amp-wrap-cta" >
        		<div class="overlay cover amp-bg-image"></div>
		        <div class="wrapper">
		          <p class="title-1">
		              life is big. <br>
		              make space.
		          </p>
		          <p class="title-4">
		            find a unit by:
		          </p>

		        	<div class="btn-wrap">
		            <a class="btn btn-yellow icon location location-homepage"
		                href="/storage-locations/?amp">
		              location
		            </a>
		            <a class="btn btn-grey icon size"
		                href="/storage-sizes/?amp">
		              size
		            </a>
		            <a class="btn btn-grey icon storage"
		                href="/mobile-storage/?amp">
		              mobile storage
		            </a>
		        	</div>
		        </div>
		    </div>
		<?php else: ?>
			<div class="gallery wp-slick-slider slide-count-<?php echo $count; ?>">
				<?php foreach($images as $image): ?>
					<div class="slide">
						<div class="img no-lazy" style="background-image: url(<?php echo $image['sizes']['slider']; ?>); opacity: <?php the_field('page_slider_opacity'); ?>;"></div>

						<?php if(get_field('media_title', $image['ID'])): ?>
							<div class="caption cta-wrap">
								<div class="wrapper">
									<h1 class="title title-1">
										<?php the_field('media_title', $image['ID']); ?>
									</h1>

									<?php if(get_field('media_button_title', $image['ID'])): ?>
										<p class="title-4">
											<?php the_field('media_button_title', $image['ID']); ?>
										</p>
									<?php endif; ?>

									<?php if( have_rows('media_buttons', $image['ID']) ): ?>
										<div class="btn-wrap">
											<?php while( have_rows('media_buttons', $image['ID']) ): the_row(); ?>
												<a class="btn btn-<?php the_sub_field('media_buttons_color'); ?> icon <?php the_sub_field('media_buttons_icon'); ?>" href="<?php the_sub_field('media_buttons_url'); ?>">
													<?php the_sub_field('media_buttons_title'); ?>
												</a>
											<?php endwhile; ?>
										</div>
									<?php endif; ?>
								</div>
							</div>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	<?php endif; ?>

	<?php if( have_rows('notices') ): ?>
		<div class="wrapper has-notices">
			<div class="notices-wrap">
				<div class="carousel-notices">
					<?php while( have_rows('notices') ): the_row(); ?>
						<?php
							if(get_sub_field('notices_background_style') === 'image'){
								$background = 'url(' . get_sub_field('notices_background_image') . ')';
							} else {
								$background = 'linear-gradient(' . get_sub_field("notices_background_angle") . 'deg, ' . get_sub_field("notices_background_1") . ' 0%, ' . get_sub_field("notices_background_2") . ' 100%)';
							}
						?>

						<div class="slide">
							<div class="notice" style="background-image: <?php echo $background; ?>;">
								<?php the_sub_field('notices_content'); ?>
							</div>
						</div>
					<?php endwhile; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>

<div class="main-container no-padding">
	<?php if( have_rows('home_features') && !$amp): ?>
		<?php $count = count(get_field('home_features')); ?>

		<div class="features-section">
			<div class="wrapper">
				<div class="content float-left">
					<?php if(get_field('home_features_content')): ?>
						<?php the_field('home_features_content'); ?>
					<?php endif; ?>
				</div>

				<div class="float-right features">
					<div class="columns columns-<?php echo $count; ?> services no-margin">
						<?php while( have_rows('home_features') ): the_row(); ?>
							<div class="col">
								<div class="content-wrap text-center">
									<?php if( get_sub_field('home_features_icon') ): ?>
										<?php $image = get_sub_field('home_features_icon'); ?>

										<div class="img-wrap disable no-radius round">
											<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />

											<?php if(get_sub_field('home_features_title')): ?>
												<h3 class="title title-5 fullwidth">
													<?php the_sub_field('home_features_title'); ?>
												</h3>
											<?php endif; ?>
										</div>
									<?php endif; ?>
								</div>
							</div>
						<?php endwhile; ?>
					</div>

					<?php if(get_field('home_features_link')): ?>
						<?php
							$link = get_field('home_features_link');
							$link_url = $link['url'];
							$link_title = $link['title'];
							$link_target = $link['target'] ? $link['target'] : '_self';
						?>

						<a class="btn btn-white" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
							<?php echo esc_html($link_title); ?>
						</a>
					<?php endif; ?>
				</div>

				<div class="clear"></div>
			</div>
		</div>
	<?php endif; ?>

	<?php if(get_field('home_solutions_content')): ?>
		<div class="reviews-section narrow spacing" style="background-image: url('<?php the_field('home_solutions_background'); ?>');">
			<div class="wrapper overflow">
				<div class="title-wrap float-left">
					<p class="title-5 icon icon-arrow">
						<?php _e('our solutions', 'html5blank'); ?>
					</p>

					<?php the_field('home_solutions_content'); ?>
				</div>

				<div class="content float-right">
					<?php if( have_rows('home_solutions_related') ): ?>
						<div class="columns columns-2 services flex flex-normal">
							<?php while( have_rows('home_solutions_related') ): the_row(); ?>
								<div class="col <?php if($amp) echo "solutions-amp-margin"; ?>">
									<div class="content-wrap">
										<?php if( get_sub_field('home_solutions_related_icon') ): ?>
											<?php $image = get_sub_field('home_solutions_related_icon'); ?>

											<a href="<?php the_sub_field('home_solutions_related_link'); ?>" class="img-wrap large disable" title="<?php echo $image['alt']; ?>">
												<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
											</a>
										<?php endif; ?>

										<div class="content">
											<?php if(get_sub_field('home_solutions_related_title')): ?>
												<h3 class="title">
													<?php the_sub_field('home_solutions_related_title'); ?>
												</h3>
											<?php endif; ?>

											<?php if(get_sub_field('home_solutions_related_description') && !$amp): ?>
												<p>
													<?php the_sub_field('home_solutions_related_description'); ?>
												</p>
											<?php endif; ?>

											<?php if(get_sub_field('home_solutions_related_link')): ?>
												<a class="btn <?php the_sub_field('home_solutions_related_title'); ?>" href="<?php the_sub_field('home_solutions_related_link'); ?>" title="<?php the_title(); ?>">
													<?php _e('learn more', 'html5blank'); ?>
												</a>
											<?php endif; ?>
										</div>
									</div>
								</div>
							<?php endwhile; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<?php if(!$amp): ?>
		<div class="main-container no-padding">
			<div class="wrapper">
				<div class="gallery-wrap overflow">
					<?php if( get_field('storage_gallery') ): ?>
						<div class="float-left">
							<?php
								$images = get_field('storage_gallery');
								$count = count($images);
								$num = 0;
							?>

							<div class="has-lightbox-simple slide-count-<?php echo $count; ?>">
								<?php foreach($images as $image): ?>
									<?php
										$caption = '';
										$caption .= '<h3>' . $image['title'] . '</h3>';
										$caption .= '<p>' . $image['description'] . '</p>';

										$caption .= '<div>';
										$caption .= '<h3>'. get_the_title() . '</h3>';
										$caption .= esc_html('<a class="btn btn-yellow" href="' . get_the_permalink() . 'book-a-unit/">reserve a unit</a>');
										$caption .= '</div>';

										$num++;
									?>

									<?php if($num == 1) : ?>
										<div class="img-wrap">
											<a href="<?php echo $image['url']; ?>" data-sub-html="<?php echo $caption; ?>" title="<?php echo $image['alt']; ?>">
												<img src="<?php echo $image['sizes']['medium-2']; ?>" alt="<?php echo $image['alt']; ?>" />

												<span class="btn btn-grey"><?php _e('view all photos', 'html5blank'); ?></span>
											</a>
										</div>
									<?php else: ?>
										<a href="<?php echo $image['url']; ?>" data-sub-html="<?php echo $caption; ?>" title="<?php echo $image['alt']; ?>"></a>
									<?php endif; ?>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endif; ?>

					<div class="float-right">
						<?php get_template_part('loop-units-custom'); ?>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<?php if(get_field('home_about_left_content') || get_field('home_about_right_content')): ?>
		<div class="gallery-wrap white spacing content" style="background-image: url('<?php the_field('home_about_background'); ?>');">
			<div class="wrapper">
				<div class="overflow">
					<?php if(get_field('home_about_left_content')): ?>
						<div class="float-left">
							<?php the_field('home_about_left_content'); ?>
						</div>
					<?php endif; ?>

					<?php if(get_field('home_about_right_content')): ?>
						<div class="float-right">
							<?php the_field('home_about_right_content'); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<?php get_template_part('module-faq'); ?>

	<?php get_template_part('module-logos'); ?>
</div>

<?php get_footer(); ?>
