<div class="columns columns-2 has-dashed-list small-width-100">
    <div class="col col-1">
        <?php if(get_sub_field('content_1')): ?>
            <?php the_sub_field('content_1'); ?>
        <?php endif; ?>
    </div>

    <div class="col col-2">
        <?php if(get_sub_field('content_2')): ?>
            <?php the_sub_field('content_2'); ?>
        <?php endif; ?>
    </div>
</div>


