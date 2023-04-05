<?php if(have_rows('video')): ?>
    <?php while(have_rows('video')): the_row(); ?>
        <?php if(get_sub_field('video_content')): ?>
            <?php the_sub_field('video_content'); ?>
        <?php endif; ?>
    <?php endwhile; ?>
<?php endif; ?>