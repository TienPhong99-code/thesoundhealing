<?php

/**
 * Template Name: Khoá Học
 */

if (!defined('ABSPATH')) {
    die();
}

get_header();
?>

<?php get_template_part('partials/sections/khoa-hoc/section', 'hero'); ?>

<section class="sec-search-home section-pd-t z-10 max-md:!pt-0 max-md:fixed max-md:top-[calc(var(--size-hd))] max-md:w-full max-md:left-0">
    <div class="container max-md:!px-0">
        <?php get_template_part('partials/components/search-booking'); ?>
    </div>
</section>
<?php get_template_part('partials/sections/khoa-hoc/section', 'list'); ?>

<?php
get_footer();
