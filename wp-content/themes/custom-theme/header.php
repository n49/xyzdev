<?php header("Access-Control-Allow-Origin: *");  ?>

<?php

/* Redirecting pages we don't want to be amp to their non-amp version */

$url = home_url( $wp->request );
$query_vars = $wp->query_vars;
$path = parse_url($url, PHP_URL_PATH);

$amp_paths = [
	"/",
	"/locations/toronto-downtown",
	"/locations/toronto-midtown",
	"/locations/toronto-west",
	"/locations/scarborough",
	"/locations/etobicoke",
	"/contact-us"
];

if($query_vars["amp"] && !in_array($path, $amp_paths) && !is_front_page()){
	wp_redirect( $url );
	exit;
}

?>
<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
<!-- Hotjar Tracking Code for https://www.xyzstorage.com -->
<!-- <script>
    (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:1406684,hjsv:6};
        a=o.getElementsByTagName('head')[0];
        r=o.createElement('script');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
</script> -->
		<meta name="facebook-domain-verification" content="1ig2ge41uxlrm5i83ze7h13cjs4ms6" />
		<!--  Google Analytics Code -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-5122540-1"></script>
		<script>
		  window.dataLayer = window.dataLayer || [];
		  function gtag(){dataLayer.push(arguments);}
		  gtag('js', new Date());

		  gtag('config', 'UA-5122540-1' , { 'optimize_id': 'GTM-TKLGLZL'});
		  gtag('config', 'AW-1034857340');
		</script>
		<!-- Google Tag Manager -->

		<!-- Header phone number -->
		<script> gtag('config', 'AW-1034857340/1LrTCMv387wDEPzWuu0D', { 'phone_conversion_number': '1-866-310-1999' }); </script>

		<?php if(is_single(85)) { ?>
		<!-- Contact Us -->
		<script> gtag('config', 'AW-1034857340/1LrTCMv387wDEPzWuu0D', { 'phone_conversion_number': '1-866-310-1999' }); </script>
		<?php } ?>

		<?php if(is_single(544)) { ?>
		<!-- Toronto Downtown -->
		<script> gtag('config', 'AW-1034857340/QqDSCNXlp70DEPzWuu0D', { 'phone_conversion_number': '(416) 463-6363' }); </script>
		<?php } ?>

		<?php if(is_single(3151)) { ?>
		<!-- Toronto Midtown -->
		<script> gtag('config', 'AW-1034857340/UiGpCM7ZqL0DEPzWuu0D', { 'phone_conversion_number': '(416) 203-3331' }); </script>
		<?php } ?>

		<?php if(is_single(269)) { ?>
		<!-- Toronto West -->
		<script> gtag('config', 'AW-1034857340/5_ZECL3E9bwDEPzWuu0D', { 'phone_conversion_number': '(416) 604-0404' }); </script>
		<?php } ?>

		<?php if(is_single(541)) { ?>
		<!-- Scarborough -->
		<script> gtag('config', 'AW-1034857340/1UMBCJi_q70DEPzWuu0D', { 'phone_conversion_number': '(416) 208-0188' }); </script>
		<?php } ?>

		<?php if(is_single(542)) { ?>
		<!-- Etobicoke -->
		<script> gtag('config', 'AW-1034857340/LklYCPm9q70DEPzWuu0D', { 'phone_conversion_number': '(416) 201-0101' }); </script>
		<?php } ?>

<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-WKQKLM2');</script>

		<meta name="ahrefs-site-verification" content="8774b03e812f982a00c9953ab9ad7aaf6a5e86faf4254be4705c3efbf95fbc38">

		<!-- End Google Tag Manager -->
<meta name="google-site-verification" content="_WGP27xKI-Ez8fTjeg3mrqdosEiwntSJg0fLMbNCPlY" />
		<meta charset="<?php bloginfo('charset'); ?>">
		<title><?php wp_title(''); ?></title>

		<link href="//www.google-analytics.com" rel="dns-prefetch">
		<link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet">

		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">

		<?php wp_head(); ?>

		<?php $amp = isset($_GET["amp"]); ?>
<?php if($amp):?>
<script async custom-element="amp-lightbox" src="https://cdn.ampproject.org/v0/amp-lightbox-0.1.js"></script>

	<style amp-custom>
		.amp-btg-image {
			background-image: url('http://xyzamp.dev.pop.ca/wp-content/uploads/2019/05/banner1.png') !important;
			opacity: 1;
			background-position: 90% 60% !important;
		}
		.amp-bg-image {
			background: url('https://self-storage.xyz/wp-content/uploads/2021/03/banner1.png') !important;
			opacity: <?php the_field('page_slider_opacity'); ?>;
		  background-position: top 0px right -230px !important;;
		  background-blend-mode: hue !important;;
		}
		.scroll-wrapper{
			overflow-y:scroll;
			webkit-overflow-scrolling:touch;
		}
		.menu-img-tab {
				margin-left: 14px;
				margin-bottom: 4px;
		}
		.menu-div-fill {
			width: 20px;
		}
		.logos-amp-padding {
			padding-bottom: 20px;
		}
		.solutions-amp-margin {
			margin-bottom: 0px !important;
		}
		.ampwrapper-padding {
			padding-bottom: 1px;
		}
		.lightbox {
			background: rgba(0,0,0,0.8);
			width: 100%;
			height: 100%;
			position: absolute;
			display: flex;
			align-items: center;
			justify-content: center;
		}
		.lightbox h1 {
			color: white;
		}
		.lightbox-hours {
			color: white;
			display: inline-block;
			margin-left: 10px;
		}
		.lightbox-notice {
			padding: 0px;
		}
		.lightbox-notice > p {
			padding: 0px;
			margin: 0px;
		}

		.notice-description{
			padding: 0px !important;
		}
		.notice-title{
			margin-top:10px !important;
			padding: 0px !important;
		}

		.notices-wrap .notice {
			opacity: 1;
		}

		.notices-wrap {

		}

		.carousel-notices > .slide {
			height: 100%;
		}

		.carousel-notices > .slide > .notice {
			height: 100%;
		}

		.ampfixed {
			top: calc(0px);
		}
		.sticky-header {
			position: sticky;
			position: -webkit-sticky;
			top: 0;
			left: 0;
			right: 0;
			height: var(--header-height);
			z-index: 500;
		}

		.btn-yellow-amp {
			width: 100%;
		}

		:root {
			--space-2: 1rem;   /* 16px */
		}
		.sample-sidebar {
			width: 250px;
			padding: var(--space-2);
		}
		.sample-sidebar > * {
			margin-top: var(--space-2);
		}
		.sample-sidebar li,
		nav[toolbar] li {
			margin-bottom: var(--space-2);
			margin-left: 0;
			margin-right: var(--space-2);
			list-style: none;
		}
		.previewOnly {
			margin: var(--space-2);
		}
		#sidebar-right nav.amp-sidebar-toolbar-target-shown {
				display: none;
		}
		/* student offer */
		.student-block {
			background: #5AC4BA;
			text-align: left;
			color: #444549;
			max-width: 100%;
			float: none;
			padding: 30px 20px;
			border-radius: 0px;
			margin: 0;
		}
		.student-block img {
			width: auto;
		}
		.img-container {
			text-align: center;
			display: inline-table;
		}
		.student-block a {
			display: inline-block;
			vertical-align: top;
			font-size: 16px;
			font-weight: 700;
			color: #444549;
		}
		.student-hide {
			display: block;
		}
		.student-main-hide {
			display: none;
		}
		.amp-bg-image{
			opacity: 1 !important;
		}
		</style>
		<?php endif;?>

		<!-- Start of HubSpot Embed Code -->
		<script type="text/javascript" id="hs-script-loader" async defer src="//js.hs-scripts.com/21102818.js"></script>
		<!-- End of HubSpot Embed Code -->

	</head>

	<body <?php body_class(); ?>>

		<?php if(!$amp): ?>
			<!-- Google Tag Manager (noscript) -->
			<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WKQKLM2"
			height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
			<!-- End Google Tag Manager (noscript) -->
		<?php endif; ?>
		<?php if($amp): ?>
			<!-- Google Tag Manager -->
			<amp-analytics config="https://www.googletagmanager.com/amp.json?id=GTM-TK66SPP&gtm.url=SOURCE_URL" data-credentials="include">
			<script type="application/json">
			{
				"vars": {
					"someCustomAmpVar": "someValue"
				}
			}
			</script>
			</amp-analytics>
		<?php endif; ?>
		<div class="preloader"></div>
		<?php if(true || !$amp): ?>
    	<!-- uncomment to show top bar <div class="top-banner-promotion-dev">An important message from XYZ Storage management & staff to our customers about COVID-19.
<a class="50promolink" href="https://www.xyzstorage.com/covid-19-response/" target="_self" style="color: inherit">Click here</a> for more details.</div> -->
		<?php endif; ?>
		<header class="header clear <?php if($amp) echo "ampfixed sticky-header"; ?>" role="banner">
			<div class="top">
				<div class="wrapper">
					<?php if(get_field('contact_phone', 'option')): ?>
						<div class="handler popupInit" data-type="callNow" data-params="Phone Numbers,Viewed" data-phNumber="<?php the_field('contact_phone', 'option'); ?>">
							<!-- <span class="descriptionText click-to-call"><?php _e('Click to Call', 'html5blank'); ?></span> -->
							<div class="number">
								<a href="tel:<?php the_field('contact_phone', 'option'); ?>"><?php the_field('contact_phone', 'option'); ?></a>
							</div>
						</div>
					<?php endif; ?>

					<a href="<?php bloginfo('url'); ?>/faq-help-centre/">
						<?php _e('Help', 'html5blank'); ?>
					</a>

					<a href="https://www.xyzstorage.com/customer-portal/" target="_self">
						<?php _e('Customer Portal', 'html5blank'); ?>
					</a>

					<ul>
						<?php pll_the_languages(array('hide_current' => true)); ?>
					</ul>

					<div class="translate-wrap">
						<div id="google_translate_element"></div>
					</div>
				</div>
			</div>

			<div class="middle">
				<div class="wrapper">
					<div class="logo float-left">
						<?php if( get_field('contact_logo', 'option') ): ?>
							<?php
								$logo = get_field('contact_logo', 'option');
								$logo_mobile = get_field('contact_logo_mobile', 'option');
							?>

							<a href="<?php echo home_url(); ?>" title="<?php echo $logo['alt']; ?>">
								<img class="img-normal no-lazy logo-header" src="<?php echo $logo['url']; ?>" alt="<?php echo $logo['alt']; ?>" />

								<img class="img-mobile no-lazy logo-header" src="<?php echo $logo_mobile['url']; ?>" alt="<?php echo $logo_mobile['alt']; ?>" />
							</a>
						<?php endif; ?>
					</div>

					<div class="nav-wrap float-right">
						<nav class="nav" role="navigation">
							<?php html5blank_nav(); ?>

							<?php
								if (is_page_template('template-locations-book.php')) {
									$locations_url = '#reserve';
								} else if (is_page_template('template-locations-location.php')){
									$locations_url = get_permalink() . 'book-a-unit#reserve';
								} else {
									$locations_url = get_bloginfo('url') . '/storage-locations/';
								}
							?>
						</nav>

						<a class="btn btn-yellow header-reserve" href="<?php echo $locations_url; ?>">
							<?php _e('reserve a unit', 'html5blank'); ?>
						</a>
						<?php if($amp): ?>
						  <amp-img
						  class="menu-img-tab"
						  src="https://www.xyzstorage.com/wp-content/themes/custom-theme/css/../img/icon-open.png"
						  on="tap:sidebar.toggle" role="menu" tabindex="0" alt="menu-image" width=31 height=30 />

            <?php endif; ?>
					</div>

					<div class="clear"></div>
				</div>
			</div>
			<?php if(!$amp): ?>
			<div class="bottom">
			<a href="<?php echo $locations_url; ?>">
				<div class="wrapper">
					<?php if( have_rows('contact_additional', pll_current_language('slug')) ): ?>
						<div class="columns columns-3">
							<?php while( have_rows('contact_additional', pll_current_language('slug')) ): the_row(); ?>
								<div class="col">
									<?php if( get_sub_field('contact_additional_icon') ): ?>
										<?php $image = get_sub_field('contact_additional_icon'); ?>

										<div class="img">
											<img class="no-lazy" src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
										</div>
									<?php endif; ?>

									<div class="content">
										<p class="title">
											<?php the_sub_field('contact_additional_title'); ?>
										</p>

										<p>
											<?php the_sub_field('contact_additional_description'); ?>
										</p>
									</div>
								</div>
							<?php endwhile; ?>
						</div>
					<?php endif; ?>
				</div>
				</a>
				<div class="breadcrumbs" typeof="BreadcrumbList" vocab="https://schema.org/">
					<div class="wrapper">
						<?php if(function_exists('bcn_display')) { bcn_display(); } ?>
					</div>
				</div>
			</div>
		<?php endif; ?>
		</header>

		<?php if($amp): ?>
			<div>
		                <amp-sidebar id="sidebar" class="sample-sidebar" layout="nodisplay" side="right">
		                  <h3>Menu</h3>
		                  <div><a class="amp-menu-locations" href="/storage-locations/" >locations</a></div>
		                  <div><a class="amp-menu-sizes" href="/storage-sizes/?amp">storage sizes</a></div>
		                </amp-sidebar>
		              </div>
		              <div>
		                <amp-sidebar id="locations-sidebar" class="sample-sidebar" layout="nodisplay" side="right">
		                  <h3>Locations</h3>
		                  <div><a href="/locations/toronto-downtown/" >Toronto Downtown</a></div>
		                  <div><a href="/locations/toronto-midtown/" >Toronto Midtown</a></div>
		                  <div><a href="/locations/toronto-west/" >Toronto West</a></div>
		                  <div><a href="/locations/scarborough/" >Scarborough</a></div>
		                  <div><a href="/locations/etobicoke/" >Etobicoke</a></div>
		                </amp-sidebar>
		              <div>
		                <amp-sidebar id="navigation-sidebar" class="sample-sidebar" layout="nodisplay" side="right">
		                  <h3>Navigation</h3>
		                  <div><a href="/storage-sizes/" >Storage Sizes</a></div>
		                  <div><a href="/pricing/" >Pricing</a></div>
		                  <div><a href="/promotions/" >Promotions</a></div>
		                </amp-sidebar>
		              </div>
		              <div>
		                <amp-sidebar id="services-sidebar" class="sample-sidebar" layout="nodisplay" side="right">
		                  <h3>Services</h3>
		                  <div><a href="/serices/self-storage/" >Self Storage</a></div>
		                  <div><a href="/seriecs/mobile-storage" >Mobile Storage</a></div>
		                  <div><a href="/serices/truck-rental/" >Truck Rental</a></div>
		                  <div><a href="/services/moving-supplies" >Moving Supplies</a></div>
		                  <div><a href="/services/shredding" >Shredding</a></div>
		                </amp-sidebar>
		              </div>
		              <div>
		                <amp-sidebar id="solutions-sidebar" class="sample-sidebar" layout="nodisplay" side="right">
		                  <h3>Residential</h3>
		                  <div><a href="/residential-storage/" >Residential</a></div>
		                  <div><a href="/business-storage/" >Business</a></div>
		                  <div><a href="/mobile-storage/" >Mobile Storage</a></div>
		                </amp-sidebar>
		              </div>
		              <div>
		                <amp-sidebar id="help-sidebar" class="sample-sidebar" layout="nodisplay" side="right">
		                  <h3>Help</h3>
		                  <div><a href="https://portal.selfstoragemanager.com/XYZStorage/account/login.aspx" >Pay a Bill</a></div>
		                  <div><a href="/help-center/" >Help Center</a></div>
		                  <div><a href="/contact-us/" >Contact Us</a></div>
		                </amp-sidebar>
		              </div>
		              <div>
		                <amp-sidebar id="know-sidebar" class="sample-sidebar" layout="nodisplay" side="right">
		                  <h3>Get to Know Us</h3>
		                  <div><a href="/about-xyz-storage/" >About XYZ Storage</a></div>
		                  <div><a href="/careers/" >Careers at XYZ Storage</a></div>
		                  <div><a href="/blog/" >Blog</a></div>
		                </amp-sidebar>
		              </div>
		              <div>
		                <amp-sidebar id="follow-sidebar" class="sample-sidebar" layout="nodisplay" side="right">
		                  <h3>Follow Us</h3>
		                  <div><a href="https://www.facebook.com/xyzselfstorage" >Facebook</a></div>
		                  <div><a href="https://www.instagram.com/xyzselfstorage" >Instagram</a></div>
		                  <div><a href="https://www.pinterest.com/xyzselfstorage" >Pinterest</a></div>
		                  <div><a href="https://www.twitter.com/xyzselfstorage" >Twitter</a></div>
		                  <div><a href="https://www.youtube.com/user/ACSelfStorage" >YouTube</a></div>
		                  <div><a href="https://ca.linkedin.com/company/all-canadian-self-storage" >LinkedIn</a></div>
		                </amp-sidebar>
		              </div>
			<?php endif;?>
