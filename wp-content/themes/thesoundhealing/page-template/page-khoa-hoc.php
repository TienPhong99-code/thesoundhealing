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
<?php get_template_part('partials/sections/khoa-hoc/section', 'list'); ?>

<?php
get_footer();
