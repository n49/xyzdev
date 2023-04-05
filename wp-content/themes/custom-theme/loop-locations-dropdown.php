<?php
	$args = array(
		'post_type' => 'locations',
		'posts_per_page' => -1,
		'post_parent' => 0
	);

	$page_slug = $post->post_name;
	$parent_title = get_the_title($post->post_parent);

	$loop = new WP_Query($args);
?>

<?php if(get_field('location_title')): ?>
	<div class="title-content">
		<h2>
			<?php the_field('location_title'); ?>
		</h2>

		<div>
			<p>
				<?php _e('change location', 'html5blank'); ?>
			</p>

			<ul class="custom-dropdown">
				<li>
					<span>
						<?php echo $parent_title; ?>
					</span>

					<ul class="submenu">
						<?php if ($loop->have_posts()): while ($loop->have_posts()) : $loop->the_post(); ?>
							<li>
								<a href="<?php the_permalink(); ?><?php echo $page_slug; ?>/">
									<?php the_title(); ?>
								</a>
							</li>

						<?php endwhile; ?>

						<?php endif; ?>

						<?php wp_reset_query(); ?>
					</ul>
				</li>
			</ul>
		</div>
	</div>
<?php endif; ?>