<?php

/**
 * Template Name: Khóa Học & Workshop
 */

if (!defined('ABSPATH')) {
    die();
}

get_header();
?>

<?php get_template_part('partials/sections/khoa-hoc-workshop/section', 'hero'); ?>

<div id="khoa-hoc" class="khws-anchor"></div>
<?php get_template_part('partials/sections/khoa-hoc-workshop/section', 'khoa-hoc'); ?>

<div id="workshop" class="khws-anchor"></div>
<?php get_template_part('partials/sections/khoa-hoc-workshop/section', 'workshop'); ?>

<?php
get_footer();
