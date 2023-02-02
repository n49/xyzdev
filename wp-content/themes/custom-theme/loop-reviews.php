<?php
$parent = $post->post_parent;
$this_location_name = get_the_title($parent);

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
/*
  $curl = curl_init();
  foreach($locations as $loc){
    curl_setopt_array($curl, array(
  	  CURLOPT_URL => $BASE_URL.$loc.$ARGS,
  	  CURLOPT_RETURNTRANSFER => true,
  	  CURLOPT_MAXREDIRS => 10,
  	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  	  CURLOPT_CUSTOMREQUEST => "GET",
  	  CURLOPT_HTTPHEADER => array($API_KEY)
  	  )
  	);
    $response = curl_exec($curl);
    $json_scarb = json_decode($response, true);
    array_push($all_reviews,$json_scarb[0]);
  }

  function get_best($json_obj){
    $best = 0;
    $best_review = array( "location" => $json_obj["name"],
                            "review" => array());
    $return_array = array();
    $continue = TRUE;

    foreach ($json_obj["reviews"] as $review){
      if($continue && $review['totalVideos'] > 0){
        $best_review["review"] = $review;
        $continue = FALSE;
      }
      if($continue && $review['rating'] > $best && $best < 5){
        $best_review["review"] = $review;
        $best = $review['rating'];
      }
    }
    return $best_review;
  }

  $best_reviews = array();

  foreach ($all_reviews as $location){
    array_push($best_reviews, get_best($location));
  }


*/

$locations_array = array(
                          1 => 'Toronto West',
                          2 => 'Etobicoke',
                          0 => 'Scarborough',
                          4 => 'Mississauga',
                          5 => 'Mobile Storage',
                          3 => 'Toronto Midtown',
                          6 => 'Toronto Downtown'
                        );

$url_array = array(
                          1 => 'toronto-west',
                          2 => 'etobicoke',
                          0 => 'scarborough',
                          4 => 'mississauga',
                          5 => 'mobile-storage',
                          3 => 'toronto-midtown',
                          6 => 'toronto-downtown'
                        );

$best_reviews = array(0 => 'a',1 => 'a', 2=> 'b', 3 => 'b');
?>
<script type="text/javascript"> 
	/* space */ 
  function getReview(location){
	  let url = 
    $(document).ready(function(){
        $.ajax({url: "/wp-json/myplugin/v1/opio-rating-new/"+location, success: function(result){
			result.map((data, index) => {
				document.getElementById("review-content-"+index).innerHTML = data.content.substring(0, 80)+"...";
          		document.getElementById("review-name-"+index).innerHTML = data.user.fullName;
          		document.getElementById("review-stars-"+index).style.width = data.rating;
			})
         }});
    });
  }
  getReview("jhrtqujzw0ystxyfk,jhrvg39uijr5q9mo3,jhrtgyf3izoyc0rih,jhrur79akaghyr692");
		
</script>

<div class="reviews-section review-slider spacing">
	<div class="wrapper">
		<p class="title-5 icon icon-arrow">
			<?php _e('reviews', 'html5blank'); ?>
		</p>
		<h2 class="title">
			<?php _e('a few words from those who made space', 'html5blank'); ?>
		</h2>
		<div class="columns columns-3 grey flex reviews">
			<?php foreach ($best_reviews as $key => $review) { ?>
				<article id="post-<?php echo $key;?>" class="col post-617 reviews type-reviews status-publish hentry slick-slide" style="width: 374px !important;">
					<a class="content">
							<h3 style="cursor:pointer;" class="title title-5" onclick="location.href='/location/<?php echo $url_array[$key]; ?>#reviews';"
 id="review-location-<?php echo $key; ?>">
							<?php
								echo $locations_array[$key];
							?>
							</h3>
              <?php //https://videocdn.n49.ca/mp4sdpad480p/6d87c4b4f8465720ef536fe8eefa173d.mp4#t=0.1  ?>
							<div class="img-wrap">
								<?php
								  //if($review["review"]["totalVideos"] > 0){
								    //$videoURL = "https://videocdn.n49.ca/mp4sdpad480p/".$review['review']['videos'][0]['videoId'].".mp4#t=0.1";
	                  ?>
<!-- 	                    <video id="video-review-<?php echo $key; ?>" preload="auto" controls style="width: 100%">
	                      <source src=""/>
	                    </video> -->
	                  <?php
								  //}
								?>
							</div>
            <p id="review-content-<?php echo $key; ?>">
						<?php //echo $review['review']['content']; ?>
            </p>
						<div class="bottom">
								<p class="author" id="review-name-<?php echo $key; ?>">
									<?php //echo $review['review']['user']['fullName']; ?>
								</p>
								<div class="rating-wrap large">
									<div class="rating">
										<div id="review-stars-<?php echo $key; ?>" style="width: 100<?php //echo $review['review']['rating'] / 5 * 100; ?>%"></div>
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
