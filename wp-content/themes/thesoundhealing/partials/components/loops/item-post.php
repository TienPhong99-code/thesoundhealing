<?php
defined('ABSPATH') || exit;

$post_id = get_the_ID();
$permalink = get_the_permalink();
$title_tag = ! empty($args['title_tag'])
    ? $args['title_tag'] : 'h3';
$title_tag = esc_html($title_tag);
?>

<div class="item-bst ver2">
    <div class="inner hover-img-rotate">
        <div class="img">
            <a class="img-inner" href="<?php echo $permalink; ?>">
                <?php the_post_thumbnail(); ?>        
            </a>
        </div>
        <div class="info">
            <h4><a class="info-tt" href="<?php echo $permalink; ?>"><?php the_title(); ?></a></h4>
        </div>
    </div>
</div>