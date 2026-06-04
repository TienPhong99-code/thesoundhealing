<?php

/**
 * Template Name: Dịch Vụ
 */

if (!defined('ABSPATH')) {
    die();
}

get_header();
?>

<?php get_template_part('partials/sections/dich-vu/section', 'hero'); ?>
<?php get_template_part('partials/sections/dich-vu/section', 'list'); ?>

<?php
get_footer();
