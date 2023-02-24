<?php /* Template Name: Storage Sizes Guide */ get_header(); ?>

<div class="header-wrap">
	<div class="wrapper">
		<h1 class="title">
			<?php the_title(); ?>
		</h1>
	</div>
</div>

<div class="main-container icon has-arrow">
	<div class="wrapper">
		<main role="main" class="padding">
			<?php if (have_posts()): while (have_posts()) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class('overflow'); ?>>
					<?php the_content(); ?>
				</article>
			<?php endwhile; ?>

			<?php endif; ?>

			<?php get_template_part('module-layout'); ?>
		</main>

		<div class="sidebar">
			<div class="widget">
				<?php wp_nav_menu( array('menu' => 'Storage Unit Size Guide', 'menu_class' => 'menu white' )); ?>
			</div>
		</div>

		<div class="clear"></div>
	</div>
</div>

<?php get_template_part('loop-locations-all'); ?>

<?php get_footer(); ?>
