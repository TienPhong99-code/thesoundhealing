<?php

/**
 * Template Name: Khóa Học & Workshop
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

<div id="khoa-hoc" class="khws-anchor"></div>
<?php get_template_part('partials/sections/khoa-hoc-workshop/section', 'khoa-hoc'); ?>

<div id="workshop" class="khws-anchor"></div>
<?php get_template_part('partials/sections/khoa-hoc-workshop/section', 'workshop'); ?>

<?php
get_footer();
