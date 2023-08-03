<div class="flex mixitup-container-wrapper active">
	<div class="width-22 mobile-fixed">
		<div class="is-sticky">
			<button type="button" class="count as-btn">
				filter <span></span>
			</button>

			<div class="filters-box">
				<div class="filters-nav side">
					<div class="normal-tooltip top">
						<p>unit rental cost</p>

						<div class="tooltip">
							You have the option to see unit rental costs monthly or daily. Monthly rent is the rent cost from the 1st day of the month to the last day of the month. Daily rent is the rate you would pay daily, calculated by dividing the total days in the month by the monthly rent value. All payments are made on the 1st of the month, payments cannot be made daily.
						</div>
					</div>

					<div class="tabs white toggle active inline">
						<ul class="horizontal">
							<li class="dailyMode">
								<a class="dailySwitch normal">
									daily
								</a>
							</li>

							<li class="monthlyMode">
								<a class="monthlySwitch normal">
									monthly
								</a>
							</li>
						</ul>
					</div>
				</div>

				<form class="filters">
					<p class="title-4">sort by</p>

					<fieldset class="radio" data-filter-group>
						<button type="button" data-filter=".featured">featured unit</button>

						<button type="button" data-filter=".special">on special</button>

						<button type="button" data-filter=".all">price <span>low to high</span></button>
					</fieldset>

					<p class="title-4">unit size</p>

					<fieldset data-filter-group>
						<button type="button" data-toggle=".compact">compact <span>locker 4x3</span></button>
						<button type="button" data-toggle=".small">small <span>5x5, 5x10</span></button>
						<button type="button" data-toggle=".medium">medium <span>5x10, 10x10, 10x15</span></button>
						<button type="button" data-toggle=".large">large <span>10x20, 10x25, 10x30</span></button>
						<button type="button" data-toggle=".parking">parking <span>18 to 38 ft deep</span></button>
					</fieldset>

					<p class="title-4">locations</p>

					<fieldset data-filter-group>
						<button type="button" data-toggle=".toronto-downtown">
							Toronto Downtown

							<div class="normal-tooltip inline bottom">
								<p class="empty"></p>

								<div class="tooltip">
									<p>459 Eastern Ave, Toronto, ON M4M 1C2</p>

									<span>Phone: (416) 463-6363</span>
									<span>Email: eastern@xyzstorage.com</span>
								</div>
							</div>
						</button>

						<button type="button" data-toggle=".toronto-midtown">
							Toronto Midtown

							<div class="normal-tooltip inline bottom">
								<p class="empty"></p>

								<div class="tooltip">
									<p>459 Eastern Ave, Toronto, ON M4M 1C2</p>

									<span>Phone: (416) 463-6363</span>
									<span>Email: eastern@xyzstorage.com</span>
								</div>
							</div>
						</button>
						
						<button type="button" data-toggle=".toronto-west">Toronto West</button>
						<button type="button" data-toggle=".scarborough">Scarborough</button>
						<button type="button" data-toggle=".etobicoke">Etobicoke</button>
					</fieldset>

					<p class="title-4">features</p>

					<fieldset data-filter-group data-logic="and">
						<button type="button" data-toggle=".heated">heated</button>
						<button type="button" data-toggle=".drive-up">drive up</button>
						<button type="button" data-toggle=".climate-control">climate control</button>
						<button type="button" data-toggle=".first-floor">first floor</button>
					</fieldset>
				</form>
			</div>
		</div>
	</div>

	<div class="width-76 mixitup-container-filters">
		<div class="filters-nav fullwidth">
			<div class="normal-tooltip top">
				<p>unit rental cost</p>

				<div class="tooltip">
					You have the option to see unit rental costs monthly or daily. Monthly rent is the rent cost from the 1st day of the month to the last day of the month. Daily rent is the rate you would pay daily, calculated by dividing the total days in the month by the monthly rent value. All payments are made on the 1st of the month, payments cannot be made daily.
				</div>
			</div>

			<div class="tabs white toggle active inline">
				<ul class="horizontal">
					<li class="dailyMode">
						<a class="dailySwitch normal">
							daily
						</a>
					</li>

					<li class="monthlyMode">
						<a class="monthlySwitch normal">
							monthly
						</a>
					</li>
				</ul>
			</div>

			<button type="button" class="count active">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 14.55"><path d="M259.64,250.92a2.18,2.18,0,1,1,2.18,2.18A2.17,2.17,0,0,1,259.64,250.92Zm-10.91.72h8.72a.72.72,0,0,0,.73-.72.73.73,0,0,0-.73-.73h-8.72a.73.73,0,0,0-.73.73A.73.73,0,0,0,248.73,251.64Zm4.36,2.19a2.18,2.18,0,0,0-2.05,1.45h-2.31a.73.73,0,0,0,0,1.46H251a2.18,2.18,0,1,0,4.11-1.46A2.16,2.16,0,0,0,253.09,253.83Zm10.18,1.45h-5.82a.73.73,0,0,0,0,1.46h5.82a.73.73,0,1,0,0-1.46Zm-8.72,5.09h-5.82a.73.73,0,1,0,0,1.46h5.82a.73.73,0,0,0,0-1.46Zm8.72,0H261a2.19,2.19,0,1,0,0,1.46h2.31a.73.73,0,0,0,0-1.46Z" transform="translate(-248 -248.74)" style="fill:#54565a"/></svg>

				<span></span>
			</button>
		</div>

		<!-- Category loop START -->

		<div class="as-category category-compact">
			<h2 class="mb-5">
				compact
			</h2>

			<p>12 - 24 square ft</p>

			<!-- Units loop START -->

			<div class="slider-units">
				<!-- Add filter classes to units -->

				<?php $filters = 'compact toronto-downtown heated drive-up first-floor featured'; ?>

				<div class="slide columns columns-5 white flex units flexible mix all <?php echo $filters; ?>" data-id="482" data-location="7">
					<div class="col img" style="margin: 0px">
						<div class="img-wrap promo-tag-482">
							<picture class="attachment-small-4 size-small-4 wp-post-image ls-is-cached lazyloaded">
								<source type="image/webp" data-srcset="https://dev1.xyz.pop.ca/wp-content/uploads/2019/05/Compact.png.webp" srcset="https://dev1.xyz.pop.ca/wp-content/uploads/2019/05/Compact.png">
								<img alt="Compact Self Storage" src="https://dev1.xyz.pop.ca/wp-content/uploads/2019/05/Compact.png" data-src="https://dev1.xyz.pop.ca/wp-content/uploads/2019/05/Compact.png">
							</picture>
						</div>
					</div>

					<div class="col desc">
						<p class="title-1 size-38 mb-0">
							4' x 3'
						</p>

						<p class="title-5 mb-10">
							4' height
						</p>

						<p class="title-4 mb-5">
							Toronto Midtown
						</p>

						<a href="#" class="view-location-details">
							<?php _e('view location details', 'html5blank'); ?>
						</a>
					</div>

					<div class="col text">
						<ul class="hide mb-20">
							<li>upper floor</li>

							<li>
								<div class="normal-tooltip inline left">
									<p>personal storage use</p>

									<div class="tooltip">
										Lorem ipsum dolor sit amet, consectur adipiscing elit, sed do eiusmod ter incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation. ullamco laboris nisi ut aliquip ex ea commodo consequat.
									</div>
								</div>
							</li>
							
							<li>
								<div class="normal-tooltip inline left">
									<p>ideal for storing documents</p>

									<div class="tooltip">
										Lorem ipsum dolor sit amet, consectur adipiscing elit, sed do eiusmod ter incididunt ut labore et dolore magna aliqua.
									</div>
								</div>
							</li>
						</ul>

						<p class="title-5 online-special-flag">Almost Sold Out Book Now!</p>

						<p id="units-available-482" class="color-orange">4 units left at this location</p>
					</div>

					<div class="col price">
						<div class="btn-box text-white">
							<p class="title-5 size-20">risk free</p>

							<h3 id="rentNowAmountSection" class="title-4 size-42">$73</h3>

							<p class="title-5 size-16">monthly</p>
							
							<a class="btn btn-reserve btn-white-2" data-unit="482" data-location="7" data-amount="1" data-dimensions="4'w x 3'd x 4'h" data-rent="73.00" data-length="3" data-width="4" data-height="4" data-price="73.00" data-id="482" data-available="1">
								reserve
							</a>
						</div>
					</div>

					<div class="col price last">
						<div class="btn-box text-white">
							<p class="title-5 online-special-flag hide" id="special-flag-482">50% off first month</p>

							<p class="title-5 size-20">
								<del>
									$72
								</del>
							</p>

							<h3 class="title-4 size-42" id="unit-sp-price-482">$36</h3>

							<p class="title-5 size-16">monthly</p>

							<a class="btn rent-now-button btn-yellow" data-unit="482" data-location="7" data-amount="1" data-dimensions="4'w x 3'd x 4'h" data-rent="73.00" data-length="3" data-width="4" data-height="4" data-price="73.00" data-id="482" data-promo="0.5" data-promolabel="50% off 1 Month">
								rent now
							</a>
						</div>
					</div>
				</div>

				<!-- Add filter classes to units -->

				<?php $filters = 'compact toronto-downtown heated drive-up climate-control special'; ?>

				<div class="slide columns columns-5 white flex units flexible last mix all <?php echo $filters; ?>" data-id="482" data-location="7">
					<div class="col img" style="margin: 0px">
						<div class="img-wrap promo-tag-482">
							<picture class="attachment-small-4 size-small-4 wp-post-image ls-is-cached lazyloaded">
								<source type="image/webp" data-srcset="https://dev1.xyz.pop.ca/wp-content/uploads/2019/05/Compact.png.webp" srcset="https://dev1.xyz.pop.ca/wp-content/uploads/2019/05/Compact.png">
								<img alt="Compact Self Storage" src="https://dev1.xyz.pop.ca/wp-content/uploads/2019/05/Compact.png" data-src="https://dev1.xyz.pop.ca/wp-content/uploads/2019/05/Compact.png">
							</picture>
						</div>
					</div>

					<div class="col desc">
						<p class="title-1 size-38 mb-0">
							4' x 3'
						</p>

						<p class="title-5 mb-10">
							4' height
						</p>

						<p class="title-4 mb-5">
							Toronto Midtown
						</p>

						<a href="#" class="view-location-details">
							<?php _e('view location details', 'html5blank'); ?>
						</a>
					</div>

					<div class="col text">
						<ul class="hide mb-20">
							<li>upper floor</li>

							<li>
								<div class="normal-tooltip inline left">
									<p>personal storage use</p>

									<div class="tooltip">
										Lorem ipsum dolor sit amet, consectur adipiscing elit, sed do eiusmod ter incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation. ullamco laboris nisi ut aliquip ex ea commodo consequat.
									</div>
								</div>
							</li>
							
							<li>
								<div class="normal-tooltip inline left">
									<p>ideal for storing documents</p>

									<div class="tooltip">
										Lorem ipsum dolor sit amet, consectur adipiscing elit, sed do eiusmod ter incididunt ut labore et dolore magna aliqua.
									</div>
								</div>
							</li>
						</ul>

						<p id="units-available-482" class="color-orange">2 units left at this location</p>
					</div>

					<div class="col price">
						<div class="btn-box text-white">
							<p class="title-5 size-20">risk free</p>

							<h3 id="rentNowAmountSection" class="title-4 size-42">$73</h3>

							<p class="title-5 size-16">monthly</p>
							
							<a class="btn btn-reserve btn-white-2" data-unit="482" data-location="7" data-amount="1" data-dimensions="4'w x 3'd x 4'h" data-rent="73.00" data-length="3" data-width="4" data-height="4" data-price="73.00" data-id="482" data-available="1">
								reserve
							</a>
						</div>
					</div>

					<div class="col price last">
						<div class="btn-box text-white">
							<p class="title-5 online-special-flag hide" id="special-flag-482">50% off first month</p>

							<p class="title-5 size-20">
								<del>
									$72
								</del>
							</p>

							<h3 class="title-4 size-42" id="unit-sp-price-482">$36</h3>

							<p class="title-5 size-16">monthly</p>

							<a class="btn rent-now-button btn-yellow" data-unit="482" data-location="7" data-amount="1" data-dimensions="4'w x 3'd x 4'h" data-rent="73.00" data-length="3" data-width="4" data-height="4" data-price="73.00" data-id="482" data-promo="0.5" data-promolabel="50% off 1 Month">
								rent now
							</a>
						</div>
					</div>
				</div>

				<!-- Units loop END-->
			</div>

			<div class="slider-units-dots">
				<div class="slide">1</div>
				<div class="slide">2</div>
			</div>

			<div class="text-right mt-60 mb-20 mobile-hidden">
				<a class="btn" href="#">
					<?php _e('view all compact units', 'html5blank'); ?>
				</a>
			</div>
		</div>

		<!-- Category loop END -->

		<!-- Category loop START -->

		<div class="as-category category-small">
			<h2 class="mb-5">
				small
			</h2>

			<p>25 - 74 square ft</p>

			<!-- Units loop START -->

			<div class="slider-units">
				<!-- Add filter classes to units -->

				<?php $filters = 'small toronto-midtown heated drive-up first-floor'; ?>

				<div class="slide columns columns-5 white flex units flexible mix all <?php echo $filters; ?>" data-id="482" data-location="7">
					<div class="col img" style="margin: 0px">
						<div class="img-wrap promo-tag-482">
							<picture class="attachment-small-4 size-small-4 wp-post-image ls-is-cached lazyloaded">
								<source type="image/webp" data-srcset="https://dev1.xyz.pop.ca/wp-content/uploads/2019/05/Compact.png.webp" srcset="https://dev1.xyz.pop.ca/wp-content/uploads/2019/05/Compact.png">
								<img alt="Compact Self Storage" src="https://dev1.xyz.pop.ca/wp-content/uploads/2019/05/Compact.png" data-src="https://dev1.xyz.pop.ca/wp-content/uploads/2019/05/Compact.png">
							</picture>
						</div>
					</div>

					<div class="col desc">
						<p class="title-1 size-38 mb-0">
							5' x 5'
						</p>

						<p class="title-5 mb-10">
							4' height
						</p>

						<p class="title-4 mb-5">
							Toronto Midtown
						</p>

						<a href="#" class="view-location-details">
							<?php _e('view location details', 'html5blank'); ?>
						</a>
					</div>

					<div class="col text">
						<ul class="hide mb-20">
							<li>upper floor</li>

							<li>
								<div class="normal-tooltip inline left">
									<p>personal storage use</p>

									<div class="tooltip">
										Lorem ipsum dolor sit amet, consectur adipiscing elit, sed do eiusmod ter incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation. ullamco laboris nisi ut aliquip ex ea commodo consequat.
									</div>
								</div>
							</li>
							
							<li>
								<div class="normal-tooltip inline left">
									<p>ideal for storing documents</p>

									<div class="tooltip">
										Lorem ipsum dolor sit amet, consectur adipiscing elit, sed do eiusmod ter incididunt ut labore et dolore magna aliqua.
									</div>
								</div>
							</li>
						</ul>

						<p class="title-5 online-special-flag">Almost Sold Out Book Now!</p>

						<p id="units-available-482" class="color-orange">4 units left at this location</p>
					</div>

					<div class="col price">
						<div class="btn-box text-white">
							<p class="title-5 size-20">risk free</p>

							<h3 id="rentNowAmountSection" class="title-4 size-42">$73</h3>

							<p class="title-5 size-16">monthly</p>
							
							<a class="btn btn-reserve btn-white-2" data-unit="482" data-location="7" data-amount="1" data-dimensions="4'w x 3'd x 4'h" data-rent="73.00" data-length="3" data-width="4" data-height="4" data-price="73.00" data-id="482" data-available="1">
								reserve
							</a>
						</div>
					</div>

					<div class="col price last">
						<div class="btn-box text-white">
							<p class="title-5 online-special-flag hide" id="special-flag-482">50% off first month</p>

							<p class="title-5 size-20">
								<del>
									$72
								</del>
							</p>

							<h3 class="title-4 size-42" id="unit-sp-price-482">$36</h3>

							<p class="title-5 size-16">monthly</p>

							<a class="btn rent-now-button btn-yellow" data-unit="482" data-location="7" data-amount="1" data-dimensions="4'w x 3'd x 4'h" data-rent="73.00" data-length="3" data-width="4" data-height="4" data-price="73.00" data-id="482" data-promo="0.5" data-promolabel="50% off 1 Month">
								rent now
							</a>
						</div>
					</div>
				</div>

				<!-- Add filter classes to units -->

				<?php $filters = 'small toronto-midtown'; ?>

				<div class="slide columns columns-5 white flex units flexible last mix all <?php echo $filters; ?>" data-id="482" data-location="7">
					<div class="col img" style="margin: 0px">
						<div class="img-wrap promo-tag-482">
							<picture class="attachment-small-4 size-small-4 wp-post-image ls-is-cached lazyloaded">
								<source type="image/webp" data-srcset="https://dev1.xyz.pop.ca/wp-content/uploads/2019/05/Compact.png.webp" srcset="https://dev1.xyz.pop.ca/wp-content/uploads/2019/05/Compact.png">
								<img alt="Compact Self Storage" src="https://dev1.xyz.pop.ca/wp-content/uploads/2019/05/Compact.png" data-src="https://dev1.xyz.pop.ca/wp-content/uploads/2019/05/Compact.png">
							</picture>
						</div>
					</div>

					<div class="col desc">
						<p class="title-1 size-38 mb-0">
							5' x 5'
						</p>

						<p class="title-5 mb-10">
							4' height
						</p>

						<p class="title-4 mb-5">
							Toronto Downtown
						</p>

						<a href="#" class="view-location-details">
							<?php _e('view location details', 'html5blank'); ?>
						</a>
					</div>

					<div class="col text">
						<ul class="hide mb-20">
							<li>upper floor</li>

							<li>
								<div class="normal-tooltip inline left">
									<p>personal storage use</p>

									<div class="tooltip">
										Lorem ipsum dolor sit amet, consectur adipiscing elit, sed do eiusmod ter incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation. ullamco laboris nisi ut aliquip ex ea commodo consequat.
									</div>
								</div>
							</li>
							
							<li>
								<div class="normal-tooltip inline left">
									<p>ideal for storing documents</p>

									<div class="tooltip">
										Lorem ipsum dolor sit amet, consectur adipiscing elit, sed do eiusmod ter incididunt ut labore et dolore magna aliqua.
									</div>
								</div>
							</li>
						</ul>

						<p id="units-available-482" class="color-orange">2 units left at this location</p>
					</div>

					<div class="col price">
						<div class="btn-box text-white">
							<p class="title-5 size-20">risk free</p>

							<h3 id="rentNowAmountSection" class="title-4 size-42">$73</h3>

							<p class="title-5 size-16">monthly</p>
							
							<a class="btn btn-reserve btn-white-2" data-unit="482" data-location="7" data-amount="1" data-dimensions="4'w x 3'd x 4'h" data-rent="73.00" data-length="3" data-width="4" data-height="4" data-price="73.00" data-id="482" data-available="1">
								reserve
							</a>
						</div>
					</div>

					<div class="col price last">
						<div class="btn-box text-white">
							<p class="title-5 online-special-flag hide" id="special-flag-482">50% off first month</p>

							<p class="title-5 size-20">
								<del>
									$72
								</del>
							</p>

							<h3 class="title-4 size-42" id="unit-sp-price-482">$36</h3>

							<p class="title-5 size-16">monthly</p>

							<a class="btn rent-now-button btn-yellow" data-unit="482" data-location="7" data-amount="1" data-dimensions="4'w x 3'd x 4'h" data-rent="73.00" data-length="3" data-width="4" data-height="4" data-price="73.00" data-id="482" data-promo="0.5" data-promolabel="50% off 1 Month">
								rent now
							</a>
						</div>
					</div>
				</div>
			</div>

			<!-- Units loop END-->

			<div class="slider-units-dots">
				<div class="slide">1</div>
				<div class="slide">2</div>
			</div>

			<div class="text-right mt-60 mb-20 mobile-hidden">
				<a class="btn" href="#">
					<?php _e('view all small units', 'html5blank'); ?>
				</a>
			</div>
		</div>

		<!-- Category loop END -->

		<article class="mt-50">
			<ul>
				<li>New unit rentals only. This promotion is not offered to existing rentals that are transferring to new units</li>
				<li>The offer cannot be combined with any other offer.</li>
				<li>On selected units and subject to change based on unit availability.</li>
				<li>All promotions are effective as of the first full month's rental cycle</li>
				<li>Promotions are revocable if units are not in good standing.</li>
			</ul>
		</article>
	</div>
</div>