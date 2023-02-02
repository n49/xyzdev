<div class="reviews-section grey-2 spacing" style="background-image: url('<?php the_sub_field('background'); ?>');">
    <div class="wrapper">
        <?php if(get_sub_field('content_1')): ?>
            <?php the_sub_field('content_1'); ?>
        <?php endif; ?>

        <div class="columns columns-2 normal units-list">
            <div class="col">
                <?php if(get_sub_field('content_2')): ?>
                    <?php the_sub_field('content_2'); ?>
                <?php endif; ?>
            </div>

            <div class="col">
                <?php if(get_sub_field('content_3')): ?>
                    <?php the_sub_field('content_3'); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
