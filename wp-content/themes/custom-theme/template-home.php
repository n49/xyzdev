<?php /* Template Name: Home */ get_header(); ?>
<?php $amp = isset($_GET["amp"]); ?>
<div class="header-wrap has-slider no-title">
	<?php if( get_field('page_slider') ): ?>
		<?php
			$images = get_field('page_slider');
			$count = count($images);
		?>

		<?php if($amp): ?>
		      <div class="cta-wrap amp-wrap-cta" >
        		<div class="overlay cover amp-bg-image no-lazy"></div>
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
									<p class="title title-1">
										<?php the_field('media_title', $image['ID']); ?>
									</p>

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
								<?php

									$tagline = "new year sale";
									if(time() > 1646110800){
										$tagline = "March Madness";
									}
									echo str_replace(
										"__TAG__",
										$tagline,
										get_sub_field('notices_content')
									);

								?>
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
		<div class="features-section">
			<div class="wrapper">
				<div class="content float-left">
					<?php if(get_field('home_features_content')): ?>
						<?php the_field('home_features_content'); ?>
					<?php endif; ?>
				</div>

				<div class="float-right features">
					<div class="columns columns-3 services">
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

	<?php get_template_part('loop-locations-single-2'); ?>

	<?php if(get_field('home_storage_related') && !$amp): ?>
		<?php $terms = get_field('home_storage_related'); ?>

		<div class="solutions-section grey spacing" style="background-image: url('<?php the_field('home_storage_background'); ?>');">
			<div class="wrapper">
				<?php if(get_field('home_storage_subtitle')): ?>
					<p class="title-5 icon icon-arrow white">
						<?php the_field('home_storage_subtitle'); ?>
					</p>
				<?php endif; ?>

				<?php if(get_field('home_storage_title')): ?>
					<h2 class="title">
						<?php the_field('home_storage_title'); ?>
					</h2>
				<?php endif; ?>

				<div class="columns columns-3 flex flex-normal keep-flex features">
					<?php foreach($terms as $term): ?>
						<div class="col margin">
							<?php if( get_field('unit_category_thumbnail', $term) ): ?>
								<?php $image = get_field('unit_category_thumbnail', $term); ?>

								<a class="img-wrap transparent" href="<?php bloginfo('url'); ?>/storage-sizes#<?php echo $term->slug; ?>" title="<?php echo $image['alt']; ?>">
									<img height="373px" width="231px" src="<?php echo $image['sizes']['medium']; ?>" alt="<?php echo $image['alt']; ?>" />
								</a>
							<?php endif; ?>

							<div class="content">
								<h3 class="title title-4 margin">
									<?php echo $term->name; ?>
								</h3>

								<?php if(get_field('unit_category_size', $term)): ?>
									<p>
										<?php the_field('unit_category_size', $term); ?>
									</p>
								<?php endif; ?>

								<p>
									<?php echo $term->description; ?>
								</p>

								<a class="btn btn-white-2" href="<?php bloginfo('url'); ?>/storage-sizes#<?php echo $term->slug; ?>"><?php _e('view', 'html5blank'); ?> <span><?php echo $term->name; ?></span> <?php _e('units', 'html5blank'); ?></a>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<?php if(!$amp) get_template_part('loop-services-single-2'); ?>

	<?php if(get_field('home_shop_title') && !$amp): ?>
		<div class="reviews-section shop-section spacing grey 1" style="background-image: url('<?php the_field('home_shop_background'); ?>');">
			<div class="wrapper overflow">
				<div class="title-wrap float-left">
					<?php if(get_field('home_shop_subtitle')): ?>
						<p class="title-5 icon icon-arrow">
							<?php the_field('home_shop_subtitle'); ?>
						</p>
					<?php endif; ?>

					<?php if(get_field('home_shop_title')): ?>
						<h2 class="title">
							<?php the_field('home_shop_title'); ?>
						</h2>
					<?php endif; ?>

					<?php if(get_field('home_shop_button_link')): ?>
						<a class="btn" href="<?php the_field('home_shop_button_link'); ?>" target="_blank">
							<?php the_field('home_shop_button_label'); ?>
						</a>
					<?php endif; ?>
				</div>

				<div class="content float-right">
					<?php the_field('home_shop_content'); ?>
				</div>
			</div>
		</div>
	<?php endif; ?>


	<?php if(!$amp) get_template_part('loop-reviews'); ?>

	<?php if( get_field('home_promotions') ): ?>
		<div class="reviews-section grey-2 spacing" style="background-image: url('<?php the_field('home_promotions_background'); ?>');">
			<div class="wrapper">
				<?php if(get_field('home_promotions_subtitle')): ?>
					<p class="title-5 icon icon-arrow">
						<?php the_field('home_promotions_subtitle'); ?>
					</p>
				<?php endif; ?>

				<?php if(get_field('home_promotions_title')): ?>
					<h2 class="title">
						<?php the_field('home_promotions_title'); ?>

						<a href="<?php bloginfo('url'); ?>/promotions/">
							<?php _e('view all promotions', 'html5blank'); ?>
						</a>
					</h2>
				<?php endif; ?>

				<div class="columns columns-3 white flex img-inside">
					<?php $posts = get_field('home_promotions'); ?>
					<?php if($amp): ?>
            <?php $post = $posts[0]; ?>
            <?php setup_postdata($post); ?>

						<article id="post-<?php the_ID(); ?>" <?php post_class('col'); ?>>
							<?php if ( has_post_thumbnail()) : ?>
								<a class="img-wrap" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
									<?php the_post_thumbnail('medium'); ?>
								</a>
							<?php endif; ?>

							<div class="content">
								<h3 class="title title-4">
									<?php the_title(); ?>
								</h3>

								<?php if (has_excerpt()) : ?>
									<?php html5wp_excerpt(); ?>
								<?php endif; ?>

								<a class="btn" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
									<?php _e('learn more', 'html5blank'); ?>
								</a>
							</div>
						</article>
          <?php else: ?>
					<?php foreach($posts as $post): ?>
						<?php setup_postdata($post); ?>

						<article id="post-<?php the_ID(); ?>" <?php post_class('col'); ?>>
							<?php if ( has_post_thumbnail()) : ?>
								<a class="img-wrap" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
									<?php the_post_thumbnail('medium'); ?>
								</a>
							<?php endif; ?>

							<div class="content">
								<h3 class="title title-4">
									<?php the_title(); ?>
								</h3>

								<?php if (has_excerpt()) : ?>
									<?php html5wp_excerpt(); ?>
								<?php endif; ?>

								<a class="btn" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
									<?php _e('learn more', 'html5blank'); ?>
								</a>
							</div>
						</article>
					<?php endforeach; endif; ?>
				</div>

				<?php wp_reset_postdata(); ?>
			</div>
		</div>
	<?php endif; ?>

	<?php if(!$amp): ?>
		<div class="reviews-section blog spacing" style="background-image: url('<?php echo get_template_directory_uri(); ?>/img/bgr-content-8.png');">
			<div class="wrapper">
				<p class="title-5 icon icon-arrow">
					<?php _e('blog', 'html5blank'); ?>
				</p>

				<h2 class="title">
					<?php _e('our latest blog ', 'html5blank'); ?> <br/><?php _e('posts', 'html5blank'); ?>

					<a href="<?php bloginfo('url'); ?>/blog/">
						<?php _e('view all blog posts', 'html5blank'); ?>
					</a>
				</h2>

				<div class="columns columns-3 grey flex img-inside radius">
					<?php get_template_part('loop-latest'); ?>
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

	<?php get_template_part('module-logos'); ?>
</div>

<?php get_footer(); ?>
