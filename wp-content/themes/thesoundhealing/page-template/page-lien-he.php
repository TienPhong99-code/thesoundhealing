<?php

/**
 * Template Name: Liên Hệ
 */

if (!defined('ABSPATH')) {
    die();
}

get_header();
?>

<?php get_template_part('partials/sections/lien-he/section', 'hero'); ?>
<?php get_template_part('partials/sections/lien-he/section', 'form'); ?>
<?php get_template_part('partials/sections/lien-he/section', 'map'); ?>
<?php get_template_part('partials/sections/lien-he/section', 'faq'); ?>

<?php
get_footer();
