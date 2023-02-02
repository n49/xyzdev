<?php /* Template Name: Choose Your Space */ get_header(); ?>

<?php

	$unit_type = $_COOKIE["unitType"];
	$location = $_COOKIE["unitLocation"];

	$unit_type_prices = get_unit_type_prices($location)[$unit_type];

	// var_dump($unit_type_prices);

	if(!$unit_type_prices || sizeof($unit_type_prices) == 1){
		wp_redirect('/rent-now');
	}

	$prices = [];
	foreach(array_keys($unit_type_prices) as $key){
		$temp_price = $key;
		array_push($prices, $temp_price);
	}

	sort($prices);

	// var_dump($unit_type_prices);
	if(sizeof($prices) == 2){
		$basic = "not available";
		$value = $prices[0];
		$premium = $prices[1];
		$premium_count = $unit_type_prices[$premium];
		$value_count = $unit_type_prices[$value];
		$basic_count = 0;
	} else {
		$basic = $prices[0];
		$value = $prices[1];
		$premium = $prices[2];
		$premium_count = $unit_type_prices[$premium];
		$value_count = $unit_type_prices[$value];
		$basic_count = $unit_type_prices[$basic];
	}

	$h = $_COOKIE["height"];
	$w = $_COOKIE["width"];
	$l = $_COOKIE["length"];

	$unit_calculate = $w * $l;
	if($unit_calculate <= 2) $label = "parking";
	else if($unit_calculate < 25) $label = "compact";
	else if($unit_calculate < 75) $label = "small";
	else if($unit_calculate < 150) $label = "medium";
	else $label = "large";

	$image_url_pre = $url_domain.'/wp-content/uploads/2019/05/';
	$image_url_post = '';
	$image_url_mids = array(
		'compact' => 'Compact.png',
		'small' => 'Small.png',
		'medium' => 'Medium.png',
		'large' => 'Large.png',
		'mobile' => 'mobile-storage-1.png',
		'parking' => 'Parking.png',
		'container' => 'Large.png',
	);


	$promo = $_COOKIE["promo"];


?>

<div class="header-wrap">
	<div class="wrapper">
		<h1 class="title">
			<?php the_title(); ?>
		</h1>
	</div>
</div>

<div class="main-container has-bgr-grey" style="background-image: url('<?php the_field('page_background_image'); ?>');">
	<div class="wrapper narrow">
		<main role="main" class="fullwidth">
			<?php if (have_posts()): while (have_posts()) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php the_content(); ?>
				</article>
			<?php endwhile; ?>

			<?php endif; ?>

			<div class="content-wrap inline">
				<div class="img-wrap">
					<img src="<?php echo $image_url_pre.$image_url_mids[$label].$image_url_post;?>" alt="Compact size storage">
				</div>

				<div class="content">
					<h3><?php echo $w; ?>'w x <?php echo $l; ?>'d x <?php echo $h; ?>’h</h3>

					<h1><?php echo $label; ?></h1>
				</div>
			</div>

			<div class="columns columns-3 flex prices has-btn mt-20">
				<div class="col">
					<div class="title">
						<h4>basic</h4>
						<?php if($promo && $basic != "not available"): ?>
							<del style="color:red;">
								<?php echo "$" . number_format($basic, 2, '.', '');; ?>
							</del>
						<?php endif; ?>
						<h3>
							<?php
								if($basic == "not available"){
									echo $basic;
								} elseif($promo){
									echo "$" . number_format($basic * floatval($promo), 2, '.', '') . "/mth";
								} else {
									echo $basic . "mth";
								}
							?>
						</h3>
						<?php if($basic_count == 1):?>
							<p>1 unit left at this location</span>
						<?php elseif($basic_count):?>
							<p><span><?php echo $basic_count; ?></span> units left at this location</span>
						<?php else:?>
							<p>no units left at this location</span>
						<?php endif; ?>

						<?php if($basic_count && $_COOKIE["promoLabel"]): ?>
							<p class="notice">
								<?php echo $_COOKIE["promoLabel"]; ?>
							</p>
						<?php endif; ?>

					</div>

					<ul class="list">
						<li>lowest price</li>
						<li>easy code access</li>
						<li>easy payment on the customer portal</li>
						<li>free wi-fi</li>
						<li>complimentary on site moving dollies</li>
					</ul>

					<?php if($basic_count):?>
						<a class="btn btn-hover-grey select-space" data-plan="Basic" data-price="<?php echo $basic; ?>">select</a>
					<?php else:?>
						<a class="btn btn-grey btn-disabled" href="#">sold out</a>
					<?php endif; ?>
				</div>

				<div class="col">
					<div class="title blue">
						<h4>value</h4>
						<?php if($promo && $value != "not available"): ?>
							<del style="color:red;">
								<?php echo "$" . number_format($value, 2, '.', '');; ?>
							</del>
						<?php endif; ?>
						<h3>
							<?php
								if($value == "not available"){
									echo $value;
								} elseif($promo){
									echo "$" . number_format($value * floatval($promo), 2, '.', '') . "/mth";
								} else {
									echo $value . "mth";
								}
							?>
						</h3>

						<?php if($value_count == 1):?>
							<p>1 unit left at this location</p>
						<?php elseif($value_count):?>
							<p><span><?php echo $value_count; ?></span> units left at this location</p>
						<?php else:?>
							<p>no units left at this location</p>
						<?php endif; ?>
						<?php if($_COOKIE["promoLabel"]): ?>
							<p class="notice">
								<?php echo $_COOKIE["promoLabel"]; ?>
							</p>
						<?php endif;?>
					</div>

					<ul class="list">
						<li>all features of basic plus:</li>
						<li>closer to the elevators, docks, and entry / exits</li>
						<li>24 hour access available</li>
						<li>half price truck rental for move in</li>
					</ul>

					<?php if($value_count):?>
						<a class="btn btn-hover-teal select-space" data-plan="Value" data-price="<?php echo $value; ?>">select</a>
					<?php else:?>
						<a class="btn btn-grey btn-disabled" href="#">sold out</a>
					<?php endif; ?>
				</div>

				<div class="col">
					<div class="title yellow">
						<h4>premium</h4>
						<?php if($promo && $premium != "not available"): ?>
							<del style="color:red;">
								<?php echo "$" . number_format($premium, 2, '.', ''); ?>
							</del>
						<?php endif; ?>
						<h3>
							<?php
								if($premium == "not available"){
									echo $premium;
								} elseif($promo){
									echo "$" . number_format($premium * floatval($promo), 2, '.', '') . "/mth";
								} else {
									echo $premium . "mth";
								}
							?>
						</h3>

						<?php if($premium_count == 1):?>
							<p>1 unit left at this location</span>
						<?php elseif($premium_count):?>
							<p><span><?php echo $premium_count; ?></span> units left at this location</span>
						<?php else:?>
							<p>no units left at this location</span>
						<?php endif; ?>

						<?php if($_COOKIE["promoLabel"]): ?>
							<p class="notice">
								<?php echo $_COOKIE["promoLabel"]; ?>
							</p>
						<?php endif;?>
					</div>

					<ul class="list">
						<li>all features of basic and value plus:</li>
						<li>best locations; closest to the elevators, docks, and entry / exits</li>
						<li>free lock</li>
						<li>free price truck rental for move in</li>
					</ul>

					<?php if($premium_count):?>
						<a class="btn btn-hover-grey select-space" data-plan="Premium" data-price="<?php echo $premium; ?>">select</a>
					<?php else:?>
						<a class="btn btn-grey btn-disabled" href="#">sold out</a>
					<?php endif; ?>
				</div>
			</div>

			<div class="columns columns-2 text-white">
				<div class="col">
					<p><?php _e('rent now to lock in this discount.', 'html5blank'); ?></p>
				</div>

				<div class="col text-right">
					<a class="btn btn-white" id="go-back-button">
						<?php _e('back to units', 'html5blank'); ?>
					</a>
				</div>
			</div>
		</main>
	</div>
</div>

<?php get_template_part('module-cta-simple'); ?>

<?php get_footer(); ?>
