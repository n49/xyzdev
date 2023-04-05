<div class="services-wrap spacing has-bottom-bgr">
	<div class="wrapper overflow">
		<?php if(get_sub_field('content')): ?>
			<?php the_sub_field('content'); ?>
		<?php endif; ?>

		<?php if(have_rows('about')): ?>
			<div class="columns columns-2 as-line">
				<?php while(have_rows('about')): the_row(); ?>
					<div class="col">
						<?php if(get_sub_field('about_gallery')): ?>
							<?php $images = get_sub_field('about_gallery'); ?>
							
							<div class="mt-40 mb-25 slider dots-bottom wp-slick-slider has-lightbox">
								<?php foreach($images as $image): ?>
									<?php
										$caption = '';
										$caption .= '<h3>' . $image['title'] . '</h3>';
										$caption .= '<p>' . $image['description'] . '</p>';

										$caption .= '<div>';
										$caption .= '<h3>'. get_the_title() . '</h3>';
										$caption .= esc_html('<a class="btn btn-yellow" href="' . get_the_permalink() . 'book-a-unit/">reserve a unit</a>');
										$caption .= '</div>';
									?>

									<div class="img-wrap">
										<a href="<?php echo $image['url']; ?>" data-html="<?php echo $image['alt']; ?>" data-sub-html="<?php echo $caption; ?>" title="<?php echo $image['alt']; ?>">
											<img src="<?php echo $image['sizes']['medium-3']; ?>" alt="<?php echo $image['alt']; ?>" />
										</a>
									</div>									
								<?php endforeach; ?>
							</div>
						<?php endif; ?>

						<?php if(get_sub_field('about_title')): ?>
							<h2>
								<?php the_sub_field('about_title'); ?>
							</h2>
						<?php endif; ?>

						<?php if(get_sub_field('about_content')): ?>
							<?php the_sub_field('about_content'); ?>
						<?php endif; ?>
					</div>
				<?php endwhile; ?>
			</div>
		<?php endif; ?>
	</div>
</div>