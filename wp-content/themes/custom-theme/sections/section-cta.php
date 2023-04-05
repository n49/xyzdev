
<div class="cta-wrap simple" style="background-image: url(<?php the_sub_field('background_image'); ?>); background-color: <?php the_sub_field('background_color'); ?>">
	<div class="wrapper narrow-3">
		<div class="flex center">
			<?php if(get_sub_field('content')): ?>
				<div class="content">
					<?php the_sub_field('content'); ?>
				</div>
			<?php endif; ?>

			<?php if(get_sub_field('link')): ?>
				<?php
					$link = get_sub_field('link');
					$link_url = $link['url'];
					$link_title = $link['title'];
					$link_target = $link['target'] ? $link['target'] : '_self';
				?>

				<div class="btn-wrap">
					<a class="btn btn-white" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
						<?php echo esc_html($link_title); ?>
					</a>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
