<?php /* Template Name: Notice */ get_header(); ?>

<div class="header-wrap">
	<div class="wrapper">
		<h1 class="title">
			<?php the_title(); ?>
		</h1>
	</div>
</div>

<div class="main-container no-bottom-padding" style="background-image: url('<?php the_field('page_background_image'); ?>');">
	<div class="wrapper">
		<main role="main" class="fullwidth">
			<div class="content-wrap normal overflow notice">
				<div class="float-left form">
					<?php if (have_posts()): while (have_posts()) : the_post(); ?>
						<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
							<?php the_content(); ?>
						</article>
					<?php endwhile; ?>

					<?php endif; ?>
				</div>

				<div class="float-right">
					<h2 class="title">
						<?php _e('got questions?', 'html5blank'); ?>
					</h2>

					<div class="info">
						<p class="icon faq">
							<a href="<?php bloginfo('url'); ?>/help-center/">
								<?php _e('help center/faq', 'html5blank'); ?>
							</a>
						</p>
						
						<?php if(get_field('contact_phone', 'option')): ?>
							<p class="icon phone last">
								<a href="tel:<?php the_field('contact_phone', 'option'); ?>">
									<?php the_field('contact_phone', 'option'); ?>
								</a>
							</p>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</main>
	</div>
	
	<div class="reviews-section wide spacing grey-section" style="background-image: url(<?php the_field('solutions_background'); ?>);">
		<div class="wrapper overflow">
			<div class="title-wrap float-left">
				<?php if(get_field('solutions_content')): ?>
					<?php the_field('solutions_content'); ?>
				<?php endif; ?>
			</div>
			
			<?php if( have_rows('solutions_features') ): ?>
				<div class="content float-right">
					<div class="columns columns-2 services">
						<?php while( have_rows('solutions_features') ): the_row(); ?>
							<div class="col">
								<div class="content-wrap small">
									<?php if( get_sub_field('solutions_features_icon') ): ?>
										<?php $image = get_sub_field('solutions_features_icon'); ?>
										
										<div class="img-wrap medium disable centered">
											<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
										</div>
									<?php endif; ?>
									
									<div class="content">
										<?php if(get_sub_field('solutions_features_title')): ?>
											<p class="last">
												<strong>
													<?php the_sub_field('solutions_features_title'); ?>
												</strong>
											</p>
										<?php endif; ?>
										
										<p>
											<?php the_sub_field('solutions_features_description'); ?>
										</p>
										
										<div class="btn-wrap">
											<?php if(get_sub_field('solutions_features_link')): ?>
												<?php
													$link = get_sub_field('solutions_features_link');
													$link_url = $link['url'];
													$link_title = $link['title'];
													$link_target = $link['target'] ? $link['target'] : '_self';
												?>

												<a class="btn btn-white-2" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
													<?php echo esc_html($link_title); ?>
												</a>
											<?php endif; ?>
											
											<?php if(get_sub_field('solutions_features_link_2')): ?>
												<?php
													$link = get_sub_field('solutions_features_link_2');
													$link_url = $link['url'];
													$link_title = $link['title'];
													$link_target = $link['target'] ? $link['target'] : '_self';
												?>

												<a class="btn btn-grey-dark" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
													<?php echo esc_html($link_title); ?>
												</a>
											<?php endif; ?>
										</div>
									</div>
								</div>
							</div>
						<?php endwhile; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
	
	<?php if(get_field('prevention_content')): ?>
		<div class="reviews-section wide spacing grey-section light">
			<div class="wrapper overflow">
				<div class="columns columns-3 services normal-margin">
					<div class="col">
						<?php if(get_field('prevention_subtitle')): ?>
							<p class="title-5 icon icon-arrow white">
								<?php the_field('prevention_subtitle'); ?>
							</p>
						<?php endif; ?>
						
						<?php the_field('prevention_content'); ?>
					</div>
					
					<?php if(get_field('prevention_column_left')): ?>
						<div class="col">
							<?php the_field('prevention_column_left'); ?>
						</div>
					<?php endif; ?>
					
					<?php if(get_field('prevention_column_right')): ?>
						<div class="col">
							<?php the_field('prevention_column_right'); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>
	
	<?php if(get_field('units_content')): ?>
		<div class="find-units narrow spacing">
			<div class="wrapper overflow">
				<?php the_field('units_content'); ?>
				
				<?php if( have_rows('units_links') ): ?>
					<div class="btn-wrap">
						<?php while( have_rows('units_links') ): the_row(); ?>
							<?php if(get_sub_field('units_links_link')): ?>
								<?php
									$link = get_sub_field('units_links_link');
									$link_url = $link['url'];
									$link_title = $link['title'];
									$link_target = $link['target'] ? $link['target'] : '_self';
								?>

								<a class="btn btn-dark" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
									<?php echo esc_html($link_title); ?>
								</a>
							<?php endif; ?>
						<?php endwhile; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
		
	<?php endif; ?>
			
	<?php get_template_part('loop-locations-single-2'); ?>
</div>

<?php get_footer(); ?>
