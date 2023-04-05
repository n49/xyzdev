<div class="section-normal grey arrow-sep">
	<div class="wrapper">
		<?php if(have_rows('items')): ?>
			<div class="columns columns-5 carousel-5">
				<?php while(have_rows('items')): the_row(); ?>
					<div class="col">
						<?php if(get_sub_field('items_image')): ?>
							<?php $image = get_sub_field('items_image'); ?>

							<div class="img-wrap normal circle">
								<img src="<?php echo $image['sizes']['small-3']; ?>" alt="<?php echo $image['alt']; ?>" />
							</div>
						<?php endif; ?>

						<?php if(get_sub_field('items_name')): ?>
							<strong>
								<?php the_sub_field('items_name'); ?>
							</strong>
						<?php endif; ?>

						<?php if(get_sub_field('items_title')): ?>
							<p class="mt-5">
								<?php the_sub_field('items_title'); ?>
							</p>
						<?php endif; ?>
					</div>
				<?php endwhile; ?>
			</div>

			<div class="carousel-5-nav as-slider-nav"></div>
		<?php endif; ?>
	</div>
</div>