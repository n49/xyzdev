<div class="header-wrap has-slider has-image <?php the_sub_field('header_text_color'); ?>">
	<div class="wrapper">
		<?php if(get_sub_field('header_content')): ?>
			<?php the_sub_field('header_content'); ?>
		<?php endif; ?>

		<?php if(have_rows('header_logos')): ?>
			<div class="logos">
				<?php while(have_rows('header_logos')): the_row(); ?>
					<?php if(get_sub_field('header_logos_link')): ?>
						<?php
							$link = get_sub_field('header_logos_link');
							$link_url = $link['url'];
							$link_title = $link['title'];
							$link_target = $link['target'] ? $link['target'] : '_self';
						?>
					<?php endif; ?>

					<?php if(get_sub_field('header_logos_image')): ?>
						<?php $image = get_sub_field('header_logos_image'); ?>

						<?php if(get_sub_field('header_logos_link')): ?>
							<a href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
						<?php endif; ?>

							<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />

						<?php if(get_sub_field('header_logos_link')): ?>
							</a>
						<?php endif; ?>
					<?php endif; ?>

					<?php if(get_sub_field('header_logos_iframe')): ?>
						<?php the_sub_field('header_logos_iframe'); ?>
					<?php endif; ?>
				<?php endwhile; ?>
			</div>
		<?php endif; ?>

		<?php if(have_rows('header_buttons')): ?>
			<div class="btn-wrap-normal">
				<?php while(have_rows('header_buttons')): the_row(); ?>
					<?php if(get_sub_field('header_buttons_link')): ?>
						<?php
							$link = get_sub_field('header_buttons_link');
							$link_url = $link['url'];
							$link_title = $link['title'];
							$link_target = $link['target'] ? $link['target'] : '_self';
						?>

						<a class="btn btn-yellow" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
							<?php echo esc_html($link_title); ?>
						</a>
					<?php endif; ?>
				<?php endwhile; ?>
			</div>
		<?php endif; ?>
	</div>

	<?php if(get_sub_field('header_image') ): ?>
		<div class="bgr" style="background-image: url(<?php the_sub_field('header_image'); ?>); opacity: <?php the_sub_field('header_image_opacity'); ?>"></div>
	<?php endif; ?>
</div>