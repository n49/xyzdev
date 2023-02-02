<div class="tabs-wrap">
	<div class="wrapper">
		<?php $note = ''; ?>

		<?php if(get_field('storage_notice')): ?>
			<?php $note = 'has-note'; ?>

			<div class="note-wrap">
				<div class="note">
					<?php the_field('storage_notice'); ?>
				</div>
			</div>
		<?php endif; ?>

		<div class="tabs white active <?php echo $note; ?>">
			<ul class="horizontal">
				<?php if(have_rows('storage_units')): ?>
					<li>
						<a class="normal" href="#all">
							<p class="title-5">
								<?php _e('storage units', 'html5blank'); ?>
							</p>
						</a>
					</li>
				<?php endif; ?>

				<?php if(have_rows('storage_units_parking')): ?>
					<li>
						<a class="normal" href="#parking">
							<p class="title-5">
								<?php _e('parking', 'html5blank'); ?>
							</p>
						</a>
					</li>
				<?php endif; ?>
			</ul>

			<?php if(have_rows('storage_units')): ?>
				<div id="all" class="tab">
					<?php while(have_rows('storage_units')): the_row(); ?>
						<div class="overflow">
							<div class="content-wrap">
								<div class="content float-left">
									<?php if(get_sub_field('storage_units_title')): ?>
										<p class="title title-4">
											<?php the_sub_field('storage_units_title'); ?>
										</p>
									<?php endif; ?>

									<?php if(get_sub_field('storage_units_description')): ?>
										<p class="size">
											<?php the_sub_field('storage_units_description'); ?>
										</p>
									<?php endif; ?>
								</div>

								<?php if(get_sub_field('storage_units_link')): ?>
									<?php
										$link = get_sub_field('storage_units_link');
										$link_url = $link['url'];
										$link_title = $link['title'];
										$link_target = $link['target'] ? $link['target'] : '_self';
									?>

									<div class="btn-wrap float-right">
										<a class="btn normal view-units-location-size" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
											<?php echo esc_html($link_title); ?>
										</a>
									</div>
								<?php endif; ?>
							</div>
						</div>
					<?php endwhile; ?>

					<?php if(get_field('storage_link')): ?>
						<?php
							$link = get_field('storage_link');
							$link_url = $link['url'];
							$link_title = $link['title'];
							$link_target = $link['target'] ? $link['target'] : '_self';
						?>

						<a class="btn green" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
							<?php echo esc_html($link_title); ?>
						</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if(have_rows('storage_units_parking')): ?>
				<div id="parking" class="tab">
					<?php while(have_rows('storage_units_parking')): the_row(); ?>
						<div class="overflow">
							<div class="content-wrap">
								<div class="content float-left">
									<?php if(get_sub_field('storage_units_title')): ?>
										<p class="title title-4">
											<?php the_sub_field('storage_units_title'); ?>
										</p>
									<?php endif; ?>

									<?php if(get_sub_field('storage_units_description')): ?>
										<p class="size">
											<?php the_sub_field('storage_units_description'); ?>
										</p>
									<?php endif; ?>
								</div>

								<?php if(get_sub_field('storage_units_link')): ?>
									<?php
										$link = get_sub_field('storage_units_link');
										$link_url = $link['url'];
										$link_title = $link['title'];
										$link_target = $link['target'] ? $link['target'] : '_self';
									?>

									<div class="btn-wrap float-right">
										<a class="btn normal view-units-location-size" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
											<?php echo esc_html($link_title); ?>
										</a>
									</div>
								<?php endif; ?>
							</div>
						</div>
					<?php endwhile; ?>

					<?php if(get_field('storage_link')): ?>
						<?php
							$link = get_field('storage_link');
							$link_url = $link['url'];
							$link_title = $link['title'];
							$link_target = $link['target'] ? $link['target'] : '_self';
						?>

						<a class="btn green" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
							<?php echo esc_html($link_title); ?>
						</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
