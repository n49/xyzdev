<?php
	$parent_title = get_the_title($post->post_parent);
    $parent_url = get_permalink($post->post_parent);
?>

<?php if(get_sub_field('gallery')): ?>
	<?php $images = get_sub_field('gallery'); ?>
	
	<div class="gallery-masonry has-lightbox-simple">
		<?php foreach($images as $image): ?>
            <?php
                $caption = '';
                $caption .= '<h3>' . $image['title'] . '</h3>';
                $caption .= '<p>' . $image['caption'] . '</p>';

                $caption .= '<div>';
                $caption .= '<h3>'. $parent_title . '</h3>';
                $caption .= esc_html('<a class="btn btn-yellow" href="' . $parent_url . 'book-a-unit/">reserve a unit</a>');
                $caption .= '</div>';

                $num++;
            ?>

            <a class="img-wrap" href="<?php echo $image['url']; ?>" data-sub-html="<?php echo $caption; ?>" title="<?php echo $image['alt']; ?>">
                <img src="<?php echo $image['sizes']['large']; ?>" alt="<?php echo $image['alt']; ?>" />
            </a>
		<?php endforeach; ?>
	</div>
<?php endif; ?>