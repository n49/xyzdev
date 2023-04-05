<?php if(get_sub_field('prices_title')): ?>
	<?php $title = get_sub_field('prices_title'); ?>
<?php endif; ?>

<?php if(have_rows('prices')): ?>
	<div class="columns columns-5 pricing">
        <div class="col col-1">
            <strong>
                <?php echo $title; ?>
            </strong>
        </div>

		<?php while(have_rows('prices')): the_row(); ?>
			<div class="col">
                <?php if(get_sub_field('prices_title')): ?>
                    <p class="title">
                        <?php the_sub_field('prices_title'); ?>
                    </p>
                <?php endif; ?>

                <?php if(get_sub_field('prices_content')): ?>
                    <div class="content">
                        <?php the_sub_field('prices_content'); ?>
                    </div>
                <?php endif; ?>

                <?php if(get_sub_field('prices_link')): ?>
                    <?php
                        $link = get_sub_field('prices_link');
                        $link_url = $link['url'];
                        $link_title = $link['title'];
                        $link_target = $link['target'] ? $link['target'] : '_self';
                    ?>

                    <a class="btn btn-yellow" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
                        <?php echo esc_html($link_title); ?>
                    </a>
                <?php endif; ?>
			</div>
		<?php endwhile; ?>
	</div>
<?php endif; ?>