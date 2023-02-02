<div class="reviews-section narrow spacing" style="background-image: url('<?php the_sub_field('background'); ?>');">
    <div class="wrapper">
        <div class="mb-40">
            <?php if(get_sub_field('subtitle')): ?>
                <p class="title-5 icon icon-arrow">
                    <?php the_sub_field('subtitle'); ?>
                </p>
            <?php endif; ?>

            <?php if(get_sub_field('content_1')): ?>
                <?php the_sub_field('content_1'); ?>
            <?php endif; ?>
        </div>

        <?php if(have_rows('units')): ?>
    		<?php while(have_rows('units')): the_row(); ?>
    			<div class="columns columns-6 flex">
                    <div class="col col-1">
                        <?php if(get_sub_field('units_thumbnail')): ?>
                        	<?php $image = get_sub_field('units_thumbnail'); ?>

                            <div class="img-wrap">
                                <img src="<?php echo $image['sizes']['small-4']; ?>" alt="<?php echo $image['alt']; ?>" />
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="col col-2">
                        <?php if(get_sub_field('units_title')): ?>
                            <h3 class="title-4">
                                <?php the_sub_field('units_title'); ?>
                            </h3>
                        <?php endif; ?>

                        <?php if(get_sub_field('units_address')): ?>
                            <p>
                                <?php the_sub_field('units_address'); ?>
                            </p>
                        <?php endif; ?>

                        <?php if(get_sub_field('units_phone')): ?>
                        	<?php
                        		$link = get_sub_field('units_phone');
                        		$link_url = $link['url'];
                        		$link_title = $link['title'];
                        		$link_target = $link['target'] ? $link['target'] : '_self';
                        	?>

                            <p>
                                <?php _e('P:', 'html5blank'); ?>

                                <a href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
                            		<?php echo esc_html($link_title); ?>
                            	</a>
                            </p>

                        <?php endif; ?>

                        <?php if(get_sub_field('units_link')): ?>
                        	<?php
                        		$link = get_sub_field('units_link');
                        		$link_url = $link['url'];
                        		$link_title = $link['title'];
                        		$link_target = $link['target'] ? $link['target'] : '_self';
                        	?>

                            <a href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
                        		<?php echo esc_html($link_title); ?>
                        	</a>
                        <?php endif; ?>
                    </div>

                    <?php if(have_rows('units_items')): ?>
                		<?php while(have_rows('units_items')): the_row(); ?>
                			<div class="col">
                                <?php if(get_sub_field('units_items_note')): ?>
                                    <span class="note">
                                        <?php the_sub_field('units_items_note'); ?>
                                    </span>
                                <?php endif; ?>

                                <?php if(get_sub_field('units_items_title')): ?>
                                    <p>
                                        <?php the_sub_field('units_items_title'); ?>
                                    </p>
                                <?php endif; ?>

                                <?php if(get_sub_field('units_items_price')): ?>
                                    <h4>
                                        <?php the_sub_field('units_items_price'); ?>
                                    </h4>
                                <?php endif; ?>

                                <?php if(get_sub_field('units_items_link')): ?>
                                	<?php
                                		$link = get_sub_field('units_items_link');
                                		$link_url = $link['url'];
                                		$link_title = $link['title'];
                                		$link_target = $link['target'] ? $link['target'] : '_self';
                                	?>

                                	<a class="btn" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
                                		<?php echo esc_html($link_title); ?>
                                	</a>
                                <?php endif; ?>
                			</div>
                		<?php endwhile; ?>
                    <?php endif; ?>
    			</div>
    		<?php endwhile; ?>
        <?php endif; ?>
    </div>
</div>
