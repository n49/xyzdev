<?php
	$taxonomy = 'faq-category';

	$terms = get_terms(array(
		'taxonomy' => $taxonomy,
		'hide_empty' => false
	));
?>

<div class="category-wrap">
	<p class="title-5 icon icon-arrow">
		<?php _e('frequently asked questions', 'html5blank'); ?>
	</p>

	<h2 class="title">
		<?php _e('browse topics', 'html5blank'); ?>
	</h2>

	<div class="columns columns-3 flex category">
		<?php foreach ($terms as $term) : ?>
			<article <?php post_class('col icon-wrap'); ?>>
				<?php if( get_field('faq_icon', $term) ): ?>
					<?php $image = get_field('faq_icon', $term); ?>

					<div class="img-wrap no-radius">
						<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
					</div>
				<?php endif; ?>

				<h3 class="title-4">
					<a href="#<?php echo $term->slug; ?>">
						<?php echo $term->name; ?>
					</a>
				</h3>
			</article>
		<?php endforeach; ?>

		<?php wp_reset_query(); ?>
	</div>
</div>
