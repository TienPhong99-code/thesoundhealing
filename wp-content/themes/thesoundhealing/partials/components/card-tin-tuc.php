<?php
defined('ABSPATH') || exit;

$post_id  = $args['post_id']  ?? 0;
$layout   = $args['layout']   ?? 'horizontal'; // 'horizontal' | 'vertical'
if (!$post_id) return;

$permalink = get_permalink($post_id);
$title     = get_the_title($post_id);
$excerpt   = get_the_excerpt($post_id);
$thumb_url = get_the_post_thumbnail_url($post_id, 'medium');
$time_ago  = human_time_diff(get_the_time('U', $post_id), current_time('timestamp')) . ' trước';

$wrap_class  = $layout === 'vertical' ? 'flex flex-col gap-3'          : 'flex gap-4 max-sm:gap-3 items-start max-sm:flex-col';
$image_class = $layout === 'vertical' ? 'w-full shrink-0'               : 'w-[45%] max-sm:w-full shrink-0';
?>

<div class="<?php echo esc_attr($wrap_class); ?>">

    <!-- Image -->
    <div class="<?php echo esc_attr($image_class); ?>">
        <a href="<?php echo esc_url($permalink); ?>"
            class="block aspect-600/389 rounded-[8px] overflow-hidden bg-pri">
            <?php if ($thumb_url) : ?>
                <img src="<?php echo esc_url($thumb_url); ?>"
                    class="block w-full h-full object-cover transition-transform duration-500 hover:scale-105"
                    alt="<?php echo esc_attr($title); ?>">
            <?php endif; ?>
        </a>
    </div>

    <!-- Content -->
    <div class="flex flex-col gap-3 max-sm:gap-2 flex-1 min-w-0">
        <a href="<?php echo esc_url($permalink); ?>"
            class="font-bold text-[20px] max-xl:text-[17px] max-md:text-[16px] text-pri hover:text-sec transition-colors line-clamp-2">
            <?php echo esc_html($title); ?>
        </a>
        <?php if ($excerpt) : ?>
            <p class="text-[16px] max-xl:text-[14px] max-sm:text-[13px] text-pri line-clamp-2">
                <?php echo esc_html($excerpt); ?>
            </p>
        <?php endif; ?>
        <p class="text-[12px] text-[#ababab]">
            <?php echo esc_html($time_ago); ?>
        </p>
    </div>

</div>