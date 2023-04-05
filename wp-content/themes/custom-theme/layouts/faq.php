<?php if(have_rows('faq_items')): ?>
    <?php $num = 1; ?>
    
    <div class="faq accordion simple">
        <?php while(have_rows('faq_items')): the_row(); ?>
            <div class="item">
                <p class="title title-4 icon arrow-left">
                    <span>
                        <?php _e('Q', 'html5blank'); ?><?php echo $num; $num++; ?>.
                    </span>

                    <?php the_sub_field('faq_items_title'); ?>
                </p>

                <div class="items content">
                    <?php the_sub_field('faq_items_content'); ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
<?php endif; ?>