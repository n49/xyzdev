<?php if( have_rows('layout') ): ?>
	<?php while (have_rows('layout')) : the_row(); ?>
		<?php if(get_row_layout() == 'content'): ?>
			<?php get_template_part('layouts/content'); ?>
		<?php elseif( get_row_layout() == 'storage_units'): ?>
			<?php get_template_part('layouts/storage_units'); ?>
		<?php elseif( get_row_layout() == 'features'): ?>
			<?php get_template_part('layouts/features'); ?>
		<?php endif; ?>
	<?php endwhile; ?>
<?php endif; ?>
