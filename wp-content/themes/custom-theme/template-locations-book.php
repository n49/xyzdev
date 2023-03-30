<?php
/*
Template Name: Book Unit
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
	                          'Toronto Downtown' => "jhmaw5br30o4p7w5g",
	                          'North York' => "ji6b4k14upi10t2pc"
	                        );
$this_location_id = $locations_opioID[$this_location_name];
$locations_array = array(
													'Scarborough' => 1,
													'Mississauga' => 5,
													'Toronto West' => 3,
													'Etobicoke' => 4,
													'Mobile Storage' => 5,
													'Toronto Midtown' => 6,
													'Toronto Downtown' => 7
												);
$this_location_code = $locations_array[$this_location_name];
/*
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
  	  CURLOPT_URL => $BASE_URL.$this_location_id,
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
*/
?>

<div class="header-wrap has-content">
	<?php $parent = $post->post_parent; ?>
	<script>
	function getReview(location){
			$(document).ready(function(){
					$.ajax({url: "https://xyzstorage.com/wp-json/myplugin/v1/opio-rating/<?php echo $this_location_code ?>/rating", success: function(result){
						document.getElementById("stars1").style.width = result.rating/ 5 * 100+"%";
						document.getElementById("location-rating1").innerHTML = result.rating+"/5";
					}});
			});
		}
		getReview(<?php echo $this_location_code?>);
		var locationName = "<?php echo get_the_title($parent); ?>";
	</script>
<?php // echo "<p>".$this_location_name." ".$this_location_id."</p>"; ?>
	<div class="wrapper">
		<?php if(get_field('location_new', $parent) === 'yes'): ?>
			<span class="new">
				<?php the_field('location_new_label', $parent); ?>
			</span>
		<?php endif; ?>

		<h1 class="title">
			<?php if(get_field('location_custom_title', $parent)): ?>
				<?php the_field('location_custom_title', $parent); ?>
			<?php else: ?>
				<?php echo get_the_title($parent); ?>
			<?php endif; ?>
		</h1>

		<?php if(get_field('location_rating', $parent)): ?>
			<div class="rating-wrap">
				<div class="rating">
					<div  id="stars1" style="width: <?php echo '100'; ?>%"></div>
				</div>
				<span id="location-rating1"></span>
			</div>
		<?php endif; ?>

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

<div class="main-container">
	<div class="wrapper">
		<?php get_template_part('loop-units'); ?>

		<?php if(get_field('location_unit_notice', $parent)): ?>
			<div class="notice">
				<?php the_field('location_unit_notice', $parent); ?>
			</div>
		<?php endif; ?>
	</div>
</div>

<?php get_footer(); ?>
