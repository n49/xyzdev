<?php if( have_rows('sections') ): ?>
	<?php while (have_rows('sections')) : the_row(); ?>
		<?php if(get_row_layout() == 'header-wrap'): ?>
			<?php get_template_part('sections/section-header'); ?>
		<?php elseif( get_row_layout() == 'content'): ?>
			<?php get_template_part('sections/section-content'); ?>
		<?php elseif( get_row_layout() == 'reviews'): ?>
			<?php get_template_part('sections/section-reviews'); ?>
			<?php // if(!$amp) get_template_part('loop-reviews'); ?>
		<?php elseif( get_row_layout() == 'locations'): ?>
			<?php get_template_part('sections/section-locations'); ?>
		<?php elseif( get_row_layout() == 'locations-custom'): ?>
			<?php get_template_part('sections/section-locations-custom'); ?>
		<?php elseif( get_row_layout() == 'features'): ?>
			<?php get_template_part('sections/section-features'); ?>
		<?php elseif( get_row_layout() == 'savings'): ?>
			<?php get_template_part('sections/section-savings'); ?>
		<?php elseif( get_row_layout() == 'about'): ?>
			<?php get_template_part('sections/section-about'); ?>
		<?php elseif( get_row_layout() == 'how-it-works'): ?>
			<?php get_template_part('sections/section-how-it-works'); ?>
		<?php elseif( get_row_layout() == 'truck-rental'): ?>
			<?php get_template_part('sections/section-truck-rental'); ?>
		<?php elseif( get_row_layout() == 'products'): ?>
			<?php get_template_part('sections/section-products'); ?>
		<?php elseif( get_row_layout() == 'team'): ?>
			<?php get_template_part('sections/section-team'); ?>
		<?php elseif( get_row_layout() == 'cta'): ?>
			<?php get_template_part('sections/section-cta'); ?>
		<?php elseif( get_row_layout() == 'storage'): ?>
			<?php get_template_part('sections/section-storage'); ?>
		<?php endif; ?>
	<?php endwhile; ?>
<?php endif; ?>
