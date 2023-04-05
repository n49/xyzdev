<?php
/*
Template Name: Location
Template Post Type: locations
*/ get_header();

$parent = $post->post_parent;
$this_location_name = get_the_title($parent);

$locations_opioID = array(
	                          'Toronto West' => "jhrvg39uijr5q9mo3",
	                          'Etobicoke' => "jhrtgyf3izoyc0rih",
	                          'Scarborough' => "jhrtqujzw0ystxyfk",
	                          'Mobile Storage' => "jhmaw5br30o4p7w5g",
	                          'Toronto Midtown' => "jhrur79akaghyr692",
	                          'Toronto Downtown' => "jwjbbu0fdl26ixz0v",
	                          'Mississauga' => "ji6b4k14upi10t2pc"
	                        );
$this_location_id = $locations_opioID[$this_location_name];
$locations_codes = array(
                          'Scarborough' => 1,
                          'Mississauga' => 2,
                          'Toronto West' => 3,
                          'Etobicoke' => 4,
                          'Mobile Storage' => 5,
                          'Toronto Midtown' => 6,
                          'Toronto Downtown' => 7,
                        );
$this_location_code = $locations_codes[$this_location_name];
	//var_dump($this_location_code, 'got it');

$API_KEY = 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6IjU3MmE0NDI3NDdhYjg5YzkzYzE0MzVlNV9UVG55Z2E0NEBvcC5pbyIsInVzZXJfbWV0YWRhdGEiOnsiX2lkIjoiajJnZWdwYzhzZnd3Y2NzYWcifSwiaXNzIjoiaHR0cHM6Ly9vcC5pby8iLCJzdWIiOiJhcGlrZXl8ajJnZWdwYzhzZnd3Y2NzYWciLCJhdWQiOiJobTNsZmlvUHAwVWhqQnNkV0V6MG9nTW9zVVNDV2p3SCIsImV4cCI6MjE0NTkxNjgwMCwiaWF0IjoxNDk0MjY0NDM1LCJyZXNvdXJjZXMiOlt7InJvdXRlIjoiZW50aXRpZXMifSx7InJvdXRlIjoicHJvcGVydGllcyIsInF1ZXJ5IjpbbnVsbF19XX0.IGrh8vnO1o9SLfmhuEzPNSkiLYhsxcIvti9Pq-9N65s';
  $BASE_URL = 'https://op.io/api/entities/';
  $ARGS = '?scopes=Review&subSkip=0&subLimit=25';

	$amp = isset($_GET["amp"]);

	$rating = 5;
	if($amp){
	$curl = curl_init();
	 curl_setopt_array($curl, array(
			CURLOPT_URL => $BASE_URL.$this_location_id,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array($API_KEY),
			CURLOPT_VERBOSE => true,
			)
		  );
		  $response = curl_exec($curl);
		  $json = json_decode($response, true);
	    $rating = $json[0]['aggregateRating']['5734f48a0b64d7382829fdf7']["average"];
	}

?>
<script>
	jQuery(document).ready(function($){

	var locationName = "<?php the_title(); ?>";
	var phoneicon = document.getElementById("showPhoneButton");
    var email_icon = document.getElementById("showEmailIcon");
	var hours_icon = document.getElementById("ShowHoursButton");
	var directions_icon = document.getElementById("showLocationButton");
				console.log('got location name', phoneicon);

	if(phoneicon) {

		phoneicon.addEventListener('click', function(x) {
					console.log('got phone icon', phoneicon);

		gtag('event', 'Phone ' + locationName);
	});
	}
	
	if(email_icon) {
		email_icon.addEventListener('click', function(x) {
		console.log('got it', x);
		gtag('event', 'Email ' + locationName);
	});
	}
	
	if(hours_icon) {
		hours_icon.addEventListener('click', function(x) {
		gtag('event', 'Hours ' + locationName);
	});
	}
	
	if(directions_icon) {
	
		directions_icon.addEventListener('click', function(x) {
		gtag('event', 'Directions ' + locationName);
	});
	}
	});


	$(document).ready(function(){
			$.ajax({url: "https://xyzstorage.com/wp-json/opio/video-review/<?php echo $this_location_code ?>/", success: function(result){
				document.getElementById("stars").style.width = result.rating/ 5 * 100+"%";
				document.getElementById("location-rating").innerHTML = result.rating+"/5";
				document.getElementById("video-review").src = "https://videocdn.n49.ca/mp4sdpad480p/"+result.video.videos[0].videoId+".mp4#t=0.1";
				document.getElementById("review-content").innerHTML = result.video.content.substring(0, 80)+"...";
				document.getElementById("review-name").innerHTML = result.video.user.fullName;
				document.getElementById("video-stars").style.width = result.video.rating;
			}});
	});
</script>
<div class="header-wrap has-content">
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
		<?php if($amp): ?>
			<div class="rating-wrap">
				<div class="rating">
					<div id="stars" style="width: <?php echo $rating/5*100; ?>%"></div>
				</div>
				<span id="location-rating"><?php echo $rating; ?>/5</span>
			</div>
		  <?php else: ?>
			<div class="rating-wrap">
				<div class="rating">
					<div id="stars" style="width: 100%"></div>
				</div>
				<span id="location-rating"></span>
			</div>
			<?php endif; ?>
		<?php if(get_field('location_address') || get_field('location_custom_address')): ?>
			<div class="info">
				<?php if(get_field('location_address')): ?>
					<?php
						$location = get_field('location_address');
						$address = $location['address'];
					?>
					<span class="icon location"><?php echo $address; ?></span>
					<a id="showLocationButton" class="btn-directions margin" href="https://www.google.com/maps/place/<?php echo $address; ?>" target="_blank"><?php _e('get directions', 'html5blank'); ?></a>
				<?php endif; ?>
				<?php if(get_field('location_address') && get_field('location_custom_address')): ?>
					<span class="sep">|</span>
				<?php endif; ?>
				<?php if(get_field('location_custom_address')): ?>
					<span class="icon location-2">
						<?php the_field('location_custom_address'); ?>
					</span>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if(get_field('location_phone') || get_field('location_hours')): ?>
			<div class="info">
				<?php if(get_field('location_phone')): ?>
					<?php if(!$amp): ?>
					<span id="showPhoneButton" class="btn-tooltip icon phone" href="#"><?php _e('show phone', 'html5blank'); ?>
						<div class="tooltip icon">
							<span class="label phone">
								<?php _e('Phone', 'html5blank'); ?>
							</span>

							<a href="tel:<?php the_field('location_phone'); ?>" class="num">
								<?php the_field('location_phone'); ?>
							</a>
						</div>
					</span>
				<?php else: ?>
					<span class="btn-tooltip icon phone">
					  <a class="call-phone-location" href="tel:<?php the_field('location_phone'); ?>">
					    <?php the_field('location_phone'); ?>
					  </a>
					</span>
					<?php endif; ?>
				<?php endif; ?>

				<?php if(get_field('location_email')): ?>
					<span class="sep">|</span>
					<?php if(!$amp): ?>
					<span id="showEmailIcon" class="btn-tooltip icon email" href="#"><?php _e('show email', 'html5blank'); ?>
						<div class="tooltip icon">
							<span class="label email">
								<?php _e('Email', 'html5blank'); ?>
							</span>

							<a href="mailto:<?php the_field('location_email'); ?>" class="num">
								<?php the_field('location_email'); ?>
							</a>
						</div>
					</span>
					<?php endif; ?>
					<?php if($amp): ?>
					 <span class="btn-tooltip icon email">
						<a class="send-email-location" href="mailto:<?php the_field('location_email'); ?>">
							<?php the_field('location_email'); ?>
						</a>
					 </span>
					<?php endif; ?>
				<?php endif; ?>

				<?php if( have_rows('location_hours') && !$amp ): ?>
					<span class="sep">|</span>

					<span id="ShowHoursButton" class="btn-tooltip icon hours"><?php _e('show hours', 'html5blank'); ?>
						<div class="tooltip icon">
							<?php while( have_rows('location_hours') ): the_row(); ?>
								<div>
									<span class="label hours">
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
				<?php elseif( have_rows('location_hours') && $amp ):?>
					<div class = "tooltip">
					 <amp-lightbox id="my-lightbox" layout="nodisplay">
						 <div class="lightbox" on="tap:my-lightbox.close" role="button" tabindex="0">
						 <div>
							 <?php while( have_rows('location_hours') ): the_row(); ?>
							 <div>
								 <div class="label hours lightbox-hours">
									 <?php the_sub_field('location_hours_title'); ?>
								 </div>

								 <div class="num lightbox-hours">
									 <?php the_sub_field('location_hours_content'); ?>
								 </div>
								 </div>
						 <?php endwhile; ?>

						 <?php if(get_field('location_hours_notice')): ?>
							 <div class="notice lightbox-hours lightbox-notice">
								 <?php the_field('location_hours_notice'); ?>
							 </div>
						 <?php endif; ?>
						 </div>
						 </div>
					 </amp-lightbox>
					 <span class="btn-tooltip icon hours" tabindex="-1" role="hours" on="tap:my-lightbox">
						 Hours
					 </span>
			 </div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</div>

<div class="main-container no-padding">
	<?php if(!$amp): ?>
	<div class="wrapper">
		<div class="gallery-wrap overflow">
			<?php if( get_field('location_gallery') ): ?>
				<div class="float-left">
					<?php
						$images = get_field('location_gallery');
						$count = count($images);
						$num = 0;
					?>

					<div class="has-lightbox-simple slide-count-<?php echo $count; ?>">
						<?php foreach($images as $image): ?>
							<?php
								$caption = '';
								$caption .= '<h3>' . $image['title'] . '</h3>';
								$caption .= '<p>' . $image['description'] . '</p>';

								$caption .= '<div>';
								$caption .= '<h3>'. get_the_title() . '</h3>';
								$caption .= esc_html('<a class="btn btn-yellow" href="' . get_the_permalink() . 'book-a-unit/">reserve a unit</a>');
								$caption .= '</div>';

								$num++;
							?>

							<?php if($num == 1) : ?>
								<div class="img-wrap">
									<a href="<?php echo $image['url']; ?>" data-html="<?php echo $image['alt']; ?>" data-sub-html="<?php echo $caption; ?>" title="<?php echo $image['alt']; ?>">
										<img src="<?php echo $image['sizes']['medium-2']; ?>" alt="<?php echo $image['alt']; ?>" />

										<span class="btn btn-grey"><?php _e('view all photos', 'html5blank'); ?></span>
									</a>
								</div>
							<?php else: ?>
								<a href="<?php echo $image['url']; ?>" data-html="<?php echo $image['alt']; ?>" data-sub-html="<?php echo $caption; ?>" title="<?php echo $image['alt']; ?>"></a>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>

			<div class="float-right">
				<?php if(get_field('storage_units')): ?>
					<?php get_template_part('loop-units-custom'); ?>
				<?php else: ?>
					<?php get_template_part('loop-units-location'); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
<?php endif; ?>

	<?php if($amp): ?>
		<amp-carousel layout="fixed-height" type="slides" height="300">
			<?php
				$images = get_field('location_gallery');
				$count = count($images);
			?>
			<?php foreach($images as $image): ?>
				<amp-img
					src="<?php echo $image['sizes']['medium-2']; ?>"
					width="450"
					height="300"
				></amp-img>
			<?php endforeach; ?>
		</amp-carousel>

		<?php get_template_part('loop-units-location'); ?>
	<?php endif;  ?>

	<div class="overview-section spacing" style="background-image: url('<?php the_field('location_overview_background'); ?>');">
		<?php if(!get_field('location_reviews_content')): ?>
			<?php $cols = 'no-first'; ?>
		<?php else: ?>
			<?php $cols = 'normal'; ?>
		<?php endif; ?>

		<div class="wrapper">
			<p class="title-5 icon icon-arrow">
				<?php _e('overview', 'html5blank'); ?>
			</p>

			<div class="columns columns-3 flex border <?php echo $cols; ?>">
				<?php if(get_field('location_reviews_content')): ?>
					<div class="col col-1">
						<h2 class="title">
							<?php _e('review score', 'html5blank'); ?>
						</h2>

						<img src="https://widgets.op.io/review-score?entity=<?php echo $this_location_id;?>&property=5734f48a0b64d7382829fdf7&style=xyz" alt="Review Score"></img>

						<?php if(!$amp): ?>
							<div style="width: 100%; background: #f1f1f1; margin: 5% 0%; padding: 20px;">
                				<div style="width: 100%; text-align: center; ">
									<video id="video-review" preload="auto" controls="" style="width: 100%;">
										<source src="">
									</video>

                  					<p id="review-content" style="font-size:14px;font-weight: 300;line-height:20px;padding-top:10px;text-align:left;margin-bottom:0px">
					                    <?php
					                      //$my_substring = $string = substr($video_review['content'],0,85);
					                      //echo $my_substring."...";
					                    ?>
									</p>

									<div id="review-name" style="font-size: 14px;">
										<?php //echo //$video_review['user']['fullName']; ?>
									</div>

									<div class="rating-wrap large">
										<div class="rating">
											<div id="video-stars" style="width: 100%"></div>
										</div>
									</div>
								</div>
							</div>

							<div style="text-align:center">
								<a href="#reviews"> <?php _e('see all reviews', 'html5blank'); ?>  </a>
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php if( have_rows('location_features') ): ?>
					<div class="col col-2">
						<h2 class="title">
							<?php _e('features', 'html5blank'); ?>

							<a href="#about">
								<?php _e('see all features', 'html5blank'); ?>
							</a>
						</h2>

						<?php if( have_rows('location_features') ): ?>
							<div class="features">
								<?php while( have_rows('location_features') ): the_row(); ?>
									<div class="feature">
										<?php if( get_sub_field('location_features_icon') ): ?>
											<?php $image = get_sub_field('location_features_icon'); ?>

											<div class="img-wrap no-radius">
												<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
											</div>
										<?php endif; ?>

										<div class="content">
											<?php the_sub_field('location_features_title'); ?>
										</div>
									</div>
								<?php endwhile; ?>
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php if(get_field('location_about_excerpt')): ?>
					<div class="col col-3">
						<h2 class="title">
							<?php _e('about', 'html5blank'); ?>

							<a href="#about">
								<?php _e('read more', 'html5blank'); ?>
							</a>
						</h2>

						<?php the_field('location_about_excerpt'); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>

    <script type="text/javascript">
      function setCookiesLocation(dimensions, rent, length, width, height, price,id){
		document.cookie = "unitLocation=<?php echo $this_location_code ?>;path=/rent-now";
		document.cookie = 'unitType='+id+'; path=/rent-now';
		var url = "<?php bloginfo('url'); ?>/rent-now/";
		document.cookie = 'unitDimensions='+dimensions+'; path=/rent-now';
		document.cookie = 'length='+length+'; path=/rent-now';
		document.cookie = 'width='+width+'; path=/rent-now';
		document.cookie = 'height='+height+'; path=/rent-now';
		document.cookie = 'unitRent='+rent+'; path=/rent-now';
		document.cookie = 'unitPrice='+price+'; path=/rent-now';
		var ssm_live = true;
		 if(ssm_live){
			 window.location = url;
		 }
		else{
			url ="https://e-storageonline.com/xyzstorage/Contents/RentOnline_TD.aspx"+
				"?FromPage=LocationDetails"+
				"&ReqPage=ALL"+
				"&LocId=<?php echo $this_location_code ?>"+
				"&UnitId="+id+
				"&Len="+length+"&Bre="+width+"&UnitRent="+rent.toFixed(2);
			window.open(url, '_blank');
		}

	  }

		function getUnitDetails(details_id,details_width,details_length,details_rent,details_height){
			var detailsUrl = "https://xyzstorage.com/wp-json/myplugin/v1/unit-details/<?php echo $this_location_code ?>/"+details_id+"/"+details_width+"/"+details_length+"/"+details_rent;
			$(document).ready(function(){
				$.ajax({url: detailsUrl, success: function(result){
					var priceToPay = 0;
					var internetPrice = parseFloat(result.InternetPrice);
					var rent = parseFloat(result.Rent);
					if(result.InternetPriceAvailable == '1'){
						document.getElementById("online-special-"+details_id).innerHTML = "online special";
						priceToPay = internetPrice;
						document.getElementById("internet-price-"+details_id).innerHTML = "$"+internetPrice.toFixed(2)+"/mo";
						document.getElementById("red-price-"+details_id).innerHTML = "$"+rent.toFixed(2);
					}
					else{
						document.getElementById("internet-price-"+details_id).innerHTML = "$"+rent.toFixed(2)+"/mo";
						priceToPay = rent;
					}
					if(result.AvailableUnits == '1'){
						document.getElementById("units-available-"+details_id).innerHTML = "Units available at this location";
						var unitsize = details_width+"'w x "+details_length+"'d x "+details_height+"'h";
						document.getElementById( "reserve-unit-"+details_id ).onclick = function(event){
							event.preventDefault();
							document.cookie = "unitLocation=<?php echo $this_location_code ?>;path=/customer-details";
							setCookiesLocation(unitsize, details_rent, details_length, details_width,details_height, priceToPay, details_id);}
					}
					else {
						document.getElementById("units-available-"+details_id).innerHTML = "No units at this location";
						document.getElementById( "reserve-unit-"+details_id ).innerHTML = "view more";
						document.getElementById( "reserve-unit-"+details_id ).onclick = function(){ window.location = window.location.href+"book-a-unit/"; };
					}
				}});
			});
		}


    </script>

	<?php if(!$amp): ?>
		<?php if(get_field('location_unit_popular_toggle') === 'active'): ?>
			<?php $posts = get_field('location_units_popular'); ?>

	    	<div class="popular-units spacing-5" id="popular-units">
				<div class="wrapper">
					<p class="title-5 icon icon-arrow">
						<?php _e('frequently booked units', 'html5blank'); ?>
					</p>

					<h2 class="title overflow">
						<?php if(get_field('location_unit_title')): ?>
							<?php the_field('location_unit_title'); ?>
						<?php endif; ?>

						<a class="btn green" href="<?php the_permalink(); ?>book-a-unit/">
							<?php _e('view all units', 'html5blank'); ?>
						</a>
					</h2>

					<div class="columns columns-4 flex units">
						<?php foreach($posts as $post): ?>
							<?php
								setup_postdata($post);

								$terms = wp_get_post_terms($post->ID, 'unit-category');
								$terms_size = get_field('unit_category_size', $terms[0]);
							?>

							<div id="post-<?php the_ID(); ?>" class="col columns columns-2 white flex units normal">
								<div class="col img">
									<?php if ( has_post_thumbnail()) : ?>
										<div class="img-wrap height large">
											<?php the_post_thumbnail('full'); ?>
										</div>
									<?php endif; ?>
								</div>

								<div class="col text desc">
									<h3 class="title title-4 margin">
										<?php echo $terms[0]->name; ?>
									</h3>

									<h4 class="title border">
										<?php echo $terms_size; ?>
									</h4>

									<p><?php _e('rent from', 'html5blank'); ?></p>

									<?php if(get_field('unit_amount_normal')): ?>
										<?php
											$price = get_field('unit_amount_normal');
											$price_edit = number_format($price, 2, '.', '');
										?>

										<h4 class="title">
											<del>$<?php echo $price_edit; ?></del>
										</h4>
									<?php endif; ?>

									<?php if(get_field('unit_amount')): ?>
										<?php
											$price = get_field('unit_amount');
											$price_edit = number_format($price, 2, '.', '');
										?>

										<h4 class="title margin">
											$<?php echo $price_edit; ?>/mo
										</h4>
									<?php endif; ?>

									<?php if(get_field('unit_custom_link')): ?>
										<?php
											$link = get_field('unit_custom_link');
											$link_url = $link['url'];
											$link_title = $link['title'];
											$link_target = $link['target'] ? $link['target'] : '_self';
										?>

										<a class="btn btn-reserve normal" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>" data-unit="<?php the_ID(); ?>" data-amount="1">
											<?php echo esc_html($link_title); ?>
										</a>
									<?php endif; ?>
								</div>
							</div>
						<?php endforeach; ?>

					</div>
				</div>
			</div>

			<?php wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly ?>
		<?php endif; ?>
	<?php endif; ?>

	<?php if(get_field('location_reviews_content')): ?>
		<div id="reviews" class="reviews-section spacing" style="background-image: url('<?php the_field('location_reviews_background'); ?>');">
			<div  class="wrapper overflow">
				<div class="title-wrap float-left">
					<p class="title-5 icon icon-arrow">
						<?php _e('reviews', 'html5blank'); ?>
					</p>
						<h2 class="title">
							<?php echo $this_location_name; ?> <br>
							<?php echo 'self storage reviews'?>
						</h2>
				</div>

				<div class="content float-right amp-reviews" >
						<?php if($this_location_id == 'jhrvg39uijr5q9mo3') { ?>
							<?php echo file_get_contents('http://34.225.94.59/allReviewFeed?entId='.$this_location_id); ?>
						<?php } else { ?>
							<?php echo file_get_contents('http://34.225.94.59/reviewFeed?entityid='.$this_location_id); ?>
						<?php } ?>

				</div>
			</div>
		</div>
	<?php endif; ?>

	<?php if(get_field('location_about_content')): ?>
		<div id="about" class="about-section spacing has-shadow" style="background-image: url('<?php the_field('location_about_background'); ?>');">
			<div class="wrapper">
				<p class="title-5 icon icon-arrow white">
					<?php _e('about', 'html5blank'); ?> <?php the_title(); ?> <?php _e('storage', 'html5blank'); ?>
				</p>

				<div class="columns columns-3 custom">
					<div class="col first">
						<?php the_field('location_about_content'); ?>
					</div>

					<?php if(get_field('location_about_location_features')): ?>
						<div class="col second">
							<?php the_field('location_about_location_features'); ?>
						</div>
					<?php endif; ?>

					<?php if(get_field('location_about_storage_features')): ?>
						<div class="col third">
							<?php the_field('location_about_storage_features'); ?>
						</div>
					<?php endif; ?>
				</div>

				<a class="btn-more normal" href="#">
					<?php _e('read more', 'html5blank'); ?>
				</a>
			</div>
		</div>
	<?php endif; ?>

	<?php get_template_part('module-faq'); ?>

	<?php if(!$amp) get_template_part('loop-locations-services-parent'); ?>

	<?php get_template_part('loop-locations-subpages-parent'); ?>

	<?php get_template_part('loop-locations-single'); ?>

	<?php get_template_part('module-logos'); ?>
</div>

<?php get_footer(); ?>
