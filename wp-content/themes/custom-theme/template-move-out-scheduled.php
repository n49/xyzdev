<?php /* Template Name: Move Out Scheduled*/ get_header(); ?>
<?php $reschedule = $_GET['reschedule'] == '1'; ?>

<div class="header-wrap">
	<div class="wrapper">
		<h1 class="title">
			<?php
			 	if($reschedule != '1'){
					the_title();
				}
				else {
					echo "move out rescheduled";
				}
			?>
		</h1>
	</div>
</div>

<div class="main-container overflow" style="background-image: url('<?php the_field('page_background_image'); ?>');">
	<div class="wrapper overflow">
		<div class="sidebar padding-top">
			<?php if(get_field('notice_title')): ?>
				<h2>
					<?php the_field('notice_title'); ?>
				</h2>
			<?php endif; ?>

			<?php
				$unit = htmlspecialchars($_GET['unit']);
				$location = htmlspecialchars($_GET['location']);
				$date1 = htmlspecialchars($_GET['date']);
				$date2 = htmlspecialchars($_GET['date2']);

				if($date2){
					$date = $date2;
				} else {
					$date = $date1;
				}
			?>

			<div class="columns columns-2 single">
				<div class="col">
					<p>
						<strong class="normal <?php echo $reschedule; ?>">
							<?php _e('move out date', 'html5blank'); ?>
						</strong>

						<?php echo $date; ?>
					</p>

					<p class="last">
						<strong class="normal">
							<?php _e('location', 'html5blank'); ?>
						</strong>

						<?php echo $location; ?>
					</p>
				</div>

				<div class="col">
					<p>
						<strong class="normal">
							<?php _e('unit number', 'html5blank'); ?>
						</strong>

						<?php echo $unit; ?>
					</p>
				</div>
			</div>

			<?php if(get_field('notice')): ?>
				<?php the_field('notice'); ?>
			<?php endif; ?>
		</div>

		<main role="main" class="padding">
			<div class="wpcf7 top">
				<div class="wpcf7-response-output wpcf7-mail-sent-ok short">
					<?php
					if($reschedule != '1'){
						_e('move out scheduled successfully', 'html5blank');
					}
					else {
						_e('move out rescheduled successfully', 'html5blank');
					}
					?>
				</div>
			</div>

			<div class="section white">
				<?php if( have_rows('steps') ): ?>
					<h2>
						<?php _e('what\'s next', 'html5blank'); ?>
					</h2>

					<div class="columns columns-3 margin steps">
						<?php while( have_rows('steps') ): the_row(); ?>
							<div class="col">
								<?php if( get_sub_field('steps_icon') ): ?>
									<?php $image = get_sub_field('steps_icon'); ?>

									<div class="img-wrap">
										<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
									</div>
								<?php endif; ?>

								<h3 class="title-4">
									<?php the_sub_field('steps_title'); ?>
								</h3>

								<p>
									<?php the_sub_field('steps_content'); ?>
								</p>
							</div>
						<?php endwhile; ?>
					</div>
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
