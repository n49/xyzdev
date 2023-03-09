<?php
// var_dump('show me all', $_COOKIE);
	if (isset($_COOKIE['unit'])) {
		$unit = json_decode(stripslashes($_COOKIE['unit']), true);
		$unit_id = $unit['unit'];
		$unit_amount = $unit['amount'];
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


<h3 class="title">
	<span class="span-mod">rental</span> summary
</h3>

<div id="post-<?php the_ID(); ?>" class="columns units single last">
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

			<span>$<?php echo number_format($price_edit, 2) ?></span>
		</h4>

		<div class="cost-wram">
			<h3 class="title">
				<?php _e('cost to move-in', 'html5blank'); ?>
			</h3>

			<p>
				<?php _e('protated monthly rent', 'html5blank'); ?>

				<span>$<span id="monthlyProRatedAmount"><?php echo $proratedAmount; ?></span></span>
			</p>

 			<p>
				<?php _e('specials: ', 'html5blank'); ?> <br><span class="sale">
				<span class="tester12323" style="all:unset; display:block"><?php _e('Takes effect  at first full month', 'html5blank'); ?></span>
				<br>
				<span style="all:unset"><?php _e('(50% off rent - 3 months)', 'html5blank'); ?></span>
				</span>

				<span>($<?php echo number_format(($price_edit/2), 2) ?>)</span>
			</p> 

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

				<span>$<span id="salesTaxText"><?php echo $price_tax_edit; ?></span></span>
			</p>

	

			<p class="total last">
				<?php _e('total', 'html5blank'); ?>

				<span>$<span id="totalCreditCardAmount"><?php echo $price_total_edit; ?></span></span>
			</p>
		</div>
	</div>
</div>

<?php if(get_field('customer_notice')): ?>
	<?php the_field('customer_notice'); ?>
<?php endif; ?>
