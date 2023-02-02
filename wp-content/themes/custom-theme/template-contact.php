<?php /* Template Name: Contact */ get_header(); ?>

<div class="header-wrap">
	<div class="wrapper">
		<h1 class="title">
			<?php the_title(); ?>
		</h1>
	</div>
</div>

<div class="main-container no-bottom-padding" style="background-image: url('<?php the_field('page_background_image'); ?>');">
	<div class="wrapper">
		<main role="main" class="fullwidth padding">
			<div class="content-wrap normal overflow">
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
						<?php _e('other ways to connect', 'html5blank'); ?>
					</h2>

					<div class="info">
						<?php if(get_field('contact_phone', 'option')): ?>
							<p class="icon phone">
								<a href="tel:<?php the_field('contact_phone', 'option'); ?>">
									<?php the_field('contact_phone', 'option'); ?>
								</a>
							</p>
						<?php endif; ?>

						<p class="icon faq">
							<a href="<?php bloginfo('url'); ?>/help-center/">
								<?php _e('help center/faq', 'html5blank'); ?>
							</a>
						</p>
					</div>

					<?php get_template_part('loop-faq-contact'); ?>
				</div>
			</div>
		</main>
	</div>

	<?php get_template_part('loop-locations-single'); ?>
</div>

<?php get_footer(); ?>
