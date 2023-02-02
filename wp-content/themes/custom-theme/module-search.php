<?php $amp = isset($_GET["amp"]); ?>


<div class="search-content">

    <?php if(!$amp): ?>

    <div class="search-wrap">
        <p class="title-3">
            <?php _e('search by location', 'html5blank'); ?>
        </p>

        <div class="input-wrap">
			<label class="hidden" for="location-address">
				<?php _e('enter address or postal code', 'html5blank'); ?>>
			</label>

			<input id="location-address" class="controls" type="text" placeholder="enter address or postal code">

			<div class="icon target btn-current"></div>
        </div>
		
		<?php if(is_front_page()): ?>
			<div id="btn-search" class="btn btn-search home-page-search">
				<?php _e('search', 'html5blank'); ?>
			</div>
		<?php else:?>
        <div id="btn-search" class="btn btn-search">
            <?php _e('search', 'html5blank'); ?>
        </div>
		<?php endif; ?>
    </div>

    <div class="clear"></div>
  <?php endif; ?>

</div>
