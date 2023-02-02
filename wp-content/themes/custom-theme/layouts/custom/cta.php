<?php if(get_sub_field('content')): ?>
    <div class="services-wrap spacing-6 full" style="background-image: url('<?php the_sub_field('background'); ?>');">
        <div class="wrapper">
            <div class="content">
                <?php the_sub_field('content'); ?>
            </div>
        </div>
    </div>
<?php endif; ?>
