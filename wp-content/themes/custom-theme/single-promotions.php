<?php get_header(); ?>

<div class="header-wrap">
	<div class="wrapper">
		<h2 class="title">
			<?php _e( 'current promotions & discounts', 'html5blank' ); ?>
		</h2>
	</div>
</div>

<div class="main-container has-right-sidebar">
	<div class="wrapper overflow">
		<div class="back-wrap">
			<a href="<?php bloginfo('url'); ?>/promotions/">
				<?php _e( 'back to promotions', 'html5blank' ); ?>
			</a>
		</div>

		<main role="main">
			<?php if (have_posts()): while (have_posts()) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<h1 class="nh1-title">
						<?php the_title(); ?>
					</h1>

					<?php the_content(); ?>

					<div class="meta-wrap">
						<?php if ( function_exists( 'ADDTOANY_SHARE_SAVE_KIT' ) ) { ADDTOANY_SHARE_SAVE_KIT(); } ?>
					</div>
				</article>
			<?php endwhile; ?>

			<?php endif; ?>
		</main>

		<div class="sidebar">
			<?php get_template_part('loop-promotions-sidebar'); ?>
		</div>
	</div>
</div>

<?php get_template_part('module-cta'); ?>

<?php get_footer(); 