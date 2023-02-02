<?php /* Template Name: Move Out */ get_header(); ?>
<?php 
	$res = isset($_GET['reschedule']);
?>
<div class="header-wrap">
	<div class="wrapper">
		<h1 class="title">
			<?php if($res){ echo "reschedule"; }?>
			<?php the_title(); ?>
		</h1>
	</div>
</div>

<div class="main-container overflow" style="background-image: url('<?php the_field('page_background_image'); ?>');">
	<div class="wrapper overflow <?php echo $res; ?>">
		<div class="sidebar">
			<?php if(get_field('notice')): ?>
				<?php the_field('notice'); ?>
			<?php endif; ?>
		</div>
		
		<main role="main" class="padding">
			<div id="form" class="section white has-normal-form">
				<?php if (have_posts()): while (have_posts()) : the_post(); ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<?php the_content(); ?>
					</article>
				<?php endwhile; ?>

				<?php endif; ?>
			</div>
		</main>
	</div>
</div>

<?php if( have_rows('faq') ): ?>
	<div id="faq" class="section white spacing">
		<div class="wrapper overflow">
			<h2>
				<?php _e('move out FAQ', 'html5blank'); ?>
			</h2>
			
			<div class="columns columns-3 margin">
				<?php while( have_rows('faq') ): the_row(); ?>
					<div class="col">
						<strong>
							<?php the_sub_field('faq_title'); ?>
						</strong>
						
						<?php the_sub_field('faq_content'); ?>
					</div>
				<?php endwhile; ?>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php get_footer(); ?>
