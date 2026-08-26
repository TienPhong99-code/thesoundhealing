<?php

/**
 * Template Name: Chính sách & Điều khoản
 *
 * Dùng chung cho cả trang VN và trang EN — nội dung tự chọn theo ngôn ngữ
 * hiện tại, xem partials/sections/chinh-sach/section-content.php
 */

if (!defined('ABSPATH')) {
    die();
}

get_header();
?>

<?php get_template_part('partials/sections/chinh-sach/section', 'content'); ?>

<?php
get_footer();
