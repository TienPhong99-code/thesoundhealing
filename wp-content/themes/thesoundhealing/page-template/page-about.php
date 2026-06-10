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
<?php get_template_part('partials/sections/lien-he/section', 'form'); ?>
<?php get_template_part('partials/sections/about/section', 'pillars'); ?>
<!-- <?php get_template_part('partials/sections/about/section', 'visionary'); ?> -->
<?php get_template_part('partials/sections/about/section', 'features'); ?>
<!-- <?php get_template_part('partials/sections/about/section', 'cta'); ?> -->
<!-- <?php get_template_part('partials/sections/about/section', 'gallery'); ?> -->

<?php
get_footer();
