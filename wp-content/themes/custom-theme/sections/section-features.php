
<?php
	$columns = get_sub_field('columns');
	$type = get_sub_field('type');
	$style = get_sub_field('style');
	$size = '';
	$bgr = '';
	$i = 1;

	if($type === 'with-icon') {
		$size = 'large';
		$bgr = 'bgr-none';
	}
?>

<div class="services-wrap spacing style-<?php echo $style; ?> <?php echo $bgr; ?>">
	<div class="wrapper">
		<div class="content">
			<?php if(get_sub_field('title')): ?>
				<h2>
					<?php the_sub_field('title'); ?>
				</h2>
			<?php endif; ?>

			<?php if(get_sub_field('content')): ?>
				<div class="mb-40">
					<?php the_sub_field('content'); ?>

					<?php if(get_sub_field('link')): ?>
						<?php
							$link = get_sub_field('link');
							$link_url = $link['url'];
							$link_title = $link['title'];
							$link_target = $link['target'] ? $link['target'] : '_self';
						?>

						<a class="btn" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
							<?php echo esc_html($link_title); ?>
						</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>

		<?php if(have_rows('features')): ?>
			<div class="columns <?php echo $columns;?> services flex flex-normal top-m <?php echo $size;?>">
				<?php while(have_rows('features')): the_row(); ?>
					<?php if(get_sub_field('features_link')): ?>
						<?php
							$link = get_sub_field('features_link');
							$link_url = $link['url'];
							$link_title = $link['title'];
							$link_target = $link['target'] ? $link['target'] : '_self';
						?>
					<?php endif; ?>

					<div class="col">
						<div class="content-wrap text-center <?php echo $type; ?>">
							<?php if($type === 'round'): ?>
								<?php if( get_sub_field('features_icon') ): ?>
									<?php $image = get_sub_field('features_icon'); ?>

									<?php if(get_sub_field('features_link')): ?>
										<a class="img-wrap disable no-radius round" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
									<?php else: ?>
										<div class="img-wrap disable no-radius round">
									<?php endif; ?>

										<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />

										<?php if(get_sub_field('features_title')): ?>
											<h3 class="title title-5">
												<?php the_sub_field('features_title'); ?>
											</h3>
										<?php endif; ?>

									<?php if(get_sub_field('features_link')): ?>
										</a>
									<?php else: ?>
										</div>
									<?php endif; ?>
								<?php endif; ?>
							<?php endif; ?>

							<?php if($type === 'ordered'): ?>
								<div class="num">
									<?php echo sprintf("%02d", $i); $i++; ?>
								</div>

								<?php if(get_sub_field('features_title')): ?>
									<h3 class="title title-4">
										<?php the_sub_field('features_title'); ?>
									</h3>
								<?php endif; ?>

								<?php if(get_sub_field('features_content')): ?>
									<?php the_sub_field('features_content'); ?>
								<?php endif; ?>
							<?php endif; ?>

							<?php if($type === 'with-icon'): ?>
								<?php if( get_sub_field('features_icon') ): ?>
									<?php $image = get_sub_field('features_icon'); ?>

									<div class="img-wrap disable">
										<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
									</div>
								<?php endif; ?>

								<div class="content">
									<?php if(get_sub_field('features_title')): ?>
										<h2 class="title-4">
											<?php the_sub_field('features_title'); ?>
										</h2>
									<?php endif; ?>

									<?php if(get_sub_field('features_content')): ?>
										<?php the_sub_field('features_content'); ?>
									<?php endif; ?>
									
									<?php if(get_sub_field('features_link')): ?>
										<?php
											$link = get_sub_field('features_link');
											$link_url = $link['url'];
											$link_title = $link['title'];
											$link_target = $link['target'] ? $link['target'] : '_self';
										?>

										<a class="btn" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
											<?php echo esc_html($link_title); ?>
										</a>
									<?php endif; ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
				<?php endwhile; ?>
			</div>
		<?php endif; ?>
	</div>
</div>