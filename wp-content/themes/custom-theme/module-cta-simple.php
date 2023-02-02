<?php if(get_field('cta_content')): ?>
    <div class="cta-wrap has-bgr" style="background-image: url('<?php the_field('cta_background'); ?>');">
        <div class="wrapper">
            <?php the_field('cta_content'); ?>
        </div>
    </div>
<?php endif; ?>
