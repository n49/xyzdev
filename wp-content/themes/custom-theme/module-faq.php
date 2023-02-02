<?php if(have_rows('faq_items')): ?>
    <?php $num = 1; ?>

    <script type="application/ld+json">
    {
        "@context":"http://schema.org",
        "@type":"FAQPage",
        "mainEntity":[
            <?php $comma = false; ?>
            <?php while(have_rows('faq_items')): the_row();
                if($comma) {
                    echo ",{";
                }
                else {
                    $comma = true;
                    echo "{";
                }
                 ?>
                    "@type":"Question",
                    "name":"<?php the_sub_field('faq_items_title'); ?>",
                    "acceptedAnswer":{
                        "@type":"Answer",
                        "text":"<?php echo substr(get_sub_field('faq_items_content'), 3, -5); ?>"
                        }
                }
            <?php endwhile; ?>
        ]
    }
    </script>

    <div class="locations-wrap spacing add-bgr">
        <div class="wrapper">
            <?php if(get_field('faq_content')): ?>
                <?php the_field('faq_content'); ?>
            <?php endif; ?>

            <div class="faq accordion simple">
                <?php while(have_rows('faq_items')): the_row(); ?>
                    <div class="item">
                        <p class="title title-4 icon arrow-left">
                            <span>
                                <?php _e('Q', 'html5blank'); ?><?php echo $num; $num++; ?>.
                            </span>

                            <?php the_sub_field('faq_items_title'); ?>
                        </p>

                        <div class="items content">
                            <?php the_sub_field('faq_items_content'); ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
<?php endif; ?>
