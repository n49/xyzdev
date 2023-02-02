<?php /* Template Name: Careers */ get_header(); ?>

<div class="header-wrap">
	<div class="wrapper">
		<h1 class="title">
			<?php the_title(); ?>
		</h1>
	</div>
</div>

<div class="main-container no-padding has-right-sidebar">
	<?php if(get_field('careers_description_content')): ?>
		<div class="services-wrap spacing-2 full" style="background-image: url('<?php the_field('careers_description_background'); ?>');">
			<div class="wrapper">
				<div class="content">
					<?php the_field('careers_description_content'); ?>
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
				<?php if( have_rows('careers_benefits') ): ?>
					<div class="widget features-2">
						<?php if(get_field('careers_benefits_title')): ?>
							<?php the_field('careers_benefits_title'); ?>
						<?php endif; ?>

						<div class="columns services side list">
							<?php while( have_rows('careers_benefits') ): the_row(); ?>
								<div class="col">
									<p class="title-5">
										<?php the_sub_field('careers_benefits_name'); ?>
									</p>
								</div>
							<?php endwhile; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

	<?php if(get_field('careers_apply_content')): ?>
		<div class="timeline-section spacing" style="background-image: url('<?php the_field('careers_apply_background'); ?>');">
			<div class="wrapper">
				<?php the_field('careers_apply_content'); ?>
			</div>
		</div>
	<?php endif; ?>
</div>

<?php get_footer(); ?>
