<?php
	$taxonomy = 'unit-category';
	$tabs_exclude = array(22, 23);
	$tabs_include = array(23);

	$terms = get_terms(array(
		'taxonomy' => $taxonomy,
		'hide_empty' => false,
		'exclude' => $tabs_exclude
	));

	$terms_tabs = get_terms(array(
		'taxonomy' => $taxonomy,
		'hide_empty' => false,
		'include' => $tabs_include
	));

	$parent_title = get_the_title();
	$parent_permalink = get_the_permalink();


	$locations_array = array(
	                          'Scarborough' => 1,
	                          'Toronto West' => 3,
	                          'Etobicoke' => 4,
	                          'Mobile Storage' => 5,
	                          'Toronto Midtown' => 6,
	                          'Toronto Downtown' => 7
	                        );

	$this_location_code = $locations_array[$parent_title];
	$amp = isset($_GET["amp"]);

?>
<?php if($amp): ?>

<div class="tabs-wrap">
	<div class="wrapper">
		<div class="tabs white active">

			<div id="all" class="tab">
			  <h2>Storage Units</h2>
				<?php foreach ($terms as $term) : ?>
					<?php
						$args = array(
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

						$loop = new WP_Query($args);
					?>

					<?php if($loop->have_posts()) : ?>
						<div id="<?php echo $term->slug; ?>" <?php post_class('overflow'); ?>>
							<div class="content-wrap">
								<div class="content float-left">
									<p class="title title-4">
										<?php echo $term->name; ?>
									</p>

									<?php if(get_field('unit_category_size', $term)): ?>
										<p class="size">
											<?php the_field('unit_category_size', $term); ?>
										</p>
									<?php endif; ?>

									<?php if( get_field('unit_category_notice', $term) ): ?>
										<?php the_field('unit_category_notice', $term); ?>
									<?php endif; ?>
								</div>

								<div class="btn-wrap float-right">
									<a class="btn normal view-units-location-size" href="<?php echo $parent_permalink; ?>book-a-unit#<?php echo $term->slug; ?>">
										<?php _e('view units', 'html5blank'); ?>
									</a>
								</div>
							</div>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>

				<?php wp_reset_query(); ?>
        <div class="overflow "><div class="content-wrap">
          <a class="btn btn-yellow btn-yellow-amp" href="<?php echo $parent_permalink; ?>book-a-unit/">
					<?php _e('see all units', 'html5blank'); ?>
				</a></div></div>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>

<?php if(!$amp): ?>

<script type='text/javascript' >
  /* global location */
  /* global jQuery*/
  function setCookies1(dimensions, rent, length, width, height, price){
		document.cookie = "unitLocation=<?php echo $this_location_code ?>;path=/customer-details";
    var discountNew = parseInt(document.getElementById("savings-new").innerHTML);
    var quantity = parseInt(document.getElementById("quant").innerHTML);
    var url = "<?php bloginfo('url'); ?>/customer-details/";
    document.cookie = "unitLocation=5;path=/customer-details";
    document.cookie = 'unitDimensions='+dimensions+'; path=/customer-details';
    document.cookie = 'length='+length+'; path=/customer-details';
    document.cookie = 'width='+width+'; path=/customer-details';
    document.cookie = 'height='+height+'; path=/customer-details';
    document.cookie = 'unitRent='+(price-discountNew/quantity)+'; path=/customer-details';
    document.cookie = 'unitPrice='+(price-discountNew/quantity)+'; path=/customer-details';
    window.location = url;
  }

	var selectedSize;
	function setSize(size,url){
		selectedSize = size;
		window.location = url;
	}

</script>


<div class="tabs-wrap">
	<div class="wrapper">
		<div class="tabs white active">
			<ul class="horizontal">
				<li>
					<a class="normal" href="#all">
						<p class="title-5">
							<?php _e('storage units', 'html5blank'); ?>
						</p>
					</a>
				</li>

				<?php if(get_field('location_unit_related')): ?>
					<li>
						<a class="normal" href="#mobile-storage">
							<p class="title-5">
								<?php _e('mobile storage', 'html5blank'); ?>
							</p>
						</a>
					</li>
				<?php endif; ?>

				<?php foreach ($terms_tabs as $terms_tab) : ?>
					<?php
						$args_tabs = array(
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
									'terms'    => $terms_tab->slug
								),
							)
						);

						$loop_tabs = new WP_Query($args_tabs);
					?>

					<?php if($loop_tabs->have_posts()) : ?>
						<li>
							<a class="normal" href="#<?php echo $terms_tab->slug; ?>">
								<p class="title-5">
									<?php echo $terms_tab->name; ?>
								</p>
							</a>
						</li>
					<?php endif; ?>
				<?php endforeach; ?>

				<?php wp_reset_query(); ?>
			</ul>

			<div id="all" class="tab">
				<?php foreach ($terms as $term) : ?>
					<?php
						$args = array(
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

						$loop = new WP_Query($args);
					?>

					<?php if($loop->have_posts()) : ?>
						<div id="<?php echo $term->slug; ?>" <?php post_class('overflow'); ?>>
							<div class="content-wrap">
								<div class="content float-left">
									<p class="title title-4">
										<?php echo $term->name; ?>
									</p>

									<?php if(get_field('unit_category_size', $term)): ?>
										<p class="size">
											<?php the_field('unit_category_size', $term); ?>
										</p>
									<?php endif; ?>

									<?php if( get_field('unit_category_notice', $term) ): ?>
										<?php the_field('unit_category_notice', $term); ?>
									<?php endif; ?>
								</div>

								<div class="btn-wrap float-right">
									<a class="btn normal view-units-location-size" href="<?php echo $parent_permalink; ?>book-a-unit#<?php echo $term->slug; ?>">
										<?php _e('view units', 'html5blank'); ?>
									</a>
								</div>
							</div>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>

				<?php wp_reset_query(); ?>

				<a class="btn green" href="<?php echo $parent_permalink; ?>book-a-unit/">
					<?php _e('view all units', 'html5blank'); ?>
				</a>
			</div>

			<?php if(get_field('location_unit_related')): ?>
				<div id="mobile-storage" class="tab has-content">
					<?php if(get_field('location_unit_related_content')): ?>
						<?php the_field('location_unit_related_content'); ?>
					<?php endif; ?>

					<div class="related">
						<?php
							$post = get_field('location_unit_related');
							setup_postdata($post);
						?>

						<?php if(get_field('unit_amount')): ?>
							<?php
								$price = get_field('unit_amount');
								$price_edit = number_format($price, 2, '.', '');
							?>

							<div class="counter" data-save="<?php the_field('unit_saving'); ?>" data-price="<?php the_field('unit_amount'); ?>">
								<h5>
									<?php _e('number of units you need', 'html5blank'); ?>
								</h5>

								<span class="icon sign dec"></span>
								<span class="num">1</span>
								<span class="icon sign inc"></span>
							</div>

							<p class="title">
								<?php _e('monthly price', 'html5blank'); ?>
							</p>

							<p class="title title-4 price">
								$<span><?php echo $price_edit; ?></span>/mo
							</p>
						<?php endif; ?>

						<?php if(get_field('unit_saving')): ?>
							<p class="savings">
								<?php _e('Savings of', 'html5blank'); ?> $<span class="save" id="savings-new">0</span> <?php _e('for', 'html5blank'); ?> <span class="num" id="quant">1</span> <?php _e('units', 'html5blank'); ?>
							</p>
						<?php endif; ?>

						<a class="btn btn-reserve mobile"  data-unit="198" data-location="5" data-amount="1"
						  onclick="setCookies1(`5'd x 8'w x 7'h` , 135.00 , 8 , 5 , 7 , 135.00)"
						  style="cursor: pointer">
							<?php _e('reserve a unit', 'html5blank'); ?>
						</a>
					</div>

					<?php wp_reset_postdata(); ?>
				</div>
			<?php endif; ?>

			<?php foreach ($terms_tabs as $terms_tab) : ?>
				<?php
					$args_tabs_2 = array(
						'post_type' => 'units',
						'posts_per_page'=> 4,
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
								'terms'    => $terms_tab->slug
							),
						)
					);

					$loop_tabs_2 = new WP_Query($args_tabs_2);
				?>

				<?php if($loop_tabs_2->have_posts()) : ?>
					<div id="<?php echo $terms_tab->slug; ?>" <?php post_class('overflow tab'); ?>>
						<?php if ($loop_tabs_2->have_posts()): while ($loop_tabs_2->have_posts()) : $loop_tabs_2->the_post(); ?>
							<div class="content-wrap">
								<div class="content float-left">
									<p class="title title-4">
										<?php the_title(); ?>
									</p>

									<?php if(get_field('unit_category_size', $terms_tab)): ?>
										<p class="size">
											<?php the_field('unit_category_size', $terms_tab); ?>
										</p>
									<?php endif; ?>

									<?php if(get_field('unit_expiration')): ?>
										<?php the_field('unit_expiration'); ?>
									<?php endif; ?>
								</div>

								<div class="btn-wrap float-right">
									<a class="btn normal" href="<?php echo $parent_permalink; ?>book-a-unit#<?php echo $terms_tab->slug; ?>">
										<?php _e('view units', 'html5blank'); ?>
									</a>
								</div>
							</div>
						<?php endwhile; ?>

						<?php endif; ?>

						<?php wp_reset_query(); ?>

						<a class="btn green" href="<?php echo $parent_permalink; ?>book-a-unit/">
							<?php _e('view all units', 'html5blank'); ?>
						</a>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>

			<?php wp_reset_query(); ?>
		</div>
	</div>
</div>
<?php endif; ?>
