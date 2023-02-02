<?php /* Template Name: About */ get_header();

$amp = isset($_GET["amp"]);
?>

<div class="header-wrap has-slider">
	<div class="wrapper">
		<h1 class="title">
			<?php the_title(); ?>
		</h1>
	</div>

	<?php if( get_field('page_slider') ): ?>
		<?php
			$images = get_field('page_slider');
			$count = count($images);
		?>
		<?php if(!$amp): ?>
		<div class="gallery wp-slick-slider slide-count-<?php echo $count; ?>">
			<?php foreach($images as $image): ?>
				<div class="slide">
					<div class="img" style="background-image: url(<?php echo $image['sizes']['slider']; ?>); opacity: <?php the_field('page_slider_opacity'); ?>;"></div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
	<?php endif; ?>
</div>

<div class="main-container about-wrap has-right-sidebar" style="background-image: url('<?php the_field('page_background_image'); ?>');">
	<div class="wrapper overflow">
		<main role="main" class="padding">
			<?php if (have_posts()): while (have_posts()) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php the_content(); ?>
				</article>
			<?php endwhile; ?>

			<?php endif; ?>
		</main>

		<aside class="sidebar narrow">
			<?php if( have_rows('about_features') ): ?>
				<div class="widget features">
					<?php while( have_rows('about_features') ): the_row(); ?>
						<div class="col">
							<h3 class="title">
								<?php the_sub_field('about_features_title'); ?>
							</h3>

							<p>
								<?php the_sub_field('about_features_description'); ?>
							</p>
						</div>
					<?php endwhile; ?>
				</div>
			<?php endif; ?>
		</aside>
	</div>
</div>

<?php if( get_field('about_gallery') ): ?>
	<div class="timeline-section spacing-4" style="background-image: url('<?php the_field('about_gallery_background'); ?>');">
		<div class="wrapper overflow">
			<?php if(get_field('about_gallery_title')): ?>
				<div class="float-left">
					<h2 class="title">
						<?php the_field('about_gallery_title'); ?>
					</h2>
				</div>
			<?php endif; ?>

			<?php if(get_field('about_gallery_description')): ?>
				<div class="float-right">
					<?php the_field('about_gallery_description'); ?>
				</div>
			<?php endif; ?>
		</div>

		<?php if( get_field('about_gallery') ): ?>
			<?php $timeline_dots = get_field('about_gallery'); ?>

			<div class="timeline-nav-wrap">
				<div class="timeline-nav">
					<?php foreach($timeline_dots as $timeline_dots): ?>
						<div class="slide">
							<span></span>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>

		<?php if( get_field('about_gallery') ): ?>
			<?php $timeline_images = get_field('about_gallery'); ?>

			<div class="timeline-for">
				<?php foreach($timeline_images as $timeline_image): ?>
					<div class="slide">
						<div class="img-wrap">
							<img src="<?php echo $timeline_image['sizes']['medium-4']; ?>" alt="<?php echo $timeline_image['alt']; ?>" />
						</div>

						<p><?php echo $timeline_image['caption']; ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
<?php endif; ?>

<?php if(get_field('about_additional_content')): ?>
	<div class="additional-section spacing-2">
		<div class="wrapper overflow">
			<div class="float-left">
				<?php the_field('about_additional_content'); ?>
			</div>

			<?php if( get_field('about_additional_content_image') ): ?>
				<?php $image = get_field('about_additional_content_image'); ?>

				<div class="float-right">
					<div class="img-wrap">
						<img src="<?php echo $image['sizes']['large-2']; ?>" alt="<?php echo $image['alt']; ?>" />
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
<?php endif; ?>

<?php get_footer(); ?>
