<?php if(have_rows('features')): ?>
	<div class="columns columns-3 flex prices">
		<?php while(have_rows('features')): the_row(); ?>
			<div class="col">
                <?php if(get_sub_field('features_title')): ?>
                    <h3 class="title small">
                        <?php the_sub_field('features_title'); ?>
                    </h3>
                <?php endif; ?>

                <?php if(have_rows('features_items')): ?>
                	<div class="cost small">
                		<?php while(have_rows('features_items')): the_row(); ?>
                			<p>
                				<?php the_sub_field('features_items_name'); ?>
                			</p>
                		<?php endwhile; ?>
                	</div>
                <?php endif; ?>
			</div>
		<?php endwhile; ?>
	</div>
<?php endif; ?>
