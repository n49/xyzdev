<?php
	$section_bgr = get_sub_field('section_background_color');
	$ordered = get_sub_field('ordered');
	$count = count(get_sub_field('items'));
	$i = 1;

	if($count === 2){
		$wrapper = 'normal';
		$width_1 = 'width-29';
		$width_2 = 'width-63';
	} else {
		$wrapper = 'wide';
		$width_1 = 'width-23';
		$width_2 = 'width-70';
	}
?>

<div class="section-normal white has-top-bgr section-bgr-<?php echo $section_bgr; ?>">
	<div class="wrapper <?php echo $wrapper; ?> boxed count-<?php echo $count; ?>" style="background-color: <?php the_sub_field('background_color'); ?>;">
		<div class="flex">
			<div class="flex-item <?php echo $width_1; ?> mobile-width-100 <?php the_sub_field('text_color'); ?>">
				<?php if(get_sub_field('content')): ?>
					<?php the_sub_field('content'); ?>
				<?php endif; ?>
			</div>

			<div class="flex-item <?php echo $width_2; ?> mobile-width-100">
				<?php if(have_rows('items')): ?>
					<div class="columns columns-3 process custom mb-0 border-10">
						<?php while( have_rows('items') ): the_row(); ?>
							<div class="col ordered-<?php echo $ordered; ?>">
								<?php if( get_sub_field('items_icon') ): ?>
									<?php $image = get_sub_field('items_icon'); ?>

									<div class="img-wrap">
										<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
									</div>
								<?php endif; ?>

								<?php if(get_sub_field('items_title')): ?>
									<?php $icon = ''; ?>

									<?php if($ordered === 'yes'): ?>
										<?php $icon = 'icon arrow big'; ?>
									<?php endif; ?>
									
									<h3 class="title-4 <?php echo $icon; ?>">
										<?php if($ordered === 'yes'): ?>
											<span class="num">
												<?php echo $i; $i++;?>
											</span>
										<?php endif; ?>

										<span>
											<?php the_sub_field('items_title'); ?>
										</span>
									</h3>
								<?php endif; ?>

								<?php if(get_sub_field('items_content')): ?>
									<div class="content max-img">
										<?php the_sub_field('items_content'); ?>
									</div>
								<?php endif; ?>

								<?php if(get_sub_field('items_link')): ?>
									<?php
										$link = get_sub_field('items_link');
										$link_url = $link['url'];
										$link_title = $link['title'];
										$link_target = $link['target'] ? $link['target'] : '_self';
									?>

									<a class="btn btn-yellow btn-hover-teal" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
										<?php echo esc_html($link_title); ?>
									</a>
								<?php endif; ?>
							</div>
						<?php endwhile; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>