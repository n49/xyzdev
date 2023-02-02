<?php $amp = isset($_GET['amp']); ?>
		<footer class="footer" role="contentinfo">
			<span class="icon scrollup"></span>

			<div class="wrapper">
				<div class="columns columns-5">
					<div class="col">
						<?php if( get_field('contact_logo', 'option') ): ?>
							<?php
								$logo = get_field('contact_logo', 'option');
								$logo_mobile = get_field('contact_logo_mobile', 'option');
							?>

							<a href="<?php echo home_url(); ?>" title="<?php echo $logo['alt']; ?>">
								<img class="img-normal logo-header" src="<?php echo $logo['url']; ?>" alt="<?php echo $logo['alt']; ?>" />

								<img class="img-mobile logo-header" src="<?php echo $logo_mobile['url']; ?>" alt="<?php echo $logo_mobile['alt']; ?>" />
							</a>
						<?php endif; ?>
					</div>
					<?php if(!$amp): ?>
						<div class="col">
							<?php wp_nav_menu( array('menu' => 'Footer Menu 1' )); ?>
						</div>

						<div class="col">
							<?php wp_nav_menu( array('menu' => 'Footer Menu 2' )); ?>
						</div>

						<div class="col">
							<?php wp_nav_menu( array('menu' => 'Footer Menu 3' )); ?>
						</div>

						<div class="col">
							<div class="social-wrap">
								<p class="label">
									<?php _e('follow us', 'html5blank'); ?>
								</p>

								<div class="social">
									<?php get_template_part('module-social'); ?>
								</div>
							</div>
						</div>
					<?php else: ?>
						<div class="col"><ul class="menu"><li>
	            <a on="tap:locations-sidebar.toggle">locations</a>
						</li></ul></div>
						<div class="col"><ul class="menu"><li>
							<a on="tap:navigation-sidebar.toggle">navigation</a>
						</li></ul></div>
						<div class="col"><ul class="menu"><li>
	            <a on="tap:services-sidebar.toggle">services</a>
						</li></ul></div>
						<div class="col"><ul class="menu"><li>
	            <a on="tap:solutions-sidebar.toggle">solutions</a>
						</li></ul></div>
						<div class="col"><ul class="menu"><li>
	            <a on="tap:help-sidebar.toggle">let us help you</a>
						</li></ul></div>
						<div class="col"><ul class="menu"><li>
	            <a on="tap:know-sidebar.toggle">get to know us</a>
						</li></ul></div>
						<div class="col"><ul class="menu"><li>
							<a on="tap:follow-sidebar.toggle">follow us</a>
						</li></ul></div>
					<?php endif; ?>
				</div>
			</div>

			<div class="copyright">
				<div class="wrapper">
					<p>&copy; <?php echo date('Y'); ?> <?php _e('XYZ Storage', 'html5blank'); ?></p> <?php wp_nav_menu( array('menu' => 'Copyright Menu' )); ?>
				</div>
			</div>
		</footer>

		<?php wp_footer(); ?>

		<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

		<script type="text/javascript">
			function googleTranslateElementInit() {
				new google.translate.TranslateElement({pageLanguage: 'en', includedLanguages: '', layout: google.translate.TranslateElement.InlineLayout.SIMPLE, gaTrack: true, gaId: 'UA-5122540-1'}, 'google_translate_element');
			}
		</script>

		<script type="text/javascript">
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-5122540-1']);
			// _gaq.push(['_setDomainName', 'canada-storage.com']);
			_gaq.push(['_setAllowLinker', true]);
			// _gaq.push(['_setCampNameKey', 'false']);
			// _gaq.push(['_setCampSourceKey', 'false']);
			// _gaq.push(['_setCampMediumKey', 'false']);
			// _gaq.push(['_setCampTermKey', 'false']);
			// _gaq.push(['_setCampContentKey', 'false']);
			// _gaq.push(['_trackPageview']);

			(function() {
				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'stats.g.doubleclick.net/dc.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();
		</script>
	</body>
</html>
