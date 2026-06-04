<?php

/**
 * The template for front page
 *
 * @author MONA.Media / Website
 */

if (! defined('ABSPATH')) {
   die();
}

get_header();
?>

<?php get_template_part('partials/sections/home/section', 'hero'); ?>
<?php get_template_part('partials/sections/home/section', 'about'); ?>
<?php get_template_part('partials/sections/home/section', 'courses'); ?>
<?php get_template_part('partials/sections/home/section', 'workshop'); ?>
<?php get_template_part('partials/sections/home/section', 'service'); ?>
<?php get_template_part('partials/sections/home/section', 'gallery'); ?>
<?php get_template_part('partials/sections/home/section', 'teams'); ?>
<?php get_template_part('partials/sections/home/section', 'partner'); ?>
<?php get_template_part('partials/sections/home/section', 'cta'); ?>

<?php
get_footer();
