<?php
	$tagline = "New Year's Sale";

	$taxonomy = 'unit-category';

	$terms = get_terms(array(
		'taxonomy' => $taxonomy,
		'hide_empty' => false
	));

	$parent = $post->post_parent;
	$parent_title = get_the_title($parent);



	$locations_array = array(
		'Scarborough' => 1,
		'Mississauga' => 5,
		'Toronto West' => 3,
		'Etobicoke' => 4,
		'Mobile Storage' => 5,
		'Toronto Midtown' => 6,
		'Toronto Downtown' => 7
	);

	$url_domain = get_site_url();
	$image_url_pre = $url_domain.'/wp-content/uploads/2019/05/';
	$image_url_post = '';//-840x520.png';
	$image_url_mids = array(
		'compact' => 'Compact.png',
		'small' => 'Small.png',
		'medium' => 'Medium.png',
		'large' => 'Large.png',
		'mobile' => 'mobile-storage-1.png',
		'parking' => 'Parking.png',
		'container' => 'Large.png',
	);
	$img_alt = array(
		'compact' => 'Compact Self Storage',
		'small' => 'Small Self Storage',
		'medium' => 'Medium Self Storage',
		'large' => 'Large Self Storage',
		'mobile' => 'Mobile Self Storage',
		'parking' => '',
		'container' => 'Large Self Storage',
	);

	$this_location_code = $locations_array[$parent_title];

	$apiunit = array();
	$curl = curl_init();
	if($this_location_code != 5){
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://www.secure.selfstoragemanager.com/ssmwebserviceV2.1/ssmws.asmx/GetUnitDetails?strCustomerCode=xyzstorage&strCustomerPassword=991852130418&strLocationCode=".$this_location_code."&strSortType=1",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
			"cache-control: no-cache",
			),
		));
		$response = curl_exec($curl);
		$xml = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
		$json = json_encode($xml);
		$apiunit = json_decode($json,TRUE)["UnitType"];
		if($apiunit) {
			
		
			
		usort($apiunit, function ($item1, $item2) {
			return $item1['Sq_Ft']*$item1['Height'] <=> $item2['Sq_Ft']*$item2['Height'];
		});
		}
		$labels = [];

		$unitID_array = array();
		$units_array = array();
		if(!$apiunit) {
			$apiunit = [];
}
		foreach($apiunit as $k => $u){
			if($u["AvailableUnits"] != '0'){
				if(!in_array($u['UnitTypeId'],$unitID_array)){
					array_push($unitID_array,$u['UnitTypeId']);
					array_push($units_array,$u);
				}
				else{
					foreach($units_array as $ku => $un){
						if( strcmp($un['UnitTypeId'], $u['UnitTypeId']) == 0 ){
							$temp = (int)$un['AvailableUnits'] + (int)$u['AvailableUnits'];
							$units_array[$ku]['AvailableUnits'] = $temp;
						}
					}
				}
			}
		}
		$apiunit = $units_array;
		$temp_array = array();

		foreach($apiunit as $key => $unit){
			if($unit['Height'] == '7') unset($apiunit[$key]); // hides mobile unit
			if($unit['Sq_Ft'] == '1' || $unit["CategoryName"] == "PARKING"){ //if it's a parking unit
				$temp = $unit;
				unset($apiunit[$key]); // remove from array
				array_push($temp_array, $temp); // add it to temp array
			}
		}

		foreach($temp_array as $key => $temp_unit){
			if(strpos($temp_unit["UnitTypeDescription"], "OFFICE" ) !== false){ // if unit is actually office
				unset($temp_array[$key]); // remove from array
						array_push($temp_array, $temp_unit); // add it to the end of temp array
			}
		}
		foreach($temp_array as $temp_unit){
			array_push($apiunit, $temp_unit); // add temporary array to end of original array
		}

		// removing this because they asked containers to be in large section
// 		foreach($apiunit as $key => $un){
// 			if($un["UnitTypeDescription"] == '20 ft containers'){
// 			  $cont = $un;
// 			  unset($apiunit[$key]); // remove from array
// 			  array_push($apiunit, $cont); // add it to conts array
// 			}
// 		}

		// the following code moves parking to the end of the list so containers show up before them.
		foreach($apiunit as $key => $un){
			if($un["CategoryName"] == "PARKING"){
				$cont = $un;
				unset($apiunit[$key]);
				array_push($apiunit, $cont);
			}
		}

		$master = array();
		$temp_cat = '';
		$temp_array = array();
		$categories = array();

		foreach($apiunit as $key => $unit){
			$unitCalculate = (int) $unit["Sq_Ft"];
			if($unitCalculate == 1) {
					if(strpos($unit["UnitTypeDescription"], "OFFICE" ) !== false ){
						$label = 'office';
						$unitSize = 'office rental';
					}
					else{
						$label = 'parking';
						if($unit["Height"] == 1) $unitSize = 'small';
						else $unitSize = 'large';
					}
			}
			if($unitCalculate > 2) $label = "compact";
			if($unitCalculate >= 25) $label = "small";
			if($unitCalculate >= 75) $label = "medium";
			if($unitCalculate >= 150) $label = "large";
			if($unit["CategoryName"] == "PARKING") $label = "parking";
			if($unit["UnitTypeDescription"] == '20 ft containers') $label = "large"; // containers to be treated as large unit

			if($temp_cat == $label){
				array_push($temp_array, $unit);
			}
			if($temp_cat != $label){
				$temp_cat = $label;
				if(sizeof($temp_array) > 0) array_push($master, $temp_array);
				$temp_array = array();
				array_push($categories, $label);
				array_push($temp_array, $unit);
			}
		}
	}
	if(sizeof($temp_array) > 0){
		if(sizeof($master) < sizeof($categories)){
			array_push($master, $temp_array);
		}
	}

	$unit_level_prices = get_unit_type_prices($this_location_code);
	// var_dump($unit_level_prices);

?>

<div id="reserve" class="tabs-wrap">

	<div id="promotion-term" style="position: absolute; color: rgba(0,0,0,0)">New Year Sale</div>
	<div id="price-mode" style="position: absolute; color: rgba(0,0,0,0)">monthly</div>

	<div class="wrapper">
		<div class="tabs">
			<?php if(get_field('location_book_category_list') === 'show' && $this_location_code != 5): ?>
				<ul class="horizontal slider">
					<?php foreach ($terms as $term) :
							$args_1 = array(
								'post_type' => 'units',
								'posts_per_page'=> -1,
								'meta_query' => array(
									'relation' => 'OR',
									array(
										'key' => 'unit_area',
										'value'	=> $parent_title,
										'compare' => 'LIKE'
									)
								),
								'tax_query' => array(
									array(
										'taxonomy' => $taxonomy,
										'field'    => 'slug',
										'terms'    => $term->slug
									),
								)
							);

							$loop_1 = new WP_Query($args_1);

 							if($loop_1->have_posts()) : ?>
							<li>
								<?php if(get_field('use_category_separator', $term) === 'yes'): ?>
									<span class="sep icon"></span>
								<?php endif; ?>

								<a href="#<?php echo $term->slug; ?>">
									<p class="title-5">
										<?php echo $term->name; ?>
									</p>

									<?php if(get_field('unit_category_size', $term)): ?>
										<p>
											<?php the_field('unit_category_size', $term); ?>
										</p>
									<?php endif; ?>
								</a>
							</li>
						<?php endif; ?>
					<?php endforeach; ?>

					<?php wp_reset_query(); ?>
				</ul>
			<?php endif; ?>

			<p class="title-5 icon icon-arrow">
				<?php _e('available storage units', 'html5blank'); ?>
			</p>

			<?php foreach($categories as $ck => $category):  ?>
				<div id="<?php echo $category; ?>" <?php post_class('tab margin'); ?>>
						<h2 class="title">
							<?php echo $category; ?>
						</h2>
						<p class="size">
							<?php
								switch($category){
									case "compact":
										echo "12 - 24 square ft";
										break;
									case "small":
										echo "25 - 74 square ft";
										break;
									case "medium":
										echo "75 - 149 square ft";
										break;
									case "large":
										echo "150 - 300+ square ft";
										break;
								}
							?>
						</p>
				<div class="slider-units">
					<?php foreach($master[$ck] as $unit):  ?>
						<?php if(($unit["AvailableUnits"] != 0 || $unit['UnitTypeId'] == '16') && $this_location_code != 5): ?>
							<?php
									$unitID = $unit["UnitTypeId"];
									$unitCalculate = (int) $unit["Sq_Ft"];
							 		$unitSize = round($unit["Breadth"])."'w x ".round($unit["Length"])."'d x ".round($unit["Height"])."'h";
					if($unit["UnitTypeCode"] == "8X16 PARKING"){
						$unitSize = "Regular";
					}
					if($unit["UnitTypeCode"] == "8X32 PARKING"){
						$unitSize = "Extra Large";
					}
							 		$unitLength = $unit["Length"];
									$unitFeatures = $unit["UnitTypeCode"];
									$unitPrice = $unit["Rent"];
									$unitCategory = $unit["CategoryName"];
									$unitInternetPrice = $unit["InternetPrice"];
									$unitDescription = $unit["UnitTypeDescription"];
									$unit_details = $unit["OnlineMessage"];
									$availableUnit = $unit["AvailableUnits"];


									$lowest_price = $unitInternetPrice;
									if($unit_level_prices[$unitID]){
										foreach(array_keys($unit_level_prices[$unitID]) as $key){
											$temp_price = (int) $key;
											if ($lowest_price > $temp_price) {
												$lowest_price = $temp_price;
											}
										}
									}
									// echo $unitID;
								?>

							<div class="slide">
								<div
					 id="post-<?php echo $unitID; ?>" class="columns columns-5 white flex units retrieve-discount"
					 data-id="<?php echo $unitID; ?>"
					 data-location="<?php echo $this_location_code; ?>"
					 >
									<div class="col img" style="margin: 0px">
										<div class="img-wrap promo-tag-<?php echo $unitID; ?>">
											<img alt="<?php echo $category; ?>" src="<?php echo $image_url_pre.$image_url_mids[$category].$image_url_post;?>"
													data-src="<?php echo $image_url_pre.$image_url_mids[$category].$image_url_post;?>"
													class="attachment-small-4 size-small-4 wp-post-image ls-is-cached lazyloaded" alt="<?php echo $img_alt[$category]; ?>" style="padding: 20px">
										</div>
									</div>
									<?php //description ?>
									<div class="col desc">
										<h3 class="title title-4">
											<span><?php echo $category; ?></span>
											<?php echo $unitSize ;?>
										</h3>

										<button type="button" class="icon btn-hide">
											show details
										</button>

										<ul class="hide">
										<?php
											$details_array = array();
											if($unit_details){
							$details_array = explode(' -',$unit_details);
						}
											if($details_array[0][0] == "-"){
												$details_array[0] = substr($details_array[0],1);
											}
											$real_description = '';
											$desc = strtolower(explode(' ',$unitDescription)[1]);
											if(strcmp($desc, 'sfs') == 0)
											{
												$real_description = 'Second Floor, Stairs Access';
											}

											else if(	$desc == "warehouse" || $desc == 'gf' ||
																$desc == 'in' || $desc == 'main')
											{
											 	$real_description = 'Ground Floor';
											}
																	else if(	$unitDescription == '20 ft containers' )
											{
											 	$real_description = 'Drive Up - Exterior';
											}
											else if( $desc == 'du' || $desc == 'out')
											{
												$real_description = 'Drive Up Unit';
											}

											else if( 	$desc == 'sf' || $desc == 'upper' ||
														$desc == 'upstairs' || $desc == 'tf' ||
																$desc == 'ft' || $desc == 'uf')
											{
												$real_description = 'Upper Floor';
											}
											else if( 	$desc == 'ff'  )
											{
												$real_description = 'Upper Floor';
											}
											else if( $desc == 'out-uh' ){
												$real_description = 'Drive Up Unit - Unheated';
											}
											else if( $desc == 'in-uh' ){
												$real_description = 'Ground Floor - Unheated Unit';
											}

																	if(	$real_description != '' &&
													strcmp($real_description, 'PARKING') != 0 &&
													strcmp($real_description, 'OFFICE') != 0 )
											{
												echo '<li><strong>'.$real_description.'</strong></li>';
											}

											if($details_array && sizeof($details_array) > 0):
												foreach($details_array as $detail){
													if(strlen($detail) > 0) {
														?>
															<li> <?php echo $detail; ?> </li>
														<?php
													}
												}
											else:
												?>

											 	<?php
											endif;
										 ?>
										</ul>
									</div>

									<div class="col text border">
										<ul id="ul-<?php echo $unitID; ?>" class="first"></ul>

										<p class="title-5">
											<span style="color: #42be2d;" id="p-<?php echo $unitID; ?>"></span>
										</p>

										<strong id="discount-name-<?php echo $unitID; ?>"></strong>

										<div id="discount-expiration-<?php echo $unitID; ?>"></div>
										<div id="discount-cookie-<?php echo $unitID?>" style="height:0px;width:0px;font-size:0px"></div>

										<p id="units-available-<?php echo $unitID; ?>"><?php echo $availableUnit; ?> units left at this location</p>
									</div>
									<div class="col text border">
										<h3 class="title-4 first" id="reservation-price-<?php echo $unitID; ?>"> $<?php echo $unitPrice; ?>/mo</h3>
										<p class="title-5 last risk-free">risk free</p>

										<a class="btn btn-reserve"
											 data-unit="<?php echo $unitID; ?>"
											 data-location="<?php echo $this_location_code?>"
											 data-amount="1"
											 data-dimensions="<?php echo $unitSize; ?>"
											 data-rent="<?php echo $unit["Rent"]; ?>"
											 data-length="<?php echo $unit["Length"]; ?>"
											 data-width="<?php echo $unit["Breadth"]; ?>"
											 data-height="<?php echo $unit["Height"]; ?>"
											 data-price="<?php echo $unitPrice; ?>"
											 data-id="<?php echo $unitID; ?>"
											 data-available="<?php echo $availableUnit; ?>"
											 data-location="<?php echo $this_location_code; ?>"
										>
											reserve now
										</a>

										<ul class="hide">
											<li> no credit card required to reserve</li>
											<li> cancel anytime</li>
										</ul>
									</div>
									<div class="col last border">
										<?php if($unitPrice != $lowest_price): ?>
											<del>
												<span id="unit-price-<?php echo $unitID; ?>">
													$<?php echo $unitPrice; ?>
												</span>
											</del>
											<h3 class="title-4 no-margin" id="unit-sp-price-<?php echo $unitID; ?>"> $<?php echo $lowest_price; ?>/mo</h3>
										<p
											 	class="title-5 online-special-flag hide"
											 id="special-flag-<?php echo $unitID; ?>"
											 style="display:none;"
										 ><?php echo $tagline; ?></p>
											<a  class="btn rent-now-button"
										<?php else: ?>
												<h3 class="title-4 no-margin  no-special"> $<?php echo $lowest_price; ?>/mo</h3>
											<a  class="btn rent-now-button no-special" style="margin-top:40px;"
										<?php endif; ?>

												data-unit="<?php echo $unitID; ?>"
												data-location="<?php echo $this_location_code?>"
												data-amount="1"
												data-dimensions="<?php echo $unitSize; ?>"
												data-rent="<?php echo $unit["Rent"]; ?>"
												data-length="<?php echo $unit["Length"]; ?>"
												data-width="<?php echo $unit["Breadth"]; ?>"
												data-height="<?php echo $unit["Height"]; ?>"
												data-price="<?php echo $unitPrice; ?>"
												data-id="<?php echo $unitID; ?>"
												data-location="<?php echo $this_location_code; ?>"
												data-promo=""
												data-promolabel=""
												>
											rent now
										</a>

										<ul class="hide">
											<li> lowest price in the market guaranteed*</li>
											<li> choose any move-in date to start</li>
										</ul>
										</div>

										<img id="price-tag-<?php echo $unitID; ?>" class="no-price-tag 22 ls-is-cached lazyloaded" src="/wp-content/uploads/2019/10/Tag@2x.png" data-src="/wp-content/uploads/2019/10/Tag@2x.png" alt="50% off first three month Tag">

									</div>
								</div>


						<?php endif; ?>
					<?php endforeach; ?>
				</div>

				<?php /* -- Dots start here -- */ ?>
				<div class="slider-units-dots">
					<?php foreach($master[$ck] as $uk => $unit):  ?>
						<?php if(($unit["AvailableUnits"] != 0 || $unit['UnitTypeId'] == '16') && $this_location_code != 5): ?>
							<div class="slide">1</div>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
				<?php /* -- Dots end here -- */ ?>
			</div>

			<?php endforeach; ?>


			<?php if(false): ?>
			<?php foreach ($terms as $term) : ?>
				<?php
					$args_2 = array(
						'post_type' => 'units',
						'posts_per_page'=> -1,
						'meta_query' => array(
							'relation' => 'OR',
							array(
								'key' => 'unit_area',
								'value'	=> $parent_title,
								'compare' => 'LIKE'
							)
						),
						'tax_query' => array(
							array(
								'taxonomy' => $taxonomy,
								'field'    => 'slug',
								'terms'    => $term->slug
							),
						)
					);

					$loop_2 = new WP_Query($args_2);
				?>

				<?php if($loop_2->have_posts()) : ?>
					<div id="<?php echo $term->slug; ?>" <?php post_class('overflow tab margin'); ?>>
						<h2 class="title">
							<?php if( get_field('unit_category_full_title', $term) ): ?>
								<?php the_field('unit_category_full_title', $term); ?>
							<?php else: ?>
								<?php echo $term->name; ?>
							<?php endif; ?>
						</h2>

						<?php if(get_field('unit_category_size', $term)): ?>
							<p class="size">
								<?php the_field('unit_category_size', $term); ?>
							</p>
						<?php endif; ?>

						<div class="slider-units">
							<?php if ($loop_2->have_posts()): while ($loop_2->have_posts()) : $loop_2->the_post(); ?>
								<!-- START Use the below slide as template -->

								<div class="slide">
									<div id="post-22" class="columns columns-5 white flex units">
										<div class="col img">
											<div class="img-wrap">
												<img src="https://www.xyzstorage.com/wp-content/uploads/2019/05/Small.png" data-src="https://www.xyzstorage.com/wp-content/uploads/2019/05/Small.png" class="attachment-small-4 size-small-4 wp-post-image ls-is-cached lazyloaded" alt="">
											</div>
										</div>

										<div class="col desc">
											<h3 class="title title-4">
												<span>small</span>

												5'w x 10'd X 8'h
											</h3>

											<button type="button" class="icon btn-hide">
												show details
											</button>

											<ul class="hide">
												<li>
													<strong>Ground Floor</strong>
												</li>

												<li> Heated facility, 24/7 monitoring and fire security</li>
												<li> Great storage size for a small condo with no large furniture</li>
												<li> Accommodate the storage of a small room of furniture</li>
											</ul>
										</div>

										<div class="col text border">
											<ul id="ul-22" class="first"></ul>

											<p class="title-5">
												<span style="color: #42be2d;" id="p-22"></span>
											</p>

											<strong id="discount-name-22"></strong>

											<div id="discount-expiration-22"></div>

											<p> 34 units left at this location</p>
										</div>

										<div class="col text border">
											<h3 class="title-4 first"> $175.00/mo</h3>
											<p class="title-5 last">risk free reservation</p>

											<a class="btn btn-reserve" data-unit="22" data-location="1" data-amount="1" onclick="setCookiesRegular(`5'w x 10'd X 8'h`,175.00,10,5,8,175.00,22)"> reserve now </a>

											<ul class="hide">
												<li> no credit card required to reserve</li>
												<li> cancel anytime</li>
											</ul>
										</div>

										<div class="col last border">
											<del>
												<span> $175.00</span>
											</del>

											<h3 class="title-4 no-margin"> $130.00/mo</h3>

											<p class="title-5 online-special-flag hide"> YEAR END SPECIAL</p>

											<a class="btn" data-unit="22" data-location="1" data-amount="1" onclick="setCookiesBookNow(`5'w x 10'd X 8'h`,175.00,10,5,8,130.00,22)"> rent now </a>

											<ul class="hide">
												<li> lowest price in the market guaranteed*</li>
												<li> choose any move-in date to start</li>
											</ul>
										</div>

										<img id="price-tag-22" class="price-tag 22 ls-is-cached lazyloaded" src="https://www.xyzstorage.com/wp-content/uploads/2019/10/Tag@2x.png" data-src="https://www.xyzstorage.com/wp-content/uploads/2019/10/Tag@2x.png">
									</div>
 								</div>

								<!-- END Use the above slide as template -->

								<div class="slide">
									<div id="post-<?php the_ID(); ?>" class="columns columns-5 white flex units">
										<div class="col img">
											<?php if ( has_post_thumbnail()) : ?>
												<div class="img-wrap">
													<?php the_post_thumbnail('small-4'); ?>
												</div>
											<?php endif; ?>
										</div>

										<div class="col desc">
											<?php if(get_field('unit_note')): ?>
												<span class="new">
													<?php the_field('unit_note'); ?>
												</span>
											<?php endif; ?>

											<h3 class="title title-4 margin">
												<span><?php echo $term->name; ?></span>

												<?php the_title(); ?>
											</h3>

											<button type="button" class="icon btn-hide">
												show details
											</button>

											<?php if(get_field('unit_description')): ?>
												<div class="hide">
													<?php the_field('unit_description'); ?>
												</div>
											<?php endif; ?>
										</div>

										<div class="col text border">
											<?php if(get_field('unit_expiration')): ?>
												<?php the_field('unit_expiration'); ?>
											<?php endif; ?>
										</div>

										<div class="col text border">
											<?php if(get_field('unit_amount')): ?>
												<?php
													$price = get_field('unit_amount');
													$price_edit = number_format($price, 2, '.', '');
												?>

												<h4>
													$<?php echo $price_edit; ?>/mo
												</h4>
											<?php endif; ?>

											<?php if(get_field('unit_price')): ?>
												<div class="hide">
													<?php the_field('unit_price'); ?>
												</div>
											<?php endif; ?>
										</div>

										<div class="col last border">
											<a class="btn btn-reserve" href="<?php bloginfo('url'); ?>/customer-details/" data-unit="<?php the_ID(); ?>" data-amount="1">
												<?php _e('reserve a unit', 'html5blank'); ?>
											</a>

											<?php if(get_field('unit_reservation')): ?>
												<div class="hide">
													<?php the_field('unit_reservation'); ?>
												</div>
											<?php endif; ?>
										</div>
									</div>
								</div>

								<div class="slide">Placeholder to test dots</div>

								<div class="slide">Placeholder to test dots</div>

								<div class="slide">Placeholder to test dots</div>

								<div class="slide">Placeholder to test dots</div>

								<div class="slide">Placeholder to test dots</div>

								<div class="slide">Placeholder to test dots</div>

								<div class="slide">Placeholder to test dots</div>

								<div class="slide">Placeholder to test dots</div>
							<?php endwhile; ?>

							<?php endif; ?>

							<?php wp_reset_query(); ?>
						</div>

						<!-- START Slider Navigation -->

						<div class="slider-units-dots">
							<?php if ($loop_2->have_posts()): while ($loop_2->have_posts()) : $loop_2->the_post(); ?>
								<div class="slide">1</div>

								<div class="slide">1</div>

								<div class="slide">1</div>

								<div class="slide">1</div>

								<div class="slide">1</div>

								<div class="slide">1</div>

								<div class="slide">1</div>

								<div class="slide">1</div>

								<div class="slide">1</div>

								<div class="slide">1</div>
							<?php endwhile; ?>

							<?php endif; ?>

							<?php wp_reset_query(); ?>
						</div>

						<!-- END Slider Navigation -->
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
			<?php endif; ?>
			<?php wp_reset_query(); ?>
		</div>
	</div>
</div>
