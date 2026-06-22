<?php

/**
 * Template Name: Dịch Vụ
 */

if (!defined('ABSPATH')) {
    die();
}

get_header();
?>

<section class="sec-search-home section-pd-t z-10 max-md:!pt-0 max-md:fixed max-md:top-[calc(var(--size-hd))] max-md:w-full max-md:left-0">
    <div class="container max-md:!px-0">
        <?php get_template_part('partials/components/search-booking'); ?>
    </div>
</section>
<?php get_template_part('partials/sections/dich-vu/section', 'list'); ?>

<?php
get_footer();
