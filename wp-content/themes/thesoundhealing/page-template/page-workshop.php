<?php

/**
 * Template Name: Workshop
 */

if (!defined('ABSPATH')) {
    die();
}

get_header();
?>

<?php get_template_part('partials/sections/workshop/section', 'hero'); ?>
<?php get_template_part('partials/sections/workshop/section', 'list'); ?>

<?php
get_footer();
