<div class="reviews-section grey has-bgr-small has-bgr-middle narrow spacing <?php the_sub_field('style'); ?>">
	<div class="wrapper overflow" style="background-image: url('<?php the_sub_field('background'); ?>');">
		<div class="title-wrap float-left">
			<?php if(get_sub_field('content')): ?>
				<?php the_sub_field('content'); ?>
			<?php endif; ?>
		</div>

		<div class="content float-right">
			<?php if(!$amp) get_template_part('loop-reviews-general'); ?>
		</div>
	</div>
</div>