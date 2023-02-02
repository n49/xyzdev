<?php if( have_rows('custom_layout') ): ?>
	<?php while (have_rows('custom_layout')) : the_row(); ?>
		<?php if(get_row_layout() == 'cta'): ?>
			<?php get_template_part('layouts/custom/cta'); ?>
		<?php elseif( get_row_layout() == 'about'): ?>
			<?php get_template_part('layouts/custom/about'); ?>
		<?php elseif( get_row_layout() == 'columns'): ?>
			<?php get_template_part('layouts/custom/columns'); ?>
		<?php elseif( get_row_layout() == 'columns'): ?>
			<?php get_template_part('layouts/custom/columns'); ?>
		<?php elseif( get_row_layout() == 'prices'): ?>
			<?php get_template_part('layouts/custom/prices'); ?>
		<?php elseif( get_row_layout() == 'storage'): ?>
			<?php get_template_part('layouts/custom/storage'); ?>
		<?php endif; ?>
	<?php endwhile; ?>
<?php endif; ?>
