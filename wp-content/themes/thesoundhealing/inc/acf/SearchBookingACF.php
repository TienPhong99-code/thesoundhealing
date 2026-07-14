<?php

use Extended\ACF\Fields\Image;
use Extended\ACF\Fields\Tab;
use Extended\ACF\Fields\Text;
use Extended\ACF\Location;

defined('ABSPATH') || exit;

/**
 * Danh mục "Loại hình" của box Search Booking.
 *
 * Chỉ chữ hiển thị + hình là sửa được. Key của từng danh mục (best-seller, sound-healing,
 * usui-reiki, khoa-hoc, workshop) là logic tìm kiếm — vừa map sang post type, vừa là slug
 * taxonomy trong page-search-results.php — nên cố định trong code, admin không đổi được.
 *
 * Để trống một ô chữ → tự dùng lại text mặc định trong partials/components/search-booking.php.
 */
add_action('acf/init', function () {
    register_extended_field_group([
        'title'    => 'Danh mục – Search Booking',
        'style'    => 'default',
        'position' => 'acf_after_title',
        'location' => [
            Location::where('options_page', '==', 'theme-settings'),
        ],
        'fields' => [

            Tab::make('Search Booking – Danh mục')
                ->placement('left'),

            // ─── Best Seller ──────────────────────────────────────────────
            Text::make('Best Seller — Tên hiển thị', 'sb_label_best_seller')
                ->helperText('Để trống → dùng mặc định: Best Seller')
                ->placeholder('Best Seller'),

            Text::make('Best Seller — Mô tả', 'sb_desc_best_seller')
                ->helperText('Dòng chữ nhỏ dưới tên. Để trống → dùng mặc định: Được yêu thích nhất')
                ->placeholder('Được yêu thích nhất'),

            Image::make('Best Seller — Hình', 'sb_img_best_seller')
                ->helperText('Hình đại diện cho danh mục Best Seller')
                ->acceptedFileTypes(['jpg', 'jpeg', 'png', 'webp', 'avif'])
                ->format('url'),

            // ─── Sound Healing ────────────────────────────────────────────
            Text::make('Sound Healing — Tên hiển thị', 'sb_label_sound_healing')
                ->helperText('Để trống → dùng mặc định: Sound Healing')
                ->placeholder('Sound Healing'),

            Text::make('Sound Healing — Mô tả', 'sb_desc_sound_healing')
                ->helperText('Dòng chữ nhỏ dưới tên. Để trống → dùng mặc định: Liệu pháp âm thanh chữa lành')
                ->placeholder('Liệu pháp âm thanh chữa lành'),

            Image::make('Sound Healing — Hình', 'sb_img_sound_healing')
                ->helperText('Hình đại diện cho danh mục Sound Healing')
                ->acceptedFileTypes(['jpg', 'jpeg', 'png', 'webp', 'avif'])
                ->format('url'),

            // ─── Usui Reiki ───────────────────────────────────────────────
            Text::make('Usui Reiki — Tên hiển thị', 'sb_label_usui_reiki')
                ->helperText('Để trống → dùng mặc định: Usui Reiki')
                ->placeholder('Usui Reiki'),

            Text::make('Usui Reiki — Mô tả', 'sb_desc_usui_reiki')
                ->helperText('Dòng chữ nhỏ dưới tên. Để trống → dùng mặc định: Năng lượng chữa lành Reiki')
                ->placeholder('Năng lượng chữa lành Reiki'),

            Image::make('Usui Reiki — Hình', 'sb_img_usui_reiki')
                ->helperText('Hình đại diện cho danh mục Usui Reiki')
                ->acceptedFileTypes(['jpg', 'jpeg', 'png', 'webp', 'avif'])
                ->format('url'),

            // ─── Khoá Học ─────────────────────────────────────────────────
            Text::make('Khoá Học — Tên hiển thị', 'sb_label_khoa_hoc')
                ->helperText('Để trống → dùng mặc định: Khoá Học')
                ->placeholder('Khoá Học'),

            Text::make('Khoá Học — Mô tả', 'sb_desc_khoa_hoc')
                ->helperText('Dòng chữ nhỏ dưới tên. Để trống → dùng mặc định: Chương trình đào tạo chuyên sâu')
                ->placeholder('Chương trình đào tạo chuyên sâu'),

            Image::make('Khoá Học — Hình', 'sb_img_khoa_hoc')
                ->helperText('Hình đại diện cho danh mục Khoá Học')
                ->acceptedFileTypes(['jpg', 'jpeg', 'png', 'webp', 'avif'])
                ->format('url'),

            // ─── Workshop ─────────────────────────────────────────────────
            Text::make('Workshop — Tên hiển thị', 'sb_label_workshop')
                ->helperText('Để trống → dùng mặc định: Workshop')
                ->placeholder('Workshop'),

            Text::make('Workshop — Mô tả', 'sb_desc_workshop')
                ->helperText('Dòng chữ nhỏ dưới tên. Để trống → dùng mặc định: Sự kiện trải nghiệm ngắn hạn')
                ->placeholder('Sự kiện trải nghiệm ngắn hạn'),

            Image::make('Workshop — Hình', 'sb_img_workshop')
                ->helperText('Hình đại diện cho danh mục Workshop')
                ->acceptedFileTypes(['jpg', 'jpeg', 'png', 'webp', 'avif'])
                ->format('url'),

        ],
    ]);
}, 10);
