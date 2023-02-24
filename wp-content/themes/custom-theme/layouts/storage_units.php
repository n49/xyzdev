<?php if(get_sub_field('storage_units')): ?>
    <?php $posts = get_sub_field('storage_units'); ?>

    <div class="popular-units spacing-2">
        <p class="title-5 icon icon-arrow">
            <?php _e('frequently booked units', 'html5blank'); ?>
        </p>

        <h2 class="title overflow">
            <?php if(get_sub_field('storage_units_title')): ?>
                <?php the_sub_field('storage_units_title'); ?>
            <?php endif; ?>

            <a class="btn green" href="<?php the_permalink(); ?>book-a-unit/">
                <?php _e('view all units', 'html5blank'); ?>
            </a>
        </h2>

        <div class="columns columns-4 half flex units">
            <?php foreach($posts as $post): ?>
                <?php
                    setup_postdata($post);

                    $terms = wp_get_post_terms($post->ID, 'unit-category');
                    $terms_size = get_field('unit_category_size', $terms[0]);
                ?>

                <div id="post-<?php the_ID(); ?>" class="col columns columns-2 white flex units">
                    <div class="col img">
                        <?php if ( has_post_thumbnail()) : ?>
                            <div class="img-wrap height large">
                                <?php the_post_thumbnail('full'); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="col text desc">
                        <h4 class="title margin">
                            <?php echo $terms[0]->name; ?>
                        </h4>

                        <h4 class="title border">
                            <?php echo $terms_size; ?>
                        </h4>

                        <p><?php _e('rent from', 'html5blank'); ?></p>

                        <?php if(get_field('unit_amount_normal')): ?>
                            <?php
                                $price = get_field('unit_amount_normal');
                                $price_edit = number_format($price, 2, '.', '');
                            ?>

                            <h4 class="title">
                                <del>$<?php echo $price_edit; ?></del>
                            </h4>
                        <?php endif; ?>

                        <?php if(get_field('unit_amount')): ?>
                            <?php
                                $price = get_field('unit_amount');
                                $price_edit = number_format($price, 2, '.', '');
                            ?>

                            <h4 class="title margin">
                                $<?php echo $price_edit; ?>/mo
                            </h4>
                        <?php endif; ?>

                        <?php if(get_field('unit_custom_link')): ?>
                            <?php
                                $link = get_field('unit_custom_link');
                                $link_url = $link['url'];
                                $link_title = $link['title'];
                                $link_target = $link['target'] ? $link['target'] : '_self';
                            ?>

                            <a class="btn btn-reserve normal" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>" data-unit="<?php the_ID(); ?>" data-amount="1">
                                <?php echo esc_html($link_title); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly ?>
<?php endif; ?>
