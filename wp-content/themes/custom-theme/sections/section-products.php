
<div class="section-normal">
	<div class="wrapper">
		<div class="columns columns-3 custom">
			<div class="col first">
				<?php if(get_sub_field('logo')): ?>
					<?php $image = get_sub_field('logo'); ?>

					<div class="img-wrap normal mb-15">
						<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
					</div>
				<?php endif; ?>

				<?php if(get_sub_field('link')): ?>
					<?php
						$link = get_sub_field('link');
						$link_url = $link['url'];
						$link_title = $link['title'];
						$link_target = $link['target'] ? $link['target'] : '_self';
					?>

					<a class="icon icon-link as-block mb-30" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>"><?php echo esc_html($link_title); ?></a>
				<?php endif; ?>

				<?php if(get_sub_field('content')): ?>
					<?php the_sub_field('content'); ?>
				<?php endif; ?>
			</div>

			<?php if(have_rows('items')): ?>
				<div class="col second wide">
					<div class="columns columns-2 flex white products">
						<?php while(have_rows('items')): the_row(); ?>
							<div class="col">
								<div class="content">
									<?php if( get_sub_field('items_image') ): ?>
										<?php $image = get_sub_field('items_image'); ?>

										<div class="img-wrap transparent">
											<img src="<?php echo $image['sizes']['small-2']; ?>" alt="<?php echo $image['alt']; ?>" />
										</div>
									<?php endif; ?>

									<h3 class="title-4">
										<?php the_sub_field('items_title'); ?>
									</h3>

									<?php if(get_sub_field('items_link')): ?>
										<?php
											$link = get_sub_field('items_link');
											$link_url = $link['url'];
											$link_title = $link['title'];
											$link_target = $link['target'] ? $link['target'] : '_self';
										?>

										<a class="icon icon-link" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>"><?php echo esc_html($link_title); ?></a>
									<?php endif; ?>
								</div>
							</div>
						<?php endwhile; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>