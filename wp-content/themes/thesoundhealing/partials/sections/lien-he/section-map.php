<?php
defined('ABSPATH') || exit;

$page_id   = get_queried_object_id();
$map_embed = get_field('lh_map_embed', $page_id);
$pin_label = get_field('lh_pin_label', $page_id) ?: 'The Sound Healing';
?>

<section class="sec-lh-map relative h-[500px] overflow-hidden">
    <iframe
        src="<?php echo esc_url($map_embed); ?>"
        class="absolute inset-0 w-full h-full border-0"
        allowfullscreen=""
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"
        title="<?php echo esc_attr($pin_label); ?>">
    </iframe>
</section>