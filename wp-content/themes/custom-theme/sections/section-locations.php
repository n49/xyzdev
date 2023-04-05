<?php
	$args = array(
		'post_type' => 'locations',
		'posts_per_page' => -1,
		'post_parent' => 0,
	);

	$loop = new WP_Query($args);

	$amp = isset($_GET['amp']);
?>

<script type='text/javascript'>
	var locationName;
	function setLocationTag(loc,url)
	{
		locationName = loc;
		window.location = url
	}
</script>

<div class="locations-wrap spacing add-bgr">
	<div class="wrapper">
		<?php if(get_sub_field('subtitle')): ?>
			<p class="title-5 icon icon-arrow">
				<?php the_sub_field('subtitle'); ?>
			</p>
		<?php endif; ?>

		<?php if(get_sub_field('title')): ?>
			<h2 class="title no-max">
				<?php the_sub_field('title'); ?>
			</h2>
		<?php endif; ?>

		<?php if(get_sub_field('content')): ?>
			<div class="mb-40">
				<?php the_sub_field('content'); ?>
			</div>
		<?php endif; ?>

		<div class="columns columns-4 locations flex white products img-inside has-view mixitup">
			<?php if ($loop->have_posts()): while ($loop->have_posts()) : $loop->the_post(); ?>
				<?php if(get_field('location_address')): ?>
					<?php
						$location = get_field('location_address');
						$address = $location['address'];
						$address_lat = $location['lat'];
						$address_lng = $location['lng'];
					?>
				<?php endif; ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class('col mix'); ?> data-location-lat="<?php echo $address_lat; ?>" data-location-lng="<?php echo $address_lng; ?>" data-distance="100000">
					<?php if( get_field('location_image') ): ?>
						<?php $image = get_field('location_image'); ?>

						<a class="img-wrap" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
							<?php if($amp): ?>
								<amp-img width="300" height="137"
								src="<?php echo $image['sizes']['small-5']; ?>" alt="<?php echo $image['alt']; ?>" layout="intrinsic"/>
							<?php else: ?>
								<img width="272px" height="81px" src="<?php echo $image['sizes']['small-5']; ?>" alt="<?php echo $image['alt']; ?>" />
							<?php endif; ?>
						</a>
					<?php endif; ?>

					<div class="content">
						<?php if(get_field('location_new') === 'yes'): ?>
							<span class="new">
								<?php the_field('location_new_label'); ?>
							</span>
						<?php endif; ?>

						<a class="no-hover" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
							<h3 class="title title-4">
								<?php the_title(); ?>
							</h3>
						</a>

						<?php if(get_field('location_address')): ?>
							<?php
								$location = get_field('location_address');
								$address = $location['address'];
							?>

							<p class="location">
								<?php if(get_field('location_custom_address_2')): ?>
									<?php the_field('location_custom_address_2'); ?>
								<?php else: ?>
									<?php echo $address; ?>
								<?php endif; ?>
							</p>
						<?php endif; ?>

						<?php if(get_field('location_custom_address')): ?>
							<p class="icon location-2">
								<?php the_field('location_custom_address'); ?>
							</p>
						<?php endif; ?>

						<?php if(get_field('location_address')): ?>
							<p class="distance">
								<span></span>

								<?php _e('km away', 'html5blank'); ?>
							</p>
						<?php endif; ?>

						<?php if($amp): ?>
	            			<a class="view view-available"  style="cursor: pointer" title="<?php the_title(); ?>" href="<?php the_permalink(); ?>book-a-unit">
	              				<?php _e('view available units', 'html5blank'); ?>
	           				</a>
	            		<?php else: ?>
							<a class="view" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
								<?php _e('view available units', 'html5blank'); ?>
							</a>
						<?php endif; ?>
					</div>
				</article>
			<?php endwhile; ?>

			<?php endif; ?>

			<?php wp_reset_query(); ?>
		</div>
	</div>
</div>
