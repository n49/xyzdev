<div class="reviews-section narrow spacing" style="background-image: url('<?php the_sub_field('background'); ?>');">
    <div class="wrapper">
        <?php if(get_sub_field('content_1')): ?>
            <?php the_sub_field('content_1'); ?>
        <?php endif; ?>

        <div class="columns columns-2 normal">
            <div class="col">
                <?php if(get_sub_field('content_2')): ?>
                    <?php the_sub_field('content_2'); ?>
                <?php endif; ?>
            </div>

            <div class="col">
                <?php if(get_sub_field('subtitle')): ?>
                    <h3 class="title-4">
                        <?php the_sub_field('subtitle'); ?>
                    </h3>
                <?php endif; ?>

                <?php if(have_rows('prices')): ?>
                    <div class="table-wrap">
                        <?php while(have_rows('prices')): the_row(); ?>
                            <div class="columns columns-5">
                                <div class="col col-1 highlight-yes">
                                    <?php if(get_sub_field('prices_title')): ?>
                                        <?php the_sub_field('prices_title'); ?>
                                    <?php endif; ?>
                                </div>

                                <?php if(have_rows('prices_items')): ?>
                            		<?php while(have_rows('prices_items')): the_row(); ?>
                            			<div class="col highlight-<?php the_sub_field('prices_items_hightlight'); ?>">
                            				<?php the_sub_field('prices_items_content'); ?>
                            			</div>
                            		<?php endwhile; ?>
                                <?php endif; ?>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
