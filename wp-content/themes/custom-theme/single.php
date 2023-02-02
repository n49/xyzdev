<?php get_header(); ?>

<div class="header-wrap">
	<div class="wrapper">
		<h2 class="title">
			<?php _e( 'blog', 'html5blank' ); ?>
		</h2>
	</div>
</div>

<div class="main-container has-right-sidebar">
	<div class="wrapper overflow">
		<div class="back-wrap">
			<a href="<?php bloginfo('url'); ?>/blog/">
				<?php _e( 'back to news and events', 'html5blank' ); ?>
			</a>
		</div>

		<main role="main">
			<?php if (have_posts()): while (have_posts()) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<h1 class="nh1-title">
						<?php the_title(); ?>
					</h1>

					<?php if ( has_post_thumbnail()) : ?>
						<a class="img-wrap radius margin" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
							<?php the_post_thumbnail('medium-3'); ?>
						</a>
					<?php endif; ?>

					<?php the_content(); ?>

					<div class="meta-wrap">
						<div class="meta">
							<p class="category">
								<?php _e( 'under: ', 'html5blank' ); the_category(', '); ?>
							</p>

							<p class="date">
								<?php _e( 'published: ', 'html5blank' ); ?>

								<span><?php the_time('j F Y'); ?></span>
							</p>
						</div>

						<?php if ( function_exists( 'ADDTOANY_SHARE_SAVE_KIT' ) ) { ADDTOANY_SHARE_SAVE_KIT(); } ?>
					</div>
				</article>
			<?php endwhile; ?>

			<?php endif; ?>
		</main>

		<div class="sidebar">
			<?php get_template_part('loop-sidebar'); ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>
