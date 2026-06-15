<?php

/**
 * Template Name: Khóa Học & Workshop
 */

if (!defined('ABSPATH')) {
    die();
}

get_header();
?>

<section class="sec-search-home section-pd-t">
    <div class="container">
        <?php get_template_part('partials/components/search-booking'); ?>
    </div>
</section>

<div id="khoa-hoc" class="khws-anchor"></div>
<?php get_template_part('partials/sections/khoa-hoc-workshop/section', 'khoa-hoc'); ?>

<div id="workshop" class="khws-anchor"></div>
<?php get_template_part('partials/sections/khoa-hoc-workshop/section', 'workshop'); ?>

<?php
get_footer();
