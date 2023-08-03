<?php get_header(); ?>

<div class="header-wrap">
	<div class="wrapper">
		<h2 class="title">
			<?php _e( 'XYZ Storage help center', 'html5blank' ); ?>
		</h2>
	</div>
</div>

<div class="main-container">
	<div class="wrapper overflow">
		<main role="main">
			<?php if (have_posts()): while (have_posts()) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<h1>
						<?php the_title(); ?>
					</h1>

					<?php the_content(); ?>

					<div class="meta-wrap">
						<p class="contact">
							<a href="<?php bloginfo('url'); ?>/contact-us/">
								<?php _e('contact XYZ for more help', 'html5blank'); ?>
							</a>
						</p>

						<?php if ( function_exists( 'ADDTOANY_SHARE_SAVE_KIT' ) ) { ADDTOANY_SHARE_SAVE_KIT(); } ?>
					</div>
				</article>
			<?php endwhile; ?>

			<?php endif; ?>
		</main>

		<div class="sidebar">
			<?php get_template_part('loop-faq-sidebar'); ?>
		</div>
	</div>
</div>

<?php get_template_part('module-cta'); ?>

<?php get_footer();