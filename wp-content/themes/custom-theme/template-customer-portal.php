<?php /* Template Name: Customer Portal */ get_header(); ?>

<div class="header-wrap">
	<div class="wrapper">
		<h1 class="title">
			<?php the_title(); ?>
		</h1>
	</div>
</div>

<div class="main-container" style="background-image: url('<?php the_field('page_background_image'); ?>');">
	<div class="wrapper">
		<?php if( have_rows('services') ): ?>
			<div class="columns columns-3 large services flex flex-normal">
				<?php while( have_rows('services') ): the_row(); ?>
					<article class="col">
						<div class="content-wrap">		
							<?php if( get_sub_field('services_icon') ): ?>
								<?php $image = get_sub_field('services_icon'); ?>

								<div class="img-wrap disable">
									<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
								</div>
							<?php endif; ?>

							<div class="content">
								<?php if(get_sub_field('services_title')): ?>
									<h2 class="title-4">
										<?php the_sub_field('services_title'); ?>
									</h2>
								<?php endif; ?>

								<?php if(get_sub_field('services_description')): ?>
									<p>
										<?php the_sub_field('services_description'); ?>
									</p>
								<?php endif; ?>
								
								<?php if(get_sub_field('services_link')): ?>
									<?php
										$link = get_sub_field('services_link');
										$link_url = $link['url'];
										$link_title = $link['title'];
										$link_target = $link['target'] ? $link['target'] : '_self';
									?>

									<a class="btn" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
										<?php echo esc_html($link_title); ?>
									</a>
								<?php endif; ?>
							</div>
						</div>
					</article>
				<?php endwhile; ?>
			</div>
		<?php endif; ?>
	</div>
</div>

<?php get_footer(); ?>
