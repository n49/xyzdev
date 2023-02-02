<div class="about-section spacing has-shadow show" style="background-image: url('<?php the_sub_field('background'); ?>');">
    <div class="wrapper">
        <div class="columns columns-3 custom">
            <div class="col first">
                <?php if(get_sub_field('subtitle')): ?>
                    <p class="title-5 icon icon-arrow white">
                        <?php the_sub_field('subtitle'); ?>
                    </p>
                <?php endif; ?>

                <?php if(get_sub_field('content_1')): ?>
                    <?php the_sub_field('content_1'); ?>
                <?php endif; ?>
            </div>

            <?php if(get_sub_field('content_2')): ?>
                <div class="col second">
                    <?php the_sub_field('content_2'); ?>
                </div>
            <?php endif; ?>

            <?php if(get_sub_field('content_3')): ?>
                <div class="col third">
                    <?php the_sub_field('content_3'); ?>
                </div>
            <?php endif; ?>
        </div>

        <a class="btn-more normal" href="#">
            <?php _e('read more', 'html5blank'); ?>
        </a>
    </div>
</div>
