<?php
/*
Template Name: Service
Template Post Type: locations
*/ get_header();

$parent = $post->post_parent;

$API_KEY = 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6IjU3MmE0NDI3NDdhYjg5YzkzYzE0MzVlNV9UVG55Z2E0NEBvcC5pbyIsInVzZXJfbWV0YWRhdGEiOnsiX2lkIjoiajJnZWdwYzhzZnd3Y2NzYWcifSwiaXNzIjoiaHR0cHM6Ly9vcC5pby8iLCJzdWIiOiJhcGlrZXl8ajJnZWdwYzhzZnd3Y2NzYWciLCJhdWQiOiJobTNsZmlvUHAwVWhqQnNkV0V6MG9nTW9zVVNDV2p3SCIsImV4cCI6MjE0NTkxNjgwMCwiaWF0IjoxNDk0MjY0NDM1LCJyZXNvdXJjZXMiOlt7InJvdXRlIjoiZW50aXRpZXMifSx7InJvdXRlIjoicHJvcGVydGllcyIsInF1ZXJ5IjpbbnVsbF19XX0.IGrh8vnO1o9SLfmhuEzPNSkiLYhsxcIvti9Pq-9N65s';
$BASE_URL = 'https://op.io/api/entities/';
$ARGS = '?scopes=Review&subSkip=0&subLimit=25';

$locations_array = array(
													'Scarborough' => 'jhrtqujzw0ystxyfk',
													'Mississauga' => 'ji6b4k14upi10t2pc',
													'Toronto West' => 'jhrvg39uijr5q9mo3',
													'Etobicoke' => 'jhrtgyf3izoyc0rih',
													'Mobile Storage' => 'jhmaw5br30o4p7w5g',
													'Toronto Midtown' => 'jhrur79akaghyr692',
													'Toronto Downtown' => 'jwjbbu0fdl26ixz0v'
												);
$this_location_name = get_the_title($parent);
$this_location_code_opio_id = $locations_array[$this_location_name];

$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => $BASE_URL.$this_location_code_opio_id.$ARGS,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_HTTPHEADER => array($API_KEY)
		)
	);
	$response = curl_exec($curl);
	$json_ = json_decode($response, true);
	$OPIO= $json_[0];
	$OPIO['reviews'] = array_slice($OPIO['reviews'], 0, 6);

	$rating = $json_[0]['rating'];


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
?>

<div class="header-wrap has-content">
	<script type="text/javascript">
		var locationName = "<?php echo get_the_title($parent); ?>";
	</script>
	<?php if($this_location_code_opio_id=="jhrtgyf3izoyc0rih") {?>
		<script id="jsonldSchema" type="application/ld+json">
{
    "@context": "http://schema.org",
    "@type": "LocalBusiness",
    "name": "<?php echo $OPIO['name']; ?>",
    "image": "<?php echo $OPIO['coverImage']?>",
    "address": {
        "@type": "PostalAddress",
        "streetAddress": "<?php echo $OPIO['address']['address1']; ?>",
        "addressLocality": "<?php echo $OPIO['address']['city']; ?>",
        "addressRegion": "<?php echo $OPIO['address']['province']; ?>",
        "addressCountry": "<?php echo $OPIO['address']['country']; ?>",
        "postalCode": "<?php echo $OPIO['address']['postalCode']; ?>"
    },
    "telephone": true,
    "description": "<?php echo $OPIO['description']; ?>",
    "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "<?php echo $OPIO['aggregateRating']['5734f48a0b64d7382829fdf7']['average']; ?>",
        "reviewCount": "<?php echo $OPIO['aggregateRating']['5734f48a0b64d7382829fdf7']['total']; ?>"
    },
    "review": [
	<?php 
		foreach($OPIO['reviews'] as $key => $review) {
	?>
		    {
            "@type": "Review",
            "author": {
                "@type": "Person",
                "name": "<?php echo $review['user']['fullName']; ?>"
            },
            "description": "<?php echo $review['content']; ?>",
            "reviewRating": {
            "@type": "Rating",
            "ratingValue": "<?php echo $review['rating']; ?>"
            }
        }
		<?php if($key != count($OPIO['reviews']) - 1){
			echo ",";	
		}
		?>
	<?php } ?>
    ]
}
</script>		
	<?php } ?>



	<div class="wrapper">
		<?php if(get_field('location_new', $parent) === 'yes'): ?>
			<span class="new">
				<?php the_field('location_new_label', $parent); ?>
			</span>
		<?php endif; ?>

		<h1 class="title">
			<?php if(get_field('location_custom_title', $parent)): ?>
				<?php echo the_field('location_custom_title', $parent); ?>
			<?php else: ?>
				<?php echo get_the_title($parent); ?>
			<?php endif; ?>
		</h1>
		<script type="text/javascript">
      document.cookie = "unitLocation=<?php echo $this_location_code;?>;";
    </script>
		<?php
				//$rating = get_field('location_rating', $parent);
				$width = $rating / 5 * 100;
			?>

			<div class="rating-wrap rate">
				<div class="rating">
					<div style="width: <?php echo $width; ?>%"></div>
				</div>

				<span>
					<?php //the_field('location_rating', $parent);
						echo $rating;
					?>/5
				</span>
			</div>
		<?php if(get_field('location_address', $parent) || get_field('location_custom_address', $parent)): ?>
			<div class="info">
				<?php if(get_field('location_address', $parent)): ?>
					<?php
						$location = get_field('location_address', $parent);
						$address = $location['address'];
					?>

					<span class="icon location"><?php echo $address; ?></span>

					<a class="btn-directions margin" href="https://www.google.com/maps/place/<?php echo $address; ?>" target="_blank"><?php _e('get directions', 'html5blank'); ?></a>
				<?php endif; ?>

				<?php if(get_field('location_address', $parent) && get_field('location_custom_address', $parent)): ?>
					<span class="sep">|</span>
				<?php endif; ?>

				<?php if(get_field('location_custom_address', $parent)): ?>
					<span class="icon location-2">
						<?php the_field('location_custom_address', $parent); ?>
					</span>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if(get_field('location_phone', $parent) || get_field('location_hours', $parent)): ?>
			<div class="info">
				<?php if(get_field('location_phone', $parent)): ?>
					<span class="btn-tooltip icon phone" href="#"><?php _e('show phone', 'html5blank'); ?>
						<div class="tooltip icon">
							<span class="label">
								<?php _e('Phone', 'html5blank'); ?>
							</span>

							<a href="tel:<?php the_field('location_phone', $parent); ?>" class="num">
								<?php the_field('location_phone', $parent); ?>
							</a>
						</div>
					</span>
				<?php endif; ?>

				<?php if(get_field('location_email', $parent)): ?>
					<span class="sep">|</span>

					<span class="btn-tooltip icon email" href="#"><?php _e('show email', 'html5blank'); ?>
						<div class="tooltip icon">
							<span class="label">
								<?php _e('Email', 'html5blank'); ?>
							</span>

							<a href="mailto:<?php the_field('location_email', $parent); ?>" class="num">
								<?php the_field('location_email', $parent); ?>
							</a>
						</div>
					</span>
				<?php endif; ?>

				<?php if( have_rows('location_hours', $parent) ): ?>
					<span class="sep">|</span>

					<span class="btn-tooltip icon hours"><?php _e('show hours', 'html5blank'); ?>
						<div class="tooltip icon">
							<?php while( have_rows('location_hours', $parent) ): the_row(); ?>
								<div>
									<span class="label">
										<?php the_sub_field('location_hours_title', $parent); ?>
									</span>

									<span class="num">
										<?php the_sub_field('location_hours_content', $parent); ?>
									</span>
								</div>
							<?php endwhile; ?>

							<?php if(get_field('location_hours_notice', $parent)): ?>
								<div class="notice">
									<?php the_field('location_hours_notice', $parent); ?>
								</div>
							<?php endif; ?>
						</div>
					</span>
				<?php endif; ?>

				<span class="sep">|</span>

				<a href="<?php echo get_permalink($parent); ?>">
					<?php _e('view location details', 'html5blank'); ?>
				</a>
			</div>
		<?php endif; ?>
	</div>
</div>

<script type="text/javascript">
      function setCookiesLocation(dimensions, rent, length, width, height, price,id){
        var url = "<?php bloginfo('url'); ?>/customer-details/";
        document.cookie = "unitLocation=<?php echo $this_location_code ?>;path=/customer-details";
        document.cookie = 'unitType='+id+'; path=/customer-details';
        document.cookie = 'unitDimensions='+dimensions+'; path=/customer-details';
        document.cookie = 'length='+length+'; path=/customer-details';
        document.cookie = 'width='+width+'; path=/customer-details';
        document.cookie = 'height='+height+'; path=/customer-details';
        document.cookie = 'unitRent='+rent+'; path=/customer-details';
        document.cookie = 'unitPrice='+price+'; path=/customer-details';
          url ="https://e-storageonline.com/xyzstorage/Contents/RentOnline_TD.aspx"+
			  "?FromPage=LocationDetails"+
	  			"&ReqPage=ALL"+
	  			"&LocId=<?php echo $this_location_code ?>"+
	  			"&UnitId="+id+
  				"&Len="+length+"&Bre="+width+"&UnitRent="+rent.toFixed(2);
		window.open(url, '_blank');
      }
			function getUnitDetails(details_id,details_width,details_length,details_rent,details_height,details_image,details_category){
				var detailsUrl = "https://xyzstorage.com/wp-json/myplugin/v1/unit-details/<?php echo $this_location_code ?>/"+details_id+"/"+details_width+"/"+details_length+"/"+details_rent;
				$(document).ready(function(){
						$.ajax({url: detailsUrl, success: function(result){
							var priceToPay = 0;
							var internetPrice = parseFloat(result.InternetPrice);
							var rent = parseFloat(result.Rent);
							document.getElementById("featured-image").src = details_image;
							var button = document.getElementById('reserve-unit-featured');
							button.setAttribute("data-location", <?php echo $this_location_code; ?>);
							button.setAttribute("data-unit", details_id);
							button.setAttribute("data-amount", 1);
							document.getElementById("see-all-units-featured").href = window.location.pathname.replace('self-storage','book-a-unit');
							if(result.InternetPriceAvailable == '1'){
								document.getElementById("online-special-featured").innerHTML = "online special";
								priceToPay = internetPrice;
								document.getElementById("internet-price-featured").innerHTML = "$"+internetPrice.toFixed(2)+"/mo";
								document.getElementById("in-store-price-featured").innerHTML = "in-store price";
								document.getElementById("red-price-featured").innerHTML = "$"+rent.toFixed(2);
							}
							else if(rent == 35){
								document.getElementById("online-special-featured").innerHTML = "online special";
								internetPrice = 25;
								priceToPay = internetPrice;
								document.getElementById("internet-price-featured").innerHTML = "$"+internetPrice.toFixed(2)+"/mo";
								document.getElementById("in-store-price-featured").innerHTML = "in-store price";
								document.getElementById("red-price-featured").innerHTML = "$"+rent.toFixed(2);
							}
							else{
								document.getElementById("internet-price-featured").innerHTML = "$"+rent.toFixed(2)+"/mo";
								priceToPay = rent;
							}
							if(result.AvailableUnits == '1' || true){
								//document.getElementById("units-available-featured").innerHTML = "Units available at this location";
								var unitsize = details_width+"'w x "+details_length+"'d x "+details_height+"'h";
								document.getElementById('unit-size').innerHTML = unitsize;
								document.getElementById('category').innerHTML = details_category;
								document.getElementById( "reserve-unit-featured" ).onclick = function(){
									document.cookie = "unitLocation=<?php echo $this_location_code ?>;path=/customer-details";
									setCookiesLocation(unitsize, details_rent, details_length, details_width,details_height, priceToPay, details_id);}
							}
							else {
								//document.getElementById("units-available-featured").innerHTML = "No units at this location";
								document.getElementById( "reserve-unit-featured" ).onclick = function(){return false};
							}
						}});
					});
			}
    </script>
    <?php
            $image_url_domain = get_site_url();
            $image_url_pre = $image_url_domain.'/wp-content/uploads/2019/05/';
            $image_url_post = '-840x520.png';
            $image_url_mids = array(
              'compact' => 'compact-size3',
              'small' => 'small-size3',
              'medium' => 'medium-size3',
              'large' => 'large-size3',
              'mobile' => 'mobile-size-img',
              'parking' => 'parking-size-img'
            );
            $frequent_scarborough = array(
                                    1 => array( 'unit_id' => 27,
                                                'width' => 5,
                                                'length' => 5,
                                                'rent' => 109,
                                                'category' => 'small',),
                                    2 => array( 'unit_id' => 22,
                                                'width' => 5,
                                                'length' => 10,
                                                'rent' => 175,
                                                'category' => 'small'),
                                    3 => array( 'unit_id' => 1,
                                                'width' => 10,
                                                'length' => 10,
                                                'rent' => 250,
                                                'category' => 'medium'),
                                    4 => array( 'unit_id' => 10,
                                                'width' => 10,
                                                'length' => 20,
                                                'rent' => 368.5,
                                                'category' => 'large')
                                  );

            $frequent_etobicoke = array( 1 => array( 'unit_id' => 107,
                                                'width' => 5,
                                                'length' => 5,
                                                'rent' => 143,
                                                'category' => 'small'),
                                    2 => array( 'unit_id' => 96,
                                                'width' => 5,
                                                'length' => 10,
                                                'rent' => 209,
                                                'category' => 'small'),
                                    3 => array( 'unit_id' => 59,
                                                'width' => 10,
                                                'length' => 10,
                                                'rent' => 324.5,
                                                'category' => 'medium'),
                                    4 => array( 'unit_id' => 67,
                                                'width' => 10,
                                                'length' => 14,
                                                'rent' => 343.33,
                                                'category' => 'medium'),
                                  );
            $frequent_toronto_west = array(1 => array( 'unit_id' => 107,
                                                  'width' => 5,
                                                  'length' => 5,
                                                  'rent' => 120,
                                                  'category' => 'small'),
                                      2 => array( 'unit_id' => 96,
                                                  'width' => 5,
                                                  'length' => 10,
                                                  'rent' => 240,
                                                  'category' => 'small'),
                                      3 => array( 'unit_id' => 1,
                                                  'width' => 10,
                                                  'length' => 10,
                                                  'rent' => 315,
                                                  'category' => 'medium'),
                                      4 => array( 'unit_id' => 70,
                                                  'width' => 10,
                                                  'length' => 15,
                                                  'rent' => 390,
                                                  'category' => 'large')
                                        );
            $frequent_toronto_midtown = array( 1 => array( 'unit_id' => 388,
                                                  'width' => 5,
                                                  'length' => 5,
                                                  'rent' => 165,
                                                  'category' => 'small'),
                                      2 => array( 'unit_id' => 378,
                                                  'width' => 5,
                                                  'length' => 10,
                                                  'rent' => 255,
                                                  'category' => 'small'),
                                      3 => array( 'unit_id' => 1,
                                                  'width' => 10,
                                                  'length' => 10,
                                                  'rent' => 365,
                                                  'category' => 'medium'),
                                      4 => array( 'unit_id' => 5,
                                                  'width' => 10,
                                                  'length' => 15,
                                                  'rent' => 505,
                                                  'category' => 'large'),
                                    );
            $frequent_toronto_downtown = array(
                                      1 => array( 'unit_id' => 19,
                                                  'width' => 4,
                                                  'length' => 3,
                                                  'rent' => 35,
                                                  'category' => 'compact'),
                                      2 => array( 'unit_id' => 22,
                                                  'width' => 5,
                                                  'length' => 10,
                                                  'rent' => 198,
                                                  'category' => 'small'),
                                      3 => array( 'unit_id' => 1,
                                                  'width' => 10,
                                                  'length' => 10,
                                                  'rent' => 315,
                                                  'category' => 'medium'),
                                      4 => array( 'unit_id' => 9,
                                                  'width' => 10,
                                                  'length' => 20,
                                                  'rent' => 460,
                                                  'category' => 'large'),
                                    );
            $frequent_mississauga = array(
                                      1 => array( 'unit_id' => 109,
                                                  'width' => 5,
                                                  'length' => 8,
																									'height' => 7,
                                                  'rent' => 181.5,
                                                  'category' => 'medium'),
                                    );
            $frequently_booked = array( 1 => $frequent_scarborough,
                                        2 => $frequent_mississauga,
                                        3 => $frequent_toronto_west,
                                        4 => $frequent_etobicoke,
                                        6 => $frequent_toronto_midtown,
                                        7 => $frequent_toronto_downtown);

            $this_frequent = $frequently_booked[$this_location_code];
?>

<div class="main-container overflow">
	<div class="wrapper">
		<main role="main" class="padding">
			<?php get_template_part('module-rental'); ?>

			<?php if (have_posts()): while (have_posts()) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class('overflow'); ?>>
					<script>
			            <?php $frequent = $this_frequent[1]; ?>
			            var locationCode = <?php echo $this_location_code; ?>  ;
			            var locationUnitId = <?php echo $frequent['unit_id']; ?>;
			            var unitLength = <?php echo $frequent['length']; ?>;
			            var unitWidth = <?php echo $frequent['width']; ?>;
			            var unitRent = <?php echo $frequent['rent']; ?>;
			            var unitHeight = <?php
			              if($frequent['height']) echo $frequent['height'];
			              else echo 8; ?>;
			            var imgUrl = '<?php echo $image_url_pre.$image_url_mids[$frequent['category']].$image_url_post  ?>';
			            var category = '<?php echo $frequent['category']?>';

			            getUnitDetails(locationUnitId,unitWidth,unitLength,unitRent,unitHeight,imgUrl,category);
			          </script>
					<?php the_content(); ?>
				</article>
			<?php endwhile; ?>

			<?php endif; ?>

			<?php get_template_part('module-layout'); ?>

			<?php if(get_field('location_service_left_content') || get_field('location_service_right_content')): ?>
				<div class="section overflow">
					<div class="columns columns-2 additional">
						<?php if(get_field('location_service_left_content')): ?>
							<div class="col">
								<?php the_field('location_service_left_content'); ?>
							</div>
						<?php endif; ?>

						<?php if(get_field('location_service_right_content')): ?>
							<div class="col">
								<?php the_field('location_service_right_content'); ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if( have_rows('location_service_process') ): ?>
				<div class="section">
					<h2 class="title">
						<?php _e('how it works', 'html5blank'); ?>
					</h2>

					<div class="columns columns-3 process">
						<?php while( have_rows('location_service_process') ): the_row(); ?>
							<div class="col">
								<?php if( get_sub_field('location_service_process_image') ): ?>
									<?php $image = get_sub_field('location_service_process_image'); ?>

									<div class="img-wrap">
										<img src="<?php echo $image['sizes']['small']; ?>" alt="<?php echo $image['alt']; ?>" />
									</div>
								<?php endif; ?>

								<h3 class="title-4">
									<?php the_sub_field('location_service_process_title'); ?>
								</h3>

								<p>
									<?php the_sub_field('location_service_process_content'); ?>
								</p>
							</div>
						<?php endwhile; ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if( have_rows('location_service_prices') ): ?>
				<div class="section">
					<h2 class="title">
						<?php _e('pricing', 'html5blank'); ?>
					</h2>

					<?php if(get_field('location_service_prices_description')): ?>
						<div class="content">
							<?php the_field('location_service_prices_description'); ?>
						</div>
					<?php endif; ?>

					<div class="columns columns-2 flex prices">
						<?php while( have_rows('location_service_prices') ): the_row(); ?>
							<div class="col">
								<h3 class="title">
									<?php the_sub_field('location_service_prices_title'); ?>
								</h3>

								<?php if( have_rows('location_service_prices_price') ): ?>
									<div class="cost">
										<?php while( have_rows('location_service_prices_price') ): the_row(); ?>
											<p>
												<span class="num">
													<?php the_sub_field('location_service_prices_price_cost'); ?>
												</span>

												<span>
													<?php the_sub_field('location_service_prices_price_description'); ?>
												</span>
											</p>
										<?php endwhile; ?>
									</div>
								<?php endif; ?>
							</div>
						<?php endwhile; ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if(get_field('location_service_content')): ?>
				<div class="section white">
					<?php the_field('location_service_content'); ?>

					<?php if( have_rows('location_service_features') ): ?>
						<div class="columns columns-3 services bottom">
							<?php while( have_rows('location_service_features') ): the_row(); ?>
								<div class="col">
									<?php if( get_sub_field('location_service_features_icon') ): ?>
										<?php $image = get_sub_field('location_service_features_icon'); ?>

										<div class="img-wrap">
											<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
										</div>
									<?php endif; ?>

									<div class="content">
										<p class="title-5">
											<?php the_sub_field('location_service_features_title'); ?>
										</p>

										<p>
											<?php the_sub_field('location_service_features_description'); ?>
										</p>
									</div>
								</div>
							<?php endwhile; ?>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</main>

		<div class="sidebar">
			<?php get_template_part('loop-locations-services-sidebar'); ?>
		</div>

		<div class="clear"></div>
	</div>
</div>

<?php if(get_field('location_about_content')): ?>
	<div id="about" class="about-section spacing has-shadow long" style="background-image: url('<?php the_field('location_about_background'); ?>');">
		<div class="wrapper">
			<div class="columns columns-3 custom">
				<div class="col first">
					<?php if(get_field('location_about_subtitle')): ?>
						<p class="title-5 icon icon-arrow white">
							<?php the_field('location_about_subtitle'); ?>
						</p>
					<?php endif; ?>

					<?php the_field('location_about_content'); ?>
				</div>

				<?php if(get_field('location_about_content_2')): ?>
					<div class="col second">
						<?php the_field('location_about_content_2'); ?>
					</div>
				<?php endif; ?>

				<?php if(get_field('location_about_content_3')): ?>
					<div class="col third">
						<?php the_field('location_about_content_3'); ?>
					</div>
				<?php endif; ?>

				<?php if(have_rows('location_about_items')): ?>
					<div class="col second wide">
						<div class="columns columns-2 flex dark-grey products">
							<?php while(have_rows('location_about_items')): the_row(); ?>
								<div class="col">
									<div class="content">
										<?php if( get_sub_field('location_about_items_image') ): ?>
											<?php $image = get_sub_field('location_about_items_image'); ?>

											<div class="img-wrap transparent">
												<img src="<?php echo $image['sizes']['small-2']; ?>" alt="<?php echo $image['alt']; ?>" />
											</div>
										<?php endif; ?>

										<h3 class="title-4">
											<?php the_sub_field('location_about_items_title'); ?>
										</h3>

										<?php if(get_sub_field('location_about_items_link')): ?>
											<?php
												$link = get_sub_field('location_about_items_link');
												$link_url = $link['url'];
												$link_title = $link['title'];
												$link_target = $link['target'] ? $link['target'] : '_self';
											?>

											<a class="icon icon-link white" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>"><?php echo esc_html($link_title); ?></a>
										<?php endif; ?>
									</div>
								</div>
							<?php endwhile; ?>
						</div>
					</div>
				<?php endif; ?>
			</div>

			<a class="btn-more normal" href="#">
				<?php _e('read more', 'html5blank'); ?>
			</a>
		</div>
	</div>
<?php endif; ?>

<?php if(get_field('reviews_content')): ?>
	<?php if(get_field('reviews_layout') === 'columns'): ?>
		<div class="reviews-section grey has-bgr-small narrow spacing">
			<div class="wrapper overflow" style="background-image: url('<?php the_field('reviews_background'); ?>');">
				<div class="title-wrap float-left">
					<?php if(get_field('reviews_subtitle')): ?>
						<p class="title-5 icon icon-arrow">
							<?php the_field('reviews_subtitle'); ?>
						</p>
					<?php endif; ?>

					<?php the_field('reviews_content'); ?>
				</div>

				<div class="content float-right">
					<?php get_template_part('loop-reviews-2'); ?>
				</div>
			</div>
		</div>
	<?php endif; ?>
<?php endif; ?>

<?php if(get_field('reviews_layout') === 'fullwidth'): ?>
	<?php get_template_part('loop-reviews-city'); ?>
<?php endif; ?>

<?php if(get_field('shop_title')): ?>
	<div class="reviews-section shop-section spacing grey 1" style="background-image: url('<?php the_field('shop_background_image'); ?>');">
		<div class="wrapper overflow">
			<div class="title-wrap float-left">
				<?php if(get_field('shop_subtitle')): ?>
					<p class="title-5 icon icon-arrow">
						<?php the_field('shop_subtitle'); ?>
					</p>
				<?php endif; ?>

				<?php if(get_field('shop_title')): ?>
					<h2 class="title">
						<?php the_field('shop_title'); ?>
					</h2>
				<?php endif; ?>

				<?php if(get_field('shop_button')): ?>
					<?php
						$link = get_field('shop_button');
						$link_url = $link['url'];
						$link_title = $link['title'];
						$link_target = $link['target'] ? $link['target'] : '_self';
					?>

					<a class="btn" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
						<?php echo esc_html($link_title); ?>
					</a>
				<?php endif; ?>
			</div>

			<div class="content float-right">
				<?php the_field('shop_content'); ?>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php get_template_part('module-cta'); ?>

<?php get_template_part('loop-locations-services'); ?>

<?php get_footer(); ?>
