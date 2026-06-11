<?php

/**
 * Template Name: Dịch Vụ
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
<?php get_template_part('partials/sections/dich-vu/section', 'list'); ?>

<?php
get_footer();
