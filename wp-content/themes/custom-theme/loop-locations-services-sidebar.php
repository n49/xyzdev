<?php
	$parent = $post->post_parent;
	$current = $post->post_name;

	$args = array(
		'post_type' => 'locations',
		'posts_per_page' => -1,
		'post_parent' => $parent,
		'meta_key' => 'location_service_type',
		'meta_value' => array ('service', 'subpage', 'none'),
		'orderby' => 'menu_order'
	);

	$loop = new WP_Query($args);
?>

<div class="widget menu-wrap">
	<ul class="menu white">
		<li>
			<a href="<?php echo get_permalink($parent); ?>">
				<?php _e('overview', 'html5blank'); ?>
			</a>
		</li>

		<?php if ($loop->have_posts()): while ($loop->have_posts()) : $loop->the_post(); ?>

			<?php $class = ($current == $post->post_name) ? 'current-menu-item' : ''; ?>

			<?php if(get_field('location_service_custom_link')): ?>
				<?php $url = get_field('location_service_custom_link'); ?>
			<?php else: ?>
				<?php $url = get_permalink(); ?>
			<?php endif; ?>

			<li class="<?php echo $class; ?>">
				<a href="<?php echo $url; ?>" title="<?php the_title(); ?>">
					<?php the_title(); ?>
				</a>
			</li>
		<?php endwhile; ?>

		<?php endif; ?>

		<?php wp_reset_query(); ?>

		<li>
			<a href="<?php echo get_permalink($parent); ?>#reviews">
				<?php _e('reviews', 'html5blank'); ?>
			</a>
		</li>

		<li>
			<a href="<?php echo get_permalink($parent); ?>book-a-unit">
				<?php _e('available storage units', 'html5blank'); ?>
			</a>
		</li>
	</ul>
</div>

<?php if(get_field('info_title')): ?>
	<div class="widget widget-box text-white" style="background-image: url(<?php the_field('info_background_image'); ?>);">
		<?php if(get_field('info_subtitle')): ?>
			<p class="title-5 icon white icon-arrow">
				<?php the_field('info_subtitle'); ?>
			</p>
		<?php endif; ?>

		<h2>
			<?php the_field('info_title'); ?>
		</h2>

		<?php if(get_field('info_link')): ?>
			<?php
				$link = get_field('info_link');
				$link_url = $link['url'];
				$link_title = $link['title'];
				$link_target = $link['target'] ? $link['target'] : '_self';
			?>

			<a class="btn btn-yellow" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
				<?php echo esc_html($link_title); ?>
			</a>
		<?php endif; ?>		
	</div>
<?php endif; 