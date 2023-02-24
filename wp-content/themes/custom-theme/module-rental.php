<?php if(get_field('rental_description_content')): ?>
    <?php if(get_field('rental_free_content')) {
        $class = '';
    } else {
        $class = 'last';
    } ?>
    <div class="rental-wrap <?php echo $class; ?>">
        <?php if(get_field('rental_title')): ?>
            <h2 class="title">
                <?php the_field('rental_title'); ?>
            </h2>
        <?php endif; ?>

        <div class="bgr">
            <?php if( get_field('rental_image') ): ?>
                <?php $image = get_field('rental_image'); ?>

                <img class="overlap" src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
            <?php endif; ?>

            <div class="columns columns-2">
                <div class="col">
                    <?php if(get_field('rental_description_title')): ?>
                        <h3 class="title">
                            <?php if( get_field('rental_description_icon') ): ?>
                                <?php $image = get_field('rental_description_icon'); ?>

                                <span class="img-wrap">
                                    <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
                                </span>
                            <?php endif; ?>

                            <span>
                                <?php the_field('rental_description_title'); ?>
                            </span>
                        </h3>
                    <?php endif; ?>

                    <?php the_field('rental_description_content'); ?>

                    <?php if(get_field('rental_description_button_link')): ?>
                        <a class="btn btn-white-2" href="<?php the_field('rental_description_button_link'); ?>">
                            <?php the_field('rental_description_button_label'); ?>
                        </a>
                    <?php endif; ?>
                </div>

                <div class="col">
                    <?php if(get_field('rental_features_title')): ?>
                        <h3 class="title">
                            <?php if( get_field('rental_features_icon') ): ?>
                                <?php $image = get_field('rental_features_icon'); ?>

                                <span class="img-wrap">
                                    <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
                                </span>
                            <?php endif; ?>

                            <span>
                                <?php the_field('rental_features_title'); ?>
                            </span>
                        </h3>
                    <?php endif; ?>

                    <?php the_field('rental_features_content'); ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if(get_field('rental_free_content')): ?>
    <div class="rental-wrap white">
        <div class="bgr">
            <div class="columns columns-2">
                <div class="col">
                    <?php the_field('rental_free_content'); ?>

                    <?php if(get_field('rental_free_button_link')): ?>
                        <a class="btn" href="<?php the_field('rental_free_button_link'); ?>">
                            <?php the_field('rental_free_button_label'); ?>
                        </a>
                    <?php endif; ?>
                </div>

                <div class="col">
                    <?php if( have_rows('rental_free_trucks') ): ?>
                    	<div class="columns columns-3 table flex">
                            <div class="col first">
                                <?php _e('unit type', 'html5blank'); ?>
                            </div>

                            <div class="col first">
                                <?php _e('truck type', 'html5blank'); ?>
                            </div>

                            <div class="col first">
                                <?php _e('duration', 'html5blank'); ?>
                            </div>

                    		<?php while( have_rows('rental_free_trucks') ): the_row(); ?>
                    			<div class="col">
                    				<?php the_sub_field('rental_free_trucks_unit_type'); ?>
                    			</div>

                                <div class="col">
                    				<?php the_sub_field('rental_free_trucks_truck_type'); ?>
                    			</div>

                                <div class="col">
                    				<?php the_sub_field('rental_free_trucks_duration'); ?>
                    			</div>
                    		<?php endwhile; ?>
                    	</div>

                        <?php if(get_field('rental_free_notice')): ?>
                            <p class="notice">
                                <?php the_field('rental_free_notice'); ?>
                            </p>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="icon arrow-down"></div>
        </div>
    </div>
<?php endif; ?>
