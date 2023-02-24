<?php
/*
Template Name: Storage
Template Post Type: locations
*/ get_header();

$parent = $post->post_parent;
$this_location_name = get_the_title();

$locations_opioID = array(
	                          'Toronto West' => "jhrvg39uijr5q9mo3",
	                          'Etobicoke' => "jhrtgyf3izoyc0rih",
	                          'Scarborough' => "jhrtqujzw0ystxyfk",
	                          'Mobile Storage' => "jhmaw5br30o4p7w5g",
	                          'Toronto Midtown' => "jhrur79akaghyr692",
	                          //'Toronto Downtown' => "jhmaw5br30o4p7w5g",
	                          'North York' => "ji6b4k14upi10t2pc"
	                        );
$this_location_id = $locations_opioID[$this_location_name];
$API_KEY = 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6IjU3MmE0NDI3NDdhYjg5YzkzYzE0MzVlNV9UVG55Z2E0NEBvcC5pbyIsInVzZXJfbWV0YWRhdGEiOnsiX2lkIjoiajJnZWdwYzhzZnd3Y2NzYWcifSwiaXNzIjoiaHR0cHM6Ly9vcC5pby8iLCJzdWIiOiJhcGlrZXl8ajJnZWdwYzhzZnd3Y2NzYWciLCJhdWQiOiJobTNsZmlvUHAwVWhqQnNkV0V6MG9nTW9zVVNDV2p3SCIsImV4cCI6MjE0NTkxNjgwMCwiaWF0IjoxNDk0MjY0NDM1LCJyZXNvdXJjZXMiOlt7InJvdXRlIjoiZW50aXRpZXMifSx7InJvdXRlIjoicHJvcGVydGllcyIsInF1ZXJ5IjpbbnVsbF19XX0.IGrh8vnO1o9SLfmhuEzPNSkiLYhsxcIvti9Pq-9N65s';
  $BASE_URL = 'https://op.io/api/entities/';
  $ARGS = '?scopes=Review&subSkip=0&subLimit=25';

  $SCARBOROUGH = 'jhrtqujzw0ystxyfk';
  $TORONTO_WEST = 'jhrvg39uijr5q9mo3';
  $TORONTO = 'jhrur79akaghyr692';
  $ETOBICOKE = 'jhrtgyf3izoyc0rih';
  $NORTH_YORK = 'ji6b4k14upi10t2pc';
  $MOBILE = 'jhmaw5br30o4p7w5g';

  $locations = array($SCARBOROUGH, $TORONTO_WEST, $TORONTO ,$ETOBICOKE, $NORTH_YORK, $MOBILE);

  $all_reviews = array();

  $curl = curl_init();
    curl_setopt_array($curl, array(
  	  CURLOPT_URL => $BASE_URL.$this_location_id.$ARGS,
  	  CURLOPT_RETURNTRANSFER => true,
  	  CURLOPT_MAXREDIRS => 10,
  	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  	  CURLOPT_CUSTOMREQUEST => "GET",
  	  CURLOPT_HTTPHEADER => array($API_KEY)
  	  )
  	);
    $response = curl_exec($curl);
    $json = json_decode($response, true);
    //var_dump($json[0]['rating']);
    $rating = $json[0]['rating'];


?>
<script type="text/javascript">
  function setCookiesM(dimensions, rent, length, width, height, price){
		document.cookie = "unitLocation=5;path=/customer-details";
    var url = "<?php bloginfo('url'); ?>/customer-details/";
    var discountNew = parseInt(document.getElementById("savings-new").innerHTML);
    var quantity = parseInt(document.getElementById("quant").innerHTML);
    document.cookie = 'unitDimensions='+dimensions+'; path=/customer-details';
    document.cookie = 'length='+length+'; path=/customer-details';
    document.cookie = 'width='+width+'; path=/customer-details';
    document.cookie = 'height='+height+'; path=/customer-details';
    document.cookie = 'unitRent='+(price-discountNew/quantity)+'; path=/customer-details';
    document.cookie = 'unitPrice='+(price-discountNew/quantity)+'; path=/customer-details';
    window.location = url;
  }
</script>
<div class="header-wrap has-content">
	<script type="text/javascript">
		var locationName = "<?php echo the_title(); ?>";
	</script>
	<div class="wrapper">
		<?php if(get_field('location_new') === 'yes'): ?>
			<span class="new">
				<?php the_field('location_new_label'); ?>
			</span>
		<?php endif; ?>

		<h1 class="title">
			<?php if(get_field('location_custom_title')): ?>
				<?php the_field('location_custom_title'); ?>
			<?php else: ?>
				<?php the_title(); ?>
			<?php endif; ?>
		</h1>

		<?php if(get_field('location_rating')): ?>
			<?php
				//$rating = get_field('location_rating');
				$width = $rating / 5 * 100;
			?>

			<div class="rating-wrap">
				<div class="rating">
					<div style="width: <?php echo $width; ?>%"></div>
				</div>

				<span>
					<?php /*the_field('location_rating');*/ echo $rating ?>/5
				</span>
			</div>
		<?php endif; ?>

		<?php if(get_field('location_custom_address')): ?>
			<div class="info">
				<span class="icon location"><?php the_field('location_custom_address'); ?></span>
			</div>
		<?php endif; ?>

		<?php if(get_field('location_phone') || get_field('location_hours')): ?>
			<div class="info">
				<?php if(get_field('location_phone')): ?>
					<span class="btn-tooltip icon phone" href="#"><?php _e('show phone', 'html5blank'); ?>
						<div class="tooltip icon">
							<span class="label">
								<?php _e('Phone', 'html5blank'); ?>
							</span>

							<a href="tel:<?php the_field('location_phone'); ?>" class="num">
								<?php the_field('location_phone'); ?>
							</a>
						</div>
					</span>
				<?php endif; ?>

				<?php if(get_field('location_email')): ?>
					<span class="sep">|</span>

					<span class="btn-tooltip icon email" href="#"><?php _e('show email', 'html5blank'); ?>
						<div class="tooltip icon">
							<span class="label">
								<?php _e('Email', 'html5blank'); ?>
							</span>

							<a href="mailto:<?php the_field('location_email'); ?>" class="num">
								<?php the_field('location_email'); ?>
							</a>
						</div>
					</span>
				<?php endif; ?>

				<?php if( have_rows('location_hours') ): ?>
					<span class="sep">|</span>

					<span class="btn-tooltip icon hours"><?php _e('show hours', 'html5blank'); ?>
						<div class="tooltip icon">
							<?php while( have_rows('location_hours') ): the_row(); ?>
								<div>
									<span class="label">
										<?php the_sub_field('location_hours_title'); ?>
									</span>

									<span class="num">
										<?php the_sub_field('location_hours_content'); ?>
									</span>
								</div>
							<?php endwhile; ?>

							<?php if(get_field('location_hours_notice')): ?>
								<div class="notice">
									<?php the_field('location_hours_notice'); ?>
								</div>
							<?php endif; ?>
						</div>
					</span>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</div>

<div class="main-container no-padding">
	<div class="wrapper">
		<div class="gallery-wrap overflow">
			<?php $page_id = 505; ?>

			<?php if( get_field('storage_gallery', $page_id) ): ?>
				<div class="float-left">
					<?php
						$images = get_field('storage_gallery', $page_id);
						$count = count($images);
					?>

					<div class="wp-slick-slider has-lightbox slide-count-<?php echo $count; ?>">
						<?php foreach($images as $image): ?>
							<div class="img-wrap">
								<a href="<?php echo $image['url']; ?>">
									<img src="<?php echo $image['sizes']['medium-2']; ?>" alt="<?php echo $image['alt']; ?>" />

									<span class="btn btn-grey"><?php _e('view all photos', 'html5blank'); ?></span>
								</a>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if( get_field('storage_gallery_content', $page_id) ): ?>
				<div class="float-right content normal">
					<?php the_field('storage_gallery_content', $page_id); ?>

					<?php if( have_rows('storage_gallery_units', $page_id) ): ?>
						<div class="columns buttons">
							<?php while( have_rows('storage_gallery_units', $page_id) ): the_row(); ?>
								<div class="col">
									<h4 class="title">
										<?php the_sub_field('storage_gallery_units_title'); ?>
									</h4>

									<p>
										<?php the_sub_field('storage_gallery_units_description'); ?>
									</p>

									<?php if( have_rows('storage_gallery_units_buttons', $page_id) ): ?>
						            	<div class="btn-wrap">
						            		<?php while( have_rows('storage_gallery_units_buttons', $page_id) ): the_row(); ?>
						                        <a class="btn" href="<?php the_sub_field('storage_gallery_units_buttons_url'); ?>">
						                            <?php if(get_sub_field('storage_gallery_units_buttons_icon')): ?>
						                                <?php $icon = get_sub_field('storage_gallery_units_buttons_icon'); ?>

						                                <img src="<?php echo $icon['url']; ?>" alt="<?php echo $icon['alt']; ?>" />
						                            <?php endif; ?>

						                            <?php the_sub_field('storage_gallery_units_buttons_title'); ?>
						                        </a>
						            		<?php endwhile; ?>
						            	</div>
						            <?php endif; ?>
								</div>
							<?php endwhile; ?>
						</div>
					<?php endif; ?>

					<?php if(get_field('storage_gallery_units_related', $page_id)): ?>
						<div class="related">
							<?php
								$post = get_field('storage_gallery_units_related', $page_id);
								setup_postdata($post);
							?>

							<?php if(get_field('unit_amount')): ?>
								<?php
									$price = get_field('unit_amount');
									$price_edit = number_format($price, 2, '.', '');
								?>

								<div class="counter" data-save="<?php the_field('unit_saving'); ?>" data-price="<?php the_field('unit_amount'); ?>">
									<p class="title-5">
										<?php _e('number of units you need', 'html5blank'); ?>
									</p>

									<span class="icon sign dec"></span>
									<span class="num">1</span>
									<span class="icon sign inc"></span>
								</div>

								<p class="title">
									<?php _e('monthly price', 'html5blank'); ?>
								</p>

								<h3 class="title-4 price">
									$<span><?php echo $price_edit; ?></span>/mo
								</h3>
							<?php endif; ?>

							<?php if(get_field('unit_saving')): ?>
								<p class="savings">
									<?php _e('Savings of', 'html5blank'); ?> $<span class="save" id="savings-new">0</span> <?php _e('for', 'html5blank'); ?> <span class="num" id="quant">1</span> <?php _e('units', 'html5blank'); ?>
								</p>
							<?php endif; ?>

							<a class="btn btn-reserve mobile"
							   data-unit="19"
							   data-location="5"
							   data-amount="1"
							   onclick="setCookiesM(`5'd x 8'w x 7'h` , 135.00 , 8 , 5 , 7 , 135.00)">
								<?php _e('reserve a unit', 'html5blank'); ?>
						  	</a>

							<p class="delivery">
								<?php _e('need a re-delivery?', 'html5blank'); ?> <a href="https://www.xyzstorage.com/contact-us/" target="_self"><?php _e(' contact us', 'html5blank'); ?></a>
							</p>

							<?php wp_reset_postdata(); ?>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<?php
		$reviews = array();
		$review_limit = 0;

		foreach($json[0]['reviews'] as $key => $rev){
			if($review_limit < 6 && $rev['totalVideos'] > 0){
				array_push($reviews, $rev);
				$review_limit++;
			}
		}
	?>
	<?php if(FALSE): ?>
	<div class="reviews-section review-slider spacing">
		<div class="wrapper">
			<p class="title-5 icon icon-arrow">
				<?php _e('reviews', 'html5blank'); ?>
			</p>

			<h2 class="title">
				<?php _e('a few words from those who made space', 'html5blank'); ?>
			</h2>

			<div class="columns columns-3 grey flex reviews">
				<?php foreach ($reviews as $review) {?>
					<article id="post-<?php echo $review['_id'];?>" class="col post-617 reviews type-reviews status-publish hentry slick-slide" style="width: 374px !important;">
						<a class="content">
							<h5 class="title">
								<?php
									$current_location = str_replace("All Canadian ","",$review['location']);
									$current_location = str_replace("Self-Storage ","",$current_location);
									echo $current_location;
								?>
							</h5>

							<?php //https://videocdn.n49.ca/mp4sdpad480p/6d87c4b4f8465720ef536fe8eefa173d.mp4#t=0.1  ?>

							<div class="img-wrap">
								<?php
									if($review["totalVideos"] > 0){
										$videoURL = "https://videocdn.n49.ca/mp4sdpad480p/".$review['videos'][0]['videoId'].".mp4#t=0.1";
								?>
									<video preload="auto" controls style="width: 100%">
										<source src="<?php echo $videoURL; ?>"/>
									</video>
								<?php } ?>
							</div>

							<p>
								<?php echo $review['content']; ?>
							</p>

							<div class="bottom">
								<p class="author">
									<?php echo $review['user']['fullName']; ?>
								</p>

								<div class="rating-wrap large">
									<div class="rating">
										<div style="width: <?php echo $review['rating'] / 5 * 100; ?>%"></div>
									</div>
								</div>
							</div>
						</a>
					</article>
				<?php } ?>

				<?php wp_reset_query(); ?>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php if(get_field('location_reviews_content') && $this_location_name != 'Toronto Downtown'): ?>
		<div id="reviews" class="reviews-section spacing" style="background-image: url('<?php the_field('location_reviews_background'); ?>');">
			<div id="opio-review-feed" class="wrapper overflow">
				<div class="title-wrap float-left">
					<p class="title-5 icon icon-arrow">
						<?php _e('reviews', 'html5blank'); ?>
					</p>
						<h2 class="title">
							<?php echo $this_location_name; ?> <br>
							<?php echo 'self storage reviews'?>
						</h2>
				</div>
				<div class="content float-right">
         <?php
				 		the_field('location_reviews_content');
          ?>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>

<?php get_footer(); ?>
