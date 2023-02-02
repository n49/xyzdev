<?php /* Template Name: Landing */ get_header(); ?>
	<script type="text/javascript">
		/* Facebook Form Submission */

		document.addEventListener( 'wpcf7submit', function( event ) {
			event.preventDefault();
			if ( '21817' == event.detail.contactFormId ) {
				const pageUrl = window.location.href;
				const hubSpotCookie = document.cookie.split('; ').find(row => row.startsWith('hubspotutk=')).split('=')[1];
				const data = Object.fromEntries(new FormData(event.target).entries());
				var hubSpotData = {
					  "fields": [
						{
						  "objectTypeId": "0-1",
						  "name": "email",
						  "value": data['email']
						},
						{
						  "objectTypeId": "0-1",
						  "name": "firstname",
						  "value": data['first-name']
						},
						{
						  "objectTypeId": "0-1",
						  "name": "lastname",
						  "value": data['last-name']
						},
						{
						  "objectTypeId": "0-1",
						  "name": "phone",
						  "value": data['phone']
						},
						{
						  "objectTypeId": "0-1",
						  "name": "location",
						  "value": data['location']
						},
						{
						  "objectTypeId": "0-1",
						  "name": "best_time",
						  "value": data['best-time-to-contact']
						}
					  ],
					 "context": {
						"hutk": hubSpotCookie,
						"pageUri": pageUrl,
						"pageName": "XYZ Storage - Facebook Promotion"
					 },
					  "legalConsentOptions": {
						"consent": {
						  "consentToProcess": data['privacy[]'] === "I accept" ? true : false,
						  "text": "I agree to allow XYZ storage terms and conditions",
						}
					  }
					};

				$.ajax({
					url: "https://api.hsforms.com/submissions/v3/integration/submit/21102818/7da2593c-7bfb-422a-a77f-ec4087d45d21",
					type: "post",
					headers: {
						'Content-Type': 'application/json',
					},
					data: JSON.stringify({
						fields: hubSpotData.fields,
						context: hubSpotData.context,
						legalConsentOptions: hubSpotData.legalConsentOptions
					}),
					success: function (response) {
						console.log('success', response);
					},
					error: function(jqXHR, textStatus, errorThrown) {
           				console.log(textStatus, errorThrown);
        			}
				});
			}
		}, false );
	</script>

	<?php $amp = isset($_GET["amp"]); ?>

	<?php if(get_field('info_content')): ?>
		<div class="section simple dark-grey first has-bgr bgr-cover no-lazy" style="background-image: url(<?php the_field('info_background'); ?>); ">
			<div class="wrapper visible">
				<div class="columns columns-2 line">
					<div class="col col-1">
						<?php if(get_field('info_image')): ?>
							<?php $image = get_field('info_image'); ?>

							<div class="img-wrap normal" style="padding-top: <?php the_field('info_background_padding'); ?>px;">
								<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
							</div>
						<?php endif; ?>
					</div>

					<div class="col col-2 has-form form-fixed">
						<?php the_field('info_content'); ?>
					</div>
				</div>
			</div>
		</div>

		<?php if(have_rows('info_features')): ?>
			<div class="section simple grey last" style="background-color: <?php the_field('info_background_color'); ?>;">
				<div class="wrapper">
					<div class="columns columns-2 line text-white">
						<div class="col col-1 text-center">
							<?php if(get_field('info_title')): ?>
								<h2>
									<?php the_field('info_title'); ?>
								</h2>
							<?php endif; ?>

							<div class="columns columns-3">
								<?php while(have_rows('info_features')): the_row(); ?>
									<div class="col">
										<?php if(get_sub_field('info_features_icon')): ?>
											<?php $image = get_sub_field('info_features_icon'); ?>

											<div class="img-wrap normal centered height">
												<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
											</div>
										<?php endif; ?>

										<?php if(get_sub_field('info_features_title')): ?>
											<p>
												<?php the_sub_field('info_features_title'); ?>
											</p>
										<?php endif; ?>
									</div>
								<?php endwhile; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>
	<?php endif; ?>

	<div class="reviews-section grey has-bgr-small narrow spacing">
		<div class="wrapper overflow" style="background-image: url('<?php the_field('reviews_background'); ?>');">
			<div class="title-wrap float-left">
				<?php if(get_field('reviews_content')): ?>
					<?php the_field('reviews_content'); ?>
				<?php endif; ?>
			</div>

			<div class="content float-right">
				<?php get_template_part('loop-reviews-2'); ?>
			</div>
		</div>
	</div>

	<?php if(get_field('home_storage_related') && !$amp): ?>
		<?php $terms = get_field('home_storage_related'); ?>

		<div class="solutions-section grey spacing" style="background-image: url('<?php the_field('home_storage_background'); ?>');">
			<div class="wrapper">
				<?php if(get_field('home_storage_subtitle')): ?>
					<p class="title-5 icon icon-arrow white">
						<?php the_field('home_storage_subtitle'); ?>
					</p>
				<?php endif; ?>

				<?php if(get_field('home_storage_title')): ?>
					<h2 class="title">
						<?php the_field('home_storage_title'); ?>
					</h2>
				<?php endif; ?>

				<div class="columns columns-3 flex flex-normal keep-flex features">
					<?php foreach($terms as $term): ?>
						<div class="col margin">
							<?php if( get_field('unit_category_thumbnail', $term) ): ?>
								<?php $image = get_field('unit_category_thumbnail', $term); ?>

								<a class="img-wrap transparent" href="<?php bloginfo('url'); ?>/storage-sizes#<?php echo $term->slug; ?>" title="<?php echo $image['alt']; ?>">
									<img src="<?php echo $image['sizes']['medium']; ?>" alt="<?php echo $image['alt']; ?>" />
								</a>
							<?php endif; ?>

							<div class="content">
								<h3 class="title title-4 margin">
									<?php echo $term->name; ?>
								</h3>

								<?php if(get_field('unit_category_size', $term)): ?>
									<p>
										<?php the_field('unit_category_size', $term); ?>
									</p>
								<?php endif; ?>

								<p>
									<?php echo $term->description; ?>
								</p>

								<a class="btn btn-white-2" href="#form">
									<?php _e('reserve a unit', 'html5blank'); ?>
								</a>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<?php if(get_field('cta_title')): ?>
		<div class="cta-wrap simple" style="background-image: url(<?php the_field('cta_background_image'); ?>); background-color: <?php the_field('cta_background_color'); ?>">
			<div class="wrapper text-center">
				<h2>
					<?php the_field('cta_title'); ?>
				</h2>

				<?php if(get_field('cta_link')): ?>
					<?php
						$link = get_field('cta_link');
						$link_url = $link['url'];
						$link_title = $link['title'];
						$link_target = $link['target'] ? $link['target'] : '_self';
					?>

					<a class="btn btn-white" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
						<?php echo esc_html($link_title); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>

	<?php if(have_rows('reasons')): ?>
		<div class="section simple white large">
			<div class="wrapper">
				<?php if(get_field('reasons_subtitle')): ?>
					<p class="title-5 icon icon-arrow">
						<?php the_field('reasons_subtitle'); ?>
					</p>
				<?php endif; ?>

				<?php if(get_field('reasons_title')): ?>
					<h2>
						<?php the_field('reasons_title'); ?>
					</h2>
				<?php endif; ?>

				<div class="columns columns-4 services flex flex-normal top-m">
					<?php while(have_rows('reasons')): the_row(); ?>
						<?php if(get_sub_field('reasons_link')): ?>
							<?php
								$link = get_sub_field('reasons_link');
								$link_url = $link['url'];
								$link_title = $link['title'];
								$link_target = $link['target'] ? $link['target'] : '_self';
							?>
						<?php endif; ?>

						<div class="col">
							<div class="content-wrap text-center">
								<?php if( get_sub_field('reasons_icon') ): ?>
									<?php $image = get_sub_field('reasons_icon'); ?>

									<?php if(get_sub_field('reasons_link')): ?>
										<a class="img-wrap disable no-radius round" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
									<?php else: ?>
										<div class="img-wrap disable no-radius round">
									<?php endif; ?>

										<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />

										<?php if(get_sub_field('reasons_title')): ?>
											<h3 class="title title-5">
												<?php the_sub_field('reasons_title'); ?>
											</h3>
										<?php endif; ?>

									<?php if(get_sub_field('reasons_link')): ?>
										</a>
									<?php else: ?>
										</div>
									<?php endif; ?>
								<?php endif; ?>

								<?php if(get_sub_field('reasons_link')): ?>
									<div class="content round">
										<?php
											$link = get_sub_field('reasons_link');
											$link_url = $link['url'];
											$link_title = $link['title'];
											$link_target = $link['target'] ? $link['target'] : '_self';
										?>

										<a href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
											<?php echo esc_html($link_title); ?>
										</a>
									</div>
								<?php endif; ?>
							</div>
						</div>
					<?php endwhile; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<?php if(get_field('reservation_content')): ?>
		<div id="form" class="solutions-section grey spacing has-form has-after">
			<div class="wrapper narrow-2">
				<?php the_field('reservation_content'); ?>
			</div>
		</div>

		<?php if(get_field('reservation_notice')): ?>
			<div class="section white simple">
				<div class="wrapper">
					<?php the_field('reservation_notice'); ?>
				</div>
			</div>
		<?php endif; ?>
	<?php endif; ?>

<?php get_footer(); ?>
