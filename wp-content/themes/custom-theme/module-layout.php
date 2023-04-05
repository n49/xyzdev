<?php if( have_rows('layout') ): ?>
	<?php while (have_rows('layout')) : the_row(); ?>
		<?php if(get_row_layout() == 'content'): ?>
			<?php get_template_part('layouts/content'); ?>
		<?php elseif( get_row_layout() == 'columns'): ?>
			<?php get_template_part('layouts/columns'); ?>
		<?php elseif( get_row_layout() == 'map'): ?>
			<?php get_template_part('layouts/map'); ?>
		<?php elseif( get_row_layout() == 'faq'): ?>
			<?php get_template_part('layouts/faq'); ?>
		<?php elseif( get_row_layout() == 'gallery'): ?>
			<?php get_template_part('layouts/gallery'); ?>
		<?php elseif( get_row_layout() == 'gallery-video'): ?>
			<?php get_template_part('layouts/gallery-video'); ?>
		<?php elseif( get_row_layout() == 'prices'): ?>
			<?php get_template_part('layouts/prices'); ?>
		<?php elseif( get_row_layout() == 'units'): ?>
			<?php get_template_part('layouts/units'); ?>
		<?php elseif( get_row_layout() == 'storage_units'): ?>
			<?php get_template_part('layouts/storage_units'); ?>
		<?php elseif( get_row_layout() == 'features'): ?>
			<?php get_template_part('layouts/features'); ?>
		<?php endif; ?>
	<?php endwhile; ?>
<?php endif; ?>
