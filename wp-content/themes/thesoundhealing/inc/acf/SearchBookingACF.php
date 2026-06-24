<?php

use Extended\ACF\Fields\Image;
use Extended\ACF\Fields\Tab;
use Extended\ACF\Location;

defined('ABSPATH') || exit;

add_action('acf/init', function () {
    register_extended_field_group([
        'title'    => 'Hình ảnh danh mục – Search Booking',
        'style'    => 'default',
        'position' => 'acf_after_title',
        'location' => [
            Location::where('options_page', '==', 'theme-settings'),
        ],
        'fields' => [

            Tab::make('Search Booking – Danh mục')
                ->placement('left'),

            Image::make('Best Seller', 'sb_img_best_seller')
                ->helperText('Hình đại diện cho danh mục Best Seller')
                ->acceptedFileTypes(['jpg', 'jpeg', 'png', 'webp', 'avif'])
                ->format('url'),

            Image::make('Sound Healing', 'sb_img_sound_healing')
                ->helperText('Hình đại diện cho danh mục Sound Healing')
                ->acceptedFileTypes(['jpg', 'jpeg', 'png', 'webp', 'avif'])
                ->format('url'),

            Image::make('Usui Reiki', 'sb_img_usui_reiki')
                ->helperText('Hình đại diện cho danh mục Usui Reiki')
                ->acceptedFileTypes(['jpg', 'jpeg', 'png', 'webp', 'avif'])
                ->format('url'),

            Image::make('Khoá Học', 'sb_img_khoa_hoc')
                ->helperText('Hình đại diện cho danh mục Khoá Học')
                ->acceptedFileTypes(['jpg', 'jpeg', 'png', 'webp', 'avif'])
                ->format('url'),

            Image::make('Workshop', 'sb_img_workshop')
                ->helperText('Hình đại diện cho danh mục Workshop')
                ->acceptedFileTypes(['jpg', 'jpeg', 'png', 'webp', 'avif'])
                ->format('url'),

        ],
    ]);
}, 10);
