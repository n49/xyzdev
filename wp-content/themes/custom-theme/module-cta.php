<?php if(get_field('cta_title')): ?>
    <div class="cta-wrap" style="background-color: <?php the_field('cta_background_color'); ?>;">
        <div class="overlay <?php the_field('cta_background_image_style'); ?>" style="background-image: url('<?php the_field('cta_background_image'); ?>'); opacity: <?php the_field('cta_background_image_opacity'); ?>"></div>

        <div class="wrapper">
            <p class="title-1">
                <?php the_field('cta_title'); ?>
            </p>

            <?php if(get_field('cta_button_title')): ?>
                <p class="title-4">
                    <?php the_field('cta_button_title'); ?>
                </p>
            <?php endif; ?>

            <?php if( have_rows('cta_buttons') ): ?>
            	<div class="btn-wrap">
            		<?php while( have_rows('cta_buttons') ): the_row(); ?>
                        <a class="btn btn-<?php the_sub_field('cta_buttons_color'); ?> icon <?php the_sub_field('cta_buttons_icon'); ?>" href="<?php the_sub_field('cta_buttons_url'); ?>">
                            <?php the_sub_field('cta_buttons_title'); ?>
                        </a>
            		<?php endwhile; ?>
            	</div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
