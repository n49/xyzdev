<?php /* Template Name: Booking Confirmed */ get_header(); ?>


<?php
	/*
	test:
	https://www.xyzstorage.com/booking-confirmed/?user_name=eric%20fleith&user_location=2&rent=%24243.00&phone=6479631427&email=ericfleith%40gmail.com&movedate=07-30-2020&size=10%E2%80%99w%20x%2010%E2%80%99d%20x%208%E2%80%99h
	*/

	$name = ucwords($_GET['user_name']);
	$location = $_GET['user_location'];
	$phone = $_GET['phone'];
	$phone1 = substr($phone, 0, 3);
	$phone2 = substr($phone, 3, 3);
	$phone3 = substr($phone, 6, 4);
	$email = $_GET['email'];
	$rent = $_GET['rent'];
	$date = $_GET['movedate'];
	$size = $_GET['size'];
	$lease = $_GET['lease'];

	// echo $name."<br />";
	// echo $location."<br />";
	// echo $phone."<br />";
	// echo $email."<br />";
	// echo $rent."<br />";
	// echo $date."<br />";

	$location_post_ids = array(
		1 => '541',
		2 => '540',
		3 => '269',
		4 => '542',
		5 => '430',
		6 => '3151',
		7 => '544'
	);
	$l_post_id = $location_post_ids[intval($location)];

	$location_post = get_post($l_post_id);
	$location_post_meta = get_post_meta($location_post->ID);

	// var_dump(unserialize($location_post_meta['location_address'][0]));

	$l_name = $location_post->post_title;

	$l_address_full = unserialize($location_post_meta['location_address'][0])['address'];
	$l_address_split = explode(",", $l_address_full, 2);

	$l_phone = $location_post_meta['location_phone'][0];

	$l_email = $location_post_meta['location_email'][0];

	$map_url_pre = get_site_url()."/wp-content/uploads/2019/09/xyz_";
	$location_imgs = array(
		1 => 'scarborough_map.png',
		2 => 'mississauga_map.png',
		3 => 'toronto_west_map.png',
		4 => 'etobicoke_map.png',
		5 => 'mississauga_map.png',
		6 => 'toronto_midtown_map.png',
		7 => 'toronto_downtown_map.png'
	);
	$location_img = $map_url_pre.$location_imgs[intval($location)];

	$location_maps = array(
		1 => 'UtP6mcTFrJAVMJUP7',
		2 => '1586oLJKNwKgJkz5A',
		3 => 'oHE5jFpzMGL89dsc8',
		4 => 'QDcZXUMnikAX2cow6',
		5 => '1586oLJKNwKgJkz5A',
		6 => 'YYHzMPM4KXjNi7mf9',
		7 => 'oXPc9KiRbLFShbMdA'
	);
	$l_map = $location_maps[intval($location)];

	if(!$location) {
		$location_img = '';
		$l_map = '';
	}

	$location_hours_count = intval($location_post_meta['location_hours'][0]);
	// echo $location_hours_count;
	$l_hours = array();
	$i = 0;
	for( $i = 0 ; $i < $location_hours_count ; $i++ ){
		$l_hours[$i] = array($location_post_meta['location_hours_'.$i.'_location_hours_title'][0] , $location_post_meta['location_hours_'.$i.'_location_hours_content'][0]);
	}
	// var_dump($l_hours);

?>


<div class="header-wrap">
	<div class="wrapper">
		<h1 class="title">
			<?php the_title(); ?>
		</h1>
	</div>
</div>

<div class="main-container overflow" style="background-image: url('<?php the_field('page_background_image'); ?>');">
	<div class="wrapper overflow">
		<div class="sidebar">
			<?php if(get_field('notice_title')): ?>
				<h2>
					<?php the_field('notice_title'); ?>
				</h2>
			<?php endif; ?>

			<p><?php _e('Dear ', 'html5blank'); echo $name; ?>,</p>

			<p>
			<?php

			?>
			Thanks for storing with XYZ Storage <?php echo $l_name; ?>. Please find your details here.
			</p>

			<p>
				<strong>You’ve signed your lease agreement.</strong>
			</p>
			<p>
				Click below to view your lease agreement.
			</p>
			<p>
				Please remember to bring your ID when you come to the facility for the first time.
			</p>
				<a class="btn btn-yellow" id="lease-button" href="<?php echo $lease; ?>" target="_blank"> view lease agreement </a>
				<a class="btn btn-yellow" style="margin-top:14px; padding:8px 28px" href="/customer-portal/" target="_blank"> visit customer portal </a>


			<?php if(get_field('notice')): ?>
				<?php the_field('notice'); ?>
			<?php endif; ?>
		</div>

		<main role="main" class="padding">
			<p>
				<strong><?php _e('storage details', 'html5blank'); ?></strong>
			</p>

			<div class="section white inside first">
				<div class="columns columns-2 has-icons">
					<div class="col icon unit-size has-line">
						<p><?php _e('unit size', 'html5blank'); ?></p>

						<h3 class="title-4"><?php echo $size; ?></h3>

						<h3 class="title-4">medium</h3>
					</div>

					<div class="col icon move-date">
						<p><?php _e('move in date', 'html5blank'); ?></p>

						<h4><?php echo str_replace("-", "/", $date); ?></h4>
					</div>
				</div>
			</div>

			<div class="section white inside">
				<div class="columns columns-2 has-icons">
					<div class="col">
						<img src="<?php echo $location_img; ?>" alt="" />
					</div>

					<div class="col info">
						<h4><?php echo $l_name; ?></h4>

						<p>
							<?php echo $l_address_split[0]; ?> <br/>
							<?php echo $l_address_split[1]; ?>
						</p>

						<a href="<?php if($location) echo "https://goo.gl/maps/?>".$l_map; ?>" >
							<?php _e('open in Google Maps', 'html5blank'); ?>
						</a>

						<p>
							<?php _e('P:', 'html5blank'); ?> <?php echo $l_phone; ?>
						</p>

						<p>
							<?php _e('F:', 'html5blank'); ?> <?php echo $l_phone; ?>
						</p>

						<p>
							<?php _e('E:', 'html5blank'); ?> <?php echo $l_email; ?>
						</p>
					</div>
				</div>

				<div class="columns columns-2 has-icons">
					<div class="col info hours">
						<p>
							<strong><?php _e('office hours', 'html5blank'); ?></strong>
						</p>
						<?php foreach($l_hours as $hour): ?>
							<p>
								<?php echo $hour[0]; ?>
								<span><?php echo $hour[1]; ?></span>
							</p>
						<?php endforeach; ?>
					</div>

					<div class="col info hours">
						<p>
							<strong><?php _e('gate hours', 'html5blank'); ?></strong>
						</p>

						<?php foreach($l_hours as $hour): ?>
							<p>
								<?php echo $hour[0]; ?>
								<span><?php echo $hour[1]; ?></span>
							</p>
						<?php endforeach; ?>

					</div>
				</div>
			</div>

			<div class="section white inside">
				<p class="title icon lock">
					<?php _e('booking details', 'html5blank'); ?>
				</p>

				<div class="columns columns-2 has-icons">
					<div class="col info normal">
						<p>
							<strong>name</strong>
							<?php echo $name; ?>
						</p>

						<p>
							<strong>company name</strong>
							N/A
						</p>

						<p>
							<strong>phone</strong>
							<?php
								if(strlen($phone) == 10){
									echo '('.$phone1.') '.$phone2.'-'.$phone3;
								}
								else {
									echo $phone;
								}
						 	?>
						</p>

						<p>
							<strong>email</strong>
							<?php echo $email; ?>
						</p>
					</div>

					<div class="col info normal">
						<p>
							<strong>unit size</strong>

							<?php echo $size; ?>
						</p>

						<p>
							<strong>move in date</strong>
							<?php echo $date; ?>
						</p>

						<p>
							<strong>location</strong>
							<?php echo $l_address_split[0]; ?> <br/>
							<?php echo $l_address_split[1]; ?>
						</p>

						<p>
							<strong>rent</strong>
							<span id="rent-value"><?php echo $rent; ?></span>/mo
						</p>

						<p>
							<strong>estimated sales tax</strong>
							$<?php echo floatval(str_replace("$", "", $rent)) * 0.13; ?>
						</p>

						<p>
							<strong>total</strong>
							$<?php echo floatval(str_replace("$", "", $rent)) * 1.13; ?>/mo
						</p>
					</div>
				</div>
			</div>
		</main>
	</div>
</div>

<?php if( have_rows('steps') ): ?>
	<div class="section white spacing outside-next">
		<div class="wrapper narrow-2 overflow">
			<?php if(get_field('steps_content')): ?>
				<?php the_field('steps_content'); ?>
			<?php endif; ?>

			<div class="columns columns-3 margin steps large">
				<?php while( have_rows('steps') ): the_row(); ?>
					<div class="col">
						<?php if( get_sub_field('steps_icon') ): ?>
							<?php $image = get_sub_field('steps_icon'); ?>

							<div class="img-wrap">
								<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
							</div>
						<?php endif; ?>

						<h4>
							<?php the_sub_field('steps_title'); ?>
						</h4>

						<?php the_sub_field('steps_content'); ?>
					</div>
				<?php endwhile; ?>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php if( have_rows('services') ): ?>
	<div class="section outside">
		<div class="wrapper narrow-2 overflow">
			<div class="columns columns-2 white flex img-inside text-center">
				<?php while( have_rows('services') ): the_row(); ?>
					<div class="col">
						<?php if( get_sub_field('services_image') ): ?>
							<?php $image = get_sub_field('services_image'); ?>

							<div class="img-wrap transparent">
								<img src="<?php echo $image['sizes']['medium']; ?>" alt="<?php echo $image['alt']; ?>" />
							</div>
						<?php endif; ?>

						<div class="content">
							<h4>
								<?php the_sub_field('services_title'); ?>
							</h4>

							<?php the_sub_field('services_content'); ?>

							<?php if(get_sub_field('services_link')): ?>
								<?php
									$link = get_sub_field('services_link');
									$link_url = $link['url'];
									$link_title = $link['title'];
									$link_target = $link['target'] ? $link['target'] : '_self';
								?>

								<a class="btn" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
									<?php echo esc_html($link_title); ?>
								</a>
							<?php endif; ?>
						</div>
					</div>
				<?php endwhile; ?>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php get_footer(); ?>
