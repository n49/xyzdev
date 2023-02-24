<?php
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
		'container' => 'Large.png'
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

	  usort($apiunit, function ($item1, $item2) {
						 return $item1['Sq_Ft'] <=> $item2['Sq_Ft'];
					});
		$labels = [];

		$unitID_array = array();
		$units_array = array();
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
			if($unit['Sq_Ft'] == '1'){ //if it's a parking unit
			  $temp = $unit;
			  unset($apiunit[$key]); // remove from array
			  array_push($temp_array, $temp); // add it to temp array
			}
			if( strcasecmp($unit['UnitTypeCode'] , '8X20 CONT') == 0){ //if it's a container unit
			  $temp = $unit;
			  unset($apiunit[$key]); // remove from array
			  array_push($temp_array, $temp); // add it to temp array
			}
		}
		foreach($temp_array as $key => $temp_unit){
			if($temp_unit['UnitTypeId'] == 173){ // if unit is actually office
				unset($temp_array[$key]); // remove from array
	  	  array_push($temp_array, $temp_unit); // add it to the end of temp array
			}
		}
		foreach($temp_array as $temp_unit){
		  array_push($apiunit, $temp_unit); // add temporary array to end of original array
		}
	}

?>
<script src="https://xyzstorage.com/wp-content/themes/custom-theme/js/google_optimize.js"></script>
<script type="text/javascript">

  console.log("this is the location <?php echo $this_location_code ?>");

  function setCookiesBookNow(dimensions, rent, length, width, height, price, id){
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
  function setCookiesRegular(dimensions, rent, length, width, height, price, id){
		var discountName = document.getElementById("discount-cookie-"+id).innerHTML;
		document.cookie = "unitDiscounts="+discountName+'; path=/customer-details';
		document.cookie = "unitLocation=<?php echo $this_location_code ?>;path=/customer-details";
    document.cookie = 'unitType='+id+'; path=/customer-details';
    var url = "<?php bloginfo('url'); ?>/customer-details/";
    document.cookie = 'unitDimensions='+dimensions+'; path=/customer-details';
    document.cookie = 'length='+length+'; path=/customer-details';
    document.cookie = 'width='+width+'; path=/customer-details';
    document.cookie = 'height='+height+'; path=/customer-details';
    document.cookie = 'unitRent='+rent+'; path=/customer-details';
    document.cookie = 'unitPrice='+price+'; path=/customer-details';
    window.location = url;
  }
  function setCookiesMobile(dimensions, rent, length, width, height, price){

    var url = "<?php bloginfo('url'); ?>/customer-details/";
    document.cookie = 'unitDimensions='+dimensions+'; path=/customer-details';
    document.cookie = 'unitLocation=5;path=/customer-details';
    document.cookie = 'unitType=198; path=/customer-details';
    document.cookie = 'length='+length+'; path=/customer-details';
    document.cookie = 'width='+width+'; path=/customer-details';
    document.cookie = 'height='+height+'; path=/customer-details';
    document.cookie = 'unitRent='+rent+'; path=/customer-details';
    document.cookie = 'unitPrice='+price+'; path=/customer-details';
    window.location = url;
  }

	var discountObject = {};
	function getSpecialsDetails(id){
		$(document).ready(function(){
				$.ajax({url: "https://xyzstorage.com/wp-json/myplugin/v1/unit-discounts/<?php echo $this_location_code ?>/"+id+"/", success: function(result){
					//console.log(result);
					discountObject[id] = result;
					if(result.length > 0){
						if(result[0]['DiscountName'].includes("50% Off Rent")){
					    var elem = document.getElementById('price-tag-'+id);
					    elem.style.width = "50px";
					    elem.classList.remove("lazyloaded");
					    elem.src = "https://www.xyzstorage.com/wp-content/uploads/2019/10/Tag@2x.png";
							document.getElementById('btn-reserve-'+id).style.width = "120px";
							document.getElementById('btn-reserve-'+id).style.padding = "10px";
							document.getElementById('risk-free-'+id).innerHTML  = "risk free <br> reservation";
							document.getElementById('discount-cookie-'+id).innerHTML = "50% Off First Three Months";
					  }
						else if(result[0]['DiscountName'] == "3 Month 4x3x4 Promo"){
							var node = document.createElement("LI");
							var textnode = document.createTextNode("Online Special Guaranteed for first 3 full months");
							node.appendChild(textnode);
							document.getElementById("ul-"+id).appendChild(node);
							document.getElementById('discount-cookie-'+id).innerHTML = "Online Special Guaranteed for first 3 full months";
						}
						else if((result[0]['DiscountName'].includes("Student Discount") || result[0]['DiscountName'].includes("Senior Discount"))){
							document.getElementById("p-"+id).innerHTML = "10% OFF";
							var strArray = result[0]['DiscountName'].split("[");
							document.getElementById("discount-name-"+id).innerHTML = strArray[0];
							var expiry = strArray[1].split(']');
							document.getElementById("discount-expiration-"+id).innerHTML = "expires: "+expiry[0];
							document.getElementById('discount-cookie-'+id).innerHTML = strArray[0];
						}
						else if(result[0]['DiscountName'].includes("One Month Free")) {
							document.getElementById("p-"+id).innerHTML = "FIRST MONTH IS FREE";
							var strArray = result[0]['DiscountName'].split("[");
							var expiry = strArray[1].split(']');
							document.getElementById("discount-expiration-"+id).innerHTML = "expires: "+expiry[0];
							document.getElementById('discount-cookie-'+id).innerHTML = "FIRST MONTH IS FREE";
						}
						else{
							if(result[0]['DiscountName'].includes('[')){
								var strArray = result[0]['DiscountName'].split('[');
								var discountName = strArray[0];
								var expiry = strArray[1].split(']');
							}
							else{
								var discountName = result[0]['DiscountName'];
								var expiry = '';
							}
							var node = document.createElement("LI");
							var textnode = document.createTextNode(discountName);
							node.appendChild(textnode);
							document.getElementById("ul-"+id).appendChild(node);
							document.getElementById("discount-expiration-"+id).innerHTML = "expires: "+expiry[0];
							document.getElementById('discount-cookie-'+id).innerHTML = discountName;
						}
					}
				}});
		});
	}

</script>

<div id="reserve" class="tabs-wrap">
	<div id="promotion-term" style="position: absolute; color: rgba(0,0,0,0)">
		ONLINE SPECIAL
	</div>
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

      <?php $previousLabel = ''; $switch = TRUE; ?>

      <?php foreach($apiunit as $unit):  ?>
      	<?php if(($unit["AvailableUnits"] != 0 || $unit['UnitTypeId'] == '16') && $this_location_code != 5): ?>
        	<?php
        		$unitID = $unit["UnitTypeId"];
        		$unitCalculate = (int) $unit["Sq_Ft"];
         		$unitSize = round($unit["Breadth"])."'w x ".round($unit["Length"])."'d X ".round($unit["Height"])."'h";
         		$unitLength = $unit["Length"];
        		$unitFeatures = $unit["UnitTypeCode"];
        		$unitPrice = $unit["Rent"];
        		$unitCategory = $unit["CategoryName"];
        		$unitInternetPrice = $unit["InternetPrice"];
        		$unitDescription = $unit["UnitTypeDescription"];
        		$unit_details = $unit["OnlineMessage"];
        		$availableUnit = $unit["AvailableUnits"];
            if($unitCalculate == 1) {
							if($unitID == 173){
								$unitLabel = 'office';
								$unitSize = 'office rental';
							}
							else{
								$unitLabel = 'parking';
								if($unit["Height"] == 1) $unitSize = 'small';
								else $unitSize = 'large';
							}
            }
        		if($unitCalculate > 2) $unitLabel = "compact";
        		if($unitCalculate >= 25) $unitLabel = "small";
        		if($unitCalculate >= 75) $unitLabel = "medium";
        		if($unitCalculate >= 150) $unitLabel = "large";
				if( strcasecmp($unitFeatures , '8X20 CONT') == 0 ){
									$unitLabel = 'container';
									$unitSize = '';
								}
            $cat = $unitLabel;
            if (!in_array($cat,$labels)){
              array_push($labels,$cat);
              $switch = TRUE;
            }
        		$details_array = array();
        		$details_array = explode(' -',$unit_details);
        		if($details_array[0][0] == "-"){
							$details_array[0] = substr($details_array[0],1);
        		}
        	?>
        	<?php	//print_r($labels) ?>
        <?php endif; ?>

        <?php if($unit["AvailableUnits"] != 0 && $this_location_code != 5) : ?>
					<div <?php if($switch){ echo "id='".strtolower($unitLabel)."'"; $switch = FALSE; } post_class('overflow tab margin'); ?> style='margin-bottom:0px'>

					  <?php
					    $currentLabel = $unitLabel;
					    if($previousLabel != $currentLabel){
					      $previousLabel = $currentLabel;

					  ?>
						<h2 class="title <?php echo $currentLabel; ?>">
							<?php echo $currentLabel; ?>
						</h2>
						<?php } ?>

							<div id="post-<?php echo $unitID; ?>" class="columns columns-5 white flex units" >
								<div class="col img">
										<div class="img-wrap" style="background:#FFFFFF">
											<img src="<?php echo $image_url_pre.$image_url_mids[$unitLabel].$image_url_post;?>" class="attachment-small-4 size-small-4 wp-post-image" style="padding:20px;" alt="" />
										</div>
								</div>

								<div class="col desc">


									<h3 class="title title-4 margin">
										<?php echo $unitSize; ?>
									</h3>
									<?php if($unitLabel != 'office'): ?>
									<h3 class="title title-4">
										<?php echo $unitLabel; ?>
									</h3>
								<?php endif; ?>
                  <ul>
                    <?php
                      // Need to make this better later...
                      $real_description = '';
                      $desc = explode(' ',$unitFeatures)[1];

											if(strcasecmp($desc, 'SFS') == 0)
											{
												$real_description = 'Second Floor, Stairs Access';
											}

											else if(	strcasecmp($desc, 'WAREHOUSE') == 0 || strcasecmp($desc, 'GF') == 0 ||
																strcasecmp($desc, 'IN') == 0 || strcasecmp($desc, 'Main') == 0 )
											{
											 	$real_description = 'Ground Floor';
											}
											else if( strcasecmp($desc, 'CONT') == 0 )
											{
												$real_description = 'Outdoor Container';
											}
											else if( strcasecmp($desc, 'du') == 0 || strcasecmp($desc, 'out') == 0 )
											{
												$real_description = 'Drive Up Unit';
											}

											else if( 	strcasecmp($desc, 'SF') == 0 || strcasecmp($desc, 'upper') == 0 ||
																strcasecmp($desc, 'upstairs') == 0 || strcasecmp($desc, 'tf') == 0 ||
																strcasecmp($desc, 'FT') == 0 )
											{
												$real_description = 'Upper Floor';
											}

                      						if(	$real_description != '' &&
													strcasecmp($real_description, 'PARKING') != 0 &&
													strcasecmp($real_description, 'OFFICE') != 0 )
											{
												echo '<li><strong>'.$real_description.'</strong></li>';
											}

                      if(sizeof($details_array) > 0){
                        foreach($details_array as $detail){
                          if(strlen($detail) > 0) {
                            ?>
                              <li> <?php echo $detail; ?> </li>
                            <?php
                          }
                        }
                      }
                      else{
                    ?>
                     	<li>*PLACEHOLDER TEXT*</li>
                     	<li>Could not get features from API</li>
                     	<li>Probably no features set up on SSM</li>
                     	<li>Make Sure to list features using a dash " - "</li>
                   	<?php
                      }
                   	?>
                  </ul>

								</div>
								<script>
									getSpecialsDetails(<?php echo $unitID?>);
								</script>
								<div class="col text border">
									<ul id="ul-<?php echo $unitID?>" style="margin:0px"></ul>
									<p class="title-5" ><span style="color: #42be2d;" id="p-<?php echo $unitID?>"></span></p>
									<strong id="discount-name-<?php echo $unitID?>"></strong>
									<div id="discount-expiration-<?php echo $unitID?>"></div>
									<div id="discount-cookie-<?php echo $unitID?>" style="height:0px;width:0px;font-size:0px"></div>

                  <?php // new part here ?>

                  <p>
                    <?php
                      $availUnits = 0;
                      if($availableUnit < 5) $availUnits = 1;
                      else if($availableUnit < 15) $availUnits = 2;
                      else $availUnits = 3;

                      echo $availableUnit; ?>

                     unit<?php if ($availableUnit > 1) echo "s";?> left at this location
                  </p>


								</div>

                <div class="col text border">
										<?php
											$price = $unitInternetPrice;
										?>
										<?php if($unit["Rent"] != $price): ?>
									<h3 class="title-4 price-opt" data-price="<?php echo $unitPrice; ?>" style="margin-top: 20px">
									  <?php else: ?>
									  <h3 class="title-4 price-opt" data-price="<?php echo $unitPrice; ?>" style="margin-top: 0px">
									  <?php endif;?>
											$<?php echo $unitPrice; ?>/mo
										</h3>
									<p class="title-5" style="margin:0px 0px 25px 0px;">risk free reservation</p>
									<a class="btn btn-reserve"
									    data-unit="<?php echo $unitID; ?>"
									    data-location="<?php echo $this_location_code?>"
									    data-amount="1"
  									  onclick="setCookiesRegular(<?php echo "`".$unitSize."`,".$unit["Rent"].",".$unit["Length"].",".$unit["Breadth"].",".$unit["Height"].",".$unitPrice.",".$unitID ?>)">
										<?php _e('reserve now', 'html5blank'); ?>
									</a>
										<ul>
											<li>
												no credit card required to reserve
											</li>
											<li>
												cancel anytime
											</li>
									</ul>

								</div>

                <div class="col last border">
                <?php if($unit["Rent"] != $price): ?>
                  <p style="position: absolute"><del><span class="price-opt-red" data-price="<?php echo $unit["Rent"]; ?>" style="color: #E0581D;">
					  $<?php echo $unit["Rent"]; ?>
					</span></del></p>
                  <h3 class="title-4 price-opt" data-price="<?php echo $price; ?>" style="margin:20px 0px 0px 0px">
                <?php else: ?>
                    <h3 class="title-4 price-opt" data-price="<?php echo $price; ?>" style="margin: 0px">
                <?php endif;?>
                      $<?php echo $price; ?>/mo
                    </h3>
                  <?php if($unit["Rent"] != $price): ?>
                    <p class="title-5 online-special-flag"
                        style="
                                margin:6px 0px 14px;
                                background-color: #E0581D;
                                color: #FFF;
                                width: max-content;
                                border-radius: 2px;
                                padding: 4px 8px;"
                                >
                      ONLINE SPECIAL
                    </p>
                  <?php else: ?>
                    <div style="height: 42px;"></div>
                  <?php endif; ?>
                  <a class="btn rent-now-button"
                      data-unit="<?php echo $unitID; ?>"
                      data-location="<?php echo $this_location_code?>"
                      data-amount="1"
                      onclick="setCookiesBookNow(<?php echo "`".$unitSize."`,".$unit["Rent"].",".$unit["Length"].",".$unit["Breadth"].",".$unit["Height"].",".$price.",".$unitID ?>)">
                      <?php _e('rent now', 'html5blank'); ?>
                  </a>
                  <ul>
                    <li>
                      lowest price in the market guaranteed*
                    </li>
                    <li>
                      choose any move-in date to start
                    </li>
                  </ul>

                <img
                id="price-tag-<?php echo $unitID; ?>"
                class="price-tag <?php echo $unitID; ?>"
                src="https://www.xyzstorage.com/wp-content/uploads/2019/10/Tag@2x.png"
                style="position: absolute;
                      right: 0px;
                      top: -30px;
                      width: 0px;
                      z-index: 10;"
                />
                </div>
							</div>
					</div>
				<?php endif; ?>
      <?php endforeach; ?>
      <?php
      // --- Mobile ---
      ?>
      <div id="mobile-storage" <?php post_class('overflow tab margin'); ?> style='margin-bottom:0px'>

						<h2 class="title mobile">
							mobile
						</h2>
							<div id="post-198" class="columns columns-5 white flex units" >
								<div class="col img">
										<div class="img-wrap" style="background:#FFFFFF">
											<img src="<?php echo $image_url_pre.$image_url_mids['mobile'].$image_url_post ?>" style="padding:20px" class="attachment-small-4 size-small-4 wp-post-image" alt="" />
										</div>
								</div>
								<div class="col desc">
									<h3 class="title-4 margin">
									  5'd x 8'w x 7'h
								  </h3>
									<h3 class="title-4">
										mobile
									</h3>

									<?php
										$curl = curl_init();
									  curl_setopt_array($curl, array(
                  	  CURLOPT_URL => "https://www.secure.selfstoragemanager.com/ssmwebserviceV2.1/ssmws.asmx/GetUnitDetails?strCustomerCode=xyzstorage&strCustomerPassword=991852130418&strLocationCode=5&strSortType=1",
                  	  CURLOPT_RETURNTRANSFER => true,
                  	  CURLOPT_MAXREDIRS => 10,
                  	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  	  CURLOPT_CUSTOMREQUEST => "GET",
                  	  CURLOPT_HTTPHEADER => array(
                  		"cache-control: no-cache",
                  	  ),
                  	));
                  	$response_mobile = curl_exec($curl);
                  	$xml_mobile = simplexml_load_string($response_mobile, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
                  	$json_mobile = json_encode($xml_mobile);
                  	$api_unit_info = json_decode($json_mobile,TRUE)['UnitType'];

                  	$mobile_unit;
                  	foreach($api_unit_info as $u){
                  	  if($u['UnitTypeId'] == 198){
                  	    $mobile_unit = $u;
                  	    break;
                  	  }
                  	}
                  	$has_internet_price = $mobile_unit['InternetPriceAvailable'];
                  	$internet_price = $mobile_unit['InternetPrice'];
                  	$regular_price = $mobile_unit['Rent'];
                  	if($has_internet_price == 1){
                  	  $real_price = $internet_price;
                  	}
                  	else{
                  	  $real_price = $regular_price;
                  	}
									?>

                  <ul>
                    <?php
                      $details_array = array();
                  		$details_array = explode(' -',$mobile_unit['OnlineMessage']);
                  		if($details_array[0][0] == "-"){
												$details_array[0] = substr($details_array[0],1);
                  		}
                  		if(sizeof($details_array) > 0){
                        foreach($details_array as $detail){
                          if(strlen($detail) > 0) {
                            echo '<li>'. $detail .'</li>';
                          }
                        }
                      }
                      else{
                    ?>
                     	<li>*PLACEHOLDER TEXT*</li>
                     	<li>Could not get features from API</li>
                     	<li>Probably no features set up on SSM</li>
                     	<li>Make Sure to list features using a dash " - "</li>
                   	<?php
                      }
                    ?>
                  </ul>
								</div>

								<div class="col text border">
                  <?php
                  	curl_setopt_array($curl, array(
                  	  CURLOPT_URL => "https://www.secure.selfstoragemanager.com/ssmwebserviceV2.1/ssmws.asmx/GetUnitTypeDiscounts?strCustomerCode=xyzstorage&strCustomerPassword=991852130418&strLocationCode=5&strUnitTypeId=198",
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
                  	$api_unit_details = json_decode($json,TRUE)["Discount"];

                  	foreach($api_unit_details as $key => $unit_detail){
                  	  if($unit_detail['DonotDisplayOnWebsite'] != 'True'){
                  	    $discount_name = explode(' - ',$unit_detail);
                  	    echo '<li>'.$unit_detail["DiscountName"].'</li>';
                  	    echo '<p>'.$discount_name[1].'</p>';
                  	  }
                  	}
                  ?>
                  </ul>

								</div>

								<div class="col text border">
										<?php
										if($has_internet_price == 1) echo 'online special';
  										?>
  										<h3 class="title-4">
  											$<?php if($has_internet_price == 1) echo $internet_price; else echo $regular_price ?>/mo
  										</h3>
                      <?php
                        if($has_internet_price == 1){ ?>
                          in-store price
                          <p><del><span style="color: #ff1e00;">	$<?php echo $regular_price; ?></span></del> </p>
                      <?php
                        }
                      ?>
                      lowest price in the market guaranteed*

								</div>
                <script>
                </script>
								<div class="col last border">
									<a class="btn btn-reserve" style="cursor:pointer" data-unit="198" data-location="5" data-amount="1"
									    onclick="setCookiesMobile(`5'd x 8'w x 7'h` , <?php echo $regular_price?> , 8 , 5 , 7 , <?php echo $real_price?>)">
										<?php _e('reserve a unit', 'html5blank'); ?>
									</a>
                <p class="title-5">risk free reservation</p>

								</div>
							</div>
					</div>


		</div>
	</div>
</div>


<?php
	/*
	$url1 = "https://www.secure.selfstoragemanager.com/ssmwebserviceV2.1/ssmws.asmx/GetUnitTypeDiscounts".
					"?strCustomerCode=xyzstorage".
					"&strCustomerPassword=991852130418".
					"&strLocationCode=".$this_location_code.
					"&strUnitTypeId=".$unitID;
	curl_setopt_array($curl, array(
		CURLOPT_URL => $url1,
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
	$api_unit_details = json_decode($json,TRUE);
	$one_month_free = FALSE;
	//echo sizeof($api_unit_details['Discount']['DiscountType']);
	//var_dump($api_unit_details);
	$single_detail = FALSE;
	$temp_d = array();

	// Below part is necessary because when response is only 1 discount, php is treating it as a single discount rather than an array of discounts
	if($api_unit_details['Discount']['DiscountType']){
		 array_push($temp_d, $api_unit_details['Discount']);
	}
	else{
		foreach($api_unit_details['Discount'] as $discount){
			if($discount['DonotDisplayOnWebsite'] == 'False'){
				array_push($temp_d, $discount);
			}
		}
	}
	$api_unit_details = $temp_d;

	foreach($api_unit_details as $key => $unit_detail){
		if($unit_detail['DonotDisplayOnWebsite'] != 'True'){
			$temp = explode('[',$unit_detail["DiscountName"]);
			$discount_name = $temp[0];
			if($discount_name == 'One Month Free '){
				echo '';//'<p class="title-5"><span style="color: #42be2d;">FIRST MONTH IS FREE</span></p>';
				$temp = explode(']',$temp[1]);
				$expiry_date = $temp[0];
				if($expiry_date != '') echo '';//'expires: '.$expiry_date;
			}
		}
	}
	foreach($api_unit_details as $unit_detail){
		if($unit_detail['DonotDisplayOnWebsite'] != 'True'){
			if(strpos($unit_detail["DiscountName"], '[') !== false && strpos($unit_detail["DiscountName"], ']') !== false){
				$temp = explode('[',$unit_detail["DiscountName"]);
				$discount_name = $temp[0];
				if($discount_name == 'Student Discount ' || $discount_name == 'Senior Discount '){
					echo '';//'<p class="title-5"><span style="color: #42be2d;">10% OFF</span></p><strong>'.$discount_name.'</strong><br>';
					$temp = explode(']',$temp[1]);
					$expiry_date = $temp[0];
					if($expiry_date != '') echo '';//'expires: '.$expiry_date;
				}
				else if($discount_name != 'One Month Free '){
					echo '<li>'.$discount_name.'</li>';
					$temp = explode(']',$temp[1]);
					$expiry_date = $temp[0];
					if($expiry_date != '') echo 'expires: '.$expiry_date;
				}
			}
			else{
				$discount_name = $unit_detail["DiscountName"];
				if($discount_name == '3 Month 4x3x4 Promo') echo '';//'<ul><li style="list-style-position: outside;">Online Special Guaranteed for 3 months</li></ul>';
				else echo '<li>'.$discount_name.'</li>';
			}
		}
	}
	*/
?>
