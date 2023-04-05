<?php if(have_rows('units')): ?>
	<div class="units-masonry">
		<?php while(have_rows('units')): the_row(); ?>
			<div class="col">
                <?php if(get_sub_field('units_title')): ?>
                    <h4>
                        <?php the_sub_field('units_title'); ?>
                    </h4>
                <?php endif; ?>

                <?php if(get_sub_field('units_subtitle')): ?>
                    <p class="subtitle">
                        <?php the_sub_field('units_subtitle'); ?>
                    </p>
                <?php endif; ?>

                <?php if(get_sub_field('units_content')): ?>
                    <p>
                        <?php the_sub_field('units_content'); ?>
                    </p>
                <?php endif; ?>

                <?php if(get_sub_field('units_link')): ?>
                    <?php
                        $link = get_sub_field('units_link');
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
	</div>
<?php endif; ?>
