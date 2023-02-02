<?php
	$args = array(
		'post_type' => 'locations',
		'posts_per_page' => -1,
		'meta_key' => 'location_area',
		'meta_value' => 'toronto'
	);

	$loop = new WP_Query($args);
?>
<script type="text/javascript">
	var locationName;

	function setLocationTag(loc,url){
		locationName = loc;
		window.location = url;
	}
</script>
<div class="locations-wrap">
	<?php get_template_part('module-search'); ?>
	<div class="columns columns-4 locations flex flex-normal-2 white products has-button mixitup">
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
				<div class="content">
					<?php if(get_field('location_new') === 'yes'): ?>
						<span class="new">
							<?php the_field('location_new_label'); ?>
						</span>
					<?php endif; ?>

					<a class="no-hover" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
						<h2 class="title title-4">
							<?php the_title(); ?>
						</h2>
					</a>

					<?php if(get_field('location_address')): ?>
						<?php
							$location = get_field('location_address');
							$address = $location['address'];
						?>

						<p class="location location-toro">
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
				</div>

				<div class="bottom">
					<div class="btn-wrap">
						<?php if(get_field('location_custom_reserve_link')): ?>
							<?php $url = get_field('location_custom_reserve_link'); ?>
						<?php else: ?>
							<?php $url = get_permalink() . 'book-a-unit'; ?>
						<?php endif; ?>

						<a class="btn" href="<?php echo $url; ?>" title="<?php the_title(); ?>">
							<?php _e('reserve a unit', 'html5blank'); ?>
						</a>
					</div>

					<a class="view-location-details" style="cursor : pointer" title="<?php the_title(); ?>" onclick='setLocationTag("<?php the_title(); ?>","<?php the_permalink(); ?>")'>
						<?php _e('view location details', 'html5blank'); ?>
					</a>
				</div>
			</article>
		<?php endwhile; ?>

		<?php endif; ?>

		<?php wp_reset_query(); ?>
	</div>
</div>
