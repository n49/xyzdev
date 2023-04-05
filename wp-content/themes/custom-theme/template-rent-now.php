<script src='https://libs.na.bambora.com/customcheckout/1/customcheckout.js'></script>



<?php /* Template Name: Rent Now */ get_header();
// var_dump($_COOKIE['unitLocation']);
if(!$_COOKIE['unitLocation']) {
	wp_redirect('/');
}
add_filter("wpcf7_form_tag", function($scanned_tag, $replace){


    if ("insurance" === $scanned_tag["name"]) {
$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL => "http://ssm20.selfstoragemanager.net/XYZStorageAPI/service.asmx/GetInsuranceSchemes?strCustomerCode=xyzstorage&strCustomerPassword=191820120406&strLocationCode=".$_COOKIE['unitLocation'],
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => [
    "Accept: */*",
    "User-Agent: Thunder Client (https://www.thunderclient.com)"
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

		$xml = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
		$json = json_encode($xml);
		$res = json_decode($json,TRUE);

//var_dump($res["InsuranceSchemes"]);
setcookie("InsuranceSchemes", json_encode($res["InsuranceSchemes"]));
$insSchemes = $res["InsuranceSchemes"]["Scheme"];
//var_dump($insSchemes);
$optionsArray = [];

if($insSchemes) {
	

foreach($insSchemes as $ins) {
	//var_dump('sdf', $ins);
	$optionString = "$" . $ins["PremiumAmount"] . " --> " . $ins["SchemeName"]  ."|" . $ins["PremiumAmount"];
	$optionsArray[] = $optionString;
}
}
		$extra = "$" . "1" . " --> " . "1"  ."|" . "1";
		array_unshift($optionsArray, $extra);
//var_dump($optionsArray);


        $contact_form = WPCF7_ContactForm::get_current();

        $number_of_posts = $contact_form->shortcode_attr("number_of_posts");
        $post_type = $contact_form->shortcode_attr("post_type");


        // using $number_of_posts and $post_type here

//var_dump($optionsArray);
        $scanned_tag['raw_values'] = $optionsArray;



        $pipes = new WPCF7_Pipes($scanned_tag['raw_values']);

        $scanned_tag['values'] = $pipes->collect_befores();
        $scanned_tag['pipes'] = $pipes;

    }


    return $scanned_tag;

}, 10, 2);


	$locations_array = array(
	                          3 => 'Toronto West',
	                          4 => 'Etobicoke',
	                          1 => 'Scarborough',
	                          2 => 'Mississauga',
	                          5 => 'Mobile Storage',
	                          6 => 'Toronto Midtown',
	                          7 => 'Toronto Downtown'
	                        );

	$locations_street_address_array = array(
	                          3 => '207 Weston Road, Toronto, Ontario M6N 4Z3, Canada',
	                          4 => '2256 Lake Shore Blvd W, Etobicoke, ON M8V 1A9, Canada',
	                          1 => '135 Beechgrove Drive, Scarborough, Ontario M1E 3Z3, Canada',
	                          2 => '2480 Stanfield Road, Mississauga, ON L4Y 1R6, Canada',
	                          5 => 'Mobile Storage',
	                          6 => '135 Beechgrove Drive, Scarborough, Ontario M1E 3Z3, Canada',
	                          7 => '459 Eastern Avenue, Toronto, ON M4M 1B7, Canada'
	                        );

	$locations_email = array(
	                          3 => 'weston@xyzstorage.com',
	                          4 => 'lakeshore@xyzstorage.com',
	                          1 => 'scarborough@xyzstorage.com',
	                          2 => 'mississauga@xyzstorage.com',
	                          5 => '',
	                          6 => 'laird@xyzstorage.com',
	                          7 => 'eastern@xyzstorage.com'
	                        );

	$location_name = $locations_array[intval($_COOKIE['unitLocation'])];
	$location_street_address = $locations_street_address_array[intval($_COOKIE['unitLocation'])];
	$location_email = $locations_email[intval($_COOKIE['unitLocation'])];
// 	 echo "HERE'S THE LOCATION NAME" .$location_name;


?>


<div class="header-wrap">
	<div class="wrapper">
		<h1 class="title">
			<?php the_title(); ?>
		</h1>
	</div>
</div>


<div class="main-container spacing-3 form-section-wrap form-active-1" style="background-image: url('<?php the_field('page_background_image'); ?>');">
	<div class="wrapper">

		<main role="main" class="fullwidth padding tabs-fake active">

			<ul class="horizontal icons has-event multiform-steps">
				<li class="first active">
					<a class="normal inactive form-btn-1">
						<p class="title-5">
							<?php _e('customer details', 'html5blank'); ?>
						</p>
					</a>
				</li>

				<li class="second">
					<span class="icon sep"></span>

					<a class="normal inactive">
						<p class="title-5">
							<?php _e('rental details', 'html5blank'); ?>
						</p>
					</a>
				</li>

				<li class="third">
					<span class="icon sep"></span>

					<a class="normal inactive">
						<p class="title-5">
							<?php _e('complete rental', 'html5blank'); ?>
						</p>
					</a>
				</li>
			</ul>

<div style="display:none" id="loader">


		<div style="zoom:2; left: 50%; transform: translateX(-50%); margin-bottom: 50px" class="lds-roller">Processing...<div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
				</div>
			<div id="second" class="content-wrap overflow1 tab">

				<?php if(get_field('customer_content')): ?>
					<div class="float-left section white">

						<?php the_field('customer_content'); ?>

						<?php if( have_rows('customer_features') ): ?>
							<div class="columns columns-3 services bottom">
								<?php while( have_rows('customer_features') ): the_row(); ?>
									<div class="col">
										<?php if( get_sub_field('customer_features_icon') ): ?>
											<?php $image = get_sub_field('customer_features_icon'); ?>

											<div class="img-wrap">
												<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
											</div>
										<?php endif; ?>

										<div class="content">
											<p class="title-5">
												<?php the_sub_field('customer_features_title'); ?>
											</p>

											<p>
												<?php the_sub_field('customer_features_description'); ?>
											</p>
										</div>
									</div>
								<?php endwhile; ?>
							</div>
						<?php endif; ?>

						<?php if(get_field('thanks_content')): ?>
							<div class="success-wrap">
								<?php the_field('thanks_content'); ?>

								<?php if(get_field('thanks_cta_content')): ?>
									<div class="cta" style="background-image: url(<?php the_field('thanks_cta_background'); ?>); ">
										<div class="columns columns-2">
											<div class="col col-1">
												<?php the_field('thanks_cta_content'); ?>
											</div>

											<div class="col col-2">
												<?php if(get_field('thanks_promo_code')): ?>
													<div class="promo-code">
														<p><?php _e('Promo code', 'html5blank'); ?></p>

														<span><?php the_field('thanks_promo_code'); ?></span>

														<button type="button" class="btn-copy" data-clipboard-text="<?php the_field('thanks_promo_code'); ?>">
															<?php _e('copy code', 'html5blank'); ?>
														</button>
													</div>
												<?php endif; ?>
											</div>
										</div>
									</div>
								<?php endif; ?>

								<?php if(have_rows('thanks_services')): ?>
									<div class="columns columns-3 flex white grey products">
										<?php while(have_rows('thanks_services')): the_row(); ?>
											<div class="col">
												<div class="content">
													<?php if( get_sub_field('thanks_services_image') ): ?>
														<?php $image = get_sub_field('thanks_services_image'); ?>

														<div class="img-wrap transparent">
															<img src="<?php echo $image['sizes']['small-2']; ?>" alt="<?php echo $image['alt']; ?>" />
														</div>
													<?php endif; ?>

													<h3 class="title-4">
														<?php the_sub_field('thanks_services_title'); ?>
													</h3>

													<?php if(get_sub_field('thanks_services_content')): ?>
														<?php the_sub_field('thanks_services_content'); ?>
													<?php endif; ?>

													<?php if(get_sub_field('thanks_services_link')): ?>
														<?php
															$link = get_sub_field('thanks_services_link');
															$link_url = $link['url'];
															$link_title = $link['title'];
															$link_target = $link['target'] ? $link['target'] : '_self';
														?>

														<a class="icon icon-link" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>"><?php echo esc_html($link_title); ?></a>
													<?php endif; ?>
												</div>
											</div>
										<?php endwhile; ?>
									</div>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<div class="float-right grey">
					<?php get_template_part('loop-units-single-rental'); ?>
				</div>
			</div>

            <div class="clear"></div>
		</main>
	</div>
</div>

<?php get_footer(); ?>
