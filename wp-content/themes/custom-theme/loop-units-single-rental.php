<?php
	if (isset($_COOKIE['unit'])) {
		$unit = json_decode(stripslashes($_COOKIE['unit']), true);
		$unit_id = $unit['unit'];
		$unit_amount = $unit['amount'];
	}

//monthly pricing
$mode = 0;

function monthly_to_daily($monthly_price) {
$days_in_month = date("t");

$daily = $monthly_price / $days_in_month;

// round to 2 decimal places
$daily = round($daily, 2);
return $daily;
}
	

	if(isset($_COOKIE['priceMode'])) {
		if($_COOKIE['priceMode'] == 'daily') {
			//daily pricing
			$mode = 1;
		}
	}

	$unit_info = array(
	    'unit_location' => $_COOKIE['unitLocation'],
	    'unit_quantity' => $_COOKIE['unitQuantity'],
	    'unit_id' => $_COOKIE['unitType']
	  );
	 if($unit_info['unit_id'] == '198'){ //Mobile Unit
	   $unit_info['unit_location'] = '5';
	 }

	$args = array(
		'post_type' => 'units',
		'posts_per_page'=> 1,
		'p' => $unit_id
	);

	$terms = wp_get_post_terms($unit_id, 'unit-category');

	$loop = new WP_Query($args);

// 	echo $_COOKIE['unitDimensions'];
	$dimensions = $_COOKIE['unitDimensions'];
	$dimensions = str_replace("\\","",$dimensions);
	//echo $dimensions;

	$rent = $_COOKIE['unitRent'];
	//echo $rent;

	$locations_array = array(
													1 => 'Scarborough',
													2 => 'Mississauga',
                          3 => 'Toronto West',
                          4 => 'Etobicoke',
                          5 => 'Mobile',
                          6 => 'Toronto Midtown',
                          7 => 'Toronto Downtown'
                        );

$locations_street_address_array = array(
	                          3 => '207 Weston Road,<br/>Toronto, Ontario M6N 4Z3',
	                          4 => '2256 Lake Shore Blvd West,<br/> Etobicoke, Ontario M8V 1A9',
	                          1 => '135 Beechgrove Drive,<br/> Scarborough, Ontario M1E 3Z3',
	                          2 => '2480 Stanfield Road, ,<br/> Mississauga, ON L4Y 1R6, Canada',
	                          5 => 'Storage units delivered anywhere in the greater Toronto area. <br/> We come to your address.',
	                          6 => '1 Laird Drive,<br/> Toronto, Ontario M4G 3S8',
	                          7 => '459 Eastern Avenue,<br/> Toronto, Ontario M4M 1C2'
	                        );

  $location_name = $locations_array[intval($_COOKIE['unitLocation'])];
	$location_street_address = $locations_street_address_array[intval($_COOKIE['unitLocation'])];


?>
<div style="display: flex; justify-content: space-between; align-items: center">
	

						<div style="height:auto; float:left" class="tabs-wrap has-tooltip">
				<div style="position:relative; top: auto; right: auto; z-index: auto" class="normal-tooltip">
					<p>unit rental cost</p>

					<div style="width:700px" class="tooltip">
						You have the option to see unit rental costs monthly or daily. Monthly rent is the rent cost from the 1st day of the month to the last day of the month. Daily rent is the rate you would pay daily, calculated by dividing the total days in the month by the monthly rent value. All payments are made on the 1st of the month, payments cannot be made daily.
					</div>
				</div>
					</div>
		<div style="float:right; width: 50%" class="tabs white toggle active">
					<ul style="position:relative; top:0px" class="horizontal">
						<li class="monthlyMode">
							<a class="monthlySwitch normal">
								monthly
							</a>
						</li>

						<li class="dailyMode">
							<a class="dailySwitch normal">
								daily
							</a>
						</li>

						
					</ul>
				</div>
	</div>

<h3 class="title">
	<span class="span-mod">rental</span> summary
</h3>

<div id="post-<?php the_ID(); ?>" class="columns units single last" style="margin-bottom: 10px">
	<div class="col">
		<?php
			$area = get_field_object('unit_area');

			$area_first = $area['value'][0];
			$area_post = get_page_by_title($area_first, $output, 'locations');
			$area_post_id = $area_post->ID;
			$area_post_location = get_field('location_address', $area_post_id);
			$area_post_address = $area_post_location['address'];
			$location = get_field('location_address');
			$streetaddress = $location['address'];
		?>

		<p class="size">
			<?php _e('location', 'html5blank'); ?>
		</p>

		<h4 class="title">
			<?php echo $location_name; ?>
		</h4>

		<p class="address">
			<?php echo $location_street_address ?>
		</p>

 		<p class="Size">
			<?php _e('size', 'html5blank'); ?>
		</p> 

		<?php
		//var_dump($unit_amount);
			$price_edit = $rent;
			$numberofdays = date('t');
			$eachdayrent = $rent / $numberofdays;
			$daysremaning =  date('t') - date('j') + 1;
			$insurance = number_format(0.00, 2);
			$proratedAmount = number_format($eachdayrent * $daysremaning, 2);
// 						var_dump($proratedAmount);

			//$price_edit = number_format($price * $unit_amount, 2, '.', '');
		?>

		<h4 class="title has-price">
			<?php echo $dimensions; ?>
			<span style="display:none" id="monthlyRentNowAmountSection">$<?php echo number_format(($price_edit), 2) ?></span>
			<?php if($mode): ?>
			<span id="rentNowAmountSection">$<?php echo number_format(monthly_to_daily($price_edit), 2) ?>/day</span>
			<?php else: ?>
			<span id="rentNowAmountSection">$<?php echo number_format($price_edit, 2) ?>/mth</span>
			<?php endif; ?>
		</h4>

		<div class="cost-wram">
			<h3 class="title">
				<?php _e('cost to move-in', 'html5blank'); ?>
			</h3>

			<p id="rentalDescriptionLabel">
							<?php if(1==1): ?>

				<?php _e('prorated monthly rent', 'html5blank'); ?>
				<br class="showMobile" />
				<span id="dateRangeRentalSummary" style="all:unset;">
									<?php echo "from " . getCurrentMonthDate() . " - " . date('t'); ?>
				</span>

							<?php else: ?>

				<?php _e('prorated monthly rent', 'html5blank'); ?>
			<?php endif; ?>

							<?php if(1==2): ?>

				<span>$<span id="monthlyProRatedAmountS"><?php echo monthly_to_daily($proratedAmount); ?></span></span>
											<?php else: ?>
				<span>$<span id="monthlyProRatedAmount"><?php echo $proratedAmount; ?></span></span>
			<?php endif; ?>

			</p>
<!-- 				<span style="display:none">$<span style="display:none" id="monthlyProRatedAmount"><?php echo ($proratedAmount); ?></span></span> -->

<!--  			<p>
				<?php _e('specials: ', 'html5blank'); ?> <br><span class="sale">
				<span class="tester12323" style="all:unset; display:block"><?php _e('Takes effect  at first full month', 'html5blank'); ?></span>
				<br style="display:none">
				<span style="all:unset"><?php _e('(50% off rent - 3 months)', 'html5blank'); ?></span>
				</span>
							<?php if(1==2): ?>

				<span>($<?php echo number_format((monthly_to_daily($price_edit)/2), 2) ?>)</span>
															<?php else: ?>
				<span>($<?php echo number_format(($price_edit/2), 2) ?>)</span>
			<?php endif; ?>

			</p>  -->

			<p>
				<?php _e('deposit: ', 'html5blank'); ?>

				<span>$0.00</span>
			</p>

			<p>
				<?php _e('insurance: ', 'html5blank'); ?>

				<span>$<span id="insuranceText"><?php echo $insurance ?></span></span>
			</p>

			<?php
				$price_tax = 0.13*$proratedAmount;
				$price_tax_edit = number_format($price_tax, 2, '.', '');
							$price_total_edit = number_format($price_tax_edit + $proratedAmount + $insurance, 2)

			?>

			<p>
				<?php _e('estimated sales tax', 'html5blank'); ?>
							<?php if(1==2): ?>

				<span>$<span id="salesTaxText"><?php echo monthly_to_daily($price_tax_edit); ?></span></span>
																			<?php else: ?>
				<span>$<span id="salesTaxText"><?php echo $price_tax_edit; ?></span></span>
<?php endif; ?>
			</p>

	

			<p class="total last">
				<?php _e('total', 'html5blank'); ?>
							<?php if(1==2): ?>

				<span>$<span id="totalCreditCardAmount"><?php echo monthly_to_daily($price_total_edit); ?></span></span>
																							<?php else: ?>
				<span>$<span id="totalCreditCardAmount"><?php echo $price_total_edit; ?></span></span>
<?php endif; ?>

			</p>
		</div>
		<div class="specials-applied">
			 <p>
				 <strong>specials applied:</strong>
			</p>
			<p class="specials-description">
				<?php echo $_COOKIE["promoLabel"]?> (takes effect in the first full month) <br class="showMobile" />
				Your <span style="all:unset" id="selectedMonthRentalSummary"> <?php echo date('F', strtotime('+1 month')) ?></span> rent will be <strong>$<?php echo number_format($_COOKIE["unitPrice"] * $_COOKIE["promo"], 2) ?></strong>, a savings of
				<strong>$<?php echo number_format($_COOKIE["unitPrice"] - ($_COOKIE["unitPrice"] * $_COOKIE["promo"]), 2) ?></strong>.
			</p>
		</div>
	</div>
</div>
<div>
	

<strong>Note:</strong> XYZ Storage charges rent on the 1st of every month. The price shown above is your monthly rental price, prorated to the number of days in your first month of stay.  Leaving before the end of the month? No problem, we will prorate your rent once you have moved out of your unit, and will provide a refund for the number of days you haven't used.
</div>

	<?php //the_field('customer_notice'); ?>
