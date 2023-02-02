<?php

$parent = $post->post_parent;
$this_location_name = get_the_title($parent);  //Toronto Downtown

$locations_array = array(
  'Scarborough' => 1,
  'Mississauga' => 2,
  'Toronto West' => 3,
  'Etobicoke' => 4,
  'Mobile Storage' => 5,
  'Toronto Midtown' => 6,
  'Toronto Downtown' => 7,
);

$url_array = array(
  3 => 'toronto-west',
  4 => 'etobicoke',
  1 => 'scarborough',
  2 => 'mississauga',
  5 => 'mobile-storage',
  6 => 'toronto-midtown',
  7 => 'toronto-downtown'
);

$location_id = $locations_array[$this_location_name];

  $API_KEY = 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6IjU3MmE0NDI3NDdhYjg5YzkzYzE0MzVlNV9UVG55Z2E0NEBvcC5pbyIsInVzZXJfbWV0YWRhdGEiOnsiX2lkIjoiajJnZWdwYzhzZnd3Y2NzYWcifSwiaXNzIjoiaHR0cHM6Ly9vcC5pby8iLCJzdWIiOiJhcGlrZXl8ajJnZWdwYzhzZnd3Y2NzYWciLCJhdWQiOiJobTNsZmlvUHAwVWhqQnNkV0V6MG9nTW9zVVNDV2p3SCIsImV4cCI6MjE0NTkxNjgwMCwiaWF0IjoxNDk0MjY0NDM1LCJyZXNvdXJjZXMiOlt7InJvdXRlIjoiZW50aXRpZXMifSx7InJvdXRlIjoicHJvcGVydGllcyIsInF1ZXJ5IjpbbnVsbF19XX0.IGrh8vnO1o9SLfmhuEzPNSkiLYhsxcIvti9Pq-9N65s';
  $BASE_URL = 'https://op.io/api/entities/';
  $ARGS = '?scopes=Review&subSkip=0&subLimit=10';

  $locations_opioID = array(3 => "jhrvg39uijr5q9mo3",
      4 => "jhrtgyf3izoyc0rih",
      1 => "jhrtqujzw0ystxyfk",
      5 => "jhmaw5br30o4p7w5g",
      6 => "jhrur79akaghyr692",
      7 => "jwjbbu0fdl26ixz0v",
      2 => "ji6b4k14upi10t2pc");

  $this_location_opio = $locations_opioID[$location_id];
  $url = $BASE_URL.$this_location_opio.$ARGS;
  $response = wp_remote_get($url, array("headers" => $API_KEY, "timeout" => 25));

  if($response->errors){
      $response = false;
  }
  if($response['body']){
      $response = json_decode($response['body'], true);
  } else {
      $response = $response;
  }    
  $review_arr = array();
  if(count($response[0]['reviews']) > 0) {
    $review_arr = array_slice($response[0]['reviews'], 0, 6);
  } else {
    return false;
  }
    

// $best_reviews = array(1 => 'a',3 => 'a', 4=> 'b', 6 => 'b');
?>

<div class="reviews-section review-slider spacing">
	<div class="wrapper">
		<p class="title-5 icon icon-arrow">
			<?php _e('reviews', 'html5blank'); ?>
		</p>
		<h2 class="title">
			<?php _e('a few words from those who made space', 'html5blank'); ?>
		</h2>
		<div class="columns columns-3 grey flex reviews">
      <?php foreach($review_arr as $key => $review){ ?>
				<article id="post-<?php echo $key ?>" class="col post-617 reviews type-reviews status-publish hentry slick-slide" style="width: 374px !important;">
					<a class="content">
							<h3 style="cursor:pointer;" class="title title-5" onclick="location.href='/location/<?php echo $url_array[$location_id]; ?>#reviews';"
 id="review-location-<?php echo $location_id; ?>">
								<?php echo "Review by ".$review['user']['fullName']; ?>
							</h3>
              <?php //https://videocdn.n49.ca/mp4sdpad480p/6d87c4b4f8465720ef536fe8eefa173d.mp4#t=0.1  ?>
							<div class="img-wrap">
								<?php
								  if($review["totalVideos"] > 0){
	                  			?>
								<video id="video-review-<?php echo $key; ?>" preload="auto" controls style="width: 100%">
								  <source src="https://videocdn.n49.ca/mp4sdpad480p/<?php echo $review['videos'][0]['videoId'] ?>.mp4#t=0.1" />
								</video>
	                  			<?php } ?>
							</div>
    
			<?php if($review["totalVideos"] > 0){?>	
						<p id="review-content-<?php echo $key;?>">
							<?php echo substr($review['content'], 0 , 80); ?>
           				 </p>
			<?php } else { ?>
							<?php echo substr($review['content'], 0 , 200); ?>
			<?php } ?>
						<div class="bottom">
								<p class="author" id="review-name-<?php echo $key; ?>" style="font-weight: bold">
									<?php echo $review['user']['fullName']; ?>
								</p>
								<div class="rating-wrap large">
									<div class="rating">
										<div id="review-stars-<?php echo $key; ?>" style="width:<?php echo $review['rating'] / 5 * 100; ?>%"></div>
									</div>
								</div>
						</div>
					</a>
				</article>
			<?php } ?>
		</div>
		<div class="slider-nav"></div>
	</div>
</div>