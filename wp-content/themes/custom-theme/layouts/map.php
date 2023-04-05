<?php if(get_sub_field('address')): ?>
    <?php $location = get_sub_field('address'); ?>

    <div class="map-wrap height-400">
        <div class="acf-map">
            <div class="marker" data-lat="<?php echo $location['lat']; ?>" data-lng="<?php echo $location['lng']; ?>"></div>
        </div>
    </div>
<?php endif; ?>

<div class="links">
    <?php if(get_sub_field('map_google')): ?>
        <?php
            $link = get_sub_field('map_google');
            $link_url = $link['url'];
            $link_title = $link['title'];
            $link_target = $link['target'] ? $link['target'] : '_self';
        ?>

        <a class="link" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>"><?php echo esc_html($link_title); ?></a>
    <?php endif; ?>

    <?php if(get_sub_field('map_apple')): ?>
        <?php
            $link = get_sub_field('map_apple');
            $link_url = $link['url'];
            $link_title = $link['title'];
            $link_target = $link['target'] ? $link['target'] : '_self';
        ?>

        <a class="link" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>"><?php echo esc_html($link_title); ?></a>
    <?php endif; ?>

    <?php if(get_sub_field('map_bing')): ?>
        <?php
            $link = get_sub_field('map_bing');
            $link_url = $link['url'];
            $link_title = $link['title'];
            $link_target = $link['target'] ? $link['target'] : '_self';
        ?>

        <a class="link" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>"><?php echo esc_html($link_title); ?></a>
    <?php endif; ?>
</div>