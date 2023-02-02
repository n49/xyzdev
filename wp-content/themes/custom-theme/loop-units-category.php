<?php
	$taxonomy = 'unit-category';

	$terms = get_terms(array(
		'taxonomy' => $taxonomy,
		'hide_empty' => false
	));
?>

<div class="tabs-wrap">
	<div class="wrapper">
		<div class="tabs active has-slider">
			<ul class="horizontal slider has-deeplinking">
				<?php foreach ($terms as $term) : ?>
					<li id="<?php echo $term->slug; ?>">
						<?php if(get_field('use_category_separator', $term) === 'yes'): ?>
							<span class="sep icon"></span>
						<?php endif; ?>

						<a class="normal" href="#tab-<?php echo $term->slug; ?>">
							<p class="title-5">
								<?php echo $term->name; ?>
							</p>

							<?php if(get_field('unit_category_size', $term)): ?>
								<p>
									<?php the_field('unit_category_size', $term); ?>
								</p>
							<?php endif; ?>
						</a>
					</li>
				<?php endforeach; ?>

				<?php wp_reset_query(); ?>
			</ul>

			<?php foreach ($terms as $term) : ?>
				<div id="tab-<?php echo $term->slug; ?>" <?php post_class('overflow tab'); ?>>
					<div class="float-left content">
						<h2 class="title">
							<?php if( get_field('unit_category_full_title', $term) ): ?>
								<?php the_field('unit_category_full_title', $term); ?>
							<?php else: ?>
								<?php echo $term->name; ?>
							<?php endif; ?>
						</h2>

						<?php if(get_field('unit_category_size', $term)): ?>
							<p class="size">
								<?php the_field('unit_category_size', $term); ?>
							</p>
						<?php endif; ?>

						<?php if( get_field('unit_category_description', $term) ): ?>
							<?php the_field('unit_category_description', $term); ?>
						<?php endif; ?>
					</div>

					<div class="float-right">
						<?php if( get_field('unit_category_gallery', $term) ): ?>
							<?php
								$images = get_field('unit_category_gallery', $term);
								$count = count($images);
							?>

							<div class="gallery wp-slick-slider has-lightbox slide-count-<?php echo $count; ?>">
								<?php foreach($images as $image): ?>
									<div class="img-wrap">
										<a href="<?php echo $image['url']; ?>">
											<img src="<?php echo $image['sizes']['medium']; ?>" alt="<?php echo $image['alt']; ?>" />

											<span class="btn btn-grey"><?php _e('view all photos', 'html5blank'); ?></span>
										</a>
									</div>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>

						<?php if(get_field('unit_category_storage_content', $term)): ?>
							<?php the_field('unit_category_storage_content', $term); ?>
						<?php endif; ?>

						<?php if(have_rows('unit_category_storage', $term)): ?>
							<div class="columns columns-4">
								<?php while(have_rows('unit_category_storage', $term)): the_row(); ?>
									<div class="col">
										<?php if(get_sub_field('unit_category_storage_title')): ?>
											<h4>
												<?php the_sub_field('unit_category_storage_title'); ?>
											</h4>
										<?php endif; ?>

										<?php if(get_sub_field('unit_category_storage_description')): ?>
											<?php the_sub_field('unit_category_storage_description'); ?>
										<?php endif; ?>

										<?php if(get_sub_field('unit_category_storage_link')): ?>
											<?php
												$link = get_sub_field('unit_category_storage_link');
												$link_url = $link['url'];
												$link_title = $link['title'];
												$link_target = $link['target'] ? $link['target'] : '_self';
											?>

											<a href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
												<?php echo esc_html($link_title); ?>
											</a>
										<?php endif; ?>
									</div>
								<?php endwhile; ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>

			<?php wp_reset_query(); ?>
		</div>
	</div>
</div>
