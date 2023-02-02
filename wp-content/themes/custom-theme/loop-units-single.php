<?php
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

	//echo $_COOKIE['unitDimensions'];
	$dimensions = $_COOKIE['unitDimensions'];
	$dimensions = str_replace("\\","",$dimensions);
	//echo $dimensions;

	$rent = $_COOKIE['unitPrice'];
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
	                          7 => '459 Eastern Avenue,<br/> Toronto, Ontario M4M 1B7'
	                        );

  $location_name = $locations_array[intval($_COOKIE['unitLocation'])];
	$location_street_address = $locations_street_address_array[intval($_COOKIE['unitLocation'])];


?>


<h3 class="title">
	<?php if(get_field('customer_label_summary')): ?>
		<?php the_field('customer_label_summary'); ?>
	<?php else: ?>
		<span class="span-mod">reservation</span> summary
	<?php endif; ?>
</h3>

<?php //if ($loop->have_posts()): while ($loop->have_posts()) : $loop->the_post(); ?>
	<div id="post-<?php the_ID(); ?>" class="columns units single">
		<div class="col">
			<?php //if(get_field('unit_area')): ?>
				<?php
					$area = get_field_object('unit_area');
			//var_dump($_COOKIE);
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
					<?php //if(get_field('location_custom_address_2', $area_post_id)): ?>
						<?php the_field('location_custom_address_2', $area_post_id); ?>
					<?php //else: ?>

						<?php echo $location_street_address ?>
					<?php //endif; ?>
				</p>
			<?php //endif; ?>

			<p class="Size">
				<?php _e('size', 'html5blank'); ?>
			</p>

			<h4 class="title">
				<?php echo $dimensions; ?>
			</h4>

			<p>

				<span>
					<?php _e('qty:', 'html5blank'); ?> <?php echo $unit_amount; ?>
				</span>
			</p>

			<h3 class="title">
				<?php _e('cost to move-in', 'html5blank'); ?>
			</h3>

			<?php //if(get_field('unit_amount')): ?>
				<?php
					$price = $rent;
					$price_edit = number_format($price * $unit_amount, 2, '.', '');
				?>

				<p>
					<?php _e('rent', 'html5blank'); ?>

					<span>$<?php echo $price_edit; ?>/mo</span>
				</p>
			<?php //endif; ?>

			<?php //if(get_field('unit_tax')): ?>
				<?php
					$price_tax = 0.13*$rent;
					$price_tax_edit = number_format($price_tax * $unit_amount, 2, '.', '');
				?>

				<p>
					<?php _e('estimated sales tax', 'html5blank'); ?>

					<span>$<?php echo $price_tax_edit; ?>/mo</span>
				</p>
			<?php //endif; ?>

			<?php //if(get_field('unit_total')): ?>
				<?php
					$price_total = $price_tax+$rent;//get_field('unit_total');
					$price_total_edit = number_format($price_total * $unit_amount, 2, '.', '');
				?>

				<p class="total">
					<?php _e('total', 'html5blank'); ?>

					<span>$<?php echo $price_total_edit; ?>/mo</span>
				</p>
			<?php //endif; ?>
		</div>
	</div>
<?php //endwhile; ?>

<?php //endif; ?>

<?php wp_reset_query(); ?>

<?php if(get_field('customer_notice')): ?>
	<?php the_field('customer_notice'); ?>
<?php endif; ?>
