<?php if( get_field('global_logos', 'option') ): ?>
    <?php $images = get_field('global_logos', 'option'); ?>

    <div class="gallery-wrap white content">
        <div class="wrapper">
            <div class="carousel columns columns-5">
                <?php foreach($images as $image): ?>
                    <?php if(get_field('media_link', $image['ID'])): ?>
                    	<?php
                    		$link = get_field('media_link', $image['ID']);
                    		$link_url = $link['url'];
                    		$link_title = $link['title'];
                    		$link_target = $link['target'] ? $link['target'] : '_self';
                    	?>
                    <?php endif; ?>

                    <div class="col <?php if($amp) echo "logos-amp-padding"; ?>">
                        <?php if(get_field('media_link', $image['ID'])): ?>
                            <a href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>" title="<?php echo $image['alt']; ?>">
                        <?php endif; ?>

                        <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />

                        <?php if(get_field('media_link', $image['ID'])): ?>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>
