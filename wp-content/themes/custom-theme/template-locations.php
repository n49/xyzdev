<?php /* Template Name: Locations */ get_header(); ?>

<div class="header-wrap">
	<div class="wrapper">
		<h1 class="title">
			<?php the_title(); ?>
		</h1>
	</div>
</div>

<div class="main-container" style="background-image: url('<?php the_field('page_background_image'); ?>');">
	<div class="wrapper">
		<div class="tabs-wrap">
			<div class="tabs active">
				<!--<ul class="horizontal">
					<li>
						<a class="normal" href="#toronto">
							<p class="title-5">
								<?php _e('Toronto', 'html5blank'); ?>
							</p>
						</a>
					</li>

					<li>
						<a class="normal" href="#montreal">
							<p class="title-5">
								<?php _e('Montreal', 'html5blank'); ?>
							</p>
						</a>
					</li>
				</ul>-->

				<div id="toronto" class="tab">
					<?php get_template_part('loop-locations-toronto'); ?>
				</div>

				<!--<div id="montreal" class="tab">
					<?php //get_template_part('loop-locations-montreal'); ?>
				</div>-->
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>
