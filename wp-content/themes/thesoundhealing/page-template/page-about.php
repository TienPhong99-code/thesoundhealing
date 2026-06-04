<?php

/**
 * Template Name: Về Chúng Tôi
 */

if (!defined('ABSPATH')) {
    die();
}

get_header();
?>

<?php get_template_part('partials/sections/about/section', 'hero'); ?>
<?php get_template_part('partials/sections/about/section', 'journey'); ?>
<?php get_template_part('partials/sections/about/section', 'pillars'); ?>
<?php get_template_part('partials/sections/about/section', 'visionary'); ?>
<?php get_template_part('partials/sections/about/section', 'cta'); ?>

<?php
get_footer();
