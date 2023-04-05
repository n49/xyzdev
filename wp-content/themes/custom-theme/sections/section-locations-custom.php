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

<div class="locations-wrap spacing">
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

		<?php if(have_rows('items')): ?>
			<div class="columns columns-4 locations flex white products img-inside has-view">
				<?php while(have_rows('items')): the_row(); ?>
					<?php if(get_sub_field('items_link')): ?>
						<?php
							$link = get_sub_field('items_link');
							$link_url = $link['url'];
							$link_title = $link['title'];
							$link_target = $link['target'] ? $link['target'] : '_self';
						?>
					<?php endif; ?>

					<article class="col">
						<?php if( get_sub_field('items_image') ): ?>
							<?php $image = get_sub_field('items_image'); ?>

							<a class="img-wrap" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
								<?php if($amp): ?>
									<amp-img width="300" height="137"
									src="<?php echo $image['sizes']['small-5']; ?>" alt="<?php echo $image['alt']; ?>" layout="intrinsic"/>
								<?php else: ?>
									<img width="272px" height="81px" src="<?php echo $image['sizes']['small-5']; ?>" alt="<?php echo $image['alt']; ?>" />
								<?php endif; ?>
							</a>
						<?php endif; ?>

						<div class="content">
							<?php if(get_sub_field('items_title')): ?>
								<a class="no-hover" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
									<h3 class="title title-4">
										<?php the_sub_field('items_title'); ?>
									</h3>
								</a>
							<?php endif; ?>

							<?php if(get_sub_field('items_address')): ?>
								<p class="icon location-2">
									<?php the_sub_field('items_address'); ?>
								</p>
							<?php endif; ?>

							<?php if($amp): ?>
								<a class="view view-available" style="cursor: pointer" href="<?php echo esc_url($link_url); ?>book-a-unit" target="<?php echo esc_attr($link_target); ?>">
									<?php _e('view available units', 'html5blank'); ?>
								</a>
							<?php else: ?>
								<a class="view" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
									<?php _e('view available units', 'html5blank'); ?>
								</a>
							<?php endif; ?>
						</div>
					</article>
				<?php endwhile; ?>
			</div>
		<?php endif; ?>
	</div>
</div>
