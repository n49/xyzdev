<?php if(get_sub_field('header_type') === 'normal'): ?>
	<div class="header">
		<div class="bottom">
			<div class="breadcrumbs normal" typeof="BreadcrumbList" vocab="https://schema.org/">
				<div class="wrapper">
					<?php if(function_exists('bcn_display')) { bcn_display(); } ?>
				</div>
			</div>
		</div>
	</div>

	<div class="header-wrap type-<?php the_sub_field('header_type'); ?> <?php the_sub_field('header_text_color'); ?>">
		<div class="wrapper">
			<h1 class="title">
				<?php the_title(); ?>
			</h1>
		</div>
	</div>
<?php else: ?>
	<div class="header-wrap has-slider has-image type-<?php the_sub_field('header_type'); ?> <?php the_sub_field('header_text_color'); ?>">
		<?php if(get_sub_field('header_image') ): ?>
			<div class="bgr" style="background-image: url(<?php the_sub_field('header_image'); ?>); opacity: <?php the_sub_field('header_image_opacity'); ?>"></div>
		<?php endif; ?>
		
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
	</div>
<?php endif; ?>