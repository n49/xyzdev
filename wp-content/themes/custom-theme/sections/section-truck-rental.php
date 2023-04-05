<div class="section-normal white pt-1 pb-0 overflow">
	<div class="wrapper narrow">
		<div class="rental-wrap ontop">
			<div class="bgr">
				<?php if( get_sub_field('image') ): ?>
					<?php $image = get_sub_field('image'); ?>

					<img class="overlap centered" src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
				<?php endif; ?>

				<div class="columns columns-2">
					<div class="col">
						<div class="max-310">
							<?php if(get_sub_field('content')): ?>
								<?php the_sub_field('content'); ?>
							<?php endif; ?>

							<?php if(get_sub_field('link')): ?>
								<?php
									$link = get_sub_field('link');
									$link_url = $link['url'];
									$link_title = $link['title'];
									$link_target = $link['target'] ? $link['target'] : '_self';
								?>

								<a class="btn btn-white-2" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
									<?php echo esc_html($link_title); ?>
								</a>
							<?php endif; ?>
						</div>
					</div>

					<div class="col">
						<?php if( have_rows('items') ): ?>
							<div class="columns columns-3 table grey flex">
								<div class="col first">
									<?php _e('unit type', 'html5blank'); ?>
								</div>

								<div class="col first">
									<?php _e('truck type', 'html5blank'); ?>
								</div>

								<div class="col first">
									<?php _e('duration', 'html5blank'); ?>
								</div>

								<?php while( have_rows('items') ): the_row(); ?>
									<div class="col">
										<?php the_sub_field('items_unit_type'); ?>
									</div>

									<div class="col">
										<?php the_sub_field('items_truck_type'); ?>
									</div>

									<div class="col">
										<?php the_sub_field('items_trucks_duration'); ?>
									</div>
								<?php endwhile; ?>
							</div>

							<?php if(get_sub_field('notice')): ?>
								<p class="notice">
									<?php the_sub_field('notice'); ?>
								</p>
							<?php endif; ?>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>